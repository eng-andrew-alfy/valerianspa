<?php

    namespace App\Http\Controllers;

    use App\Models\categories;
    use App\Models\categories_prices;
    use App\Models\Coupon;
    use App\Models\Gift;
    use App\Models\Order;
    use App\Models\OrderSession;
    use App\Models\Packages;
    use App\Models\packages_prices;
    use App\Models\User;
    use App\Services\TamaraService;
    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Session;
    use Tymon\JWTAuth\Facades\JWTAuth;

    class PaymentGiftTamaraController extends Controller
    {
        public function storeOrder(Request $request, TamaraService $tamaraService)
        {
            //Log::info('Payload to Tamara 1: ' . json_encode($request->all()));
            DB::beginTransaction(); // Start the database transaction
            try {
                // 1. **🌟 START Initialize Variables 🌟**
                $total_price = $this->calculateTotalPrice($request);
                //Log::info('Payload total_price to Tamara 1: ' . $total_price);

                // 2. **🏷️ START Apply Coupon Discount 🏷️**
                $total_price = $this->applyCouponDiscount($request, $total_price);

                if ($this->applyCouponDiscount($request, $total_price) != $total_price = $this->calculateTotalPrice($request)) {
                    $total_price = $this->applyCouponDiscount($request, $total_price);
                } else {
                    $total_price += $total_price * 0.07;
                }

                // 3. **📦 START Create Order 📦**
                $order = $this->createOrder($request, $total_price);


                // 4. **📅 START Create Sessions 📅**
                $this->createSessions($request, $order);

                // 5. **🌍 START Get Address 🌍**
                $address = 'Riyadh, As Sulimaniyah, 12836'; // Default address for now

                // 6. **📝 START Prepare Order Data 📝**
                $orderData = $this->prepareOrderData($order, $total_price, $address, $request);

                // 7. **🌟 START Request Payment from Tamara 🌟**
                $tamaraResponse = $tamaraService->createOrder($orderData);


                // تحقق من حالة الاستجابة وأكمل عملية الدفع
                if (isset($tamaraResponse['checkout_url'])) {
                    $paymentUrl = $tamaraResponse['checkout_url'] . '&order_code=' . $order->order_code;
                    DB::commit();
                    return redirect()->away($paymentUrl);


                } else {
                    // سجل استجابة Tamara للمساعدة في تشخيص المشكلة
                    DB::rollBack();
                    return response()->json(['error' => 'خطأ في الاتصال بـ Tamara: ' . json_encode($tamaraResponse)], 500);
                }
            } catch (\Exception $e) {
                // 12. **🛑 Handle Exceptions 🛑**
                DB::rollBack();
                return response()->json(['error' => 'An error occurred while processing the order.'], 500);
            }
        }

        /**
         * Calculate the total price based on categories or packages.
         */
        private function calculateTotalPrice(Request $request)
        {
            $total_price = 0; // 💰 Initialize the total price to 0
            $type = Session::has('service_type') ? Session::get('service_type') : request()->route('type');

            $locale_price = ($type === 'homeServices') ? 'at_home' : 'at_spa';
            // 🏷️ Check if the request contains a category
            if ($request->has('categoryId')) {
                // 🔍 Find the category price by category_id
                $category = categories::findorFail($request->categoryId);

                // ✅ If the category price is found, calculate the total price
                if ($category) {
                    $total_price = $category->discount ? $category->discount->$locale_price : $category->prices->$locale_price;
                }
            } // 📦 Check if the request contains a package if no category is provided
            elseif ($request->has('packageId')) {
                // 🔍 Find the package price by package_id
                $package = Packages::findorFail($request->packageId);

                // ✅ If the package price is found, calculate the total price
                if ($package) {
                    $total_price = $package->discount ? $package->discount->$locale_price : $package->prices->$locale_price;
                }
            }

            // 🔙 Return the calculated total price including the Tamara tax
            return $total_price;
        }


        /**
         * Apply coupon discount if applicable.
         */

        private function applyCouponDiscount(Request $request, $total_price)
        {
            //Log::info('applyCouponDiscount: ' . $total_price);

            // 🔢 Calculate 15% and 7% of the original total price
            $percentage15 = $total_price * 0.15;
            //Log::info('percentage15: ' . $percentage15);

            $percentage7 = $total_price * 0.07;
            //Log::info('percentage7: ' . $percentage7);

            // 🏷️ Check if a coupon code is provided in the request
            if ($request->has('coupon_code')) {
                // 🔍 Look up the coupon in the database by its code
                $coupon = Coupon::where('code', $request->coupon_code)
                    ->where('is_active', true) // ✅ Make sure the coupon is active
                    ->where(function ($query) use ($request) {
                        $query->whereHas('categories', function ($query) use ($request) {
                            // 📂 Check if the coupon applies to the requested category
                            $query->where('category_id', $request->categoryId);
                        })->orWhereHas('packages', function ($query) use ($request) {
                            // 📦 Check if the coupon applies to the requested package
                            $query->where('package_id', $request->packageId);
                        });
                    })
                    ->first();

                if ($coupon) {
                    // 💵 Subtract 15% from the original price
                    $price_after_15_percent = $total_price - $percentage15;

                    // 💵 Apply percentage discount from the coupon
                    if ($coupon->discount_type === 'percentage') {
                        //Log::info('price_after_15_percent: ' . $price_after_15_percent);

                        $price_after_coupon = $price_after_15_percent - ($price_after_15_percent * $coupon->value / 100);
                        //Log::info('price_after_coupon: ' . $price_after_coupon);

                    } elseif ($coupon->discount_type === 'fixed') {
                        $price_after_coupon = $price_after_15_percent - $coupon->value;
                    }

                    // 💵 Add 15% and 7% back to the final price
                    $total_price = $price_after_coupon + $percentage15 + $percentage7;
                    //Log::info('Total price after applying coupon: ' . $total_price);
                }
            }

            // 🚫 Ensure that the final total price is never negative
            return max($total_price, 0);
        }


        /**
         * Create a new order and save it to the database.
         */
        private function createOrder(Request $request, $total_price)
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
            $order->package_id = $request->packageId;
            $order->category_id = $request->input('categoryId');
            $order->total_price = $total_price;
            $order->payment_gateway = 'Tamara';
            $order->notes = $request->input('notes');
            $order->reservation_status = $locale_price;
            $order->sessions_count = $this->getSessionsCount($request);
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


        /**
         * Create sessions based on the order.
         */
        private function createSessions(Request $request, $order)
        {
            $sessions_count = $order->sessions_count;
            for ($i = 0; $i < $sessions_count; $i++) {
                $session = new OrderSession();
                $session->order_id = $order->id;

                $session->session_date = null;
                $session->status = 'pending';

                $session->save();
            }
        }


        /**
         * Prepare the order data for the Tamara API request.
         */
        private function prepareOrderData($order, $total_price, $address, Request $request)
        {
            $user = JWTAuth::parseToken()->authenticate();

            return [
                'total_amount' => [
                    'amount' => number_format((float)$total_price, 2, '.', ''),
                    'currency' => 'SAR',
                ],
                'shipping_amount' => [
                    'amount' => '0.00',
                    'currency' => 'SAR',
                ],
                'tax_amount' => [
                    'amount' => number_format((float)($total_price * 0.07), 2, '.', ''),
                    'currency' => 'SAR',
                ],
                'order_reference_id' => (string)$order->order_code,
                'order_number' => (string)$order->order_code,
                'items' => [
                    [
                        'name' => (string)(
                        $order->package_id && $order->package
                            ? $order->package->getTranslation('name', 'ar')
                            : ($order->category ? $order->category->getTranslation('name', 'ar') : 'فئة غير محددة')
                        ),
                        'reference_id' => (string)$order->order_code,
                        'quantity' => 1,
                        'unit_price' => [
                            'amount' => number_format((float)$total_price, 2, '.', ''),
                            'currency' => 'SAR',
                        ],
                        'total_amount' => [
                            'amount' => number_format((float)$total_price, 2, '.', ''),
                            'currency' => 'SAR',
                        ],
                        'type' => 'physical',
                        'sku' => (string)$order->order_code
                    ]
                ],
                'consumer' => [
                    'email' => 'test@test.com',
                    'first_name' => explode(' ', $user->name)[0],
                    'last_name' => array_key_exists(1, explode(' ', $user->name)) ? explode(' ', $user->name)[1] : 'NONE',
                    'phone_number' => (string)$user->phone,
                ],
                'country_code' => 'SA',
                'description' => 'Enter order description here.',
                'merchant_url' => [
                    'cancel' => route('PaymentGIFT.TAMARA.cancel', ['order_code' => $order->order_code]),
                    //'return' => route('order.thank_you'),
                    'success' => route('PaymentGIFT.TAMARA.success', ['order_code' => $order->order_code]),
                    'failure' => route('PaymentGIFT.TAMARA.failed', ['order_code' => $order->order_code]),
                ],
                'shipping_address' => [
                    'first_name' => explode(' ', $user->name)[0],
                    'last_name' => array_key_exists(1, explode(' ', $user->name)) ? explode(' ', $user->name)[1] : 'NONE',
                    'line1' => (string)$address, // حقل واحد للنص الكامل للعنوان
                    'line2' => '', // إذا كان هناك تفاصيل إضافية يمكن إضافتها هنا
                    'city' => 'Riyadh',
                    'region' => 'As Sulimaniyah',
                    'postal_code' => '12836',
                    'country_code' => 'SA',
                    'phone_number' => (string)$user->phone,
                ],
                'additional_data' => [
                    'order_id' => (int)$order->id,
                    'location' => (string)$address,
                    // 'reservation_date' => (string) $request->input('formattedDate')
                ],
                //'callback_url' => route('payment.callback'),
            ];
        }

        /**
         * Get the count of sessions based on the request.
         */
        private function getSessionsCount(Request $request)
        {
            if ($request->has('packageId')) {
                $sessions_count = Packages::findOrFail($request->packageId)->sessions_count;
            } elseif ($request->has('categoryId')) {
                $sessions_count = categories::findOrFail($request->categoryId)->sessions_count;
            }
            return $sessions_count; // Default to 1 if not provided
        }

        /**
         * Handle success payment response from Tamara.
         */
        public function handleSuccess(Request $request, $order_code)
        {
            $paymentStatus = $request->query('paymentStatus');
            $orderId = $request->query('orderId');
            DB::beginTransaction();

            try {
                $order = Order::where('order_code', $order_code)->first();

                if ($paymentStatus == 'approved') {

                    $order->is_paid = 1;
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
                    //Log::info('Payment was successful.GIFTCODE::::::::::::  ' . route('showGift', ['type' => $locale_type, 'gift' => $gift->id]));
                    //Log::info('Payment was successful.GIFTCODE::::::::::::  ' . route('showGift', $gift->id));

                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://watsabot.com/api/create-message',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => http_build_query(array(
                            'appkey' => '9ee0edee-99c1-45c7-8fd3-80f0ad278176',
                            'authkey' => 'Cn6OIsqpTbTMDAccQRrJYksGZIVuE9gPWTT7B7SN7TCcT8wN45',
                            'to' => '+966' . $gift->recipient->phone,
                            'message' => "أهلين وسهلين عندنا لك هدية 🤩🎁\nمن شخص يعزك 🤍\n🎁 الخدمة منزلية وتوصلك لباب بيتك 🚪✨\n\n" .
                                "👈 [اضغط هنا لتفاصيل الهدية](" . route('showGift', ['type' => $locale_type, 'gift' => $gift->id]) . ")",
                            'file' => 'https://valerianspa.com/assets/admin/images/logo/FullLogo.png',
                            'sandbox' => 'false'
                        )),

                    ));

                    $response = curl_exec($curl);

                    curl_close($curl);
                    echo $response;

                    return view('front.status_order.success')->with('message', 'Payment was successful.');

                }

                return view('front.status_order.failed')->with('error', 'Order not found or already paid.');
            } catch (\Exception $e) {
                DB::rollBack();
                return view('front.status_order.failed')->with('error', 'An error occurred while processing the payment.');
            }
        }

        /**
         * Handle failure payment response from Tamara.
         */
        public function handleFailure(Request $request, $order_code)
        {
            DB::beginTransaction();

            try {
                $order = Order::where('order_code', $order_code)->first();

                if ($order) {
                    $order->delete();

                    DB::commit();

                    return view('front.status_order.failed')->with('message', 'Payment failed. The order has been deleted.');
                }

                return view('front.status_order.failed')->with('error', 'Order not found.');
            } catch (\Exception $e) {
                DB::rollBack();
                return view('front.status_order.failed')->with('error', 'An error occurred while processing the payment.');
            }
        }

        public function handleCancel(Request $request, $order_code)
        {
            DB::beginTransaction();

            try {
                $order = Order::where('order_code', $order_code)->first();

                if ($order) {
                    $order->delete();

                    DB::commit();

                    return view('front.status_order.failed')->with('message', 'Payment failed. The order has been deleted.');
                }

                return view('front.status_order.failed')->with('error', 'Order not found.');
            } catch (\Exception $e) {
                DB::rollBack();
                return view('front.status_order.failed')->with('error', 'An error occurred while processing the payment.');
            }
        }
    }
