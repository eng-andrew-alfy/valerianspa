<?php

    namespace App\Http\Middleware;

    use Closure;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Log;
    use Symfony\Component\HttpFoundation\Response;
    use Tymon\JWTAuth\Exceptions\JWTException;
    use Tymon\JWTAuth\Exceptions\TokenExpiredException;
    use Tymon\JWTAuth\Facades\JWTAuth;

    class AuthenticateToken
    {
        /**
         * Handle an incoming request.
         *
         * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
         */

        public function handle(Request $request, Closure $next)
        {
            $token = $request->cookie('token');
            if (!$token) {
                return redirect()->route('login');
            }

            try {
                JWTAuth::setToken($token)->authenticate();
            } catch (JWTException $e) {
                return redirect()->route('login');
            }

            return $next($request);
        }
//        public function handle(Request $request, Closure $next): Response
//        {
//            $token = $request->bearerToken();
//            Log::info('Received token: ' . $token);
//
//            if (!$token) {
//                Log::info('No token provided');
//                return redirect()->route('login');
//            }
//
//            try {
//                $user = JWTAuth::setToken($token)->authenticate();
//            } catch (TokenExpiredException $e) {
//                Log::warning('JWT Token expired: ' . $e->getMessage());
//
//                try {
//                    $refreshedToken = JWTAuth::setToken($token)->refresh();
//                    $response = $next($request);
//                    $response->headers->setCookie(cookie('token', $refreshedToken, 60));
//                    return $response;
//                } catch (JWTException $e) {
//                    Log::error('Failed to refresh JWT Token: ' . $e->getMessage());
//                    return redirect()->route('login');
//                }
//            } catch (JWTException $e) {
//                Log::error('JWT Token validation error: ' . $e->getMessage());
//                return redirect()->route('login');
//            }
//
//            if (!$user) {
//                Log::info('User not found for token');
//                return redirect()->route('login');
//            }
//
//            return $next($request);
//        }

    }
