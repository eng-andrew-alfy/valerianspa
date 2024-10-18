<?php

    namespace Database\Seeders;

    use Illuminate\Database\Console\Seeds\WithoutModelEvents;
    use Illuminate\Database\Seeder;
    use Spatie\Permission\Models\Permission;

    class PermissionTableSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         */
        public function run(): void
        {
            $permissions = [
                // Employees
                'employee.create' => 'إضافة موظف',
                'employee.edit' => 'تعديل موظف',
                'employee.delete' => 'حذف موظف',
                'employee.list' => 'قائمة الموظفين',

                // Locations
                'location.create' => 'إضافة مكان',
                'location.edit' => 'تعديل مكان',
                'location.delete' => 'حذف مكان',
                'location.list' => 'قائمة الأماكن',

                // Packages
                'package.create' => 'إضافة باقة',
                'package.edit' => 'تعديل باقة',
                'package.delete' => 'حذف باقة',
                'package.list' => 'قائمة الباقات',

                // Categories
                'category.create' => 'إضافة خدمة',
                'category.edit' => 'تعديل خدمة',
                'category.delete' => 'حذف خدمة',
                'category.list' => 'قائمة الخدمات',


                // Services
                'service.create' => 'إضافة قسم',
                'service.edit' => 'تعديل قسم',
                'service.delete' => 'حذف قسم',
                'service.list' => 'قائمة الأقسام',

                // Coupons
                'coupon.create' => 'إضافة كوبون',
                'coupon.edit' => 'تعديل كوبون',
                'coupon.delete' => 'حذف كوبون',
                'coupon.list' => 'قائمة الكوبونات',

                // Orders
                'order.create' => 'إضافة طلب',
                'order.delete' => 'حذف طلب',
                'order.list' => 'قائمة الطلبات',
                'order.archive' => 'أرشيف الطلبات',
                'order.unpaid' => 'الطلبات غير المدفوعة',


                // Orders Gifts
                'order.gift.list' => 'قائمة الهدايا',
                'order.gift.archive' => 'أرشيف الهدايا',

                // Calendar
                'calendar.home' => 'تقويم المنزل',
                'calendar.branch' => 'تقويم الفرع',
                'calendar.list' => 'قائمة التقويم',


                // Users
                'user.create' => 'إضافة مستخدم',
                'user.edit' => 'تعديل مستخدم',
                'user.delete' => 'حذف مستخدم',
                'user.list' => 'قائمة المستخدمين',


                // Settings
                'settings.manage' => 'إعدادات النظام',
                'admin.manage' => 'إدارة الإداريين',
                'admin.create' => 'إضافة إداري',
                'admin.edit' => 'تعديل إداري',
                'admin.delete' => 'حذف إداري',
                'admin.permissions' => 'صلاحيات الإداريين',


                // Permissions
                'permission.view' => 'عرض صلاحية',
                'permission.create' => 'إضافة صلاحية',
                'permission.edit' => 'تعديل صلاحية',
                'permission.delete' => 'حذف صلاحية',
                'permissions.list' => 'قائمة الصلاحيات',

                // Logs
                'logs.manage' => 'إدارة السجلات',
                'logs.view' => 'عرض السجلات',
                'logs.error' => 'عرض الأخطاء',

                // Roles
                'role.view' => 'عرض دور',
                'role.create' => 'إضافة دور',
                'role.edit' => 'تعديل دور',
                'role.delete' => 'حذف دور',
                'roles.list' => 'قائمة الأدوار',

                // Notifications
                'notifications.manage' => 'إدارة الإشعارات',
            ];


            foreach ($permissions as $key => $value) {
                Permission::firstOrCreate(['name' => $key], ['description' => $value, 'guard_name' => 'admin']);
            }
        }
    }
