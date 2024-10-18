<?php

    namespace App\Http\Middleware;

    use Closure;
    use Illuminate\Support\Facades\Auth;
    use Throwable;
    use App\Models\ErrorLog;
    use Illuminate\Http\Request;
    use Jenssegers\Agent\Agent;
    use Illuminate\Support\Facades\Log;
    use Exception;

    class LogErrorMiddleware
    {
        public function handle($request, Closure $next)
        {
            try {
                return $next($request);
            } catch (\Exception $e) {
                Log::channel('database_errors')->error($e->getMessage(), [
                    'admin_id' => auth('admin')->id() ?? null,
                    'stack_trace' => $e->getTraceAsString(),
                    'url' => $request->fullUrl(),
                    'ip' => $request->ip(),
                    'device' => $request->header('User-Agent'),
                    'user_agent' => $request->header('User-Agent'),
                    'error_code' => $e->getCode(),
                    'request_data' => $request->all(),
                    'previous_url' => url()->previous(),
                    'os' => php_uname('s'),
                    'platform' => $request->header('Platform', 'web'),
                ]);

                throw $e;
            }
        }
    }
