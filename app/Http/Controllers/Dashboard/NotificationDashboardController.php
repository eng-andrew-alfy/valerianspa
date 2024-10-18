<?php

    namespace App\Http\Controllers\Dashboard;

    use App\Http\Controllers\Controller;
    use App\Models\DashboardNotification;

    class NotificationDashboardController extends Controller
    {
        public function index(DashboardNotification $notification)
        {

            if ($notification->read_at === null) {
                $notification->markAsRead();
            }
            return view('dashboard.notification.index', compact('notification'));
        }
    }
