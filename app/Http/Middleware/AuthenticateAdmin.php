<?php


    namespace App\Http\Middleware;

    use Closure;
    use Illuminate\Http\Request;
    use Illuminate\Routing\Controllers\Middleware;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Log;
    use Symfony\Component\HttpFoundation\Response;


    class AuthenticateAdmin
    {
        /**
         * Handle an incoming request.
         *
         * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
         */

        public function handle($request, Closure $next, $guard = 'admin')
        {
            // Get the current path
            $path = $request->path();

            // Check if the path contains 'admin'
            if (strpos($path, 'admin') !== false) {
                // Check if the user is trying to access the admin login page and is already logged in
                if ($path == 'admin/login' && Auth::guard($guard)->check()) {
                    return redirect()->route('admin.dashboard');
                }

                // Check if the user is not logged in and trying to access admin pages other than the login page
                if (!Auth::guard($guard)->check() && $path !== 'admin/login') {
                    return redirect()->route('admin.login');
                }
            }

            return $next($request);
        }


    }
