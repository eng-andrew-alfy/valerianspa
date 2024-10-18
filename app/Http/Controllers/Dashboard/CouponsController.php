<?php

    namespace App\Http\Controllers\Dashboard;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\Dashboard\StoreCouponRequest;
    use App\Http\Requests\Dashboard\UpdateCouponRequest;
    use App\Models\categories;
    use App\Models\Coupon;
    use App\Models\Packages;
    use Brian2694\Toastr\Facades\Toastr;
    use Carbon\Carbon;
    use Illuminate\Database\Eloquent\ModelNotFoundException;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Validation\ValidationException;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Http\Request;

    class CouponsController extends Controller
    {
        public function index()
        {
            $coupons = Coupon::with('categories', 'packages')->orderBy('created_at', 'desc')->get();
            return view('Dashboard.coupons.index', compact('coupons'));
        }

        public function show($id)
        {
            $coupon = Coupon::with('categories', 'packages', 'usages')->findOrFail($id);
            return response()->json($coupon);
        }

        public function create()
        {
            $categories = categories::where('is_active', true)->get();
            $packages = Packages::where('is_active', true)->get();
            $today = Carbon::today();

            $endDate = $today->copy()->addDays(5);

            $dateRange = $today->format('m/d/Y') . ' - ' . $endDate->format('m/d/Y');
            return view('Dashboard.coupons.create', compact('categories', 'packages', 'dateRange'));
        }

        public function store(StoreCouponRequest $request)
        {
            try {
                if ($request->input('coupon_type') === 'temporary') {
                    if ($request->has('daterange')) {
                        $dateRange = $request->input('daterange');
                        $dates = $this->parseDateRange($dateRange);
                        $startDate = $dates['start'];
                        $endDate = $dates['end'];
                    }
                } else {
                    $startDate = null;
                    $endDate = null;
                }
                if ($request->input('time_statues') === 'restricted') {
                    $startTime = $request->input('start_time');
                    $endTime = $request->input('end_time');

                } else {
                    $startTime = null;
                    $endTime = null;
                }

                // Create the coupon
                $coupon = Coupon::create([
                    'code' => $request->input('code'),
                    'discount_type' => $request->input('discount_type'),
                    'coupon_type' => $request->input('coupon_type'),
                    'value' => $request->input('value'),
                    'usage_limit' => $request->input('usage_limit'),
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'is_active' => true,
                    'created_by' => auth('admin')->id(),
                ]);

                // Attach categories and packages
                $associations = [
                    'categories' => 'categories',
                    'packages' => 'packages',
                ];

                foreach ($associations as $inputKey => $relation) {
                    if ($request->has($inputKey)) {
                        $items = $request->input($inputKey);
                        if (is_array($items)) {
                            $coupon->$relation()->attach($items);
                        } else {
                            $coupon->$relation()->attach([$items]);
                        }
                    }
                }

                Toastr::success('😀 لقد تم إضافة الكوبون بنجاح', 'عملية ناجحة');
                return redirect()->route('admin.coupons.index');
            } catch (\Exception $e) {
                // Log the exception and display an error message
                Log::error('Error while storing coupon: ' . $e->getMessage());
                Toastr::error('حدث خطأ أثناء إضافة الكوبون', 'عملية فاشلة');
                return redirect()->route('admin.coupons.index')->withInput();
            }
        }

        public function edit($id)
        {
            $coupon = Coupon::with('categories', 'packages')->findOrFail($id);

            $categories = categories::where('is_active', true)->get();
            $packages = Packages::where('is_active', true)->get();
            $startDate = $coupon->start_date ? \Carbon\Carbon::parse($coupon->start_date) : null;
            $endDate = $coupon->end_date ? \Carbon\Carbon::parse($coupon->end_date) : null;

            return view('Dashboard.coupons.edit', compact('coupon', 'categories', 'packages', 'startDate', 'endDate'));
        }

        public function update(UpdateCouponRequest $request, $id)
        {
            try {
                // Validation is already handled by UpdateCouponRequest

                $coupon = Coupon::findOrFail($id);

                $data = $request->only([
                    'code',
                    'discount_type',
                    'value',
                    'usage_limit',
                    'coupon_type',
                ]);

                if ($request->has('daterange')) {
                    [$startDate, $endDate] = explode(' - ', $request->input('daterange'));
                    $data['start_date'] = Carbon::createFromFormat('m/d/Y', $startDate);
                    $data['end_date'] = Carbon::createFromFormat('m/d/Y', $endDate);
                }

                $coupon->update($data);

                if ($request->has('categories')) {
                    $coupon->categories()->sync($request->input('categories'));
                }

                if ($request->has('packages')) {
                    $coupon->packages()->sync($request->input('packages'));
                }

                Toastr::success('😀 لقد تم تعديل الكوبون بنجاح', 'عملية ناجحة');

                return redirect()->route('admin.coupons.index');
            } catch (ModelNotFoundException $e) {
                Toastr::error('📛انت تقوم بعملية انتهاك واضحة ومن الممكن انك تريد تخريب النظام', 'خطأ');
                return redirect()->route('admin.coupons.index');
            } catch (ValidationException $e) {
                Toastr::error('📛 هناك خطأ في البيانات المدخلة', 'خطأ');
                return redirect()->back()->withErrors($e->errors())->withInput();
            } catch (\Exception $e) {
                Toastr::error('📛 حدث خطأ غير متوقع', 'خطأ');
                return redirect()->route('admin.coupons.index');
            }
        }

        public function parseDateRange($dateRange)
        {
            // Split the string into two dates
            $dates = explode(' - ', $dateRange);

            // Check if we have exactly two dates
            if (count($dates) === 2) {
                $startDate = trim($dates[0]); // Extract and trim the start date
                $endDate = trim($dates[1]); // Extract and trim the end date

                // Convert the dates to Carbon objects for date manipulation
                $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', $startDate);
                $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', $endDate);

                // Determine which date is earlier and which is later
                if ($startDate->gt($endDate)) {
                    // Swap dates if start date is greater than end date
                    $temp = $startDate;
                    $startDate = $endDate;
                    $endDate = $temp;
                }

                // Return the start and end dates in 'Y-m-d' format (year-month-day)
                return [
                    'start' => $startDate->format('Y-m-d'),
                    'end' => $endDate->format('Y-m-d'),
                ];
            }

            // If the string is not in the correct format, return empty values or handle the error
            return [
                'start' => null,
                'end' => null,
            ];
        }

        public function update_Status(Request $request, $id)
        {
            $coupon = Coupon::findOrFail($id);
            $coupon->is_active = $request->is_active;
            if ($coupon->save()) {
                return response()->json(['success' => true]);
            }
            return response()->json(['success' => false]);
        }

        public function destroy($couponId)
        {
            DB::beginTransaction();

            try {
                // Find the service by ID
                $service = Coupon::findOrFail($couponId);


                // Delete the service
                $service->delete();


                // Commit the transaction
                DB::commit();

                // Return success response
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                // Rollback the transaction if an error occurs
                DB::rollBack();

                // Handle any exceptions
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }
        }

    }
