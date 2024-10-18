<?php

    namespace App\Http\Controllers\Dashboard;

    use App\Http\Controllers\Controller;
    use App\Models\ErrorLog;

    class ErrorLogController extends Controller
    {
        public function index()
        {
            $logs = ErrorLog::with('admin')->take(100)->orderBy('id', 'desc')->get();

            return view('Dashboard.error_logs.index', compact('logs'));
        }

        public function show($id)
        {
            $log = ErrorLog::findOrFail($id); // الحصول على السجل بناءً على الـ ID

            // يمكنك استخدام dd() هنا لتفريغ تفاصيل الخطأ
            dd($log);

            // بدلاً من ذلك، يمكنك عرض تفاصيل الخطأ في عرض منفصل إذا كنت تفضل ذلك
            // return view('Dashboard.error_logs.show', compact('log'));
        }
    }
