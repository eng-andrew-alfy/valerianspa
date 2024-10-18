<?php

    use App\Http\Controllers\BookingController;
    use App\Http\Controllers\Dashboard\AdminAuthController;
    use App\Http\Controllers\Dashboard\AdminController;
    use App\Http\Controllers\Dashboard\CalendarController;
    use App\Http\Controllers\Dashboard\CategoriesController;
    use App\Http\Controllers\Dashboard\ClientsController;
    use App\Http\Controllers\Dashboard\CouponsController;
    use App\Http\Controllers\Dashboard\DashboardLogController;
    use App\Http\Controllers\Dashboard\DiscountController;
    use App\Http\Controllers\Dashboard\EmployeeController;
    use App\Http\Controllers\Dashboard\ErrorLogController;
    use App\Http\Controllers\Dashboard\GiftsController;
    use App\Http\Controllers\Dashboard\HomeController;
    use App\Http\Controllers\Dashboard\NotificationDashboardController;
    use App\Http\Controllers\Dashboard\OrderController;
    use App\Http\Controllers\Dashboard\PackagesController;
    use App\Http\Controllers\Dashboard\PlaceController;
    use App\Http\Controllers\Dashboard\RoleController;
    use App\Http\Controllers\Dashboard\ServicesController;
    use App\Http\Controllers\NotificationController;
    use App\Http\Middleware\AdminIdleLogout;
    use App\Http\Middleware\AuthenticateAdmin;
    use App\Http\Middleware\LogErrorMiddleware;

    use Illuminate\Support\Facades\Route;

    Route::prefix('admin')->middleware([AuthenticateAdmin::class, LogErrorMiddleware::class, AdminIdleLogout::class])->name('admin.')->group(function () {
        Route::get('/', [AdminAuthController::class, 'index'])->name('dashboard');

        Route::get('/notifications/fetch', [NotificationController::class, 'fetch'])->name('notifications.fetch');
        Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.MarkAsRead');

        Route::get('/notifications/fetch-latest', [NotificationController::class, 'fetchLatest'])->name('notifications.fetchLatest');
        Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
        //Route::get('/AllNotifications', [NotificationController::class, 'index'])->name('notificationsAll');
        Route::get('/AllNotifications', [NotificationController::class, 'fetchAll'])->name('notificationsFetchAll');

        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login']);
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        /** *******************************************************   START PLACES   ******************************************************  **/
        Route::resource('places', PlaceController::class);
        Route::post('/places/update-status/{place}', [PlaceController::class, 'updateStatus'])->name('places.updateStatus');
        Route::delete('/places/{place}', [PlaceController::class, 'destroy'])->name('places.destroy');
        /** *******************************************************   END PLACES   ******************************************************  **/
        Route::resource('employees', EmployeeController::class)->names('employees');
        /** *******************************************************   START SERVICES   ******************************************************  **/
        Route::resource('service', ServicesController::class)->names('services');
        Route::name('services.')->group(function () {
            Route::post('service/update-status/{id}', [ServicesController::class, 'update_Status'])->name('updateStatus');
            Route::delete('service/{service}', [ServicesController::class, 'destroy'])->name('destroy');
            Route::post('/updated-availability', [ServicesController::class, 'updateAvailability'])->name('updateAvailability');
        });
        /** *******************************************************   END SERVICES   ******************************************************  **/

        /** *******************************************************   START ADMIN & ROLES   ******************************************************  **/


        Route::resource('roles', RoleController::class);
        Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('role.destroy');
        Route::resource('admins', AdminController::class);


        /** *******************************************************   END ADMIN & ROLES   ******************************************************  **/

        /** *******************************************************   START LOGS & ERRORS   ******************************************************  **/


        Route::get('/logs', [DashboardLogController::class, 'index'])->name('logs.index');
        Route::get('/logs/{id}', [DashboardLogController::class, 'show'])->name('logs.show');
        Route::get('/error-logs', [ErrorLogController::class, 'index'])->name('error_logs.index');
        Route::get('/error-logs/{id}', [ErrorLogController::class, 'show'])->name('error_logs.show');


        /** *******************************************************   END LOGS & ERRORS   ******************************************************  **/

        /** *******************************************************   START PACKAGES   ******************************************************  **/
        Route::resource('AllPackages', PackagesController::class);
        Route::get('/fetch-employee', [PackagesController::class, 'fetchEmployees'])->name('packages.fetchEmployeesOfPackage');
        Route::name('package.')->group(function () {
            Route::post('package/update-status/{id}', [PackagesController::class, 'update_Status'])->name('updateStatus');
            Route::delete('package/{package}', [PackagesController::class, 'destroy'])->name('Destroy');
            Route::post('/update-availability', [PackagesController::class, 'updateAvailability'])->name('updateAvailability');
        });

        /** *******************************************************   END PACKAGES   ******************************************************  **/
        /** *******************************************************   START CATEGORIES   ******************************************************  **/
        Route::resource('categories', CategoriesController::class);
        Route::get('/fetch-employees', [CategoriesController::class, 'fetchEmployees'])->name('fetchEmployeesOFCategory');
        Route::get('/get-services', [CategoriesController::class, 'getServices'])->name('getServices');
        Route::delete('/category/{categories}', [CategoriesController::class, 'destroy'])->name('categoryDestroy');
        Route::post('/update-category-availability', [CategoriesController::class, 'updateCategoryAvailability'])->name('updateCategoryAvailability');
        Route::post('category/update-status/{id}', [CategoriesController::class, 'update_Status'])->name('category.updateStatus');

        /** *******************************************************   END CATEGORIES   ******************************************************  **/

        /** *******************************************************   START COUPONS   ******************************************************  **/
        Route::resource('coupons', CouponsController::class);
        Route::post('coupons/update-status/{id}', [CouponsController::class, 'update_Status'])->name('coupons.updateStatus');
        /** *******************************************************   END COUPONS   ******************************************************  **/
        /** *******************************************************   START ORDERS   ******************************************************  **/
        Route::resource('orders', OrderController::class);

        Route::post('/orders/update-status', [OrderController::class, 'updatePaymentStatus'])->name('orders.updateStatus');
        // Route::get('/edit/{id}', [OrderController::class, 'edit'])->name('orders.edit');
        Route::delete('/destroy/{id}', [OrderController::class, 'destroy'])->name('orderDestroy');

        Route::get('/get-category-availability/{id}', [OrderController::class, 'categoryAvailability'])->name('getCategoryAvailability');

        Route::get('/get-package-availability/{id}', [OrderController::class, 'packageAvailability'])->name('getPackageAvailability');


        Route::get('/ArchivedOrder', [OrderController::class, 'archivedOrder'])->name('ArchivedOrder');
        Route::get('/ArchivedOrder/{id}', [OrderController::class, 'showArchivedOrder'])->name('showArchivedOrder');
        Route::post('/generate-invoice-code', [OrderController::class, 'generateInvoiceCode'])->name('generate-invoice-code');
        Route::get('/OrdersUnPaid', [OrderController::class, 'unpaidOrder'])->name('OrdersUnPaid');
        Route::post('/unpaidPaymentUpdate', [OrderController::class, 'unpaidPaymentUpdate'])->name('UnpaidPaymentUpdate');
        Route::get('/ShowSession/{code}', [OrderController::class, 'showSession'])->name('ShowSession');
        /** *******************************************************   END ORDERS   ******************************************************  **/

        /** *******************************************************   START EMPLOYEE FILTERING   ******************************************************  **/

        Route::post('/available-employees', [BookingController::class, 'getEmployeesInRadius'])->name('availableEmployeesPlaces');
        Route::get('/get-available-employees', [BookingController::class, 'getAvailableEmployees'])->name('filterEmployees');

        /** *******************************************************   END EMPLOYEE FILTERING   ******************************************************  **/

        /** *******************************************************   START checkClient CODE   ******************************************************  **/

        Route::post('/check-client', [OrderController::class, 'checkClient'])->name('check-client-code');

        /** *******************************************************   END checkClient CODE   ******************************************************  **/

        /** *******************************************************   START CALENDAR   ******************************************************  **/

        Route::get('/calendar-home', [CalendarController::class, 'index'])->name('homeCalendar');
        Route::get('/calendar-spa', [CalendarController::class, 'indexSpa'])->name('spaCalendar');
        Route::get('/get-calendar-data', [CalendarController::class, 'getCalendarData'])->name('getCalendarData');
        Route::get('/get-calendar-data-spa', [CalendarController::class, 'getCalendarSpaData'])->name('getCalendarSpaData');
        Route::post('/update-session-status', [CalendarController::class, 'updateStatus'])->name('updateStatusCalendar');

        /** *******************************************************   END CALENDAR   ******************************************************  **/

        /** *******************************************************   START GIFTS   ******************************************************  **/

        Route::get('/gifts', [GiftsController::class, 'index'])->name('Gifts');
        Route::post('/gifts/update-expiry', [GiftsController::class, 'updateExpiry'])->name('gifts.updateExpiry');
        Route::get('/gifts/{gift}', [GiftsController::class, 'edit'])->name('gifts.edit');

        /** *******************************************************   END GIFTS   ******************************************************  **/

        /** *******************************************************   START SESSION ACTION   ******************************************************  **/

        Route::post('/session/handle-session-action', [OrderController::class, 'handleSessionAction'])->name('handleSession');
        Route::post('/session/handle-session-date/{session}', [OrderController::class, 'update_date'])->name('dateSession');
        /** *******************************************************   END SESSION ACTION   ******************************************************  **/


        /** *******************************************************   START  NOTIFICATION   ******************************************************  **/

        Route::get('/notifications/{notification}', [NotificationDashboardController::class, 'index'])->name('notifications');

        /** *******************************************************   END  NOTIFICATION   ******************************************************  **/

        /** *******************************************************   START CHART JS   ******************************************************  **/

        Route::get('/orders-data', [HomeController::class, 'getOrdersData'])->name('ordersDataChartJS');
        Route::get('/employee-orders-data', [HomeController::class, 'getEmployeeOrdersData'])->name('getEmployeeOrdersData');

        /** *******************************************************   END CHART JS   ******************************************************  **/

        /** *******************************************************   START CLIENT   ******************************************************  **/

        Route::get('/clients', [ClientsController::class, 'index'])->name('clients.index');
        Route::post('/update-information-customer', [ClientsController::class, 'update'])->name('updateInformationCustomer');

        /** *******************************************************   END CLIENT   ******************************************************  **/

        /** *******************************************************   START DISCOUNT   ******************************************************  **/

        Route::get('/package_discounts', [DiscountController::class, 'indexPackages'])->name('getPackageDiscount');
        Route::get('/category_discounts', [DiscountController::class, 'indexCategories'])->name('getCategoryDiscount');
        Route::get('/create_discounts', [DiscountController::class, 'createDiscount'])->name('createAllDiscounts');
        Route::get('/get-category-price/{id}', [DiscountController::class, 'CategoryPrice'])->name('getCategoryPrice');
        Route::get('/get-package-price/{id}', [DiscountController::class, 'PackagePrice'])->name('getPackagePrice');
        Route::post('/store_discounts', [DiscountController::class, 'storeAllDiscount'])->name('storeAllDiscounts');
        /** *******************************************************   END DISCOUNT   ******************************************************  **/


//        Route::get('/test-archive-orders', function () {
//            $job = new \App\Jobs\ArchiveOrders;
//            $job->handle();
//            return 'ArchiveOrders job handled directly';
//        });


    });
    Route::get('admin/unauthorized', function () {
        return view('Dashboard.unauthorized'); // عرض صفحة الخطأ
    })->name('admin.unauthorized');
