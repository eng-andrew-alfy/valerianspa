<?php

    namespace App\Http\Middleware;

    use Closure;
    use Illuminate\Http\Request;
    use Symfony\Component\HttpFoundation\Response;

    class CheckPaymentRedirect
    {
        /**
         * Handle an incoming request.
         *
         * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
         */
        public function handle(Request $request, Closure $next): Response
        {
            if (!$request->session()->has('payment_redirect')) {
                return redirect('/');
            }

            $request->session()->forget('payment_redirect');

            return $next($request);
        }
    }
