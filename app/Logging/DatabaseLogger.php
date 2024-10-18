<?php
    //
    namespace App\Logging;


    use Monolog\Logger;
    use Monolog\Handler\AbstractProcessingHandler;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Request as FacadesRequest;

    class DatabaseLogger
    {
        public function __invoke(array $config)
        {
            $logger = new Logger('database_errors');
            $logger->pushHandler(new class extends AbstractProcessingHandler {
                protected function write(array|\Monolog\LogRecord $record): void
                {
                    // الحصول على الطلب الحالي
                    $request = Request::capture();

                    // تسجيل البيانات في قاعدة البيانات
                    DB::table('error_logs')->insert([
                        'admin_id' => Auth::guard('admin')->check() ? Auth::guard('admin')->id() : null,
                        'error_message' => $record->message ?? null,
                        'stack_trace' => $this->getStackTrace($record), // فرضًا تخزين معلومات الخطأ هنا
                        'url' => $request->fullUrl() ?? null,
                        'ip' => $request->ip() ?? null,
                        'device' => $request->header('User-Agent') ?? null,
                        'user_agent' => $request->header('User-Agent') ?? null,
                        'error_code' => $this->getErrorCode($record), // فرضًا تخزين معلومات الخطأ هنا
                        'request_data' => json_encode($request->all()) ?? null,
                        'previous_url' => url()->previous() ?? null,
                        'os' => php_uname('s') ?? null,
                        'platform' => $request->header('Platform', 'web') ?? 'web',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // الحصول على trace من السجل
                private function getStackTrace(array|\Monolog\LogRecord $record): ?string
                {
                    if ($record instanceof \Monolog\LogRecord) {
                        return $record->context['stack_trace'] ?? null;
                    }
                    return null;
                }

                // الحصول على error code من السجل
                private function getErrorCode(array|\Monolog\LogRecord $record): ?int
                {
                    if ($record instanceof \Monolog\LogRecord) {
                        // تحقق مما إذا كانت القيمة موجودة وتأكد من تحويلها إلى int
                        return isset($record->context['error_code']) && is_numeric($record->context['error_code'])
                            ? (int)$record->context['error_code']
                            : null;
                    }
                    return null; // إرجاع null إذا لم يكن من نوع LogRecord
                }

            });

            return $logger;
        }
    }
