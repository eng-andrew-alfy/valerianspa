<?php

    namespace App\Http\Controllers\Dashboard;

    use App\Http\Controllers\Controller;
    use App\Models\Service_Availabilities;
    use App\Models\Services;
    use Brian2694\Toastr\Facades\Toastr;
    use Illuminate\Http\Request;

    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Storage;
    use WebPConvert\WebPConvert;


    class ServicesController extends Controller
    {
        /**
         * Display a listing of the resource.
         */
        public function index()
        {
            // Retrieve all services along with their availability data
            $services = Services::with('serviceAvailability')->get();

            // Pass the data to the view
            return view('Dashboard.services.index', compact('services'));
        }

        /**
         * Show the form for creating a new resource.
         */
        public function create()
        {
            //
        }

        /**
         * Store a newly created resource in storage.
         */
        public function store(Request $request)
        {
            DB::beginTransaction();

            try {
                // Validate input data
                $request->validate([
                    'arabic_name' => 'required|string|max:255',
                    'english_name' => 'required|string|max:255',
                    'services' => 'required|array',
                    'file_name' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
                ]);

                // Initialize image variables
                $imageName = null;
                $path = null;

                // Check if a file is attached
                if ($request->hasFile('file_name')) {
                    // Get the uploaded image
                    $image = $request->file('file_name');

                    // Check if the image is already WebP
                    $imageMimeType = $image->getMimeType();

                    if ($imageMimeType === 'image/webp') {
                        // If the image is already WebP, just move it to the destination
                        $imageName = time() . '.webp'; // Keep the WebP extension
                        $path = storage_path('app/public/uploads/services/' . $imageName);
                        $image->move(storage_path('app/public/uploads/services/'), $imageName);
                    } else {
                        // If the image is not WebP, convert it to WebP format
                        $imageName = time() . '.webp'; // Use WebP extension
                        $path = storage_path('app/public/uploads/services/' . $imageName);

                        // Convert the image to WebP format using WebPConvert
                        WebPConvert::convert($image->getPathname(), $path, [
                            'quality' => 90,
                        ]);
                    }
                }


                // Save service data
                $service = new Services();
                $service->name = ['en' => $request->english_name, 'ar' => $request->arabic_name];
                $service->created_by = auth('admin')->id();
                $service->image = $imageName; // Set image name if it was uploaded
                $service->save(); // Save the service

                // Retrieve the service ID
                $serviceId = $service->id;

                // Process and save service availability data
                $services = $request->input('services', []);
                $availability = new Service_Availabilities();
                $availability->service_id = $serviceId;
                $availability->in_spa = in_array('home_services', $services);
                $availability->in_home = in_array('branch_services', $services);
                $availability->save(); // Save the availability data
                // Commit the transaction
                DB::commit();

                // Notify success
                Toastr::success(' 😀  لقد تم أضافة القسم بنجاح', 'عملية ناجحة');
                return redirect()->route('admin.services.index');
            } catch (\Exception $e) {
                // Rollback the transaction if an error occurs
                DB::rollBack();

                // Remove the image if it was saved
                if ($path && file_exists($path)) {
                    unlink($path);
                }

                // Handle any exceptions
                return redirect()->back()->withErrors(['error' => $e->getMessage()]);
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
        public function edit($id)
        {
            try {
                $service = Services::with('serviceAvailability')->findOrFail($id);

                // تسجيل معلومات الخدمة
                Log::info('Fetched service for edit: ' . json_encode($service));

                return response()->json([
                    'success' => true,
                    'services' => [
                        'id' => $service->id,
                        'arabic_name' => $service->getTranslation('name', 'ar'),
                        'english_name' => $service->getTranslation('name', 'en'),
                        'in_home' => $service->serviceAvailability->in_home,
                        'in_spa' => $service->serviceAvailability->in_spa,
                        'image' => asset('storage/uploads/services/' . $service->image), // تأكد من مسار الصورة هنا
                    ]
                ]);
            } catch (\Exception $e) {
                // تسجيل الخطأ
                Log::error('Error fetching service for edit: ' . $e->getMessage());

                return response()->json(['error' => 'Service not found.'], 404);
            }
        }


        /**
         * Update the specified resource in storage.
         */
        public function update(Request $request, $serviceId)
        {
            DB::beginTransaction();

            try {
                // Validate input data
                $request->validate([
                    'arabic_name' => 'required|string|max:255',
                    'english_name' => 'required|string|max:255',
                    'services' => 'required|array',
                    'file_name' => 'sometimes|image|mimes:jpeg,png,jpg,webp|max:2048',
                ]);

                // Initialize image variables
                $imageName = null;
                $path = null;

                // Check if a file is attached
                if ($request->hasFile('file_name')) {
                    // Get the uploaded image
                    $image = $request->file('file_name');
                    $imageName = time() . '.webp'; // Use WebP extension
                    $path = storage_path('app/public/uploads/services/' . $imageName);

                    // Convert the image to WebP format using WebPConvert
                    WebPConvert::convert($image->getPathname(), $path, [
                        'quality' => 90,
                    ]);
                }

                // Update existing service
                $service = Services::findOrFail($serviceId);
                $service->name = ['en' => $request->english_name, 'ar' => $request->arabic_name];
                if ($imageName) {
                    $service->image = $imageName; // Update image if a new one was uploaded
                }
                $service->save(); // Save the updated service

                // Process and save service availability data
                $services = $request->input('services', []);
                $availability = Service_Availabilities::updateOrCreate(
                    ['service_id' => $serviceId],
                    [
                        'in_spa' => in_array('home_services', $services),
                        'in_home' => in_array('branch_services', $services),
                    ]
                );

                // Commit the transaction
                DB::commit();

                // Notify success
                Toastr::success('😀 لقد تم تحديث القسم بنجاح', 'عملية ناجحة');
                return redirect()->route('admin.services.index');
            } catch (\Exception $e) {
                // Rollback the transaction if an error occurs
                DB::rollBack();

                // Remove the image if it was saved
                if ($path && file_exists($path)) {
                    unlink($path);
                }

                // Handle any exceptions
                return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            }
        }


        /**
         * Remove the specified resource from storage.
         */
        public function destroy($serviceId)
        {
            DB::beginTransaction();

            try {
                // Find the service by ID
                $service = Services::findOrFail($serviceId);

                // Delete the associated image if it exists
                if ($service->image && file_exists(storage_path('app/public/uploads/services/' . $service->image))) {
                    unlink(storage_path('app/public/uploads/services/' . $service->image));
                }

                // Delete the service
                $service->delete();

                // Delete associated service availability
                Service_Availabilities::where('service_id', $serviceId)->delete();

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

        public function update_Status(Request $request, $id)
        {
            $service = Services::findOrFail($id);
            $service->is_active = $request->is_active;
            if ($service->save()) {
                return response()->json(['success' => true]);
            }
            return response()->json(['success' => false]);
        }

        public function updateAvailability(Request $request)
        {
            $request->validate([
                'service_id' => 'required|integer|exists:services,id',
                'home_services' => 'required|boolean',
                'branch_services' => 'required|boolean',
            ]);

            $serviceId = $request->input('service_id');
            $homeServices = $request->input('home_services');
            $branchServices = $request->input('branch_services');

            try {
                Service_Availabilities::updateOrCreate(
                    ['service_id' => $serviceId],
                    [
                        'in_home' => $homeServices,
                        'in_spa' => $branchServices,
                    ]
                );

                return response()->json(['status' => 'success']);
            } catch (\Exception$e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }


    }
