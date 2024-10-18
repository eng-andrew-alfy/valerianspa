<?php

    namespace App\Http\Middleware;

    use Closure;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Symfony\Component\HttpFoundation\Response;

    class AdminIdleLogout
    {
        /**
         * Handle an incoming request.
         *
         * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
         */
        public function handle(Request $request, Closure $next): Response
        {
            $sessionLifetime = 60 * 60; // مدة الجلسة بالدقائق (15 دقيقة)، وتحويلها لثواني
            $lastActivity = session('lastActivityTime') ? session('lastActivityTime') : now()->timestamp;

            // التحقق من مدة الخمول وإذا تجاوزت المدة المحددة قم بتسجيل الخروج
            if (now()->timestamp - $lastActivity > $sessionLifetime) {
                Auth::guard('admin')->logout();

                // تحقق من أن طلب تسجيل الدخول ليس ضمن العملية الحالية
                if ($request->path() !== 'admin/login') {
                    return redirect()->route('admin.login')->withErrors('لقد تم تسجيل الخروج بسبب عدم النشاط.');
                }
            }

            // تحديث وقت النشاط الأخير
            session(['lastActivityTime' => now()->timestamp]);

            return $next($request);
        }

    }
