<?php

    namespace App\Http\Controllers\Dashboard;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\Dashboard\StorePackageRequest;
    use App\Models\Employee;
    use App\Models\Employee_Availability_Packages;
    use App\Models\Package_Availability;
    use App\Models\Packages;
    use App\Models\packages_prices;
    use Brian2694\Toastr\Facades\Toastr;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;

    class PackagesController extends Controller
    {
        /**
         * Display a listing of the resource.
         */
        public function index()
        {
            $packages = Packages::with(['service', 'prices', 'employees', 'availability'])->get();

            return view('Dashboard.packages.index', compact('packages'));
        }

        /**
         * Show the form for creating a new resource.
         */
        public function create()
        {
            return view('Dashboard.packages.create');
        }

        /**
         * Store a newly created resource in storage.
         */
        public function store(StorePackageRequest $request)
        {
            try {

                $name = [
                    'ar' => $request->input('name_ar'),
                    'en' => $request->input('name_en')
                ];

                $description = [
                    'ar' => $request->input('description_ar'),
                    'en' => $request->input('description_en')
                ];

                $benefits = [
                    'ar' => $request->input('inputs_ar', []),
                    'en' => $request->input('inputs_en', [])
                ];

                // إنشاء حزمة جديدة
                $package = Packages::create([
                    'name' => $name,
                    'sessions_count' => $request->input('num_sessions'),
                    'duration_minutes' => $request->input('time'),
                    'description' => $description,
                    'benefits' => $benefits,
                    'service_id' => 1, // assuming service_id is available in request
                    'created_by' => auth('admin')->id(), // assuming the user is authenticated
                    'is_active' => true
                ]);

                // إدخال الأسعار
                Packages_Prices::create([
                    'package_id' => $package->id,
                    'at_home' => $request->input('price_home'),
                    'at_spa' => $request->input('price_spa'),
                ]);

                // إدخال التوفر
                Package_Availability::create([
                    'package_id' => $package->id,
                    'in_home' => in_array('home', $request->input('work_location')),
                    'in_spa' => in_array('spa', $request->input('work_location')),
                ]);

                // إدخال الموظفين المتاحين
                if ($request->has('employee_ids')) {
                    foreach ($request->input('employee_ids') as $employee_id) {
                        Employee_Availability_Packages::create([
                            'employee_id' => $employee_id,
                            'package_id' => $package->id,
                        ]);
                    }
                }

                Toastr::success(' 😀  لقد تم أضافة الباقة بنجاح', 'عملية ناجحة');

                // Redirect back with a success message
                return redirect()->route('admin.packages.index');

            } catch (\Exception $e) {
                // تسجيل الخطأ في اللوج
                Log::error('Error in store package: ' . $e->getMessage(), [
                    'stack_trace' => $e->getTraceAsString(),
                    'error_code' => $e->getCode(),
                    'request_data' => $request->all()
                ]);

                return redirect()->back()->withErrors($e->getMessage())->withInput();
            }
        }

        /**
         * Display the specified resource.
         */
        public function show(string $id)
        {
            //
        }

        /**
         * Show the form for editing the specified resource.
         */
        public function edit(string $id)
        {
            $package = Packages::with(['service', 'prices', 'employees', 'availability'])->findOrFail($id);

            $work_locations = [
                'home' => 'خدمات منزلية',
                'spa' => 'خدمات داخل الفرع'
            ];
            $employees = Employee::all();
            return view('Dashboard.packages.edit', [
                'package' => $package,
                'work_locations' => $work_locations,
                'employees' => $employees,
                'benefits_ar' => $package->getTranslation('benefits', 'ar'),
                'benefits_en' => $package->getTranslation('benefits', 'en'),

            ]);
        }

        /**
         * Update the specified resource in storage.
         */
        public function update(Request $request, string $id)
        {
            try {
                // البحث عن الحزمة المطلوبة
                $package = Packages::findOrFail($id);

                // تحديث بيانات الاسم والوصف والمزايا
                $name = [
                    'ar' => $request->input('name_ar'),
                    'en' => $request->input('name_en')
                ];

                $description = [
                    'ar' => $request->input('description_ar'),
                    'en' => $request->input('description_en')
                ];

                $benefits = [
                    'ar' => $request->input('inputs_ar', []),
                    'en' => $request->input('inputs_en', [])
                ];

                // تحديث بيانات الحزمة
                $package->update([
                    'name' => $name,
                    'sessions_count' => $request->input('num_sessions'),
                    'duration_minutes' => $request->input('time'),
                    'description' => $description,
                    'benefits' => $benefits,
                    'service_id' => 1, // assuming service_id is available in request
                    'updated_by' => auth('admin')->id(), // assuming the user is authenticated
                    'is_active' => true
                ]);

                // تحديث الأسعار
                $package->prices()->updateOrCreate(
                    ['package_id' => $package->id],
                    [
                        'at_home' => $request->input('price_home'),
                        'at_spa' => $request->input('price_spa'),
                    ]
                );

                // تحديث التوفر
                $work_location = $request->input('work_location', []);

                $package->availability()->updateOrCreate(
                    ['package_id' => $package->id],
                    [
                        'in_home' => in_array('home', $work_location),
                        'in_spa' => in_array('spa', $work_location),
                    ]
                );


                // تحديث الموظفين المتاحين
                if ($request->has('employee_ids')) {
                    // حذف الموظفين السابقين
                    $package->employees()->sync($request->input('employee_ids'));
                }

                Toastr::success(' 😀  تم تحديث الباقة بنجاح', 'عملية ناجحة');

                // إعادة التوجيه إلى صفحة الحزم
                return redirect()->route('admin.packages.index');

            } catch (\Exception $e) {
                // تسجيل الخطأ في اللوج
                Log::error('Error in update package: ' . $e->getMessage(), [
                    'stack_trace' => $e->getTraceAsString(),
                    'error_code' => $e->getCode(),
                    'request_data' => $request->all()
                ]);

                return redirect()->back()->withErrors($e->getMessage())->withInput();
            }

        }

        /**
         * Remove the specified resource from storage.
         */
        public function destroy(Packages $package)
        {
            try {
                $package->delete();
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                // تسجيل الخطأ في اللوج
//                Log::error('Error in delete package: ' . $e->getMessage(), [
//                    'stack_trace' => $e->getTraceAsString(),
//                    'error_code' => $e->getCode(),
//                    'package_id' => $package->id
//                ]);
// Rollback the transaction if an error occurs
                DB::rollBack();
                // Handle any exceptions
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }
        }


        public function fetchEmployees(Request $request)
        {
            $workLocations = $request->input('work_locations');
            // Retrieve employees based on selected work locations
            $employees = Employee::whereIn('work_location', $workLocations)->get();
            // Prepare employees with translations
            $employees = $employees->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'name' => $employee->getTranslation('name', 'ar') // Assuming 'ar' is the Arabic locale
                ];
            });
            return response()->json($employees);
        }

        public function update_Status(Request $request, $id)
        {
            $package = Packages::findOrFail($id);
            $package->is_active = $request->is_active;
            if ($package->save()) {
                return response()->json(['success' => true]);
            }
            return response()->json(['success' => false]);
        }

        public function updateAvailability(Request $request)
        {
            $request->validate([
                'package_id' => 'required|integer|exists:packages,id',
                'in_home' => 'required|boolean',
                'in_spa' => 'required|boolean',
            ]);

            $packageId = $request->input('package_id');
            $homeServices = $request->input('in_home');
            $branchServices = $request->input('in_spa');

            try {
                Package_Availability::updateOrCreate(
                    ['package_id' => $packageId],
                    [
                        'in_home' => $homeServices,
                        'in_spa' => $branchServices,
                    ]
                );

                return response()->json(['status' => 'success']);

            } catch (\Exception$e) {
                //Log::error('Error updating availability: ' . $e->getMessage());
                return response()->json(['status' => 'error']);

            }
        }
    }
