<?php

    namespace App\Http\Controllers;

    use App\Http\Requests\StoreSaveGiftOrderRequest;
    use App\Models\categories;
    use App\Models\Coupon;
    use App\Models\CouponUsage;
    use App\Models\Gift;
    use App\Models\Order;
    use App\Models\OrderSession;
    use App\Models\Packages;
    use Brian2694\Toastr\Facades\Toastr;
    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Log;
    use stdClass;
    use Tymon\JWTAuth\Facades\JWTAuth;
    use Illuminate\Support\Facades\Session;

    class GiftController extends Controller
    {
        public function gift(Request $request, $type = null)
        {
            if (JWTAuth::getToken()) {
                $user = JWTAuth::parseToken()->authenticate();
                $order_code = Order::generateCustomId($user->code);
            }

            $serviceId = $request->input('serviceId');
            $serviceName = $request->input('serviceName');
            $serviceType = $request->input('serviceType');
            $phone = $request->input('phoneNumber');

            $Data = new stdClass();
            $Data->serviceId = $serviceId;
            $Data->serviceName = $serviceName;
            $Data->serviceType = $serviceType;
            $Data->phone = $phone;

            if ($Data->serviceType == 'package') {
                $package = Packages::with(['prices', 'employees', 'availability'])
                    ->where('id', $serviceId)
                    ->firstOrFail();
                $Data->package = $package;
                $Data->category = null;

                return view('front.gift.index_packages', ['Data' => $Data]);

            } else {
                $category = Categories::with(['prices', 'employees', 'availability'])
                    ->where('id', $serviceId)
                    ->firstOrFail();
                $Data->category = $category;
                $Data->package = null;

                return view('front.gift.index_categories', ['Data' => $Data]);
            }
        }


        public function show($type = null, Gift $gift)
        {
            $locale_type = $type ?? '';
            $reservation_status = '';
            if ($locale_type === 'homeServices') {
                $reservation_status = 'at_home';
            } elseif ($locale_type === 'SPA') {
                $reservation_status = 'at_spa';
            }
            $order = Order::where('id', $gift->order_id)->first();
            if ($gift->used == 0 && $gift->expiry_date > now() && $order->reservation_status == $reservation_status) {
                return view('front.gift.recipient', compact('gift', 'order', 'type'));
            } elseif ($gift->used == 0 && $gift->expiry_date < now()) {
                return view('front.status_order.failed_gift');
            } else {
                return view('front.status_order.used_gift');
            }
        }

        public function saveOrderGift(StoreSaveGiftOrderRequest $request, Gift $gift)
        {
            $validatedData = $request->validated();
            $order = Order::where('id', $gift->order_id)->with('sessions')->first();

            if (!$order) {
                // Handle the case when the order is not found
                Toastr::error('لا يوجد هذه الهدية لدينا', 'عملية فاشلة');

                return redirect()->route('index', ['type' => Session::has('service_type') ? Session::get('service_type') : request()->route('type')]);
            }

            // Update sessions
            $this->updateSessions($request, $order);
            // Update order details if necessary
            $this->updateOrderDetails($request, $order);
            // Handle any other logic if needed
            $gift->used = 1;
            $gift->save();
            Toastr::success(' 😀  لقد تم أضافة الهدية بنجاح', 'عملية ناجحة');

            return redirect()->route('index', ['type' => Session::has('service_type') ? Session::get('service_type') : request()->route('type')]);
        }

        /**
         * Update the order details.
         */
        private function updateOrderDetails(Request $request, $order)
        {
            // Example: Update order status or other attributes if needed
            $order->employee_id = $request->employeeAvailable; // Update status if provided
            $order->notes = $request->notes;
            // Add other updates as needed
            $order->location = json_encode([
                'latitude' => $request->latitude,
                'longitude' => $request->longitude
            ]);
            $order->save(); // Save the updated order
        }

        /**
         * Update sessions for the given order.
         */
        private function updateSessions(Request $request, $order)
        {
            $sessions_count = $order->sessions_count;
            $sessions = $order->sessions;

            foreach ($sessions as $index => $session) {
                if ($index === 0) {
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


        public function checkCouponGifts(Request $request)
        {
            try {
                $request->validate([
                    'coupon_code' => 'required|string',
                    'package_id' => 'nullable|integer',
                    'category_id' => 'nullable|integer',
                ]);

                $coupon = Coupon::where('code', $request->coupon_code)
                    ->where('is_active', true)
                    ->where(function ($query) use ($request) {
                        $query->whereHas('categories', function ($query) use ($request) {
                            $query->where('category_id', $request->category_id);
                        })->orWhereHas('packages', function ($query) use ($request) {
                            $query->where('package_id', $request->package_id);
                        });
                    })
                    ->first();

                if (!$coupon) {
                    return response()->json([
                        'success' => false,
                        'message' => 'الكوبون غير صالح أو منتهي الصلاحية أو غير قابل للتطبيق.'
                    ]);
                }

                $currentDateTime = Carbon::now(); // الحصول على الوقت والتاريخ الحالي
                $startDateTime = $coupon->start_date ? Carbon::parse($coupon->start_date . ' ' . $coupon->start_time) : null;
                $endDateTime = $coupon->end_date ? Carbon::parse($coupon->end_date . ' ' . $coupon->end_time) : null;

                // التحقق من صلاحية الكوبون بناءً على وقت البدء ووقت الانتهاء
                if ($startDateTime && $currentDateTime->lessThan($startDateTime)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'عذراً، الكوبون غير صالح حالياً. صالح ابتداءً من ' . $startDateTime->format('Y-m-d H:i:s')
                    ]);
                }

                if ($endDateTime && $currentDateTime->greaterThan($endDateTime)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'عذراً، انتهت صلاحية الكوبون. صالح حتى ' . $endDateTime->format('Y-m-d H:i:s')
                    ]);
                }

                if ($coupon->usage_limit && $coupon->usages()->count() >= $coupon->usage_limit) {
                    return response()->json([
                        'success' => false,
                        'message' => 'تم تجاوز الحد الأقصى لاستخدام الكوبون.'
                    ]);
                }

                if (JWTAuth::getToken()) {
                    $user = JWTAuth::parseToken()->authenticate();
                    CouponUsage::create([
                        'coupon_id' => $coupon->id,
                        'user_id' => $user->id
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'discount_value' => $coupon->value,
                    'discount_type' => $coupon->discount_type,
                    'message' => 'تم تطبيق الكوبون بنجاح.'
                ]);
            } catch (\Exception $e) {
                // سجل الخطأ
                Log::error('خطأ في تطبيق الكوبون: ' . $e->getMessage());

                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء معالجة طلبك.',
                    //  'error' => $e->getMessage() // قم بإزالة هذا في الإنتاج
                ]);
            }
        }

    }
