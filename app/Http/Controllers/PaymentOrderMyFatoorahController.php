<?php

    namespace App\Http\Controllers;

    use App\Http\Requests\StoreOrderRequest;
    use App\Models\categories;
    use App\Models\categories_prices;
    use App\Models\Coupon;
    use App\Models\Order;
    use App\Models\OrderSession;
    use App\Models\Packages;
    use App\Models\packages_prices;
    use App\Services\MyFatoorahService;
    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;
    use MyFatoorah\Library\API\Payment\MyFatoorahPayment;
    use Tymon\JWTAuth\Facades\JWTAuth;
    use MyFatoorah\Library\API\Payment\MyFatoorahPaymentEmbedded;
    use MyFatoorah\Library\API\Payment\MyFatoorahPaymentStatus;
    use Illuminate\Support\Facades\Session;

    class PaymentOrderMyFatoorahController extends Controller
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
// Validate the data for accuracy ✅🔍
            //$validatedData = $request->validated();

// Determine the total price based on the category or package 💲📦
            $total_price = $this->calculateTotalPrice($request);

// Validate the coupon and apply discount if available 🎟️✨
            $total_price = $this->applyCoupon($request, $total_price);

// Prepare the data for the order 📋🛒
            $orderData = $this->prepareOrderData($request, $total_price);

            try {
// Initiate the transaction process 🚀💳
                DB::beginTransaction();

// Create the order and save the data 🛒💾
                $order = $this->createOrder($orderData);

// Create the invoice in MyFatoorah 🧾💼
                $invoiceURL = $this->createInvoiceInMyFatoorah($order, $total_price);

// Save sessions related to the order 💾🛒
                $this->createOrderSessions($request, $order);

// Complete the transaction ✅💼
                DB::commit();

// Return the invoice link 🔗🧾
                if ($request->ajax() && (auth()->check() || $request->input('payment_method') === 'myfatoorah')) {
                    return response()->json(['invoice_url' => $invoiceURL]);
                } else {
                    return redirect()->away($invoiceURL);
                }

            } catch (\Exception $e) {
// Cancel the transaction in case of an error ❌⚠️
                DB::rollBack();
                return response()->json(['error' => 'حدث خطأ أثناء معالجة الطلب: ' . $e->getMessage()], 500);
            }
        }

// Calculate the price based on the category or package 💲📦
        private function calculateTotalPrice(Request $request)
        {
            $type = Session::has('service_type') ? Session::get('service_type') : request()->route('type');
            $locale_price = ($type === 'homeServices') ? 'at_home' : 'at_spa';

            $total_price = 0;

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

        // Validate coupon and apply discount if available 🎟️✨
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


// Prepare data for the order 📋🛒
        private function prepareOrderData(Request $request, $total_price)
        {
            $packageId = $request->input('packageId');
            $categoryId = $request->input('categoryId');
            $employeeId = $request->input('employeeAvailable');
            $location = json_encode([
                'latitude' => $request->latitude,
                'longitude' => $request->longitude
            ]);
            $notes = $request->input('notes');
            Log::info('Employee ID:', [$employeeId]);

// Calculate the number of sessions based on the package or category 📊🔢
            $sessions_count = $this->calculateSessionsCount($request);

            return [
                'employee_id' => $employeeId,
                'package_id' => $packageId,
                'category_id' => $categoryId,
                'total_price' => $total_price,
                'payment_gateway' => 'MyFatoorah',
                'location' => $location,
                'notes' => $notes,
                'sessions_count' => $sessions_count,
            ];
        }

// Calculate the number of sessions based on the package or category 📊🔢
        private function calculateSessionsCount(Request $request)
        {
            if ($request->has('packageId')) {
                return Packages::findOrFail($request->packageId)->sessions_count;
            } elseif ($request->has('categoryId')) {
                return categories::findOrFail($request->categoryId)->sessions_count;
            }
            return 0;
        }

// Create the order and save the data 🛒💾
        private function createOrder($orderData)
        {

            $order = new \App\Models\Order();
            $type = Session::has('service_type') ? Session::get('service_type') : request()->route('type');

            $locale_price = ($type === 'homeServices') ? 'at_home' : 'at_spa';
            if (JWTAuth::getToken()) {
                $user = JWTAuth::parseToken()->authenticate();
                $order->order_code = Order::generateCustomId($user->code);
                $order->user_id = $user->id;
            }

            $order->fill($orderData);
            $order->reservation_status = $locale_price;
            $order->is_paid = false;
            $order->save();

            return $order;
        }

// Create invoice in MyFatoorah 🧾💼
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

// Save sessions related to the order 💾🛒
        private function createOrderSessions(Request $request, $order)
        {
            for ($i = 0; $i < $order->sessions_count; $i++) {
                $session = new OrderSession();
                $session->order_id = $order->id;

                if ($i === 0) {
                    try {
                        $convertedDate = $request->input('formattedDate');
                        $timeAvailable = $request->input('timeAvailable');

                        $convertedDate = (string)$convertedDate;
                        $timeAvailable = (string)$timeAvailable;

                        $dateTimeString = $convertedDate . ' ' . $timeAvailable;
                        $sessionDateTime = Carbon::createFromFormat('Y-m-d g:i A', $dateTimeString)->format('Y-m-d H:i:s');
                        $session->session_date = $sessionDateTime;
                    } catch (\Exception $e) {
                        Log::error('Error while storing order: ' . $e->getMessage());
                        throw new \Exception('حدث خطأ أثناء معالجة الطلب.');
                    }
                    $session->status = 'pending'; // Set status for the first session
                } else {
                    // For subsequent sessions, set session_date to null and status to 'empty'
                    $session->session_date = null;
                    $session->status = 'pending';
                }
                $session->save();
            }
        }

        public function paymentCallback(Request $request)
        {
            Log::info('Payment Callback Data:', $request->all());

            try {
                // Retrieve paymentId from the order 📦💳
                $paymentId = $request->input('paymentId');

                // Check payment status from MyFatoorah using paymentId 🧾🔍
                $mfObj = new MyFatoorahPaymentStatus($this->mfConfig);
                $paymentData = $mfObj->getPaymentStatus($paymentId, 'PaymentId');

                $invoiceStatus = $paymentData->InvoiceStatus;
                $transactionStatus = $paymentData->InvoiceTransactions[0]->TransactionStatus;

                // If the invoice is paid or the transaction is successful 💳✅
                if ($invoiceStatus === 'Paid' && $transactionStatus === 'Succss') {
                    try {
                        DB::beginTransaction();

                        // Retrieve orderId from CustomerReference 🔗📄
                        $orderId = $paymentData->CustomerReference;
                        $order = \App\Models\Order::findOrFail($orderId);
                        // Update order status to Paid 💵✔️
                        $order->is_paid = true;
                        $order->save();

                        DB::commit();

                        // Operation successful ✅✨
                        return redirect()->route('Payment.MyFatoorah.success')->with('payment_redirect', true)->with('order', $order);

                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error('Error updating order: ' . $e->getMessage());
                        return redirect()->route('Payment.MyFatoorah.failed')->with('payment_redirect', true)->with('error', 'حدث خطأ أثناء معالجة الطلب.');
                    }
                } else {
// If the invoice status is failed or the transaction is not successful, delete the order ❌🧾
                    try {
                        DB::beginTransaction();

                        // Retrieve orderId from CustomerReference 🔍📦
                        $orderId = $paymentData->CustomerReference;
                        $order = \App\Models\Order::findOrFail($orderId);

                        // Delete the order 🗑️
                        $order->delete();

                        DB::commit();
                        return redirect()->route('Payment.MyFatoorah.failed')->with('payment_redirect', true)->with('error', 'عملية الدفع فشلت وتم حذف الطلب.');
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
            $type = Session::has('service_type') ? Session::get('service_type') : request()->route('type');

            $locale_price = ($type === 'homeServices') ? 'at_home' : 'at_spa';

            $convertedDate = $request->input('formattedDate');
            $timeAvailable = $request->input('timeAvailable');


            $convertedDate = (string)$convertedDate;
            $timeAvailable = (string)$timeAvailable;

            $dateTimeString = $convertedDate . ' ' . $timeAvailable;

            $sessionDateTime = Carbon::createFromFormat('Y-m-d g:i A', $dateTimeString)->format('Y-m-d H:i:s');
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


        public function success()
        {
            return view('front.status_order.success');
        }

        public function failed()
        {
            return view('front.status_order.failed');
        }
    }
