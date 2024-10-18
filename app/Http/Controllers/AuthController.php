<?php

    namespace App\Http\Controllers;

    use App\Jobs\SendOtpJob;
    use Brian2694\Toastr\Facades\Toastr;
    use Illuminate\Http\Request;
    use App\Models\User;
    use App\Models\OtpCode;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Validator;

    use Illuminate\Validation\Rule;
    use Tymon\JWTAuth\Facades\JWTAuth;
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Session;

    class AuthController extends Controller
    {

        public function login(Request $request)
        {
            return view('front.auth.login');
        }

        public function register(Request $request)
        {
            return view('front.auth.register');
        }

        // إرسال OTP
        public function sendOtp(Request $request)
        {
            // تعريف القواعد
            $rules = [
                'phone' => [
                    'required',
                    'string',
                    'regex:/^01[0-9]{9}$/'
                ],
            ];

            $referer = $request->headers->get('referer');

            if (Str::contains($referer, 'register')) {
                $rules['phone'][] = 'unique:users,phone';
                $rules['name'] = [
                    'required',
                    'string',
                    'max:255',
                    'regex:/^[a-zA-Z\s]+$/u', // Only letters and spaces allowed
                ];
            }

            // التحقق من صحة البيانات
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                // إعادة الأخطاء بالتفصيل
                return response()->json([
                    'error' => 'Validation failed',
                    'messages' => $validator->errors()->all(), // تحويل الأخطاء إلى مصفوفة نصية
                ], 422);
            }

            $validatedData = $validator->validated();
            $validatedData['name'] = isset($validatedData['name']) ? strip_tags($validatedData['name']) : null;
            $validatedData['phone'] = strip_tags($validatedData['phone']);

            try {
                $otp = rand(100000, 999999);

                // البحث عن المستخدم بناءً على الهاتف
                $user = User::where('phone', $validatedData['phone'])->first();

                if (!$user) {
                    if (Str::contains($referer, 'register')) {
                        $user = new User();
                        $user->name = $validatedData['name'];
                        $user->phone = $validatedData['phone'];
                        $user->code = User::generateUniqueCode();
                        $user->save();
                    } else {
                        return response()->json(['message' => 'User not found.'], 404);
                    }
                }

                // تحديث أو إنشاء رمز OTP
                OtpCode::updateOrCreate(
                    ['user_id' => $user->id],
                    ['otp' => $otp, 'expires_at' => now()->addMinutes(5)]
                );

                // تخزين معرف المستخدم في الجلسة
                session(['user_id' => $user->id]);
                SendOtpJob::dispatch($user->phone, $otp); // هنا تم استخدام المتغير العشوائي مباشرة
                return response()->json(['message' => 'OTP sent.']);
            } catch (\Exception $e) {
                Log::error('Error in sendOtp: ' . $e->getMessage());
                return response()->json(['message' => 'حدث خطأ ما، يرجى المحاولة مرة أخرى.'], 500);
            }
        }


        public function verifyOtp(Request $request)
        {
            $otpCode = OtpCode::where('user_id', session('user_id'))
                ->where('otp', $request->otp)
                ->where('expires_at', '>', now())
                ->first();

            if (!$otpCode) {
                return response()->json(['message' => 'Invalid or expired OTP.'], 422);
            }
            $user = User::where('id', session('user_id'))->first();
            $user->update(['phone_verified_at' => now()]);
            $token = JWTAuth::fromUser($user);

            $postData = [
                'latitude' => session('request_data.latitude', null),
                'longitude' => session('request_data.longitude', null),
                'servicesAvailable' => session('request_data.servicesAvailable', null),
                'formattedDate' => session('request_data.formattedDate', null),
                'timeAvailable' => session('request_data.timeAvailable', null),
                'employeeAvailable' => session('request_data.employeeAvailable', null),
                'notes' => session('request_data.notes', null),
                'payment_method' => session('request_data.payment_method', null),
                'name_recipient' => session('request_data.name_recipient', null),
                'phone_recipient' => session('request_data.phone_recipient', null),
            ];

            if (session()->has('request_data.packageId')) {
                $postData['packageId'] = session('request_data.packageId');
            }

            if (session()->has('request_data.categoryId')) {
                $postData['categoryId'] = session('request_data.categoryId');
            }

            if (session('request_data.payment_method') == 'tamara' && session('request_data.latitude') == null && session('request_data.employeeAvailable') == null && session('request_data.formattedDate') == null && session('request_data.name_recipient') != null && session('request_data.phone_recipient') != null) {
                session()->forget('request_data');
                session()->forget('user_id');
                return response()->json([
                    'redirect_url' => route('PaymentGIFT.TAMARA'),
                    'post_data' => $postData,
                    'token' => $token,
                ])->cookie('token', $token, 7200, '/', null, true, true, false, 'Strict');
            } elseif (session('request_data.payment_method') == 'myfatoorah' && session('request_data.latitude') == null && session('request_data.employeeAvailable') == null && session('request_data.formattedDate') == null && session('request_data.name_recipient') != null && session('request_data.phone_recipient') != null) {
                session()->forget('request_data');
                session()->forget('user_id');
                Log::info('ANDREW_GIFTredirect_url');
                return response()->json([
                    'redirect_url' => route('PaymentGIFT.MyFatoorah'),
                    'post_data' => $postData,
                    'token' => $token,
                ])->cookie('token', $token, 7200, '/', null, true, true, false, 'Strict');
            } elseif (session('request_data.payment_method') == 'myfatoorah' && session('request_data.name_recipient') == null && session('request_data.phone_recipient') == null) {
                Log::info('ANDREWredirect_url');

                session()->forget('request_data');
                session()->forget('user_id');
                return response()->json([
                    'redirect_url' => route('PaymentMyFatoorah'),
                    'post_data' => $postData,
                    'token' => $token,
                ])->cookie('token', $token, 7200, '/', null, true, true, false, 'Strict');
            } elseif (session('request_data.payment_method') == 'tamara') {
                session()->forget('request_data');
                session()->forget('user_id');
                return response()->json([
                    'redirect_url' => route('PaymentTAMARA'),
                    'post_data' => $postData,
                    'token' => $token,
                ])->cookie('token', $token, 7200, '/', null, true, true, false, 'Strict');
            } else {
                return response()->json([
                    'redirect_url' => route('index', ['type' => Session::has('service_type') ? Session::get('service_type') : request()->route('type')]),

                    'token' => $token,
                ])->cookie('token', $token, 7200, '/', null, true, true, false, 'Strict');
            }


            // إرجاع الرد النهائي
            return $response;
        }

        public function logout()
        {
            auth()->logout();
            Toastr::error(__('toastr.logout_message'), __('toastr.title'));

            return redirect()->route('index', ['type' => Session::has('service_type') ? Session::get('service_type') : request()->route('type')])
                ->cookie('token', '', 1, null, null, true, true, false, 'Strict');
        }

        public function me()
        {
            $user = null;
            if (JWTAuth::getToken()) {
                try {
                    $user = JWTAuth::parseToken()->authenticate();
                } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
                    $user = null;
                }
            }
            return view('front.user.profile', compact('user'));
        }

        public function refresh()
        {
            $newToken = JWTAuth::refresh();
            return response()->json(['success' => true])
                ->cookie('token', $newToken, 7200, '/', null, true, true, false, 'Strict');
        }

        protected function respondWithToken($token)
        {
            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ])->cookie('token', $token, 7200, null, null, true, true, false, 'Strict');
        }
    }
