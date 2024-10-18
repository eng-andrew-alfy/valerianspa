<?php

    namespace App\Http\Controllers;

    use App\Models\DashboardNotification;
    use Illuminate\Http\Request;

    class NotificationController extends Controller
    {
        public function index()
        {
            return view('notifications.index');
        }


        public function fetch()
        {
            $dashboardNotifications = DashboardNotification::where('read_at', null)->get();
            $laravelNotifications = auth('admin')->user()->unreadNotifications;

            $allNotifications = $dashboardNotifications->merge($laravelNotifications)->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification instanceof DashboardNotification ? 'dashboard' : 'laravel',
                    'title' => $notification->order_id ?? ($notification->data['order_code'] ?? 'طلب جديد'),
                    'message' => $notification->message ?? ($notification->data['service_name'] ?? 'خدمة جديدة'),
                    'url' => $this->getNotificationUrl($notification),
                    'created_at' => $notification->created_at->diffForHumans(),
                ];
            });

            return response()->json([
                'notifications' => $allNotifications,
                'count' => $allNotifications->count()
            ]);
        }

        public function fetchLatest()
        {
            $dashboardNotifications = DashboardNotification::where('read_at', null)
                ->latest()
                ->take(5)
                ->get();
            $laravelNotifications = auth('admin')->user()->unreadNotifications()
                ->latest()
                ->take(5)
                ->get();

            $allNotifications = $dashboardNotifications->merge($laravelNotifications)->sortByDesc('created_at')->take(5)->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification instanceof DashboardNotification ? 'dashboard' : 'laravel',
                    'title' => $notification->order_id ?? ($notification->data['order_code'] ?? 'طلب جديد'),
                    'message' => $notification->message ?? ($notification->data['service_name'] ?? 'خدمة جديدة'),
                    'url' => $this->getNotificationUrl($notification),
                    'created_at' => $notification->created_at->diffForHumans(),
                    'read' => !is_null($notification->read_at),
                ];
            });

            $totalUnread = DashboardNotification::where('read_at', null)->count() +
                auth('admin')->user()->unreadNotifications()->count();

            return response()->json([
                'notifications' => $allNotifications,
                'totalUnread' => $totalUnread
            ]);
        }

        public function markAllAsRead()
        {
            DashboardNotification::where('read_at', null)->update(['read_at' => now()]);
            auth('admin')->user()->unreadNotifications()->update(['read_at' => now()]);

            return response()->json(['status' => 'success']);
        }

        public function markAsRead(Request $request)
        {
            $id = $request->input('id');
            $type = $request->input('type');

            if ($type === 'dashboard') {
                $notification = DashboardNotification::find($id);
                if ($notification) {
                    $notification->update(['read_at' => now()]);
                }
            } else {
                $notification = auth('admin')->user()->notifications()->find($id);
                if ($notification) {
                    $notification->markAsRead();
                }
            }

            return response()->json(['status' => 'success']);
        }

        private function getNotificationUrl($notification)
        {
            if ($notification instanceof DashboardNotification) {
                return route('admin.notifications.show', $notification->id);
            } else {
                return route('admin.orders.show', $notification->data['customer_id'] ?? '');
            }
        }

        public function fetchAll()
        {
            $dashboardNotifications = DashboardNotification::latest()->get();
            $laravelNotifications = auth('admin')->user()->notifications()->latest()->get();

            $allNotifications = $dashboardNotifications->merge($laravelNotifications)->sortByDesc('created_at')->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification instanceof DashboardNotification ? 'dashboard' : 'laravel',
                    'title' => $notification->order_id ?? ($notification->data['order_code'] ?? 'طلب جديد'),
                    'message' => $notification->message ?? ($notification->data['service_name'] ?? 'خدمة جديدة'),
                    'url' => $this->getNotificationUrl($notification),
                    'created_at' => $notification->created_at->diffForHumans(),
                    'read' => !is_null($notification->read_at),
                ];
            });
//            $url = $allNotifications->url; // الرابط الذي تريده
//            $path = parse_url($url, PHP_URL_PATH); // استخرج الجزء الخاص بالمسار من الرابط
//            $segments = explode('/', $path); // قسم المسار إلى أجزاء بناءً على "/"
//
//            $orderId = end($segments);
//            dd($allNotifications);
            return view('Dashboard.notification.all', ['notifications' => $allNotifications]);
        }
    }
