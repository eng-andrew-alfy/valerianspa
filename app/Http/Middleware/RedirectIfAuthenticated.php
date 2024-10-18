<?php

    namespace App\Http\Middleware;

    use Closure;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Mcamara\LaravelLocalization\LaravelLocalization;
    use Symfony\Component\HttpFoundation\Response;
    use Tymon\JWTAuth\Facades\JWTAuth;

    class RedirectIfAuthenticated
    {
        /**
         * Handle an incoming request.
         *
         * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
         */
        public function handle(Request $request, Closure $next, $guard = null)
        {
            try {
                $token = $request->cookie('token');

                if ($token) {
                    $user = JWTAuth::setToken($token)->toUser();
                    if ($user) {
                        // الحصول على اللغة من الكوكيز أو من طلب المستخدم
                        $locale = $request->cookie('locale') ?: LaravelLocalization::getCurrentLocale();

                        // تأكد من عدم إعادة التوجيه إلى نفس الصفحة المحمية
                        if (!$request->is('login') && !$request->is('register')) {
                            // إعادة التوجيه إلى الصفحة الرئيسية مع اللغة
                            return redirect()->route('index',['type' => Session::has('service_type') ? Session::get('service_type') : request()->route('type')]);
                        }
                    }
                }
            } catch (\Exception $e) {
                // التعامل مع الأخطاء المحتملة في التوكن
                // تسجيل الاستثناءات إذا لزم الأمر
            }

            // متابعة الطلب إذا لم يكن المستخدم مسجلاً دخوله
            return $next($request);
        }


    }
