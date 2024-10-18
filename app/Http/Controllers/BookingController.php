<?php

    namespace App\Http\Controllers;

    use App\Models\Availability_Employee_Days;
    use App\Models\Availability_Employees;
    use App\Models\AvailabilityEmployeePlace;
    use App\Models\categories;
    use App\Models\DayOfWeek;
    use App\Models\Employee;
    use App\Models\Employee_Availability_Category;
    use App\Models\Employee_Availability_Packages;
    use App\Models\OrderSession;
    use App\Models\Packages;
    use App\Models\Place;
    use Illuminate\Http\Request;
    use Carbon\Carbon;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Collection;

    class BookingController extends Controller
    {

        public function getEmployeesInRadius(Request $request, $type = null)
        {
            $latitude = $request->input('latitude') ? $request->input('latitude') : null;
            $longitude = $request->input('longitude') ? $request->input('longitude') : null;
            $localeType = $type ?? '';
            $reservation_status = $request->input('reservation_status') ? $request->input('reservation_status') : '';
            if ($request->input('reservation_status') == null) {
                if ($localeType === 'homeServices') {
                    $reservation_status = 'at_home';
                } elseif ($localeType === 'SPA') {
                    $reservation_status = 'at_spa';
                }
            }

            //$reservation_status = 'at_home';
            // Log::info('$reservation_status::$type$type: ' . $reservation_status);
            $radius = 10000; // Range in meters

            if (is_null($latitude) || is_null($longitude)) {
                return response()->json(['message' => 'إحداثيات غير صحيحة'], 400);
            }

            $filteredPlaces = $this->getFilteredPlaces($latitude, $longitude, $radius);

            if (empty($filteredPlaces)) {
                return response()->json(['message' => 'لا توجد أماكن قريبة ضمن النطاق المحدد']);
            }

            $employees = $this->getAvailableEmployeesPlaces($filteredPlaces, $reservation_status);

            return response()->json([
                'message' => 'المكان داخل النطاق المحدد',
                'places' => $filteredPlaces,
                'employees' => $employees,
            ], 200, ['Content-Type' => 'application/json']);
        }

        /*********************** START Filter places based on location and range ************************/
        private function getFilteredPlaces($latitude, $longitude, $radius)
        {
            $places = Place::all();
            $filteredPlaces = [];

            foreach ($places as $place) {
                $coordinates = json_decode($place->coordinates, true);

                if (empty($coordinates) || !is_array($coordinates) || count($coordinates) < 3) {
                    continue;
                }

                if ($this->isPointInPolygon([$latitude, $longitude], $coordinates)) {
                    $distance = $this->haversineGreatCircleDistance($latitude, $longitude, $latitude, $longitude);

                    if ($distance < $radius) {
                        $place->distance = $distance;
                        $filteredPlaces[] = $place;
                    }
                }
            }

            usort($filteredPlaces, function ($a, $b) {
                return $a->distance <=> $b->distance;
            });

            return $filteredPlaces;
        }
        /*********************** END Filter places based on location and range ************************/


        /*********************** START Get available employees in specified locations ************************/
        private function getAvailableEmployeesPlaces($filteredPlaces, $reservation_status)
        {
            if ($reservation_status === 'at_home' || empty($reservation_status)) {
                $placeIds = array_column($filteredPlaces, 'id');
                $employees = AvailabilityEmployeePlace::whereIn('place_id', $placeIds)->with('employee')->get();

                $employees = $employees->sortBy(function ($availability) use ($filteredPlaces) {
                    $place = $filteredPlaces[array_search($availability->place_id, array_column($filteredPlaces, 'id'))];
                    return $place->distance;
                });
                // Log::info('getAvailableEmployeesPlaces :::', $employees->toArray());
                return $employees;
            } elseif ($reservation_status === 'at_spa') {
                $employees = Employee::where('work_location', 'spa')->get();
                //  Log::info('getAvailableEmployeesPlaces$reservation_status :::', $employees->toArray());

                return $employees;
            }

        }
        /*********************** END Get available employees in specified locations ************************/


        /*********************** START Check if the point is inside a polygon ************************/
        private function isPointInPolygon($point, $polygon)
        {
            $x = $point[0];
            $y = $point[1];
            $inside = false;
            $numVertices = count($polygon);
            $j = $numVertices - 1;

            for ($i = 0; $i < $numVertices; $i++) {
                $xi = $polygon[$i][0];
                $yi = $polygon[$i][1];
                $xj = $polygon[$j][0];
                $yj = $polygon[$j][1];

                $intersect = (($yi > $y) != ($yj > $y)) &&
                    ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi) + $xi);
                if ($intersect) {
                    $inside = !$inside;
                }
                $j = $i;
            }
            return $inside;
        }
        /*********************** END Check if the point is inside a polygon ************************/


        /*********************** START Calculating the distance between two points on the Earth's surface ************************/
        private function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
        {
            $earthRadius = 6371000;
            $latFrom = deg2rad($latitudeFrom);
            $lonFrom = deg2rad($longitudeFrom);
            $latTo = deg2rad($latitudeTo);
            $lonTo = deg2rad($longitudeTo);

            $latDelta = $latTo - $latFrom;
            $lonDelta = $lonTo - $lonFrom;

            $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                    cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

            return $angle * $earthRadius;
        }
        /*********************** END Calculating the distance between two points on the Earth's surface ************************/


        /*********************** START Determine available times based on date and service type ************************/

        private function getEmployeeAvailableTimes($employeeId, $date, $reservation_status)
        {
            // Convert the date from 'Y-m-d' format to 'Y-m-d' format
            $formattedDate = Carbon::createFromFormat('Y-m-d', $date, 'UTC')->format('Y-m-d');

            // Get the current date
            $currentDate = Carbon::now()->format('Y-m-d');

            // Determine the current time and add 2 hours if the date is today
            if ($formattedDate === $currentDate && $reservation_status === 'at_home') {
                $now = Carbon::now();
                // Add 2 hours
                $adjustedTime = $now->addHours(2);

                // If the minutes are greater than or equal to 45, round up to the next hour
                if ($adjustedTime->minute >= 30) {
                    $adjustedTime->addHour()->minute(0); // Round up to the next hour
                } else {
                    $adjustedTime->minute(0); // Set minutes to 0
                }

                // Format the current time after adjustments
                $currentTime = $adjustedTime->format('H:i');
            } elseif ($formattedDate === $currentDate && $reservation_status === 'at_spa') {
                $now = Carbon::now();
                $adjustedTime = $now->addHours();
                $currentTime = $adjustedTime->format('H:i');

            } else {
                $currentTime = '00:00'; // If the selected date is not today, start from midnight
            }

            // Get booked times for the specified date, excluding those with status 'canceled'
            $bookedSessions = DB::table('order_sessions')
                ->join('orders', 'order_sessions.order_id', '=', 'orders.id')
                ->whereDate('order_sessions.session_date', $formattedDate)
                ->where('orders.employee_id', $employeeId)
                ->where('orders.reservation_status', $reservation_status)
                ->where('order_sessions.status', '!=', 'canceled')
                ->select('order_sessions.session_date', 'orders.package_id', 'orders.category_id')
                ->get();

            // Map booked times to their corresponding session date in 'g:i A' format
            $bookedTimes = $bookedSessions->map(function ($session) {
                return [
                    'time' => Carbon::parse($session->session_date)->format('g:i A'),
                    'package_id' => $session->package_id,
                    'category_id' => $session->category_id,
                ];
            });

            // Get employee availability for the specified date
            $availabilities = Availability_Employees::where('employee_id', $employeeId)->get();
            $allPossibleTimes = [];

            foreach ($availabilities as $availability) {
                $startTime = Carbon::parse($availability->start_time);
                $endTime = Carbon::parse($availability->end_time);

                while ($startTime <= $endTime) {
                    $currentFormattedTime = $startTime->format('g:i A');
                    if (!in_array($currentFormattedTime, $allPossibleTimes)) {
                        $allPossibleTimes[] = $currentFormattedTime;
                    }
                    $startTime->addMinutes($reservation_status === 'at_spa' ? 10 : 60);
                }
            }

            // Filter times to include only those after the current time if today is the specified date
            $filteredTimes = array_filter($allPossibleTimes, function ($time) use ($currentTime) {
                return Carbon::createFromFormat('g:i A', $time)->greaterThanOrEqualTo(Carbon::createFromFormat('H:i', $currentTime));
            });

            // Remove booked times and consider only 1 hour after each booking for transportation
            $availableTimes = [];
            $transportationTime = ($reservation_status === 'at_spa') ? 0 : 0;

            // Collect booked times in a more convenient format for comparison
            $bookedTimeSlots = [];
            foreach ($bookedTimes as $booked) {
                $bookedStartTime = Carbon::createFromFormat('g:i A', $booked['time']);
                $sessionDuration = DB::table('packages')->where('id', $booked['package_id'])->value('duration_minutes') ?: 60;
                $bookedEndTime = $bookedStartTime->copy()->addMinutes($sessionDuration);

                // Add the booked start and end time to the booked time slots array
                $bookedTimeSlots[] = [
                    'start' => $bookedStartTime->copy()->subMinutes($transportationTime),
                    'end' => $bookedEndTime->copy()->addMinutes($transportationTime)
                ];
            }

            // Check availability for each filtered time
            foreach ($filteredTimes as $time) {
                $currentTime = Carbon::createFromFormat('g:i A', $time);
                $isTimeAvailable = true;

                // Check if the current time is within any booked time frame
                foreach ($bookedTimeSlots as $slot) {
                    if ($currentTime->between($slot['start'], $slot['end'], true)) {
                        $isTimeAvailable = false;
                        break; // No need to check further, it's already unavailable
                    }
                }

                // If the time is available, add it to the list
                if ($isTimeAvailable) {
                    $availableTimes[] = $currentTime->format('g:i A');
                }
            }

            // Remove duplicates and sort available times
            return array_unique($availableTimes);
        }

        /*********************** END Determine available times based on date and service type ************************/

        /*********************** START Get available employees based on location and services ************************/

        public function getAvailableEmployees(Request $request, $type = null)
        {
            // Get parameters
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');
            $categoryId = $request->input('category_id');
            $packageId = $request->input('package_id');
            $date = $request->input('date');
            $time = $request->input('time');
            $localeType = $type ?? '';
            $reservation_status = $request->input('reservation_status') ? $request->input('reservation_status') : '';
            if ($request->input('reservation_status') == null) {
                if ($localeType === 'homeServices') {
                    $reservation_status = 'at_home';
                } elseif ($localeType === 'SPA') {
                    $reservation_status = 'at_spa';
                }
            }
            //Log::info('$reservation_status::getAvailableEmployees$type$type: ' . $reservation_status);
            // Check location coordinates
            if ($reservation_status === 'at_home' && (is_null($latitude) || is_null($longitude))) {
                return response()->json(['message' => 'إحداثيات غير صحيحة'], 400);
            }

            try {
                $formattedDate = Carbon::createFromFormat('Y-m-d', $date, 'UTC')->format('Y-m-d');

                // Get nearby places
                $filteredPlaces = $this->getFilteredPlaces($latitude, $longitude, 10000);
                if (empty($filteredPlaces)) {
                    return response()->json(['message' => 'لا توجد أماكن قريبة ضمن النطاق المحدد']);
                }

                // Get available employees in specified locations
                $employees = $this->getAvailableEmployeesPlaces($filteredPlaces, $reservation_status);
                //  Log::info('$employees::: ', $employees->toArray());

                // Filter employees based on category if provided
                if ($categoryId) {
                    $employeeIdsWithCategory = Employee_Availability_Category::where('category_id', $categoryId)
                        ->pluck('employee_id');
                    if ($reservation_status === 'at_spa') {
                        $employee_id = 'id';  // إذا كان حالة الحجز "في السبا"
                    } else {
                        $employee_id = 'employee_id';  // إذا لم يكن، يمكنك استخدام حقل آخر أو تخصيص القيم هنا كما ترغب
                    }

// ثم نستخدم المتغير $employee_id في عملية الفلترة
                    $employees = $employees->filter(function ($availability) use ($employeeIdsWithCategory, $employee_id) {
                        return $employeeIdsWithCategory->contains($availability->$employee_id);
                    });

                    // Log::info('$employeeIdsWithCategory::: ', $employeeIdsWithCategory->toArray());
                }

                // Filter employees based on package if provided
                if ($packageId) {
                    //Log::info('Employee_Availability_Packages::where(\'package_id\', $packageId)::', Employee_Availability_Packages::where('package_id', $packageId)->get()->toArray());

                    $employeeIdsWithPackage = Employee_Availability_Packages::where('package_id', $packageId)
                        ->pluck('employee_id');
                    if ($reservation_status === 'at_spa') {
                        $employee_id = 'id';  // إذا كان حالة الحجز "في السبا"
                    } else {
                        $employee_id = 'employee_id';  // إذا لم يكن، يمكنك استخدام حقل آخر أو تخصيص القيم هنا كما ترغب
                    }
                    $employees = $employees->filter(function ($availability) use ($employeeIdsWithPackage, $employee_id) {
                        return $employeeIdsWithPackage->contains($availability->$employee_id);
                    });

                    // Log::info('$employeeIdsWithPackage::: ', $employeeIdsWithPackage->toArray());
                    // Log::info('$employees بعد تطبيق الفلترة بناء على package: ', $employees->toArray());
                }


                $availableTimesData = [];
                // Log::info('$formattedDate::: ' . $formattedDate);
                if ($reservation_status === 'at_spa') {
                    $employee_id = 'id';  // إذا كان حالة الحجز "في السبا"
                } else {
                    $employee_id = 'employee_id';  // إذا لم يكن، يمكنك استخدام حقل آخر أو تخصيص القيم هنا كما ترغب
                }
                // Filter employees based on their availability on the specified date
                if ($formattedDate) {
                    $availableEmployeesIds = Availability_Employee_Days::where('day_of_week_id', Carbon::parse($formattedDate)->dayOfWeek)
                        ->pluck('availability_employee_id');
                    //  Log::info('$availableEmployeesIds بعد تحديد التاريخ:', $availableEmployeesIds->toArray());

                    $employees = $employees->filter(function ($availability) use ($availableEmployeesIds, $employee_id) {
                        return $availableEmployeesIds->contains($availability->$employee_id);
                    });

                    // Log employees after filtering by date
                    // Log::info('$employees بعد تطبيق فلترة التاريخ: ', $employees->toArray());

                    // Loop through each employee to get their available times
                    foreach ($employees as $employee) {
                        $availableTimes = $this->getEmployeeAvailableTimes($employee->$employee_id, $formattedDate, $reservation_status);
                        //  Log::info('$availableTimes for employee: ', $availableTimes);
                        if ($reservation_status === 'at_spa') {
                            $employee_name = $employee->getTranslation('name', 'ar');  // إذا كان حالة الحجز "في السبا"
                        } else {
                            $employee_name = $employee->employee->getTranslation('name', 'ar');  // إذا لم يكن، يمكنك استخدام حقل آخر أو تخصيص القيم هنا كما ترغب
                        }
                        if (!empty($availableTimes)) {
                            $availableTimesData[] = [
                                'employee_id' => $employee->$employee_id,
                                'employee_name' => $employee_name,
                                'available_times' => $availableTimes,
                            ];
                        }
                    }
                }

                return response()->json(['available_employees' => $availableTimesData]);
            } catch (\Exception $e) {
                Log::error('Error occurred: ' . $e->getMessage());
                return response()->json(['error' => 'حدث خطأ أثناء معالجة الطلب.'], 500);
            }
        }

        /*********************** END Get available employees based on location and services ************************/
    }
