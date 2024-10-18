<?php

    namespace App\Http\Controllers\Dashboard;

    use App\Http\Controllers\Controller;
    use App\Models\DashboardLog;
    use Illuminate\Routing\Controllers\Middleware;
    use Spatie\Permission\Middleware\PermissionMiddleware;
    use Spatie\Permission\Middleware\RoleMiddleware;


    class DashboardLogController extends Controller
    {


        public function index()
        {
            if (auth('admin')->user()->can('logs.view')) {
                $logs = DashboardLog::with('admin')->take(100)->orderBy('id', 'desc')->get(); // Get the latest 20 logs

                return view('Dashboard.logs.index', compact('logs'));
            } else {
                abort(403);
            }

        }

        public function show($id)
        {
            $log = DashboardLog::findOrFail($id); // استرجاع السجل بناءً على الـ ID
            dd($log); // عرض كل بيانات السجل
        }

    }
