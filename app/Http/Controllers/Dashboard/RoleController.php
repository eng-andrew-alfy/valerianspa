<?php

    namespace App\Http\Controllers\Dashboard;


    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use Illuminate\Routing\Controllers\Middleware;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;
    use Spatie\Permission\Models\Role;
    use Spatie\Permission\Models\Permission;
    use Illuminate\View\View;
    use Illuminate\Http\RedirectResponse;
    use Brian2694\Toastr\Facades\Toastr;
    use Illuminate\Support\Facades\Validator;
    use Spatie\Permission\Middleware\RoleMiddleware;

    // تأكد من استيراد Middleware من Spatie
    use Spatie\Permission\Middleware\PermissionMiddleware;
    use Illuminate\Support\Facades\Gate;

    // استيراد Middleware آخر من Spatie
    class RoleController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index(Request $request): View
        {
            $admin = auth()->guard('admin')->user();
            $permissions = ['roles.list', 'roles.create', 'roles.edit', 'roles.delete'];

            if ($admin->hasAnyPermission($permissions)) {
                $roles = Role::orderBy('id', 'DESC')->paginate(5);
                return view('Dashboard.roles.index', compact('roles'));
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
            $admin = auth()->guard('admin')->user();
            $permissions = ['roles.list', 'role.create', 'role.edit', 'role.delete'];

            if ($admin->hasAnyPermission($permissions)) {
                $permission = Permission::get();
                return view('Dashboard.roles.create', compact('permission'));
            } else {
                abort(403);
            }

        }

        /**
         * Store a newly created resource in storage.
         *
         * @param \Illuminate\Http\Request $request
         * @return \Illuminate\Http\Response
         */
        public function store(Request $request)
        {
            // التحقق من صحة الإدخال
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:roles,name',
                'permission' => 'required',
            ]);

            // افترض أن $request->permissions تحتوي على مصفوفة بها سلسلة نصية واحدة تحتوي على أرقام الأذونات
            $permissionsArray = $request->permissions;

            // احصل على السلسلة النصية من المصفوفة
            $permissionsString = $permissionsArray[0]; // استخراج العنصر الأول من المصفوفة (وهو السلسلة النصية)

            // استخدم explode لتقسيم السلسلة النصية إلى مصفوفة من الأرقام
            $numberArray = explode(",", $permissionsString);

            // تحويل كل عنصر في المصفوفة إلى عدد صحيح
            $permissionsID = array_map('intval', $numberArray);

            // التأكد من أن الأذونات متوافقة مع الحارس 'admin'
            $existingPermissions = Permission::whereIn('id', $permissionsID)
                ->where('guard_name', 'admin')
                ->get();

            if ($existingPermissions->count() !== count($permissionsID)) {
                return redirect()->back()->withErrors('بعض الأذونات غير موجودة أو ليست مخصصة للحارس admin.');
            }

            // إنشاء الدور مع الحارس 'admin'
            $role = Role::create([
                'name' => $request->input('name'),
                'guard_name' => 'admin'
            ]);

            // مزامنة الأذونات مع الدور باستخدام IDs
            $role->syncPermissions($existingPermissions);

            // تسجيل الرسالة في السجل
            Log::info('Role created successfully');

            // رسالة نجاح
            Toastr::success('😀 لقد تم إضافة الصلاحية بنجاح', 'عملية ناجحة');

            // إعادة التوجيه إلى صفحة الأدوار
            return redirect()->route('admin.roles.index');
        }

        /**
         * Display the specified resource.
         *
         * @param int $id
         * @return \Illuminate\Http\Response
         */
        public function show($id): View
        {
            $admin = auth()->guard('admin')->user();

            if ($admin->hasPermissionTo('roles.list')) {
                $role = Role::find($id);
                $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
                    ->where("role_has_permissions.role_id", $id)
                    ->get();

                return view('Dashboard.roles.show', compact('role', 'rolePermissions'));
            } else {
                abort(403);
            }


        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param int $id
         * @return \Illuminate\Http\Response
         */
        public function edit($id): View
        {
            $admin = auth()->guard('admin')->user();

            if ($admin->hasPermissionTo('role.edit')) {
                $role = Role::find($id);
                $permission = Permission::get();
                $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
                    ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
                    ->all();

                return view('roles.edit', compact('role', 'permission', 'rolePermissions'));
            } else {
                abort(403);
            }


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
                'permission' => 'required',
            ]);

            $role = Role::find($id);
            $role->name = $request->input('name');
            $role->save();

            $permissionsID = array_map(
                function ($value) {
                    return (int)$value;
                },
                $request->input('permission')
            );

            $role->syncPermissions($permissionsID);

            return redirect()->route('roles.index')
                ->with('success', 'Role updated successfully');
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param int $id
         * @return \Illuminate\Http\Response
         */
        public function destroy(Role $role)
        {
            $admin = auth()->guard('admin')->user();

            if ($admin->hasPermissionTo('role.delete')) {
                try {
                    $role->delete();

                    return response()->json(['success' => true]);

                } catch (\Exception $e) {
                    return response()->json(['success' => false, 'message' => $e->getMessage()]);
                }
            } else {
                return response()->json(['success' => false, 'warning' => true, 'message' => 'ليس لديك صلاحية الحذف']);
            }
        }
    }
