<?php

    namespace App\Http\Controllers\Dashboard;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\Dashboard\StoreCategoryRequest;
    use App\Models\categories;
    use App\Models\categories_prices;
    use App\Models\Category_Availability;
    use App\Models\Employee;
    use App\Models\Services;
    use Brian2694\Toastr\Facades\Toastr;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;

    class CategoriesController extends Controller
    {
        /**
         * Display a listing of the resource.
         */
        public function index()
        {
            $categories = categories::with(['service', 'prices', 'employees', 'availability'])->get();

            return view('Dashboard.categories.index', compact('categories'));
        }

        /**
         * Show the form for creating a new resource.
         */
        public function create()
        {
            $services = Services::where('is_active', true)->with('serviceAvailability')->get();

            return view('Dashboard.categories.create', compact('services'));
        }

        /**
         * Store a newly created resource in storage.
         */
        public function store(StoreCategoryRequest $request)
        {

            try {
                // Create the category
                $category = new categories();
                $category->name = [
                    'ar' => $request->name_ar,
                    'en' => $request->name_en
                ];
                $category->description = [
                    'ar' => $request->description_ar,
                    'en' => $request->description_en
                ];
                $category->benefits = [
                    'ar' => $request->input('inputs_ar', []),
                    'en' => $request->input('inputs_en', [])
                ];

                $category->created_by = auth('admin')->id(); // Assuming you have an authenticated admin user
                $category->sessions_count = 1;
                $category->duration_minutes = $request->time;
                $category->service_id = $request->services; // Assuming you allow selecting only one service at a time
                // Assuming the category is active by default
                $category->save();

                // Insert into category_availability table
                $availability = new Category_Availability();
                $availability->category_id = $category->id;
                $availability->in_spa = in_array('spa', $request->work_location);
                $availability->in_home = in_array('home', $request->work_location);
                $availability->save();

                $price = new categories_prices();
                $price->category_id = $category->id;
                $price->at_spa = $request->price_spa;
                $price->at_home = $request->price_home;
                $price->save();
                // Insert into employee_availability_category table
                if ($request->employee_ids) {
                    foreach ($request->employee_ids as $employee_id) {
                        DB::table('employee_availability_category')->insert([
                            'employee_id' => $employee_id,
                            'category_id' => $category->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
                Toastr::success(' 😀  لقد تم أضافة الخدمة بنجاح', 'عملية ناجحة');

                // Redirect back with a success message
                return redirect()->route('admin.categories.index');

                //return redirect()->back()->with('success', 'Category created successfully.');
            } catch (\Exception $e) {
                // تسجيل الخطأ باستخدام Log
                Log::error('Error in store category: ' . $e->getMessage(), [
                    'stack_trace' => $e->getTraceAsString(),
                    'error_code' => $e->getCode(),
                    'request_data' => $request->all()
                ]);

                return redirect()->back()->withErrors($request)->withInput();

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
            try {
                $category = Categories::with(['availability', 'prices', 'employees'])->findOrFail($id);
                $services = Services::where('is_active', true)->with('serviceAvailability')->get();
                $work_locations = [
                    'home' => 'خدمات منزلية',
                    'spa' => 'خدمات داخل الفرع'
                ];
                $benefits_ar = $category->getTranslation('benefits', 'ar');
                $benefits_en = $category->getTranslation('benefits', 'en');
                $employees = Employee::all();
                return view('Dashboard.categories.edit', compact('category', 'services', 'work_locations', 'benefits_ar', 'benefits_en', 'employees'));
            } catch (\Exception $e) {
                Log::error('Error in edit category: ' . $e->getMessage(), [
                    'stack_trace' => $e->getTraceAsString(),
                    'error_code' => $e->getCode(),
                ]);

                return redirect()->back()->withErrors('تعذر تحميل الخدمة للتحرير')->withInput();
            }
        }

        /**
         * Update the specified resource in storage.
         */
        public function update(Request $request, string $id)
        {
            try {
                // تحديث الفئة مباشرة باستخدام Eloquent
                Categories::where('id', $id)->update([
                    'name' => [
                        'ar' => $request->name_ar,
                        'en' => $request->name_en
                    ],
                    'description' => [
                        'ar' => $request->description_ar,
                        'en' => $request->description_en
                    ],
                    'benefits' => [
                        'ar' => $request->input('inputs_ar', []),
                        'en' => $request->input('inputs_en', [])
                    ],
                    'sessions_count' => 1,
                    'duration_minutes' => $request->time,
                    'service_id' => $request->services,
                    'created_by' => auth('admin')->id(),
                    'updated_at' => now() // تحديث وقت التعديل
                ]);

                // تحديث التوفر
                Category_Availability::where('category_id', $id)->update([
                    'in_spa' => in_array('spa', $request->work_location),
                    'in_home' => in_array('home', $request->work_location)
                ]);

                // تحديث الأسعار
                Categories_Prices::where('category_id', $id)->update([
                    'at_spa' => $request->price_spa,
                    'at_home' => $request->price_home
                ]);

                // تحديث الموظفين المتاحين
                if ($request->has('employee_ids')) {
                    $category = Categories::findOrFail($id); // جلب الكائن لمواصلة استخدام العلاقة
                    $category->employees()->sync($request->input('employee_ids'));
                }

                Toastr::success(' 😀  لقد تم تعديل الخدمة بنجاح', 'عملية ناجحة');

                // إعادة التوجيه إلى صفحة العرض مع رسالة نجاح
                return redirect()->route('admin.categories.index');
            } catch (\Exception $e) {
                // تسجيل الخطأ باستخدام Log
                Log::error('Error in update category: ' . $e->getMessage(), [
                    'stack_trace' => $e->getTraceAsString(),
                    'error_code' => $e->getCode(),
                    'request_data' => $request->all()
                ]);
                return redirect()->back()->withErrors('تعذر تحديث الخدمة')->withInput();
            }
        }

        /**
         * Remove the specified resource from storage.
         */
        public function destroy(categories $categories)
        {
            try {
                $categories->delete();
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
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
            $category = categories::findOrFail($id);
            $category->is_active = $request->is_active;
            if ($category->save()) {
                return response()->json(['success' => true]);
            }
            return response()->json(['success' => false]);
        }

        public function updateCategoryAvailability(Request $request)
        {
            $request->validate([
                'category_id' => 'required|integer|exists:categories,id',
                'in_home' => 'required|boolean',
                'in_spa' => 'required|boolean',
            ]);

            $categoryId = $request->input('category_id');
            $in_home = $request->input('in_home');
            $in_spa = $request->input('in_spa');

            try {
                Category_Availability::updateOrCreate(
                    ['category_id' => $categoryId],
                    [
                        'in_home' => $in_home,
                        'in_spa' => $in_spa,
                    ]
                );

                return response()->json(['status' => 'success']);
            } catch (\Exception $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }

        }

    }
