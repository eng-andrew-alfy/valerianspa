<?php

    namespace Database\Seeders;

    use App\Models\Admin;
    use Illuminate\Database\Seeder;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Hash;
    use Spatie\Permission\Models\Permission;
    use Spatie\Permission\Models\Role;

    class AdminSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         */
        public function run(): void
        {
            DB::table('admin_permission')->truncate();

            // Retrieve all permissions and assign them to the role
            $permissions = Permission::pluck('id')->all();

            // Create roles and permissions
            $role = Role::firstOrCreate(['name' => 'Developer', 'guard_name' => 'admin']);
            $role->syncPermissions($permissions);

            // Create an admin user
            $admin = Admin::updateOrCreate(
                ['email' => 'eng.andrew@admin.com'],
                [
                    'name' => 'Eng. Andrew',
                    'phone' => '01202829754',
                    'password' => Hash::make('password'),
                    'roles_name' => ["Developer"],
                ]
            );

            // Assign the role to the admin
            $admin->assignRole($role->name);

            // Link the admin with permissions
            foreach ($permissions as $permissionId) {
                DB::table('admin_permission')->insert([
                    'admin_id' => $admin->id,
                    'permission_id' => $permissionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
