<?php

    namespace App\Http\Controllers\Dashboard;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\Dashboard\StoreAdminRequest;
    use App\Models\Admin;
    use Brian2694\Toastr\Facades\Toastr;
    use Exception;
    use Illuminate\Database\QueryException;
    use Illuminate\Http\RedirectResponse;
    use Illuminate\Http\Request;
    use Illuminate\Support\Arr;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Validation\ValidationException;
    use Illuminate\View\View;
    use Spatie\Permission\Models\Role;

    class AdminController extends Controller
    {
//        public function __construct()
//        {
//            $this->middleware('role:Super Admin');
//        }

        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index(Request $request): View
        {
            $permissions = ['admin.manage', 'admin.create', 'admin.edit', 'admin.delete'];

            if (auth('admin')->user()->hasAnyPermission($permissions)) {
                $data = Admin::latest()->paginate(5);
                return view('Dashboard.admins.index', compact('data'))
                    ->with('i', ($request->input('page', 1) - 1) * 5);
            } else {
                abort(403);
            }


        }

        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function create(): View
        {
            if (auth('admin')->user()->hasPermissionTo('admin.create')) {
                $roles = Role::pluck('name', 'name')->all();

                return view('Dashboard.admins.create', compact('roles'));
            } else {
                abort(403);
            }


        }

        /**
         * Store a newly created resource in storage.
         *
         * @param \Illuminate\Http\Request $request
         * @return RedirectResponse
         */
        public function store(Request $request)
        {
            try {
                // Retrieve all input data
                $input = $request->all();

                // Hash the password
                $input['password'] = Hash::make($input['password']);

                // Create a new admin record in the database
                $admin = Admin::create($input);

                // Assign roles to the new admin
                $admin->assignRole($request->input('roles_name'));

                // Display a success message
                Toastr::success(' 😀  لقد تم إضافة المستخدم بنجاح', 'عملية ناجحة');

                // Redirect to the admin list page
                return redirect()->route('admin.admins.index');

            } catch (QueryException $e) {
                // Handle database-related errors
                Toastr::error('حدث خطأ أثناء إدخال البيانات في قاعدة البيانات.', 'خطأ');
                return redirect()->route('admin.admins.index')->withInput();

            } catch (ValidationException $e) {
                // Handle validation errors
                Toastr::error('البيانات المدخلة غير صحيحة.', 'خطأ');
                return redirect()->route('admin.admins.index')->withErrors($e->errors())->withInput();

            } catch (Exception $e) {
                // Handle general errors
                Toastr::error('حدث خطأ غير متوقع.', 'خطأ');
                return redirect()->route('admin.admins.index')->withInput();
            }
        }

        /**
         * Display the specified resource.
         *
         * @param int $id
         * @return \Illuminate\Http\Response
         */
        public function show($id): View
        {
            $admin = Admin::find($id);

            return view('admins.show', compact('admin'));
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param int $id
         * @return \Illuminate\Http\Response
         */
        public function edit($id): View
        {
            $admin = Admin::find($id);
            $roles = Role::pluck('name', 'name')->all();
            $adminRole = $admin->roles->pluck('name', 'name')->all();

            return view('admins.edit', compact('admin', 'roles', 'adminRole'));
        }

        /**
         * Update the specified resource in storage.
         *
         * @param \Illuminate\Http\Request $request
         * @param int $id
         * @return \Illuminate\Http\Response
         */
        public function update(Request $request, $id): RedirectResponse
        {
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|email|unique:admins,email,' . $id,
                'password' => 'sometimes|nullable|same:confirm-password',
                'roles' => 'required'
            ]);

            $input = $request->all();
            if (!empty($input['password'])) {
                $input['password'] = Hash::make($input['password']);
            } else {
                $input = Arr::except($input, ['password']);
            }

            $admin = Admin::find($id);
            $admin->update($input);
            DB::table('model_has_roles')->where('model_id', $id)->delete();

            $admin->assignRole($request->input('roles'));

            return redirect()->route('admins.index')
                ->with('success', 'Admin updated successfully');
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param int $id
         * @return \Illuminate\Http\JsonResponse
         */
        public function destroy($id)
        {
            $admin = auth()->guard('admin')->user();
            if ($admin->hasPermissionTo('admin.delete')) {
                Admin::find($id)->delete();
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'warning' => true, 'message' => 'ليس لديك صلاحية لحذف هذا المستخدم']);
            }
        }
    }
