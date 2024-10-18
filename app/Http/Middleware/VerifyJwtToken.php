<?php

    namespace App\Http\Middleware;

    use Brian2694\Toastr\Facades\Toastr;
    use Closure;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Symfony\Component\HttpFoundation\Response;
    use Tymon\JWTAuth\Exceptions\TokenExpiredException;
    use Tymon\JWTAuth\Exceptions\TokenInvalidException;
    use Tymon\JWTAuth\Facades\JWTAuth;
    use Tymon\JWTAuth\Exceptions\JWTException;

    class VerifyJwtToken
    {
        /**
         * Handle an incoming request.
         *
         * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
         */

        public function handle(Request $request, Closure $next): Response
        {
            try {
                if ($token = JWTAuth::getToken()) {
                    $user = JWTAuth::parseToken()->authenticate();
                    if ($user) {
                        auth()->setUser($user);
                    }
                }
            } catch (TokenExpiredException $e) {
                try {
                    $newToken = JWTAuth::refresh(JWTAuth::getToken());
                    return $next($request)->header('Authorization', 'Bearer ' . $newToken);
                } catch (\Exception $e) {
                    return $this->handleInvalidToken($request);
                }
            } catch (TokenInvalidException $e) {
                return $this->handleInvalidToken($request);
            } catch (JWTException $e) {
                return $this->handleInvalidToken($request);
            }

            return $next($request);
        }

        private function handleInvalidToken(Request $request)
        {
            if (!$request->expectsJson()) {
                Toastr::error('زعلنا على تسجيل خروجك! 😔 لا تطول الغيبة، ننتظرك ترجع قريب. 👋', 'عملية فاشلة');
                return redirect()->route('login')->with('error', 'توكن غير صالح أو منتهي الصلاحية');
            }
            return response()->json(['error' => 'Invalid Token'], 401);
        }


    }
