<?php

    namespace App\Http\Controllers;

    use App\Models\categories;
    use App\Models\categories_prices;
    use App\Models\Coupon;
    use App\Models\Order;
    use App\Models\OrderSession;
    use App\Models\Packages;
    use App\Models\packages_prices;
    use App\Services\TamaraService;
    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;
    use Tymon\JWTAuth\Facades\JWTAuth;

    class PaymentTestController extends Controller
    {

        public function storeOrder(Request $request, TamaraService $tamaraService)
        {
            DB::beginTransaction(); // Start the database transaction
            try {
                // 1. **🌟 START Initialize Variables 🌟**
                $total_price = $this->calculateTotalPrice($request);
                Log::info('Payload total_price to Tamara 1: ' . $total_price);

                // 2. **🏷️ START Apply Coupon Discount 🏷️**
                $total_price = $this->applyCouponDiscount($request, $total_price);
                if ($this->applyCouponDiscount($request, $total_price) == $total_price = $this->calculateTotalPrice($request)) {
                    $total_price += $total_price * 0.07;
                }

                // 3. **📦 START Create Order 📦**
                $order = $this->createOrder($request, $total_price);

                // 4. **📅 START Create Sessions 📅**
                $this->createSessions($request, $order);

                // 5. **🌍 START Get Address 🌍**
                $address = $this->getAddress($request);

                // 6. **📝 START Prepare Order Data 📝**
                $orderData = $this->prepareOrderData($order, $total_price, $address, $request);
                Log::info('Payload sent to Tamara: ' . print_r($orderData, true));

                // 7. **🌟 START Request Payment from Tamara 🌟**
                $tamaraResponse = $tamaraService->createOrder($orderData);

                // Log the full response for debugging purposes
                Log::info('Tamara API Response: ' . print_r($tamaraResponse, true));

                // تحقق من حالة الاستجابة وأكمل عملية الدفع
                if (isset($tamaraResponse['checkout_url'])) {
                    $paymentUrl = $tamaraResponse['checkout_url'] . '&order_code=' . $order->order_code;
                    DB::commit();
                    return redirect()->away($paymentUrl);


                } else {
                    // سجل استجابة Tamara للمساعدة في تشخيص المشكلة
                    Log::error('Tamara response: ' . json_encode($tamaraResponse));
                    DB::rollBack();
                    return response()->json(['error' => 'خطأ في الاتصال بـ Tamara: ' . json_encode($tamaraResponse)], 500);
                }
            } catch (\Exception $e) {
                // 12. **🛑 Handle Exceptions 🛑**
                Log::error('Order Processing Error: ' . $e->getMessage());
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

            // 🏷️ Check if the request contains a category
            if ($request->has('categories')) {
                // 🔍 Find the category price by category_id
                $category_price = categories_prices::where('category_id', $request->categories)->first();

                // ✅ If the category price is found, calculate the total price
                if ($category_price) {
                    $total_price = $category_price->at_home;
                }
            } // 📦 Check if the request contains a package if no category is provided
            elseif ($request->has('packageId')) {
                // 🔍 Find the package price by package_id
                $package_price = packages_prices::where('package_id', $request->packageId)->first();

                // ✅ If the package price is found, calculate the total price
                if ($package_price) {
                    $total_price = $package_price->at_home;
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
            Log::info('applyCouponDiscount: ' . $total_price);

            // 🔢 Calculate 15% and 7% of the original total price
            $percentage15 = $total_price * 0.15;
            Log::info('percentage15: ' . $percentage15);

            $percentage7 = $total_price * 0.07;
            Log::info('percentage7: ' . $percentage7);

            // 🏷️ Check if a coupon code is provided in the request
            if ($request->has('coupon_code')) {
                // 🔍 Look up the coupon in the database by its code
                $coupon = Coupon::where('code', $request->coupon_code)
                    ->where('is_active', true) // ✅ Make sure the coupon is active
                    ->where(function ($query) use ($request) {
                        $query->whereHas('categories', function ($query) use ($request) {
                            // 📂 Check if the coupon applies to the requested category
                            $query->where('category_id', $request->category_id);
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
                        $price_after_coupon = $price_after_15_percent - ($price_after_15_percent * $coupon->value / 100);
                        Log::info('price_after_coupon: ' . $price_after_coupon);

                    } elseif ($coupon->discount_type === 'fixed') {
                        $price_after_coupon = $price_after_15_percent - $coupon->value;
                    }

                    // 💵 Add 15% and 7% back to the final price
                    $total_price = $price_after_coupon + $percentage15 + $percentage7;
                    Log::info('Total price after applying coupon: ' . $total_price);
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
            //DB::beginTransaction();

            $order = new \App\Models\Order();
            if (JWTAuth::getToken()) {
                $user = JWTAuth::parseToken()->authenticate();
                $order->order_code = Order::generateCustomId($user->code);
                $order->user_id = $user->id;
            }

            $order->package_id = $request->packageId;
            $order->category_id = $request->input('category_id');
            $order->employee_id = $request->input('employeeAvailable');
            $order->total_price = $total_price;
            $order->payment_gateway = 'Tamara';
            $order->location = json_encode([
                'latitude' => $request->latitude,
                'longitude' => $request->longitude
            ]);
            $order->notes = $request->input('notes');
            $order->reservation_status = 'at_home';
            $order->sessions_count = $this->getSessionsCount($request);
            $order->is_paid = false; // The invoice is not paid yet
            $order->save();
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
                if ($i === 0) {
                    $this->setFirstSessionDate($request, $session);
                } else {
                    $session->session_date = null;
                    $session->status = 'pending';
                }
                $session->save();
            }
        }

        /**
         * Set the date and time for the first session.
         */
        private function setFirstSessionDate(Request $request, $session)
        {
            try {
                $convertedDate = $request->input('formattedDate'); // Date in 'Y-m-d' format
                $timeAvailable = $request->input('timeAvailable'); // Time in 'g:i A' format

                $dateTimeString = $convertedDate . ' ' . $timeAvailable;
                $sessionDateTime = Carbon::createFromFormat('Y-m-d g:i A', $dateTimeString)->format('Y-m-d H:i:s');
                $session->session_date = $sessionDateTime;
                $session->status = 'pending'; // Set status for the first session
            } catch (\Exception $e) {
                Log::error('Error while setting session date: ' . $e->getMessage());
                throw new \Exception('Error while setting session date');
            }
        }

        /**
         * Get the address based on latitude and longitude.
         */
        private function getAddress(Request $request)
        {
            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$latitude}&lon={$longitude}";

            $options = [
                'http' => [
                    'header' => "User-Agent: YourAppName/1.0 (yourname@example.com)\r\n"
                ]
            ];
            $context = stream_context_create($options);
            $response = file_get_contents($url, false, $context);

            if ($response !== false) {
                $data = json_decode($response, true);
                if (isset($data['display_name'])) {
                    $address = $data['display_name'];
                } else {
                    $address = 'NONE';
                }
            }
            Log::info('Address Data: ' . print_r($address, true));

            return $address;
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
                        'name' => (string)$order->package->getTranslation('name', 'ar'),
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
                    'last_name' => explode(' ', $user->name)[1],
                    'phone_number' => (string)$user->phone,
                ],
                'country_code' => 'SA',
                'description' => 'Enter order description here.',
                'merchant_url' => [
                    'cancel' => route('Payment.TAMARA.cancel', ['order_code' => $order->order_code]),
                    //'return' => route('order.thank_you'),
                    'success' => route('Payment.TAMARA.success', ['order_code' => $order->order_code]),
                    'failure' => route('Payment.TAMARA.failed', ['order_code' => $order->order_code]),
                ],
                'shipping_address' => [
                    'first_name' => explode(' ', $user->name)[0],
                    'last_name' => '999',
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
            } elseif ($request->has('categories')) {
                $sessions_count = categories::findOrFail($request->categories)->sessions_count;
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
                // جلب الطلب باستخدام معرف الطلب أو أي وسيلة تعريف أخرى
                $order = Order::where('order_code', $order_code)->first();
                Log::info('$order_code::::::::::::  ' . $order);

                if ($paymentStatus == 'approved') {
                    // إذا كان الطلب موجودًا ولم يُدفع بعد، يتم تحديث حالته إلى مدفوع
                    Log::info(' $order->is_paid::::::::::::  ' . $order->is_paid);

                    $order->is_paid = 1;
                    $order->save();

                    DB::commit();

                    return view('front.status_order.success')->with('message', 'Payment was successful.');
                }

                return view('front.status_order.failed')->with('error', 'Order not found or already paid.');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error handling successful payment: ' . $e->getMessage());
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
                // جلب الطلب باستخدام معرف الطلب أو أي وسيلة تعريف أخرى
                $order = Order::where('order_code', $order_code)->first();

                if ($order) {
                    // إذا كان الطلب موجودًا، يتم حذفه
                    $order->delete();

                    DB::commit();

                    return view('front.status_order.failed')->with('message', 'Payment failed. The order has been deleted.');
                }

                return view('front.status_order.failed')->with('error', 'Order not found.');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error handling failed payment: ' . $e->getMessage());
                return view('front.status_order.failed')->with('error', 'An error occurred while processing the payment.');
            }
        }

        public function handleCancel(Request $request, $order_code)
        {
            DB::beginTransaction();

            try {
                // جلب الطلب باستخدام معرف الطلب أو أي وسيلة تعريف أخرى
                $order = Order::where('order_code', $order_code)->first();

                if ($order) {
                    // إذا كان الطلب موجودًا، يتم حذفه
                    $order->delete();

                    DB::commit();

                    return view('front.status_order.failed')->with('message', 'Payment failed. The order has been deleted.');
                }

                return view('front.status_order.failed')->with('error', 'Order not found.');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error handling failed payment: ' . $e->getMessage());
                return view('front.status_order.failed')->with('error', 'An error occurred while processing the payment.');
            }
        }

    }
