<?php
    //
    //    namespace App\Http\Controllers;
    //
    //    use App\Models\categories;
    //    use App\Models\categories_prices;
    //    use App\Models\Coupon;
    //    use App\Models\Order;
    //    use App\Models\OrderSession;
    //    use App\Models\Packages;
    //    use App\Models\packages_prices;
    //    use App\Services\MyFatoorahService;
    //    use Carbon\Carbon;
    //    use Illuminate\Http\Request;
    //    use Illuminate\Support\Facades\DB;
    //    use Illuminate\Support\Facades\Log;
    //    use MyFatoorah\Library\API\Payment\MyFatoorahPayment;
    //    use Tymon\JWTAuth\Facades\JWTAuth;
    //    use MyFatoorah\Library\API\Payment\MyFatoorahPaymentEmbedded;
    //    use MyFatoorah\Library\API\Payment\MyFatoorahPaymentStatus;
    //
    //    class BookingNowController extends Controller
    //    {
    //        protected $myFatoorahService;
    //        public $mfConfig = [];
    //
    //        public function __construct(MyFatoorahService $myFatoorahService)
    //        {
    //            $this->myFatoorahService = $myFatoorahService;
    //            $this->mfConfig = [
    //                'apiKey' => config('myfatoorah.api_key'),
    //                'isTest' => config('myfatoorah.test_mode'),
    //                'countryCode' => config('myfatoorah.country_iso'),
    //            ];
    //        }
    //
    //        public function storeOrder(Request $request)
    //        {
    //            $total_price = 0;
    //
    //            // حساب السعر بناءً على الفئة أو الباقة
    //            if ($request->has('categories')) {
    //                $category_price = categories_prices::where('category_id', $request->categories)->first();
    //                if ($category_price) {
    //                    $total_price = $category_price->at_home;
    //                }
    //            } elseif ($request->has('packageId')) {
    //                $package_price = packages_prices::where('package_id', $request->packageId)->first();
    //                if ($package_price) {
    //                    $total_price = $package_price->at_home;
    //                }
    //            }
    //
    //            // التحقق من الكوبون وتطبيق الخصم إذا كان موجودًا
    //            if ($request->has('coupon_code')) {
    //                $coupon = Coupon::where('code', $request->coupon_code)
    //                    ->where('is_active', true)
    //                    ->where(function ($query) use ($request) {
    //                        $query->whereHas('categories', function ($query) use ($request) {
    //                            $query->where('category_id', $request->category_id);
    //                        })->orWhereHas('packages', function ($query) use ($request) {
    //                            $query->where('package_id', $request->packageId);
    //                        });
    //                    })
    //                    ->first();
    //
    //                if ($coupon) {
    //                    if ($coupon->discount_type === 'fixed') {
    //                        $total_price -= $coupon->value;
    //                    } else if ($coupon->discount_type === 'percentage') {
    //                        $total_price -= ($total_price * $coupon->value / 100);
    //                    }
    //                }
    //            }
    //
    //            $packageId = $request->packageId;
    //            $categoryId = $request->input('category_id');
    //            $employeeId = $request->input('employeeAvailable');
    //            $totalPrice = $total_price;
    //            $paymentGateway = 'MyFatoorah';
    //            $location = json_encode([
    //                'latitude' => $request->latitude,
    //                'longitude' => $request->longitude
    //            ]);
    //            $notes = $request->input('notes');
    //
    //            // حساب عدد الجلسات بناءً على الباقة أو الفئة
    //            if ($request->has('packageId')) {
    //                $sessions_count = Packages::findOrFail($request->packageId)->sessions_count;
    //            } elseif ($request->has('categories')) {
    //                $sessions_count = categories::findOrFail($request->categories)->sessions_count;
    //            }
    //            $sessionsCount = $sessions_count;
    //
    //            try {
    //                // بدء عملية المعاملة
    //                DB::beginTransaction();
    //
    //                $order = new \App\Models\Order();
    //                if (JWTAuth::getToken()) {
    //                    $user = JWTAuth::parseToken()->authenticate();
    //                    $order->order_code = Order::generateCustomId($user->code);
    //                    $order->user_id = $user->id;
    //                }
    //
    //                // حفظ البيانات في الطلب لكن الفاتورة لم تدفع بعد
    //                $order->package_id = $packageId;
    //                $order->category_id = $categoryId;
    //                $order->employee_id = $employeeId;
    //                $order->total_price = $totalPrice;
    //                $order->payment_gateway = $paymentGateway;
    //                $order->location = $location;
    //                $order->notes = $notes;
    //                $order->reservation_status = 'at_home';
    //                $order->sessions_count = $sessionsCount;
    //                $order->is_paid = false; // الفاتورة لم تدفع بعد
    //                $order->save();
    //
    //                // بعد حفظ الطلب، نحصل على قيم orderId و invoiceCode و productName
    //                $orderId = $order->id;
    //                $productName = $order->package->getTranslation('name', 'ar');
    //                $invoiceCode = $order->order_code;
    //
    //
    //                // استدعاء MyFatoorah لإنشاء الفاتورة
    //                $invoiceURL = $this->myFatoorahService->createInvoice(
    //                    $orderId,
    //                    $productName,
    //                    $invoiceCode,
    //                    0,
    //                    null,
    //                    $totalPrice // تمرير السعر النهائي
    //                );
    //
    //                if ($invoiceURL) {
    //                    $order->invoice_url = $invoiceURL;
    //                    $order->save();
    //                    for ($i = 0; $i < $order->sessions_count; $i++) {
    //                        $session = new OrderSession();
    //                        $session->order_id = $order->id;
    //
    //                        if ($i === 0) {
    //                            try {
    //                                $convertedDate = $request->input('formattedDate'); // التاريخ بتنسيق 'Y-m-d'
    //                                $timeAvailable = $request->input('timeAvailable'); // الوقت بتنسيق 'g:i A'
    //
    //                                $convertedDate = (string)$convertedDate;
    //                                $timeAvailable = (string)$timeAvailable;
    //
    //                                $dateTimeString = $convertedDate . ' ' . $timeAvailable;
    //
    //                                $sessionDateTime = Carbon::createFromFormat('Y-m-d g:i A', $dateTimeString)->format('Y-m-d H:i:s');
    //                                $session->session_date = $sessionDateTime;
    //                            } catch (\Exception $e) {
    //                                Log::error('Error while storing order: ' . $e->getMessage());
    //                                return response()->json(['error' => 'حدث خطأ أثناء معالجة الطلب.'], 500);
    //                            }
    //                            $session->status = 'pending'; // Set status for the first session
    //                        } else {
    //                            // For subsequent sessions, set session_date to null and status to 'empty'
    //                            $session->session_date = null;
    //                            $session->status = 'pending';
    //                        }
    //                        Log::info('session: ' . $session);
    //                        $session->save();
    //                    }
    //
    //                    DB::commit();
    //
    //                    // إعادة رابط الفاتورة
    //                    return redirect()->away($invoiceURL);
    //                } else {
    //                    throw new \Exception('فشل في معالجة الدفع.');
    //                }
    //
    //            } catch (\Exception $e) {
    //                // إلغاء المعاملة في حالة حدوث خطأ
    //                DB::rollBack();
    //                return response()->json(['error' => 'حدث خطأ أثناء معالجة الطلب: ' . $e->getMessage()], 500);
    //            }
    //        }
    //
    //
    //        public function paymentCallback(Request $request)
    //        {
    //            Log::info('Payment Callback Data:', $request->all());
    //
    //            try {
    //                // الحصول على الـ paymentId من الطلب
    //                $paymentId = $request->input('paymentId');
    //
    //                // التحقق من حالة الدفع من MyFatoorah باستخدام paymentId
    //                $mfObj = new MyFatoorahPaymentStatus($this->mfConfig);
    //                $paymentData = $mfObj->getPaymentStatus($paymentId, 'PaymentId');
    //
    //                $invoiceStatus = $paymentData->InvoiceStatus;
    //                $transactionStatus = $paymentData->InvoiceTransactions[0]->TransactionStatus;
    //
    //                // إذا كانت الفاتورة مدفوعة أو المعاملة ناجحة
    //                if ($invoiceStatus === 'Paid' && $transactionStatus === 'Succss') {
    //                    try {
    //                        DB::beginTransaction();
    //
    //                        // الحصول على orderId من CustomerReference
    //                        $orderId = $paymentData->CustomerReference;
    //                        $order = \App\Models\Order::findOrFail($orderId);
    //
    //                        // تحديث حالة الطلب إلى مدفوع
    //                        $order->is_paid = true;
    //                        $order->save();
    //
    //                        DB::commit();
    //
    //                        // نجاح العملية
    //                        return redirect()->route('order.success')->with('order', $order);
    //
    //                    } catch (\Exception $e) {
    //                        DB::rollBack();
    //                        Log::error('Error updating order: ' . $e->getMessage());
    //                        return redirect()->route('order.failed')->with('error', 'حدث خطأ أثناء معالجة الطلب.');
    //                    }
    //                } else {
    //                    // إذا كانت حالة الفاتورة فاشلة أو حالة المعاملة ليست ناجحة، نقوم بحذف الطلب
    //                    try {
    //                        DB::beginTransaction();
    //
    //                        // الحصول على orderId من CustomerReference
    //                        $orderId = $paymentData->CustomerReference;
    //                        $order = \App\Models\Order::findOrFail($orderId);
    //
    //                        // حذف الطلب
    //                        $order->delete();
    //
    //                        DB::commit();
    //                        return redirect()->route('order.failed')->with('error', 'عملية الدفع فشلت وتم حذف الطلب.');
    //                    } catch (\Exception $e) {
    //                        DB::rollBack();
    //                        Log::error('Error deleting order: ' . $e->getMessage());
    //                        return redirect()->route('order.failed')->with('error', 'حدث خطأ أثناء معالجة الطلب: ' . $e->getMessage());
    //                    }
    //                }
    //            } catch (\Exception $ex) {
    //                Log::error('Error processing payment callback: ' . $ex->getMessage());
    //                return redirect()->route('order.failed')->with('error', 'حدث خطأ أثناء معالجة الطلب: ' . $e->getMessage());
    //            }
    //        }
    //
    //
    //        public function store(Request $request)
    //        {
    //            $convertedDate = $request->input('formattedDate'); // التاريخ بتنسيق 'Y-m-d'
    //            $timeAvailable = $request->input('timeAvailable'); // الوقت بتنسيق 'g:i A'
    //
    //
    //            $convertedDate = (string)$convertedDate;
    //            $timeAvailable = (string)$timeAvailable;
    //
    //            $dateTimeString = $convertedDate . ' ' . $timeAvailable;
    //
    //            $sessionDateTime = Carbon::createFromFormat('Y-m-d g:i A', $dateTimeString)->format('Y-m-d H:i:s');
    //            // return $sessionDateTime;
    //            if (JWTAuth::getToken()) {
    //                $user = JWTAuth::parseToken()->authenticate();
    //
    //                $order_code = Order::generateCustomId($user->code);
    //            }
    //
    //            if ($request->has('categories')) {
    //                // Fetch category price from the database
    //                $category_price = categories_prices::where('category_id', $request->categories)->first();
    //                if ($category_price) {
    //                    // Set price based on reservation status
    //                    $total_price = $category_price->at_home;
    //                }
    //            } elseif ($request->has('packageId')) {
    //                // Fetch package price from the database
    //                $package_price = packages_prices::where('package_id', $request->packageId)->first();
    //                if ($package_price) {
    //                    // Set price based on reservation status
    //                    $total_price = $package_price->at_home;
    //                }
    //            }
    //            if ($request->has('coupon_code')) {
    //                $coupon = Coupon::where('code', $request->coupon_code)
    //                    ->where('is_active', true)
    //                    ->where(function ($query) use ($request) {
    //                        $query->whereHas('categories', function ($query) use ($request) {
    //                            $query->where('category_id', $request->category_id);
    //                        })->orWhereHas('packages', function ($query) use ($request) {
    //                            $query->where('package_id', $request->packageId);
    //                        });
    //                    })
    //                    ->first();
    //                if ($coupon->discount_type === 'fixed') {
    //
    //                    $total_price -= $coupon->value;
    //                } else if ($coupon->discount_type === 'percentage') {
    //
    //                    $total_price -= ($total_price * $coupon->value / 100);
    //                }
    //            }
    //
    //            return $request->all();
    //
    //
    //        }
    //
    //
    //    }
    //
