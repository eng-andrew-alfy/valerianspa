<?php

    namespace App\Http\Controllers\Dashboard;

    use App\Http\Controllers\Controller;
    use App\Models\Employee;
    use App\Models\Order;
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Log;

    class HomeController extends Controller
    {
        public function index()
        {
            //return view('dashboard.home');
        }

        public function getOrdersData()
        {
            $dailyOrders = Order::whereDate('created_at', Carbon::today())->count();

            $weeklyOrders = Order::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

            $monthlyOrders = Order::whereMonth('created_at', Carbon::now()->month)->count();

            return response()->json([
                'daily' => $dailyOrders,
                'weekly' => $weeklyOrders,
                'monthly' => $monthlyOrders
            ]);
        }

        // Controller لجلب بيانات الموظفين والطلبات
        public function getEmployeeOrdersData()
        {
            // احضار كل الموظفين مع عدد الطلبات واللون الخاص بكل موظف
            $employees = Employee::withCount('orders')->get(['name', 'color']);

            // تجهيز البيانات للإرسال إلى الواجهة
            $employeeData = $employees->map(function ($employee) {
                return [
                    'name' => $employee->getTranslation('name', 'ar'),
                    'order_count' => $employee->orders_count, // عدد الطلبات لكل موظف
                    'color' => $employee->availability->color // اللون المميز لكل موظف
                ];
            });
            Log::info('employeesPolarChart::::::::::' . $employeeData);
            // إرسال البيانات كاستجابة JSON
            return response()->json($employeeData);
        }

    }


