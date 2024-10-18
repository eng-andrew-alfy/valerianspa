<?php

    namespace App\Http\Controllers\Dashboard;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\Dashboard\StoreOrderDashboardRequest;
    use App\Models\ArchivedOrder;
    use App\Models\categories;
    use App\Models\categories_prices;
    use App\Models\Category_Availability;
    use App\Models\Order;
    use App\Models\OrderSession;
    use App\Models\OtpCode;
    use App\Models\Package_Availability;
    use App\Models\Packages;
    use App\Models\packages_prices;
    use App\Models\User;
    use Brian2694\Toastr\Facades\Toastr;
    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Http\JsonResponse;


    class OrderController extends Controller
    {
        /**
         * Display a listing of the resource.
         */
        public function index()
        {
            $orders = Order::with(['user', 'employee', 'admin'])
                ->where('is_gift', 0)
                ->whereIn('id', function ($query) {
                    $query->selectRaw('MAX(id)')
                        ->from('orders')
                        ->groupBy('user_id');
                })
                ->get();
            return view('Dashboard.orders.index', compact('orders'));
        }

        /**
         * Show the form for creating a new resource.
         */
        public function generateInvoiceCode(Request $request)
        {
            $clientCode = $request->input('client_code');

            // تحقق من أن كود العميل موجود أو جديد
            if ($clientCode) {
                // في حال كود العميل موجود، يمكنك استخدامه كما هو
                $invoiceCode = Order::generateCustomId($clientCode);
            } else {
                // في حال عدم وجود كود عميل، قم بتوليد كود عميل جديد
                $invoiceCode = Order::generateCustomId();
            }

            return response()->json(['invoice_code' => $invoiceCode]);
        }


        public function create()
        {
            // $oder_code = Order::generateCustomId($clint_code = null);

            $categories = categories::where('is_active', true)->get();
            $packages = Packages::where('is_active', true)->get();
            $user_code = User::generateUniqueCode();
            $enumValues = DB::select("SHOW COLUMNS FROM orders LIKE 'neighborhoods'")[0]->Type;

            preg_match("/^enum\('(.*)'\)$/", $enumValues, $matches);
            $neighborhoods = explode("','", $matches[1]);


            return view('Dashboard.orders.create', compact('categories', 'packages', 'user_code', 'neighborhoods'));
        }

        /**
         * Store a newly created resource in storage.
         */
        public function store(Request $request)
        {

            try {
                // Start the transaction
                DB::beginTransaction();

                //$validatedData = $request->validated();

                // Determine reservation status from the request
                $reservation_status = $request->input('reservation_status');

                // Check if categories or packages are selected
                if ($request->has('categories')) {
                    // Fetch category price from the database
                    $category_price = categories_prices::where('category_id', $request->categories)->first();
                    if ($category_price) {
                        // Set price based on reservation status
                        $total_price = $reservation_status === 'at_home' ? $category_price->at_home : ($reservation_status === 'at_spa' ? $category_price->at_spa : 0);
                    }
                } elseif ($request->has('packages')) {
                    // Fetch package price from the database
                    $package_price = packages_prices::where('package_id', $request->packages)->first();
                    if ($package_price) {
                        // Set price based on reservation status
                        $total_price = $reservation_status === 'at_home' ? $package_price->at_home : ($reservation_status === 'at_spa' ? $package_price->at_spa : 0);
                    }
                }
                if ($total_price === 0) {
                    throw new \Exception('Total price could not be determined.');
                }

                // Save order data
                $order = new Order();

                $order->order_code = $request->order_code;

                $order->user_id = $this->getUserId($request);


                $order->reservation_status = $reservation_status;
                $order->neighborhoods = $request->neighborhoods;
                $order->package_id = $request->packages;
                $order->category_id = $request->categories;
                $order->employee_id = $request->employee_available;
                $order->total_price = $total_price;
                $order->created_by = auth('admin')->id();
                $order->payment_gateway = $request->payment_type == 'cash' ? 'Cash' : 'Bank Transfer';
                $order->location = json_encode([
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude
                ]);
                $order->notes = $request->notes;
                $order->qr_code = null; // Generate QR code as needed

                // Determine sessions count
                if ($request->has('packages')) {
                    $sessions_count = Packages::findOrFail($request->packages)->sessions_count;
                } elseif ($request->has('categories')) {
                    $sessions_count = categories::findOrFail($request->categories)->sessions_count;
                }
                $order->sessions_count = $sessions_count;

                $order->is_paid = $request->payment_status == 'paid';
                $order->save();

                // Create the sessions
                for ($i = 0; $i < $sessions_count; $i++) {
                    $session = new OrderSession();
                    $session->order_id = $order->id;

                    if ($i === 0) {
                        try {
                            $dateTimeString = $request->date . ' ' . $request->time_available;
                            // تسجيل التفاصيل لتحديد المشكلة
                            Log::info('DateTime String: ' . $dateTimeString);

                            // Parse date and time using Carbon
                            $sessionDateTime = Carbon::createFromFormat('m/d/Y g:i A', $dateTimeString)->format('Y-m-d H:i:s');
                            $session->session_date = $sessionDateTime;
                        } catch (\Exception $e) {
                            Log::error('Error while storing order: ' . $e->getMessage());
                            return response()->json(['error' => 'حدث خطأ أثناء معالجة الطلب.'], 500);
                        }
                        $session->status = 'pending'; // Set status for the first session
                    } else {
                        // For subsequent sessions, set session_date to null and status to 'empty'
                        $session->session_date = null;
                        $session->status = 'pending';
                    }
                    Log::info('session: ' . $session);
                    $session->save();
                }


                // Commit the transaction
                DB::commit();

                Toastr::success(' 😀  لقد تم أضافة الطلب بنجاح', 'عملية ناجحة');

                return redirect()->route('admin.orders.index');
            } catch (\Exception $e) {
                // Rollback the transaction
                DB::rollBack();

                Toastr::error('حدث خطأ أثناء إضافة الطلب: ' . $e->getMessage(), 'عملية غير ناجحة');
                //Log::error('Error while storing order: ' . $e->getMessage());

                return redirect()->back()->withInput();
            }

        }

        /**
         * Display the specified resource.
         */
        public function show(string $id)
        {


            $user = User::with(['orders.sessions'])->findOrFail($id);

            $sessions = $user->orders->flatMap(function ($order) {
                return $order->sessions;
            });

            // Calculate the number of sessions
            $totalSessions = $user->orders->sum(function ($order) {
                return $order->sessions->count();
            });
            $completedSessions = $user->orders->flatMap(function ($order) {
                return $order->sessions->filter(function ($session) {
                    return $session->status === 'completed';
                });
            })->count();

            $canceledSessions = $user->orders->flatMap(function ($order) {
                return $order->sessions->filter(function ($session) {
                    return $session->status === 'canceled';
                });
            })->count();

            $pendingSessions = $user->orders->flatMap(function ($order) {
                return $order->sessions->filter(function ($session) {
                    return $session->status === 'pending';
                });
            })->count();

            $totalSessions = $user->orders->flatMap(function ($order) {
                return $order->sessions;
            })->count();

            $remainingSessions = $totalSessions - $completedSessions - $canceledSessions;


            return view('Dashboard.orders.show', compact('user', 'sessions', 'totalSessions', 'completedSessions', 'canceledSessions', 'pendingSessions', 'remainingSessions'));


        }

        /**
         * Show the form for editing the specified resource.
         */
        public function edit($code)
        {
            //$orders = Order::where('order_code', $code)->with(['user', 'employee', 'admin'])
//                ->whereIn('id', function ($query) {
//                    $query->selectRaw('MAX(id)')
//                        ->from('orders')
//                        ->groupBy('user_id');
//                })
//                ->firstOrFail();
            $order = Order::where('order_code', $code)->firstOrFail();
            $user = User::with(['orders.sessions'])->findOrFail($order->user_id);
            $categories = categories::where('is_active', true)->get();
            $packages = Packages::where('is_active', true)->get();
            return view('Dashboard.orders.edit', compact('order', 'user', 'categories', 'packages'));
        }

        /**
         * Update the specified resource in storage.
         */
        public function update(Request $request, string $id)
        {
            //
        }

        /**
         * Remove the specified resource from storage.
         */
        public function destroy($id)
        {
            Log::info('Entering destroy method for order ID: ' . $id);

            try {
                $order = Order::findOrFail($id);
                $order->delete();

                // استجابة ناجحة
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                // تسجيل الخطأ في اللوج
                Log::error('Error in delete Order: ' . $e->getMessage(), [
                    'stack_trace' => $e->getTraceAsString(),
                    'error_code' => $e->getCode(),
                    'order_id' => isset($order) ? $order->id : null  // التحقق مما إذا كانت متاحة
                ]);

                // التراجع عن العملية في حال حدوث خطأ
                DB::rollBack();

                // إعداد استجابة الخطأ
                $response = [
                    'success' => false,
                    'message' => $e->getMessage()
                ];

                // سجل البيانات قبل الإرجاع
                Log::info('Response data: ' . json_encode($response));

                // استجابة بالفشل
                return response()->json($response, 500);
            }
        }


        protected function getUserId(Request $request)
        {
            try {
                if ($request->client_status == 'existing') {
                    // 🛠️ Validate the existing client code
                    $validated = $request->validate([
                        'existing_client_code' => 'required|string|exists:users,code',
                    ]);

                    // 🔍 Fetch the existing client
                    $user = User::where('code', $validated['existing_client_code'])->first();

                    // ✅ Return the user's ID if found
                    return $user ? $user->id : null;
                } else {
                    // 🛠️ Validate the new client data
                    $validated = $request->validate([
                        'client_name' => 'required|string|max:255',
                        'client_phone' => [
                            'required',
                            'string',
                            'unique:users,phone',
                            'regex:/^01[0-9]{9}$/'
                        ],
                    ]);

                    // 🆕 Create a new client
                    $user = new User();
                    $user->name = $validated['client_name'];
                    $user->phone = $validated['client_phone'];
                    $user->code = User::generateUniqueCode();
                    $user->save();

                    // 🔢 Generate OTP
                    $otp = rand(1000, 9999);
                    OtpCode::create([
                        'user_id' => $user->id,
                        'otp' => $otp,
                        'expires_at' => now()->addMinutes(10), // ⏳ Set expiration time for OTP
                    ]);


                    // ✅ Return the newly created user's ID
                    return $user->id;
                }
            } catch (\Illuminate\Validation\ValidationException $e) {
                // ⚠️ Handle validation errors
                return response()->json([
                    'error' => 'Validation failed',
                    'messages' => $e->errors(),
                ], 422);
            } catch (\Exception $e) {
                // 🔴 Handle unexpected errors
                return response()->json([
                    'error' => 'An error occurred',
                    'message' => $e->getMessage(),
                ], 500);
            }
        }

        public function checkClient(Request $request)
        {
            $request->validate([
                'input' => 'required'
            ]);

            $client = User::where('code', $request->input)
                ->orWhere('phone', $request->input)
                ->first();

            if ($client) {
                return response()->json([
                    'message' => 'تم العثور على العميل بنجاح',
                    'client_name' => $client->name,
                    'client_code' => $client->code,
                ]);
            } else {
                return response()->json([
                    'message' => ' هذا العميل غير موجود برجاء التأكد من البيانات او قم بإضافة العميل',
                ]);
            }
        }

        public function categoryAvailability($id)
        {
            $availability = Category_Availability::where('category_id', $id)->firstOrFail();
            return response()->json($availability);
        }

        public function packageAvailability($id)
        {
            $availability = Package_Availability::where('package_id', $id)->firstOrFail();
            return response()->json($availability);
        }

        public function archivedOrder()
        {

            $ArchivedOrders = ArchivedOrder::all();

            // تنظيم البيانات للحصول على أحدث طلب لكل عميل
            $archivedOrders = $ArchivedOrders->groupBy(function ($order) {
                return $order->data['order']['user_id'];
            })->map(function ($orders) {
                return $orders->sortByDesc(function ($order) {
                    return $order->data['order']['created_at'];
                })->first();
            });
            return view('Dashboard.archived_orders.index', compact('archivedOrders'));
        }

        public function showArchivedOrder(string $id)
        {

            $user = User::findOrFail($id);

            // استرجاع جميع الطلبات المؤرشفة الخاصة بالمستخدم
            $archivedOrders = ArchivedOrder::where('data->order->user_id', $id)->get();

            if ($archivedOrders->isEmpty()) {
                return view('Dashboard.orders.show', [
                    'user' => null,
                    'sessions' => [],
                    'totalSessions' => 0,
                    'completedSessions' => 0,
                    'remainingSessions' => 0
                ]);
            }
            // استخراج الجلسات من جميع الطلبات المؤرشفة
            $sessions = $archivedOrders->flatMap(function ($order) {
                return $order->data['sessions'] ?? [];
            });
            // حساب عدد الجلسات
            $totalSessions = $sessions->count();
            $completedSessions = $sessions->filter(function ($session) {
                return $session['status'] === 'completed';
            });


            // حساب عدد الجلسات لكل حالة
            $completedSessionsCount = $completedSessions->count();


            // إرجاع العرض مع البيانات المؤرشفة
            return view('Dashboard.archived_orders.show', [
                'user' => $archivedOrders->first()->data['order'] ?? null, // يمكن استخدام بيانات أول طلب كمثال للمستخدم
                'archivedOrders' => $archivedOrders, // إرسال جميع الطلبات المؤرشفة إلى العرض
                'sessions' => $sessions,
                'totalSessions' => $totalSessions,
                'completedSessions' => $completedSessionsCount,
                'user' => $user
            ]);

        }

        public function updatePaymentStatus(Request $request)
        {
            // Validate the request
            $validated = $request->validate([
                'order_id' => 'required|exists:orders,id',
                'payment_gateway' => 'required_if:is_paid,true|string'
            ]);

            // Find the order
            $order = Order::findOrFail($validated['order_id']);

            // Update the payment status to paid
            $order->is_paid = true;
            $order->payment_gateway = $validated['payment_gateway']; // Set the payment gateway
            $order->save();

            // Return a JSON response
            return response()->json([
                'success' => true,
                'message' => 'حالة الدفع تم تحديثها بنجاح'
            ]);
        }

        public function handleSessionAction(Request $request)
        {
            $sessionOrderId = $request->input('sessionOrderId');
            $action = $request->input('action');

            // تحقق من وجود معرف الطلب ونوع الإجراء
            if (empty($sessionOrderId) || empty($action)) {
                return response()->json(['error' => 'Missing sessionOrderId or action'], 400);
            }

            $session = OrderSession::find($sessionOrderId);
            $message = '';
            $notes = '';

            switch ($action) {
                case 'update':
                    // منطق تحديث الجلسة
                    // هنا يمكن أن تضيف منطق التحديث الفعلي
                    $message = 'Session updated successfully';
                    break;
                case 'end':
                    if ($session) {
                        $session->status = 'completed';
                        if ($request->has('reason')) {
                            $session->notes = $request->reason;
                        }
                        $session->save();
                    }
                    $notes = $request->reason;
                    $message = 'تم الانتهاء من الجلسة بنجاح';
                    break;
                case 'cancel':
                    if ($session) {
                        $session->status = 'canceled';
                        if ($request->has('reason')) {
                            $session->notes = $request->reason;
                        }
                        $session->save();
                    }
                    $notes = $request->reason;

                    $message = 'تم إلغاء الجلسة بنجاح';
                    break;
                case 'opened':
                    if ($session) {
                        $session->status = 'pending';
                        $session->notes = '';
                        $session->save();
                    }
                    $message = 'تم فتح الجلسة بنجاح';
                    break;
                default:
                    return response()->json(['error' => 'Invalid action'], 400);
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'sessionOrderId' => $sessionOrderId,
                'notes' => $notes,
                'action' => $action
            ]);
        }


        public function update_date(Request $request, OrderSession $session)
        {
            try {
                $sessions = OrderSession::findOrFail($session->id);

                $formattedDate = Carbon::createFromFormat('m/d/Y', $request->date)->format('Y-m-d');
                $formattedTime = Carbon::createFromFormat('h:i A', $request->time_available)->format('H:i:s');
                $formatted = $formattedDate . ' ' . $formattedTime;

                if ($request->change_employee == 'no' && $request->employee_availabl == '' && $request->employeeAvailable2 != '') {
                    $sessions->update([
                        'session_date' => $formatted,
                        'status' => 'pending'
                    ]);

                    Toastr::success('😀 لقد تم تحديث تاريخ الجلسة بنجاح', 'عملية ناجحة');
                } else {
                    $order = Order::findOrFail($session->order_id);

                    $order->employee_id = $request->employee_available;
                    $order->save();

                    $sessions->update([
                        'session_date' => $formatted,
                        'status' => 'pending'
                    ]);

                    Toastr::success('😀 لقد تم تحديث تاريخ الجلسة والموظف بنجاح', 'عملية ناجحة');
                }

                return redirect()->back();
            } catch (\Exception $e) {
                Toastr::error('عذرًا، حدث خطأ أثناء تحديث البيانات. يرجى المحاولة مرة أخرى.', 'خطأ');
                return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            }
        }

        public function unpaidOrder()
        {
            $orders = Order::with(['user', 'employee', 'admin'])->where('is_paid', false)->get();
            return view('Dashboard.orders.unpaid', compact('orders'));
        }

        public function unpaidPaymentUpdate(Request $request)
        {
            $order = Order::find($request->order_id);
            if ($order) {
                $order->payment_gateway = $request->payment_type;
                $order->created_by = auth('admin')->id();
                $order->is_paid = true;
                $order->save();
                return response()->json(['message' => 'تم تحديث نوع الدفع بنجاح!']);
            } else {
                return response()->json(['message' => 'الطلب غير موجود!'], 404);
            }
        }


        public function showSession($code)
        {

            $order = Order::where('order_code', $code)->firstOrFail();
            $user = User::with(['orders.sessions'])->findOrFail($order->user_id);

            $sessions = OrderSession::where('order_id', $order->id)->get();

            // Calculate the number of sessions
            $totalSessions = $user->orders->sum(function ($order) {
                return $order->sessions->count();
            });
            $completedSessions = $user->orders->flatMap(function ($order) {
                return $order->sessions->filter(function ($session) {
                    return $session->status === 'completed';
                });
            })->count();

            $canceledSessions = $user->orders->flatMap(function ($order) {
                return $order->sessions->filter(function ($session) {
                    return $session->status === 'canceled';
                });
            })->count();

            $pendingSessions = $user->orders->flatMap(function ($order) {
                return $order->sessions->filter(function ($session) {
                    return $session->status === 'pending';
                });
            })->count();

            $totalSessions = $user->orders->flatMap(function ($order) {
                return $order->sessions;
            })->count();

            $remainingSessions = $totalSessions - $completedSessions - $canceledSessions;


            return view('Dashboard.orders.show_session', compact('user', 'order', 'sessions', 'totalSessions', 'completedSessions', 'canceledSessions', 'pendingSessions', 'remainingSessions'));

        }
    }
