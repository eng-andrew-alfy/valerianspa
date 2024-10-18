<?php

    namespace App\Http\Controllers;


    use App\Models\categories;
    use App\Models\OrderSession;
    use App\Models\Packages;
    use App\Models\Services;
    use Carbon\Carbon;
    use Illuminate\Database\Eloquent\ModelNotFoundException;
    use Illuminate\Support\Facades\Log;
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Session;

    class FrontController extends Controller
    {
        public function fristPage()
        {
            return view('front.frist_page');
        }

        public function index()
        {
            if (Session::has('service_type')) {
                Session::forget('service_type');
            }
            $type = request()->route('type');
            Session::put('service_type', $type);

            return view('front.index', compact('type'));
        }

        public function about()
        {
            return view('front.pages.about');
        }

        public function terms()
        {
            return view('front.pages.terms');
        }


//        public function allServices()
//        {
//            $services = Services::get();
//            dd($services);
//        }

        public function packages($type = null)
        {
            $availability = $type == 'SPA' ? 'in_spa' : 'in_home';

            $packages = Packages::whereHas('availability', function ($query) use ($availability) {
                $query->where($availability, 1);
            })->with([
                'service',
                'prices',
                'employees',
                'availability'
            ])->get();


            return view('front.packages.index', compact('packages', 'type'));
        }


        public function getPackage($type = null, $namePart)
        {
            $package = Packages::whereRaw('LOWER(name) LIKE ?', ['%' . strtolower(str_replace('_', ' ', $namePart)) . '%'])->firstOrFail();

            return view('front.packages.show', compact('package', 'type'));
        }

        public function services($type = null, $namePart)
        {

            $service = Services::whereRaw('LOWER(name) LIKE ?', ['%' . strtolower(str_replace('_', ' ', $namePart)) . '%'])->firstOrFail();

            $serviceId = $service->id;
            $availability = $type == 'SPA' ? 'in_spa' : 'in_home';

            $categories = categories::with(['service', 'prices', 'employees', 'availability'])
                ->whereHas('service', function ($query) use ($serviceId) {
                    $query->where('id', $serviceId);
                })
                ->whereHas('availability', function ($query) use ($availability) {
                    $query->where($availability, 1);
                })
                ->get();


            //dd($categories);
            return view('front.services.index', compact('categories', 'service', 'type'));
        }

        public function myOrders()
        {
            $orders = auth()->user()->orders->where('is_gift', false);
            return view('front.user.orders', compact('orders'));
        }

        public function orderSessions($order)
        {
            $orders = auth()->user()->orders()->where('order_code', $order)->firstOrFail();
            $ordersWithSessions = auth()->user()->orders()->where('order_code', $order)->with('sessions')->get();
            return view('front.user.order-sessions', compact('ordersWithSessions', 'orders'));
        }

        public function showUpdateSession(OrderSession $session)
        {

            return view('front.user.update-session', compact('session'));

        }

        public function createOrderSessions(Request $request)
        {
            $request->validate([
                'session_id' => 'required|integer|exists:order_sessions,id',
                'date' => 'required|date_format:Y-m-d',
                'time' => 'required|string',
            ]);

            $convertedDate = $request->input('date');
            $timeAvailable = $request->input('time');
            $dateTimeString = $convertedDate . ' ' . $timeAvailable;

            try {
                $sessionDateTime = Carbon::createFromFormat('Y-m-d g:i A', $dateTimeString)->format('Y-m-d H:i:s');
                $session = OrderSession::findOrFail($request->session_id);

                // Get the current date and time
                $currentTime = now();

                // Check the previous session based on the current session's order_id
                $previousSession = OrderSession::where('order_id', $session->order_id)
                    ->where('id', '<', $session->id)
                    ->orderBy('session_date', 'desc')
                    ->first();

                if ($previousSession) {
                    if ($previousSession->status == 'pending') {
                        $previousSessionDate = Carbon::parse($previousSession->session_date);
                        $timeDifference = $previousSessionDate->diffInDays($currentTime);

                        if ($timeDifference <= 2) {
                            // If the time difference is 2 days or less
                            return response()->json([
                                'success' => false,
                                'message' => 'لم تتمكن من تحديد موعدك الجديد إلا لما تنتهي من موعد الجلسة القادمة.',
                            ], 400);
                        } else {
                            return response()->json([
                                'success' => false,
                                'message' => 'نعتذر منك لم تتمكن من تحديد الموعد برجاء التواصل مع خدمة العملاء لحلها.',
                            ], 400);
                        }
                    }
                }

                // Check if the session's remaining time is less than 2 days
                $sessionDate = Carbon::parse($session->session_date);
                $timeUntilSession = $currentTime->diffInDays($sessionDate);

                if ($timeUntilSession <= 2) {
                    return response()->json([
                        'success' => false,
                        'message' => 'لم تتمكن من تحديث موعد الجلسة، يرجى التواصل مع خدمة العملاء.',
                    ], 400);
                }

                // Update the session date and save
                $session->session_date = $sessionDateTime;
                $session->save();

                return response()->json([
                    'success' => true,
                    'message' => 'تم تحديث الجلسة بنجاح!',
                ]);
            } catch (ModelNotFoundException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'الجلسة المطلوبة غير موجودة.',
                ], 404);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء تحديث الجلسة. يرجى المحاولة لاحقاً.',
                    'error' => $e->getMessage()
                ], 500);
            }
        }


        public function cancelOrderSessions(Request $request)
        {
            // 🎯 Validate the incoming request
            $request->validate([
                'session_id' => 'required|integer|exists:order_sessions,id',
                'reason' => 'required|string'
            ]);

            $sessionId = $request->input('session_id');
            $reason = $request->input('reason');

            try {
                // 🔍 Find the requested session
                $session = OrderSession::findOrFail($sessionId);

                // 🕒 Get the current time and the session's scheduled time
                $currentDate = Carbon::now();
                $sessionDate = Carbon::parse($session->session_date);
                // Assuming session_date is the date and time of the session

                // 📅 Calculate the difference in days between now and the session date


                if ($sessionDate->diffInDays($currentDate, false) <= 2) {
                    // ❌ If less than or equal to 2 days, prevent cancellation and inform the user to contact customer support


                    return response()->json([
                        'success' => false,
                        'message' => 'لا يمكنك إلغاء الحجز في هذا الوقت. يرجى التواصل مع خدمة العملاء لإلغاء الحجز.'
                    ], 400);
                }

                // ✅ Proceed with cancellation
                $session->status = 'canceled'; // Or any status that represents cancellation
                $session->notes = $reason; // Store the reason for cancellation (assuming you have a field for this in the database)
                $session->save();

                return response()->json([
                    'success' => true,
                    'message' => 'تم إلغاء الموعد بنجاح!'
                ]);
            } catch (\Exception $e) {
                // ⚠️ Handle any errors that occur during cancellation
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء إلغاء الموعد. يرجى المحاولة لاحقاً.',
                    'error' => $e->getMessage()
                ], 500);
            }
        }


    }
