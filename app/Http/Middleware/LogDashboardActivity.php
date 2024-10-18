<?php

    namespace App\Http\Middleware;

    use App\Models\DashboardLog;
    use Closure;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Log;
    use Symfony\Component\HttpFoundation\Response;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Str;

    class LogDashboardActivity
    {
        /**
         * Handle an incoming request.
         *
         * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
         */
        public function handle(Request $request, Closure $next): Response
        {
            if ($this->shouldLogActivity($request)) {
                $this->logActivity($request);
            }

            return $next($request);
        }

        /**
         * Determine if the request should be logged.
         *
         * @param Request $request
         * @return bool
         */
        private function shouldLogActivity(Request $request): bool
        {
            return $request->is('admin/*') && in_array($request->method(), ['POST', 'PUT', 'DELETE']);
        }

        /**
         * Log the activity.
         *
         * @param Request $request
         */
        private function logActivity(Request $request): void
        {
            try {
                $logData = $this->prepareLogData($request);
                $this->addActionSpecificData($request, $logData);
                DashboardLog::create($logData);
            } catch (\Exception $e) {
                Log::error('Failed to log dashboard activity: ' . $e->getMessage());
            }
        }

        /**
         * Prepare the base log data.
         *
         * @param Request $request
         * @return array
         */
        private function prepareLogData(Request $request): array
        {
            return [
                'admin_id' => auth('admin')->id(),
                'action' => $request->method(),
                'url' => $request->url(),
                'ip' => $request->ip(),
                'device' => $request->header('User-Agent'),
            ];
        }

        /**
         * Add action-specific data to the log.
         *
         * @param Request $request
         * @param array $logData
         */
        private function addActionSpecificData(Request $request, array &$logData): void
        {
            $action = $request->method();
            $model = $this->getModelFromRequest($request);

            if ($action === 'POST') {
                $logData['details'] = json_encode(['new_data' => $request->all()]);
            } elseif ($action === 'PUT' && $model) {
                $logData['details'] = json_encode([
                    'original_data' => $model->toArray(),
                    'updated_data' => $request->all(),
                ]);
            } elseif ($action === 'DELETE' && $model) {
                $logData['details'] = json_encode(['deleted_data' => $model->toArray()]);
            }
        }

        /**
         * Get the model instance based on the request.
         *
         * @param Request $request
         * @return Model|null
         */
        private function getModelFromRequest(Request $request): ?Model
        {
            $path = $request->path();
            $segments = explode('/', $path);

            if (count($segments) >= 3) {
                $modelName = Str::studly(Str::singular($segments[1])); // Use Str::studly and Str::singular
                $modelClass = "App\\Models\\$modelName";

                if (class_exists($modelClass) && isset($segments[2]) && is_numeric($segments[2])) {
                    return $modelClass::find($segments[2]);
                }
            }

            return null;
        }
    }
