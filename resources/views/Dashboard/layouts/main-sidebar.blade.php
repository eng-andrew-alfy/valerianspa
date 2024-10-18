@php
    $user = auth('admin')->user();
    $admin = auth('admin')->user();
    $permissionsUser = ['user.create', 'user.list'];
    $permissionsOrder = ['order.create', 'order.list'];
    $permissionsCalendar = ['calendar.list', 'calendar.branch', 'calendar.home'];
    $permissionsGift = ['order.gift.list', 'order.gift.archive'];

    $permissionsLocation = ['location.list', 'location.create'];

    $permissionsEmployee = ['employee.list', 'employee.create'];
    $permissionsPackageORCategory = ['package.list', 'category.list'];
    $permissionsPackage = ['package.list', 'package.create'];
    $permissionsCategory = ['category.list', 'category.create'];

    $permissionsCoupon = ['coupon.list', 'coupon.create'];
    $permissionsAdmin = ['admin.manage', 'admin.permissions'];

@endphp

<nav class="pcoded-navbar">
    <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
    <div class="pcoded-inner-navbar main-menu">

        <div class="pcoded-navigatio-lavel" data-i18n="nav.category.navigation">لوحة تحكم</div>
        <ul class="pcoded-item pcoded-left-item">
            <li class=" {{ Route::is('admin.dashboard') ? 'active pcoded-trigger' : '' }} ">
                <a href="{{ route('admin.dashboard') }}">
                    <span class="pcoded-micon"><i class="ti-home"></i></span>
                    <span class="pcoded-mtext" data-i18n="nav.dash.main">الرئيسية</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>


        @if ($admin->hasAnyPermission($permissionsUser))
            {
            <div class="pcoded-navigatio-lavel" data-i18n="nav.category.ui-element">العملاء</div>
        @endif

        <ul class="pcoded-item pcoded-left-item">
            @if ($admin->hasAnyPermission($permissionsUser))
                <li class="pcoded-hasmenu {{ Route::is('admin.clients.index') }}">
                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="ion-person-stalker"></i></span>
                        <span class="pcoded-mtext" data-i18n="nav.basic-components.main">العملاء</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                    <ul class="pcoded-submenu ">
                        @if ($admin->hasPermissionTo('user.list'))
                            <li class=" {{ Route::is('admin.clients.index') ? 'active pcoded-trigger' : '' }}">
                                <a href="{{ route('admin.clients.index') }}">
                                    <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                    <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">قائمة
                                        العملاء</span>
                                    <span class="pcoded-mcaret"></span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if ($admin->hasAnyPermission($permissionsOrder))
                <div class="pcoded-navigatio-lavel" data-i18n="nav.category.chart-and-maps">الطلبات</div>

                <ul class="pcoded-item pcoded-left-item">
                    <li
                        class="pcoded-hasmenu {{ Route::is('admin.orders.index') || Route::is('admin.orders.create') ? 'active pcoded-trigger' : '' }}">
                        <a href="javascript:void(0)">
                            <span class="pcoded-micon"><i class="ion-ios-cart"></i></span>
                            <span class="pcoded-mtext" data-i18n="nav.charts.main">الطلبات</span>
                            <span class="pcoded-mcaret"></span>
                        </a>

                        <ul class="pcoded-submenu">
                            @if ($admin->hasPermissionTo('order.list'))
                                <li class="{{ Route::is('admin.orders.index') ? 'active pcoded-trigger' : '' }}">
                                    <a href="{{ route('admin.orders.index') }}">
                                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                        <span class="pcoded-mtext" data-i18n="nav.charts.google-chart"> الطلبات</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>
                            @endif

                            @if ($admin->hasPermissionTo('order.create'))
                                <li class="{{ Route::is('admin.orders.create') ? 'active pcoded-trigger' : '' }}">
                                    <a href="{{ route('admin.orders.create') }}">
                                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                        <span class="pcoded-mtext" data-i18n="nav.charts.google-chart">أضافة طلب</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>
                            @endif
                            @if ($admin->hasPermissionTo('order.archive'))
                                <li class="{{ Route::is('admin.ArchivedOrder') ? 'active pcoded-trigger' : '' }}">
                                    <a href="{{ route('admin.ArchivedOrder') }}">
                                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                        <span class="pcoded-mtext" data-i18n="nav.charts.google-chart"> إرشيف
                                            الطلبات</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>
                            @endif
                            @if ($admin->hasPermissionTo('order.unpaid'))
                                <li class="{{ Route::is('admin.OrdersUnPaid') ? 'active pcoded-trigger' : '' }}">
                                    <a href="{{ route('admin.OrdersUnPaid') }}">
                                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                        @if (App\Models\Order::where('is_paid', false)->count() != 0)
                                            <span
                                                class="pcoded-badge label label-danger">{{ App\Models\Order::where('is_paid', false)->count() }}</span>
                                        @endif
                                        <span class="pcoded-mtext" data-i18n="nav.charts.google-chart"> الطلبات الغير
                                            مدفوعة</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                    @if ($admin->hasAnyPermission($permissionsCalendar))
                        <div class="pcoded-navigatio-lavel" data-i18n="nav.category.chart-and-maps">
                            التقويم
                        </div>

                        <li
                            class="pcoded-hasmenu {{ Route::is('admin.homeCalendar') || Route::is('admin.spaCalendar') ? 'active pcoded-trigger' : '' }}">
                            <a href="javascript:void(0)">
                                <span class="pcoded-micon"><i class="icon-calendar"></i></span>
                                <span class="pcoded-mtext" data-i18n="nav.maps.main">التقويم</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                            <ul class="pcoded-submenu">
                                @if ($admin->hasPermissionTo('calendar.home'))
                                    <li class="{{ Route::is('admin.homeCalendar') ? 'active pcoded-trigger' : '' }}">
                                        <a href="{{ route('admin.homeCalendar') }}">
                                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                            <span class="pcoded-mtext" data-i18n="nav.maps.google-maps">تقويم
                                                المنازل</span>
                                            <span class="pcoded-mcaret"></span>
                                        </a>
                                    </li>
                                @endif
                                @if ($admin->hasPermissionTo('calendar.branch'))
                                    <li class="{{ Route::is('admin.spaCalendar') ? 'active pcoded-trigger' : '' }}">
                                        <a href="{{ route('admin.spaCalendar') }}">
                                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                            <span class="pcoded-mtext" data-i18n="nav.maps.vector-map">تقويم الفرع
                                            </span>
                                            <span class="pcoded-mcaret"></span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if ($admin->hasAnyPermission($permissionsGift))

                        <div class="pcoded-navigatio-lavel" data-i18n="nav.category.chart-and-maps">الهدايا
                        </div>

                        <li
                            class="pcoded-hasmenu {{ Route::is('admin.Gifts') || Route::is('admin.Gifts') ? 'active pcoded-trigger' : '' }}">
                            <a href="javascript:void(0)">
                                <span class="pcoded-micon"><i class="icofont icofont-gift"></i></span>
                                <span class="pcoded-mtext" data-i18n="nav.maps.main">الهدايا</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                            <ul class="pcoded-submenu">
                                @if ($admin->hasPermissionTo('order.gift.list'))
                                    <li class="{{ Route::is('admin.Gifts') ? 'active pcoded-trigger' : '' }}">
                                        <a href="{{ route('admin.Gifts') }}">
                                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                            <span class="pcoded-mtext" data-i18n="nav.maps.google-maps">الهدايا</span>
                                            <span class="pcoded-mcaret"></span>
                                        </a>
                                    </li>
                                @endif
                                @if ($admin->hasPermissionTo('order.gift.archive'))
                                    <li class="">
                                        <a href="#">
                                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                            <span class="pcoded-mtext" data-i18n="nav.maps.vector-map"> إرشيف الهدايا
                                            </span>
                                            <span class="pcoded-mcaret"></span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>

                    @endif
                </ul>
            @endif
            @if ($admin->hasAnyPermission($permissionsLocation))
                <div class="pcoded-navigatio-lavel" data-i18n="nav.category.ui-element">الأماكن
                </div>


                <li
                    class="pcoded-hasmenu {{ Route::is('admin.places.index') || Route::is('admin.places.create') ? 'active pcoded-trigger' : '' }}">
                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="ion-ios-location-outline"></i></span>
                        <span class="pcoded-mtext" data-i18n="nav.advance-components.main">الأماكن</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                    <ul class="pcoded-submenu ">
                        @if ($admin->hasPermissionTo('location.list'))
                            <li class=" {{ Route::is('admin.places.index') ? 'active pcoded-trigger' : '' }}">
                                <a href="{{ route('admin.places.index') }}">
                                    <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                    <span class="pcoded-mtext"
                                        data-i18n="nav.advance-components.draggable">الأماكن</span>
                                    {{-- <span class="pcoded-badge label label-info">NEW</span> --}}
                                    <span class="pcoded-mcaret"></span>
                                </a>
                            </li>
                        @endif
                        @if ($admin->hasPermissionTo('location.create'))
                            <li class=" {{ Route::is('admin.places.create') ? 'active pcoded-trigger' : '' }}">
                                <a href="{{ route('admin.places.create') }}">
                                    <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                    <span class="pcoded-mtext" data-i18n="nav.advance-components.draggable">أضافة
                                        مكان</span>
                                    <span class="pcoded-mcaret"></span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if ($admin->hasPermissionTo('service.list'))
                <div class="pcoded-navigatio-lavel" data-i18n="nav.category.ui-element">الأقسام</div>
                <li class="pcoded-hasmenu {{ Route::is('admin.services.index') ? 'active pcoded-trigger' : '' }}">
                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="icofont icofont-justify-right"></i></span>
                        <span class="pcoded-mtext" data-i18n="nav.extra-components.main">الأقسام</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li class=" {{ Route::is('admin.services.index') ? 'active pcoded-trigger' : '' }}">
                            <a href="{{ route('admin.services.index') }}">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext"
                                    data-i18n="nav.extra-components.session-timeout">الأقسام</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

        </ul>
        @if ($admin->hasAnyPermission($permissionsEmployee))
            <div class="pcoded-navigatio-lavel" data-i18n="nav.category.ui-element">الموظفين</div>
            <ul class="pcoded-item pcoded-left-item">

                <li
                    class="pcoded-hasmenu {{ Route::is('admin.employees.index') || Route::is('admin.employees.create') ? 'active pcoded-trigger' : '' }}">
                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="icofont icofont-architecture-alt"></i></span>
                        <span class="pcoded-mtext" data-i18n="nav.basic-components.main">الموظفين</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                    <ul class="pcoded-submenu ">
                        @if ($admin->hasPermissionTo('employee.list'))
                            <li class=" {{ Route::is('admin.employees.index') ? 'active pcoded-trigger' : '' }}">
                                <a href="{{ route('admin.employees.index') }}">
                                    <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                    <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">قائمة
                                        الموظفين</span>
                                    <span class="pcoded-mcaret"></span>
                                </a>
                            </li>
                        @endif
                        @if ($admin->hasPermissionTo('employee.create'))
                            <li class=" {{ Route::is('admin.employees.create') ? 'active pcoded-trigger' : '' }}">
                                <a href="{{ route('admin.employees.create') }}">
                                    <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                    <span class="pcoded-mtext" data-i18n="nav.basic-components.breadcrumbs">أضافة
                                        موظف</span>
                                    <span class="pcoded-mcaret"></span>
                                </a>
                            </li>
                        @endif

                    </ul>
                </li>


            </ul>
        @endif
        @if ($admin->hasAnyPermission($permissionsPackageORCategory))
            <div class="pcoded-navigatio-lavel" data-i18n="nav.category.forms">الباقات و الخدمات</div>
        @endif
        <ul class="pcoded-item pcoded-left-item">
            @if ($admin->hasAnyPermission($permissionsPackage))
                <li
                    class="pcoded-hasmenu {{ Route::is('admin.AllPackages.index') || Route::is('admin.AllPackages.create') ? 'active pcoded-trigger' : '' }}">
                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="icon-social-dropbox"></i></span>
                        <span class="pcoded-mtext" data-i18n="nav.form-components.main">الباقات </span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                    <ul class="pcoded-submenu">
                        @if ($admin->hasPermissionTo('package.list'))
                            <li class="{{ Route::is('admin.AllPackages.index') ? 'active pcoded-trigger' : '' }} ">
                                <a href="{{ route('admin.AllPackages.index') }}">
                                    <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                    <span class="pcoded-mtext"
                                        data-i18n="nav.form-components.form-components">الباقات</span>
                                    <span class="pcoded-mcaret"></span>
                                </a>
                            </li>
                        @endif
                        @if ($admin->hasPermissionTo('package.create'))
                            <li class="{{ Route::is('admin.AllPackages.create') ? 'active pcoded-trigger' : '' }} ">
                                <a href="{{ route('admin.AllPackages.create') }}">
                                    <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                    <span class="pcoded-mtext" data-i18n="nav.form-components.form-components">أضافة
                                        باقة</span>
                                    <span class="pcoded-mcaret"></span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if ($admin->hasAnyPermission($permissionsCategory))
                <li
                    class="pcoded-hasmenu {{ Route::is('admin.categories.index') || Route::is('admin.categories.create') ? 'active pcoded-trigger' : '' }}">

                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="ti-layout-cta-btn-right"></i></span>
                        <span class="pcoded-mtext" data-i18n="nav.json-form.main">الخدمات</span>
                        {{-- <span class="pcoded-badge label label-danger">HOT</span> --}}
                        <span class="pcoded-mcaret"></span>
                    </a>
                    @if ($admin->hasPermissionTo('category.list'))
                        <ul class="pcoded-submenu">
                            <li class=" {{ Route::is('admin.categories.index') ? 'active pcoded-trigger' : '' }}">
                                <a href="{{ route('admin.categories.index') }}">
                                    <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                    <span class="pcoded-mtext" data-i18n="nav.json-form.simple-form">الخدمات</span>
                                    <span class="pcoded-mcaret"></span>
                                </a>
                            </li>
                    @endif
                    @if ($admin->hasPermissionTo('category.create'))
                <li class=" {{ Route::is('admin.categories.create') ? 'active pcoded-trigger' : '' }}">
                    <a href="{{ route('admin.categories.create') }}">
                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                        <span class="pcoded-mtext" data-i18n="nav.json-form.clubs-view">أضافة خدمة</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>
            @endif
        </ul>
        </li>
        @endif
        </ul>
        @if ($admin->hasAnyPermission($permissionsCoupon))
            <div class="pcoded-navigatio-lavel " data-i18n="nav.category.tables">الكوبونات</div>
            <ul class="pcoded-item pcoded-left-item">
                <li
                    class="pcoded-hasmenu {{ Route::is('admin.coupons.index') || Route::is('admin.coupons.create') ? 'active pcoded-trigger' : '' }}">
                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="ti-receipt"></i></span>
                        <span class="pcoded-mtext" data-i18n="nav.bootstrap-table.main">الكوبونات </span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                    <ul class="pcoded-submenu">
                        @if ($admin->hasPermissionTo('coupon.list'))
                            <li class="{{ Route::is('admin.coupons.index') ? 'active pcoded-trigger' : '' }} ">
                                <a href="{{ route('admin.coupons.index') }}">
                                    <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                    <span class="pcoded-mtext" data-i18n="nav.bootstrap-table.basic-table">
                                        الكوبونات</span>
                                    <span class="pcoded-mcaret"></span>
                                </a>
                            </li>
                        @endif
                        @if ($admin->hasPermissionTo('coupon.create'))
                            <li class="{{ Route::is('admin.coupons.create') ? 'active pcoded-trigger' : '' }} ">
                                <a href="{{ route('admin.coupons.create') }}">
                                    <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                    <span class="pcoded-mtext" data-i18n="nav.bootstrap-table.basic-table">أضافة
                                        كوبون</span>
                                    <span class="pcoded-mcaret"></span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>

            </ul>
        @endif
        <div class="pcoded-navigatio-lavel " data-i18n="nav.category.tables">الخصومات</div>
        <ul class="pcoded-item pcoded-left-item">
            <li
                class="pcoded-hasmenu {{ Route::is('admin.getPackageDiscount') || Route::is('admin.getCategoryDiscount') ? 'active pcoded-trigger' : '' }}">
                <a href="javascript:void(0)">
                    <span class="pcoded-micon"><i class="icofont icofont-sale-discount"></i></span>
                    <span class="pcoded-mtext" data-i18n="nav.bootstrap-table.main">الخصومات </span>
                    <span class="pcoded-mcaret"></span>
                </a>
                <ul class="pcoded-submenu">
                    @if ($admin->hasPermissionTo('coupon.list'))
                        <li class="{{ Route::is('admin.getCategoryDiscount') ? 'active pcoded-trigger' : '' }} ">
                            <a href="{{ route('admin.getCategoryDiscount') }}">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" data-i18n="nav.bootstrap-table.basic-table"> الخدمات</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                    @endif

                    @if ($admin->hasPermissionTo('coupon.create'))
                        <li class="{{ Route::is('admin.getPackageDiscount') ? 'active pcoded-trigger' : '' }} ">
                            <a href="{{ route('admin.getPackageDiscount') }}">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" data-i18n="nav.bootstrap-table.basic-table"> الباقات</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                    @endif

                    <li class="{{ Route::is('admin.createAllDiscounts') ? 'active pcoded-trigger' : '' }} ">
                        <a href="{{ route('admin.createAllDiscounts') }}">
                            <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                            <span class="pcoded-mtext" data-i18n="nav.bootstrap-table.basic-table"> اضافة خصم</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                </ul>
            </li>

        </ul>

        {{--        <div class="pcoded-navigatio-lavel" data-i18n="nav.category.pages">الإعدادات</div> --}}
        <ul class="pcoded-item pcoded-left-item">


            @if ($user->hasAnyPermission($permissionsAdmin))
                <div class="pcoded-navigatio-lavel" data-i18n="nav.category.tables">الإداريين</div>


                <li
                    class="pcoded-hasmenu {{ Route::is('admin.admins.index') || Route::is('admin.roles.index') ? 'active pcoded-trigger' : '' }}">

                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="ti-user"></i></span>
                        <span class="pcoded-mtext" data-i18n="nav.user-profile.main">الإداريين</span>
                        <span class="pcoded-mcaret"></span>
                    </a>


                    <ul class="pcoded-submenu">
                        @if ($user->hasPermissionTo('admin.manage'))
                            <li class="{{ Route::is('admin.admins.index') ? 'active pcoded-trigger' : '' }}">
                                <a href="{{ Route('admin.admins.index') }}">
                                    <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                    <span class="pcoded-mtext" data-i18n="nav.user-profile.timeline">الإداريين</span>
                                    <span class="pcoded-mcaret"></span>
                                </a>
                            </li>
                        @endif
                        @if ($user->hasPermissionTo('admin.permissions'))
                            <li class="{{ Route::is('admin.roles.index') ? 'active pcoded-trigger' : '' }}">
                                <a href="{{ Route('admin.roles.index') }}">
                                    <span class="pcoded-micon"><i class="typcn typcn-key-outline"></i></span>
                                    <span class="pcoded-mtext" data-i18n="nav.user-profile.timeline">الصلاحيات</span>
                                    <span class="pcoded-mcaret"></span>
                                </a>
                            </li>
                        @endif


                    </ul>
                </li>
            @endif
            @if ($user && $user->hasRole('Developer'))
                @if ($user->hasPermissionTo('settings.manage'))
                    <ul class="pcoded-item pcoded-left-item">
                        <div class="pcoded-navigatio-lavel" data-i18n="nav.category.tables">الإعدادات</div>


                        <li class="pcoded-hasmenu ">
                            <a href="javascript:void(0)">
                                <span class="pcoded-micon"><i class="ti-settings"></i></span>
                                <span class="pcoded-mtext" data-i18n="nav.maintenance.main">الإعدادات</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                            <ul class="pcoded-submenu">
                                <li class="">
                                    <a href="#">
                                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                        <span class="pcoded-mtext" data-i18n="nav.maintenance.error">الإعدادات</span>
                                        <span class="pcoded-mcaret"></span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                    </ul>
                @endif

                <li
                    class="pcoded-hasmenu {{ Route::is('admin.logs.index') || Route::is('admin.error_logs.index') ? 'active pcoded-trigger' : '' }}">
                    <a href="javascript:void(0)">
                        <span class="pcoded-micon"><i class="fa fa-expeditedssl"></i></span>
                        <span class="pcoded-mtext" data-i18n="nav.e-commerce.main">النظام</span>
                        <span class="pcoded-badge label label-danger">NEW</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                    <ul class="pcoded-submenu">
                        <li class="{{ Route::is('admin.logs.index') ? 'active pcoded-trigger' : '' }}">
                            <a href="{{ Route('admin.logs.index') }}">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" data-i18n="nav.e-commerce.product">الترافيك</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="{{ Route::is('admin.error_logs.index') ? 'active pcoded-trigger' : '' }}">
                            <a href="{{ Route('admin.error_logs.index') }}">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext" data-i18n="nav.e-commerce.product-list">الإخطاء</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

        </ul>
    </div>
</nav>
