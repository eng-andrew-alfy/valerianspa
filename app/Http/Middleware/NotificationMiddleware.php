<?php

    namespace App\Http\Middleware;

    use App\Models\DashboardNotification;
    use Closure;
    use Illuminate\Http\Request;
    use Symfony\Component\HttpFoundation\Response;

    class NotificationMiddleware
    {
        /**
         * Handle an incoming request.
         *
         * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
         */
        public function handle(Request $request, Closure $next)
        {
            // جلب الإشعارات
            $dashboardNotifications = DashboardNotification::where('read_at', null)->get();

            if (auth('admin')->check()) {
                $laravelNotifications = auth('admin')->user()->unreadNotifications;
            } else {
                $laravelNotifications = collect(); // مجموعة فارغة إذا لم يكن هناك مستخدم مسجل دخول
            }

            // دمج الإشعارات
            $allNotifications = $dashboardNotifications->merge($laravelNotifications);

            // تخزين الإشعارات في الـ View
            view()->share('allNotifications', $allNotifications);

            return $next($request);
        }


    }
