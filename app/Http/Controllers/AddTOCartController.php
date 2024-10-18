<?php

    namespace App\Http\Controllers;

    use App\Models\categories;
    use App\Models\Coupon;
    use App\Models\CouponUsage;
    use App\Models\Order;
    use App\Models\Packages;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Log;
    use stdClass;
    use Tymon\JWTAuth\Facades\JWTAuth;
    use Carbon\Carbon;

    class AddTOCartController extends Controller
    {


        public function ajaxCart(Request $request)
        {
//            if (JWTAuth::getToken()) {
//                $user = JWTAuth::parseToken()->authenticate();
//                $order_code = Order::generateCustomId($user->code);
//            }

            $serviceId = $request->input('serviceId');
            $serviceName = $request->input('serviceName');
            $serviceType = $request->input('serviceType');

            // تحقق مما إذا كانت جميع البيانات فارغة
            if (empty($serviceId) && empty($serviceName) && empty($serviceType)) {
                // استرجاع البيانات من الجلسة إذا كانت البيانات كلها فارغة
                $serviceId = session('serviceId');
                $serviceName = session('serviceName');
                $serviceType = session('serviceType');
            } else {
                session([
                    'serviceId' => $serviceId,
                    'serviceName' => $serviceName,
                    'serviceType' => $serviceType
                ]);
            }

            $Data = new stdClass();
            $Data->serviceId = $serviceId;
            $Data->serviceName = $serviceName;
            $Data->serviceType = $serviceType;

            // التحقق مما إذا كانت الخدمة من نوع "package"
            if ($Data->serviceType == 'package') {
                // هنا نبحث عن الباقة بناءً على الـ serviceId إذا كان موجودًا، أو نبحث عن أول باقة
                $package = Packages::with(['prices', 'employees', 'availability'])
                    ->where('id', $serviceId) // البحث باستخدام الـ serviceId
                    ->firstOrFail();
                $Data->package = $package;
                session()->forget('cartPackage');
                session(['cartPackage' => ['Data' => $Data]]);
                return view('front.cart.index_packages', ['Data' => $Data]);

            } else {
                $category = Categories::with(['prices', 'employees', 'availability'])
                    ->where('id', $serviceId)
                    ->firstOrFail();
                $Data->category = $category;
                session()->forget('cartCategories');
                session(['cartCategories' => ['Data' => $Data]]);

                return view('front.cart.index_categories', ['Data' => $Data]);
            }
        }


        public function checkCoupon(Request $request)
        {
            try {
                $request->validate([
                    'coupon_code' => 'required|string',
                    'package_id' => 'nullable|integer',
                    'time_available' => 'required|string',
                    'category_id' => 'nullable|integer',
                ]);
                Log::info('category_id :: ' . $request->input('category_id'));
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
                        'message' => 'Invalid or expired coupon or not applicable.'
                    ]);
                }

                // تحقق من الاستخدام
                if ($coupon->usage_limit && $coupon->usages()->count() >= $coupon->usage_limit) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Coupon usage limit exceeded.'
                    ]);
                }

                // تحقق من صلاحية الكوبون بناءً على end_date فقط إذا كان له تاريخ انتهاء
                $endDateTime = $coupon->end_date ? Carbon::parse($coupon->end_date) : null;

                if ($endDateTime && Carbon::now()->greaterThan($endDateTime)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Coupon has expired.'
                    ]);
                }

                // تحقق من وقت البداية والنهاية إذا كان موجودًا
                $startDateTime = $coupon->start_date ? Carbon::parse($coupon->start_date) : null;

                // إذا كان لا يوجد وقت بداية، نتحقق فقط من الوقت المدخل بالنسبة لنهاية الكوبون
                if ($startDateTime && $endDateTime) {
                    $selectedTime = Carbon::createFromFormat('g:i A', $request->time_available);
                    $selectedDateTime = Carbon::now()->setTime($selectedTime->hour, $selectedTime->minute); // استخدام الوقت المدخل مع التاريخ الحالي

                    // تحقق من الوقت المدخل
                    if ($selectedDateTime->lessThan($startDateTime)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Coupon is not valid for the selected time. Valid from ' . $startDateTime->format('Y-m-d H:i:s')
                        ]);
                    }

                    if ($endDateTime && $selectedDateTime->greaterThan($endDateTime)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Coupon is not valid for the selected time. Valid until ' . $endDateTime->format('Y-m-d H:i:s')
                        ]);
                    }
                } elseif ($endDateTime) {
                    // إذا كان فقط وقت النهاية موجود، تحقق منه
                    $selectedTime = Carbon::createFromFormat('g:i A', $request->time_available);
                    $selectedDateTime = Carbon::now()->setTime($selectedTime->hour, $selectedTime->minute);

                    if ($selectedDateTime->greaterThan($endDateTime)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Coupon is not valid for the selected time. Valid until ' . $endDateTime->format('Y-m-d H:i:s')
                        ]);
                    }
                }

                // إذا كانت جميع الشروط متوافقة، قم بتسجيل الاستخدام
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
                    'message' => 'Coupon applied successfully.'
                ]);
            } catch (\Exception $e) {
                Log::error('Coupon application error: ' . $e->getMessage());

                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while processing your request.',
                ]);
            }
        }


//        public function checkCoupon(Request $request)
//        {
//            $request->validate([
//                'coupon_code' => 'required|string',
//                'category_id' => 'nullable|integer',
//                'package_id' => 'nullable|integer',
//            ]);
//
//
//            $coupon = Coupon::where('code', $request->coupon_code)
//                ->where('is_active', true)
//                ->where(function ($query) use ($request) {
//                    $query->whereHas('categories', function ($query) use ($request) {
//                        $query->where('category_id', $request->category_id);
//                    })->orWhereHas('packages', function ($query) use ($request) {
//                        $query->where('package_id', $request->package_id);
//                    });
//                })
//                ->first();
//
//            if (!$coupon) {
//                return response()->json([
//                    'success' => false,
//                    'message' => 'Invalid or expired coupon or not applicable.'
//                ]);
//            }
//
//            // Optionally check usage limit or user-specific restrictions
//            if ($coupon->usage_limit && $coupon->usages()->count() >= $coupon->usage_limit) {
//                return response()->json([
//                    'success' => false,
//                    'message' => 'Coupon usage limit exceeded.'
//                ]);
//            }
//
//            // Record coupon usage
//            if (JWTAuth::getToken()) {
//                $user = JWTAuth::parseToken()->authenticate();
//                CouponUsage::create([
//                    'coupon_id' => $coupon->id,
//                    'user_id' => $user->id
//                ]);
//            }
//
//
//            return response()->json([
//                'success' => true,
//                'discount_value' => $coupon->value,
//                'discount_type' => $coupon->discount_type,
//                'message' => 'Coupon applied successfully.'
//            ]);
//        }

//        public function index(Request $request)
//        {
//            // استرجاع بيانات الخدمة من الجلسة
//            $serviceData = $request->session()->get('serviceData', [
//                'serviceId' => null,
//                'serviceName' => null,
//                'serviceType' => null
//            ]);
//
//            // تحويل البيانات إلى كائن
//            $Data = new stdClass();
//            $Data->serviceId = $serviceData['serviceId'];
//            $Data->serviceName = $serviceData['serviceName'];
//            $Data->serviceType = $serviceData['serviceType'];
//
//            // التحقق مما إذا كانت الخدمة من نوع "package"
//            if ($Data->serviceType == 'package') {
//                $package = Packages::with(['prices', 'employees', 'availability'])
//                    ->firstOrFail();
//                $Data->package = $package;
//            }
//
//            // عرض العرض مع البيانات المطلوبة
//            return view('front.cart.index', ['Data' => $Data]);
//        }
    }
