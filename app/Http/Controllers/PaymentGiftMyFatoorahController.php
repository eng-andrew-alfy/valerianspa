<?php

    namespace App\Http\Controllers;

    use App\Http\Requests\StoreOrderRequest;
    use App\Models\categories;
    use App\Models\categories_prices;
    use App\Models\Coupon;
    use App\Models\Gift;
    use App\Models\Order;
    use App\Models\OrderSession;
    use App\Models\Packages;
    use App\Models\packages_prices;
    use App\Models\User;
    use App\Services\MyFatoorahService;
    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Session;
    use MyFatoorah\Library\API\Payment\MyFatoorahPayment;
    use Tymon\JWTAuth\Facades\JWTAuth;
    use MyFatoorah\Library\API\Payment\MyFatoorahPaymentEmbedded;
    use MyFatoorah\Library\API\Payment\MyFatoorahPaymentStatus;

    class PaymentGiftMyFatoorahController extends Controller
    {
        protected $myFatoorahService;
        public $mfConfig = [];

        public function __construct(MyFatoorahService $myFatoorahService)
        {
            $this->myFatoorahService = $myFatoorahService;
            $this->mfConfig = [
                'apiKey' => config('myfatoorah.api_key'),
                'isTest' => config('myfatoorah.test_mode'),
                'countryCode' => config('myfatoorah.country_iso'),
            ];
        }


        public function storeOrder(Request $request)
        {
            // التحقق من صحة البيانات
            //$validatedData = $request->validated();

            // تحديد السعر الإجمالي بناءً على الفئة أو الباقة
            $total_price = $this->calculateTotalPrice($request);

            // التحقق من الكوبون وتطبيق الخصم إذا كان موجودًا
            $total_price = $this->applyCoupon($request, $total_price);

            // إعداد البيانات للطلب
            $orderData = $this->prepareOrderData($request, $total_price);

            try {
                // بدء عملية المعاملة
                DB::beginTransaction();

                // إنشاء الطلب وحفظ البيانات
                $order = $this->createOrder($request, $orderData);

                // إنشاء الفاتورة في MyFatoorah
                $invoiceURL = $this->createInvoiceInMyFatoorah($order, $total_price);

                // حفظ الجلسات المتعلقة بالطلب
                $this->createOrderSessions($request, $order);

                // إنهاء المعاملة
                DB::commit();

                // إعادة رابط الفاتورة
                return redirect()->away($invoiceURL);
            } catch (\Exception $e) {
                // إلغاء المعاملة في حالة حدوث خطأ
                DB::rollBack();
                return response()->json(['error' => 'حدث خطأ أثناء معالجة الطلب: ' . $e->getMessage()], 500);
            }
        }

// حساب السعر بناءً على الفئة أو الباقة
        private function calculateTotalPrice(Request $request)
        {
            $total_price = 0;
            $type = Session::has('service_type') ? Session::get('service_type') : request()->route('type');

            $locale_price = ($type === 'homeServices') ? 'at_home' : 'at_spa';

            if ($request->has('categoryId')) {
                $category = categories::findorFail($request->categoryId);
                if ($category) {
                    $total_price = $category->discount ? $category->discount->$locale_price : $category->prices->$locale_price;
                }
            } elseif ($request->has('packageId')) {
                $package = Packages::findorFail($request->packageId);
                if ($package) {
                    $total_price = $package->discount ? $package->discount->$locale_price : $package->prices->$locale_price;
                }
            }

            return $total_price;
        }

// التحقق من الكوبون وتطبيق الخصم إذا كان موجودًا
        private function applyCoupon(Request $request, $total_price)
        {
            // 1. Apply initial 15% tax deduction on the total amount 🧾
            $tax_percentage = 15;
            $initial_tax = $total_price * ($tax_percentage / 100);
            $total_price -= $initial_tax;

            // 2. Check for coupon and apply discount 🎟️
            if ($request->has('coupon_code')) {
                $coupon = Coupon::where('code', $request->coupon_code)
                    ->where('is_active', true)
                    ->where(function ($query) use ($request) {
                        $query->whereHas('categories', function ($query) use ($request) {
                            $query->where('category_id', $request->categoryId);
                        })->orWhereHas('packages', function ($query) use ($request) {
                            $query->where('package_id', $request->packageId);
                        });
                    })
                    ->first();

                // Apply discount based on coupon type (fixed or percentage) 💸
                if ($coupon) {
                    if ($coupon->discount_type === 'fixed') {
                        $total_price -= $coupon->value;
                    } else if ($coupon->discount_type === 'percentage') {
                        $total_price -= ($total_price * $coupon->value / 100);
                    }
                }
            }

            // 3. Reapply 15% tax on the discounted amount 💰
            $final_tax = $initial_tax; // Tax calculated from the original amount
            $total_price += $final_tax;

            return $total_price;
        }

// إعداد البيانات للطلب
        private function prepareOrderData(Request $request, $total_price)
        {
            $packageId = $request->packageId;
            $categoryId = $request->input('categoryId');
            $employeeId = $request->input('employeeAvailable');
            $location = json_encode([
                'latitude' => $request->latitude,
                'longitude' => $request->longitude
            ]);
            $notes = $request->input('notes');

            // حساب عدد الجلسات بناءً على الباقة أو الفئة
            $sessions_count = $this->calculateSessionsCount($request);
            return [
                'package_id' => $packageId,
                'category_id' => $categoryId,
                'employee_id' => $employeeId,
                'total_price' => $total_price,
                'payment_gateway' => 'MyFatoorah',
                'location' => $location,
                'notes' => $notes,
                'sessions_count' => $sessions_count,
            ];
        }

// حساب عدد الجلسات بناءً على الباقة أو الفئة
        private function calculateSessionsCount(Request $request)
        {
            if ($request->has('packageId')) {
                return Packages::findOrFail($request->packageId)->sessions_count;
            } elseif ($request->has('categoryId')) {
                return categories::findOrFail($request->categoryId)->sessions_count;
            }
            return 0;
        }

// إنشاء الطلب وحفظ البيانات
        private function createOrder(Request $request, $orderData)
        {
            $user_recipient = null;
            $type = Session::has('service_type') ? Session::get('service_type') : request()->route('type');

            $locale_price = ($type === 'homeServices') ? 'at_home' : 'at_spa';
            if ($request->has('phone_recipient')) {
                $user_recipient = User::where('phone', $request->phone_recipient)->first();
                if (!$user_recipient) {
                    $user_recipient = new User();
                    $user_recipient->name = $request->name_recipient;
                    $user_recipient->phone = $request->phone_recipient;
                    $user_recipient->code = User::generateUniqueCode();
                    $user_recipient->save();
                }
            }
            $order = new \App\Models\Order();

            if (JWTAuth::getToken()) {
                try {
                    $user = JWTAuth::parseToken()->authenticate();
                    $order->order_code = Order::generateCustomId($user->code);

                } catch (\Exception $e) {
                    return response()->json(['error' => 'User not authenticated'], 401);
                }
            } else {
                return response()->json(['error' => 'No token provided'], 401);
            }
            $order->user_id = $user_recipient->id;
            $order->fill($orderData);
            $order->reservation_status = $locale_price;
            $order->is_paid = false;
            $order->is_gift = true;
            $order->save();
            if ($user_recipient) {
                $gift = new Gift();
                $gift->gift_code = Gift::generateGiftCode();
                $gift->order_id = $order->id;
                $gift->category_id = $order->category_id;
                $gift->package_id = $order->package_id;
                $gift->sender_id = $user->id;
                $gift->recipient_id = $user_recipient->id;
                $gift->expiry_date = Carbon::now()->addDays(3);
                $gift->used = false;
                $gift->save();
            }
            return $order;
        }

// إنشاء الفاتورة في MyFatoorah
        private function createInvoiceInMyFatoorah($order, $total_price)
        {
            $productName = $order->package_id && $order->package
                ? $order->package->getTranslation('name', 'ar')
                : ($order->category ? $order->category->getTranslation('name', 'ar') : 'فئة غير محددة');
            $invoiceCode = $order->order_code;
            return $this->myFatoorahService->createInvoice(
                $order->id,
                $productName,
                $invoiceCode,
                0,
                null,
                $total_price
            );
        }

// حفظ الجلسات المتعلقة بالطلب
        private function createOrderSessions(Request $request, $order)
        {
            for ($i = 0; $i < $order->sessions_count; $i++) {
                $session = new OrderSession();
                $session->order_id = $order->id;
                // For subsequent sessions, set session_date to null and status to 'empty'
                $session->session_date = null;
                $session->status = 'pending';
                $session->save();
            }
        }

        public function PaymentGIFTCallback(Request $request)
        {
            Log::info('Payment Callback Data ANDREW:', $request->all());

            try {
                // الحصول على الـ paymentId من الطلب
                $paymentId = $request->input('paymentId');
                // التحقق من حالة الدفع من MyFatoorah باستخدام paymentId
                $mfObj = new MyFatoorahPaymentStatus($this->mfConfig);
                $paymentData = $mfObj->getPaymentStatus($paymentId, 'PaymentId');
                $invoiceStatus = $paymentData->InvoiceStatus;
                $transactionStatus = $paymentData->InvoiceTransactions[0]->TransactionStatus;
                // إذا كانت الفاتورة مدفوعة أو المعاملة ناجحة
                if ($invoiceStatus === 'Paid' && $transactionStatus === 'Succss') {
                    try {
                        DB::beginTransaction();
                        // الحصول على orderId من CustomerReference
                        $orderId = $paymentData->CustomerReference;
                        $order = \App\Models\Order::findOrFail($orderId);
                        // تحديث حالة الطلب إلى مدفوع
                        $order->is_paid = true;
                        $order->save();
                        DB::commit();
                        $gift = Gift::where('order_id', $order->id)->first();
                        $orderGifts = Order::findorFail($order->id);
                        $reservation_status = $orderGifts->reservation_status;
                        if ($reservation_status === 'at_home') {
                            $locale_type = 'homeServices';
                        } else {
                            $locale_type = 'SPA';
                        }

                        // Log::info('Payment was successful.GIFTCODE::::::::::::  ' . route('showGift', ['type' => $locale_type, 'gift' => $gift->id]));
//                        $curl = curl_init();
//                        curl_setopt_array($curl, array(
//                            CURLOPT_URL => 'https://watsabot.com/api/create-message',
//                            CURLOPT_RETURNTRANSFER => true,
//                            CURLOPT_ENCODING => '',
//                            CURLOPT_MAXREDIRS => 10,
//                            CURLOPT_TIMEOUT => 0,
//                            CURLOPT_FOLLOWLOCATION => true,
//                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                            CURLOPT_CUSTOMREQUEST => 'POST',
//                            CURLOPT_POSTFIELDS => http_build_query(array(
//                                'appkey' => '9ee0edee-99c1-45c7-8fd3-80f0ad278176',
//                                'authkey' => 'Cn6OIsqpTbTMDAccQRrJYksGZIVuE9gPWTT7B7SN7TCcT8wN45',
//                                'to' => '+966' . $gift->recipient->phone,
//                                'message' => "أهلين وسهلين عندنا لك هدية 🤩🎁\nمن شخص يعزك 🤍\n🎁 الخدمة منزلية وتوصلك لباب بيتك 🚪✨\n\n" .
//                                    "👈 [اضغط هنا لتفاصيل الهدية](" . route('showGift', $gift->id) . ")",
//                                'file' => 'https://valerianspa.com/assets/admin/images/logo/FullLogo.png',
//                                'sandbox' => 'false'
//                            )),
//                        ));
//                        $response = curl_exec($curl);
//                        curl_close($curl);
//                        echo $response;

                        // نجاح العملية
                        return redirect()->route('PaymentGIFT.MyFatoorah.success')->with('payment_redirect', true)->with('order', $order);

                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error('Error updating order: ' . $e->getMessage());
                        return redirect()->route('PaymentGIFT.MyFatoorah.failed')->with('payment_redirect', true)->with('error', 'حدث خطأ أثناء معالجة الطلب.');
                    }
                } else {
                    // إذا كانت حالة الفاتورة فاشلة أو حالة المعاملة ليست ناجحة، نقوم بحذف الطلب
                    try {
                        DB::beginTransaction();

                        // الحصول على orderId من CustomerReference
                        $orderId = $paymentData->CustomerReference;
                        $order = \App\Models\Order::findOrFail($orderId);

                        // حذف الطلب
                        $order->delete();

                        DB::commit();
                        return redirect()->route('PaymentGIFT.MyFatoorah.failed')->with('payment_redirect', true)->with('error', 'عملية الدفع فشلت وتم حذف الطلب.');
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error('Error deleting order: ' . $e->getMessage());
                        // return redirect()->route('Payment.MyFatoorah.failed')->with('error', 'حدث خطأ أثناء معالجة الطلب: ' . $e->getMessage());
                    }
                }
            } catch (\Exception $ex) {
                Log::error('Error processing payment callback: ' . $ex->getMessage());
                //return redirect()->route('Payment.MyFatoorah.failed')->with('error', 'حدث خطأ أثناء معالجة الطلب: ' . $e->getMessage());
            }
        }


        public function store(Request $request)
        {

            //$dateTimeString = $convertedDate . ' ' . $timeAvailable;
            $type = Session::has('service_type') ? Session::get('service_type') : request()->route('type');

            $locale_price = ($type === 'homeServices') ? 'at_home' : 'at_spa';

            // $sessionDateTime = Carbon::createFromFormat('Y-m-d g:i A', $dateTimeString)->format('Y-m-d H:i:s');
            // return $sessionDateTime;
            if (JWTAuth::getToken()) {
                $user = JWTAuth::parseToken()->authenticate();

                $order_code = Order::generateCustomId($user->code);
            }

            if ($request->has('categoryId')) {
                // Fetch category price from the database
                $category = categories::findorFail($request->categoryId);

                if ($category) {
                    // Set price based on reservation status
                    $total_price = $category->discount ? $category->discount->$locale_price : $category->prices->$locale_price;
                }
            } elseif ($request->has('packageId')) {
                // Fetch package price from the database
                $package = Packages::findorFail($request->packageId);
                if ($package) {
                    // Set price based on reservation status
                    $total_price = $package->discount ? $package->discount->$locale_price : $package->prices->$locale_price;
                }
            }
            if ($request->has('coupon_code')) {
                $coupon = Coupon::where('code', $request->coupon_code)
                    ->where('is_active', true)
                    ->where(function ($query) use ($request) {
                        $query->whereHas('categories', function ($query) use ($request) {
                            $query->where('category_id', $request->categoryId);
                        })->orWhereHas('packages', function ($query) use ($request) {
                            $query->where('package_id', $request->packageId);
                        });
                    })
                    ->first();
                if ($coupon->discount_type === 'fixed') {

                    $total_price -= $coupon->value;
                } else if ($coupon->discount_type === 'percentage') {

                    $total_price -= ($total_price * $coupon->value / 100);
                }
            }

            return $request->all();


        }


        public function successGift()
        {
            return view('front.status_order.success');
        }

        public function failedGift()
        {
            return view('front.status_order.failed');
        }
    }
