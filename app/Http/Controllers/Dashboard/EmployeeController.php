<?php

    namespace App\Http\Controllers\Dashboard;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\Dashboard\UpdateEmployeeRequest;
    use App\Models\Availability_Employees;
    use App\Models\DayOfWeek;
    use App\Models\Employee;
    use App\Models\Place;
    use Brian2694\Toastr\Facades\Toastr;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;

    class EmployeeController extends Controller
    {
        public function index()
        {
            $employees = Employee::with('availability')->get();

            return view('Dashboard.employee.index', compact('employees'));
        }

        public function create()
        {
            $places = Place::with('admin')->where('is_active', true)->get();
            $daysOfWeek = DayOfWeek::all();
            $color = Availability_Employees::generateUniqueColor();
            return view('Dashboard.employee.create', compact('places', 'daysOfWeek', 'color'));
        }

        public function store(Request $request)
        {
            try {
                //Start transaction
                DB::beginTransaction();

                // Save data with name in Arabic and English
                $employee = Employee::create([
                    'name' => [
                        'en' => $request->input('name_en'),
                        'ar' => $request->input('name_ar'),
                    ],
                    'code' => $request->input('employee_code'),
                    'email' => $request->input('email'),
                    'phone' => str_replace('-', '', $request->input('phone')), // Remove the hyphen from the phone number
                    'identity_card' => $request->input('national_id'),
                    'country' => $request->input('employee_nationality'),
                    'work_location' => $request->input('work_location'),
                    'created_by' => auth('admin')->id(),
                ]);

                // Save availability data
                $availabilityEmployee = Availability_Employees::create([
                    'employee_id' => $employee->id,
                    'color' => $request->input('employee_color'),
                    'start_time' => $request->input('start_time'),
                    'end_time' => $request->input('end_time'),
                ]);
                if ($request->has('place_ids') && is_array($request->input('place_ids'))) {
                    foreach ($request->input('place_ids') as $placeId) {
                        DB::table('availability_employee_places')->insert([
                            'employee_id' => $employee->id,
                            'place_id' => $placeId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
                if ($request->has('working_days') && is_array($request->input('working_days'))) {
                    foreach ($request->input('working_days') as $dayId) {
                        DB::table('availability_employee_days')->insert([
                            'availability_employee_id' => $availabilityEmployee->id,
                            'day_of_week_id' => $dayId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }


                // If saved successfully, confirm the transaction.
                DB::commit();
                Toastr::success(' 😀  لقد تم أضافة الموظف بنجاح', 'عملية ناجحة');

                return redirect()->route('admin.employees.index');

            } catch (\Exception $e) {

                // If any problem occurs, cancel the transaction.
                DB::rollBack();

                Toastr::error('حدث خطأ أثناء حفظ البيانات ', 'خطأ');
                Log::error('Error in store employee: ' . $e->getMessage(), ['stack_trace' => $e->getTraceAsString(), 'error_code' => $e->getCode(), 'request_data' => $request->all()]);
                return redirect()->route('admin.employees.index')->withErrors(['error' => 'حدث خطأ أثناء حفظ البيانات: ' . $e->getMessage()])->withInput();
            }
        }

        public function show()
        {
        }

        public function edit($id)
        {

            $employee = Employee::with('places')->findOrFail($id);
            $places = Place::all();
            $daysOfWeek = DayOfWeek::all();

            // تحويل مجموعة Collection إلى مصفوفة
            $employeePlaces = $employee->places->pluck('id')->toArray();
            $employeeDays = $employee->workingDays->pluck('id')->toArray();

            return view('Dashboard.employee.edit', [
                'employee' => $employee,
                'places' => $places,
                'daysOfWeek' => $daysOfWeek,
                'employeePlaces' => $employeePlaces,
                'employeeDays' => $employeeDays
            ]);
        }

        public function update(Request $request, $id)
        {
            try {
                // Start transaction
                DB::beginTransaction();

                // Find employee by ID
                $employee = Employee::findOrFail($id);

                // Update employee data
                $employee->update([
                    'name' => [
                        'en' => $request->input('name_en'),
                        'ar' => $request->input('name_ar'),
                    ],
                    'code' => $request->input('employee_code'),
                    'email' => $request->input('email'),
                    'phone' => str_replace('-', '', $request->input('phone')), // Remove the hyphen from the phone number
                    'identity_card' => $request->input('national_id'),
                    'country' => $request->input('employee_nationality'),
                    'work_location' => $request->input('work_location'),
                    'created_by' => auth('admin')->id(), // Assuming you have an updated_by field
                ]);

                // Find and update availability data
                $availabilityEmployee = Availability_Employees::where('employee_id', $employee->id)->first();
                if ($availabilityEmployee) {
                    $availabilityEmployee->update([
                        'color' => $request->input('employee_color'),
                        'start_time' => $request->input('start_time'),
                        'end_time' => $request->input('end_time'),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    $availabilityEmployee = Availability_Employees::create([
                        'employee_id' => $employee->id,
                        'color' => $request->input('employee_color'),
                        'start_time' => $request->input('start_time'),
                        'end_time' => $request->input('end_time'),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Update place_ids
                DB::table('availability_employee_places')->where('employee_id', $employee->id)->delete();
                if ($request->has('place_ids') && is_array($request->input('place_ids'))) {
                    foreach ($request->input('place_ids') as $placeId) {
                        DB::table('availability_employee_places')->insert([
                            'employee_id' => $employee->id,
                            'place_id' => $placeId,
                            'updated_at' => now(),
                        ]);
                    }
                }

                // Update working_days
                DB::table('availability_employee_days')->where('availability_employee_id', $availabilityEmployee->id)->delete();
                if ($request->has('working_days') && is_array($request->input('working_days'))) {
                    foreach ($request->input('working_days') as $dayId) {
                        DB::table('availability_employee_days')->insert([
                            'availability_employee_id' => $availabilityEmployee->id,
                            'day_of_week_id' => $dayId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }

                // If saved successfully, confirm the transaction.
                DB::commit();
                Toastr::success(' 😀  لقد تم تحديث الموظف بنجاح', 'عملية ناجحة');

                return redirect()->route('admin.employees.index');

            } catch (\Exception $e) {
                // If any problem occurs, cancel the transaction.
                DB::rollBack();

                Toastr::error('حدث خطأ أثناء تحديث البيانات ', 'خطأ');
                Log::error('Error in update employee: ' . $e->getMessage(), ['stack_trace' => $e->getTraceAsString(), 'error_code' => $e->getCode(), 'request_data' => $request->all()]);
                return redirect()->route('admin.employees.index')->withErrors(['error' => 'حدث خطأ أثناء تحديث البيانات: ' . $e->getMessage()])->withInput();
            }
        }

        public function destroy($id)
        {
            dd($id);
        }
    }
