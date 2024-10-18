<?php

    namespace App\Http\Controllers\Dashboard;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use App\Models\OrderSession;
    use App\Models\Order;
    use App\Models\Availability_Employees;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\Log;

    class CalendarController extends Controller
    {
        public function index()
        {
            return view('Dashboard.calendar.home.calendar');
        }

        public function getCalendarData()
        {
            // 🌟 Fetching sessions from the database with related table joins
            $sessions = OrderSession::join('orders', 'order_sessions.order_id', '=', 'orders.id')
                ->join('employees', 'orders.employee_id', '=', 'employees.id')
                ->join('availability_employees', 'orders.employee_id', '=', 'availability_employees.employee_id')
                ->leftJoin('categories', function ($join) {
                    // 📦 Joining categories table where category_id is not null
                    $join->on('orders.category_id', '=', 'categories.id')
                        ->whereNotNull('orders.category_id');
                })
                ->leftJoin('packages', function ($join) {
                    // 🎁 Joining packages table where package_id is not null
                    $join->on('orders.package_id', '=', 'packages.id')
                        ->whereNotNull('orders.package_id');
                })
                // 👤 Joining users table to add the client's name
                ->leftJoin('users', 'orders.user_id', '=', 'users.id') // Ensure user_id is the correct column
                ->select(
                    'order_sessions.session_date',  // 📅 Session date
                    'employees.name as employee_name', // 🙋‍♂️ Employee's name
                    'availability_employees.color', // 🎨 Employee's availability color
                    'categories.name as category_name', // 📂 Category name
                    'packages.name as package_name', // 📦 Package name
                    'orders.sessions_count', // 🔢 Total sessions count
                    'orders.notes', // 📝 Order notes
                    'order_sessions.status', // ✅ Session status
                    'order_sessions.*', // 🔍 All order session fields
                    'orders.location', // 📍 Adding location from orders table
                    'users.name as client_name' // 🧑‍🤝‍🧑 Adding client's name
                )
                ->whereNotNull('order_sessions.session_date') // 🌈 Ensure session date is not null
                // ->where('orders.is_paid', true) // 💳 Uncomment if only paid orders are needed
                ->where('orders.reservation_status', 'at_home') // 🏠 Filtering for home reservations
                ->get(); // 📥 Execute the query to get sessions


            // 🔍 Compute additional details for each session
            $sessions->transform(function ($session) {
                // 📊 Get the total number of sessions for this customer, order, and service
                $totalSessions = OrderSession::where('order_id', $session->order_id)
                    ->where('session_date', $session->session_date)
                    ->where(function ($query) use ($session) {
                        // 📦 Check for package ID if available
                        if (!empty($session->package_id)) {
                            $query->where('orders.package_id', $session->package_id);
                        }
                        // 📂 Check for category ID if available
                        if (!empty($session->category_id)) {
                            $query->where('orders.category_id', $session->category_id);
                        }
                    })
                    ->where('status', 'completed') // ✅ Consider only completed sessions
                    ->count(); // 🔢 Count total sessions

                // 🌍 Decode the location data
                $location = json_decode($session->location);
                if (json_last_error() === JSON_ERROR_NONE && isset($location->latitude) && isset($location->longitude)) {
                    // 📍 Create Google Maps URL for the session location
                    $address = "https://www.google.com/maps?q={$location->latitude},{$location->longitude}";
                } else {
                    // ❌ If decoding fails, set address to null
                    $address = null;
                }

                // ✨ Add the computed data to the session
                $session->total_sessions = $totalSessions; // 🔢 Total sessions
                $session->google_maps_url = $address; // 🗺️ Google Maps URL

                return $session; // 🚀 Return the modified session
            });

            return response()->json($sessions);  // 📤 Return the sessions as JSON
        }

        public function indexSpa()
        {
            return view('Dashboard.calendar.spa.calendar');
        }

        public function getCalendarSpaData()
        {
            // 🌟 Fetching sessions from the database with related table joins
            $sessions = OrderSession::join('orders', 'order_sessions.order_id', '=', 'orders.id')
                ->join('employees', 'orders.employee_id', '=', 'employees.id')
                ->join('availability_employees', 'orders.employee_id', '=', 'availability_employees.employee_id')
                ->leftJoin('categories', function ($join) {
                    // 📦 Joining categories table where category_id is not null
                    $join->on('orders.category_id', '=', 'categories.id')
                        ->whereNotNull('orders.category_id');
                })
                ->leftJoin('packages', function ($join) {
                    // 🎁 Joining packages table where package_id is not null
                    $join->on('orders.package_id', '=', 'packages.id')
                        ->whereNotNull('orders.package_id');
                })
                // 👤 Joining users table to add the client's name
                ->leftJoin('users', 'orders.user_id', '=', 'users.id') // Ensure user_id is the correct column
                ->select(
                    'order_sessions.session_date',  // 📅 Session date
                    'employees.name as employee_name', // 🙋‍♂️ Employee's name
                    'availability_employees.color', // 🎨 Employee's availability color
                    'categories.name as category_name', // 📂 Category name
                    'packages.name as package_name', // 📦 Package name
                    'orders.sessions_count', // 🔢 Total sessions count
                    'orders.notes', // 📝 Order notes
                    'order_sessions.status', // ✅ Session status
                    'order_sessions.*', // 🔍 All order session fields
                    'orders.location', // 📍 Adding location from orders table
                    'users.name as client_name' // 🧑‍🤝‍🧑 Adding client's name
                )
                ->whereNotNull('order_sessions.session_date') // 🌈 Ensure session date is not null
                // ->where('orders.is_paid', true) // 💳 Uncomment if only paid orders are needed
                ->where('orders.reservation_status', 'at_spa') // 🏠 Filtering for home reservations
                ->get(); // 📥 Execute the query to get sessions


            // 🔍 Compute additional details for each session
            $sessions->transform(function ($session) {
                // 📊 Get the total number of sessions for this customer, order, and service
                $totalSessions = OrderSession::where('order_id', $session->order_id)
                    ->where('session_date', $session->session_date)
                    ->where(function ($query) use ($session) {
                        // 📦 Check for package ID if available
                        if (!empty($session->package_id)) {
                            $query->where('orders.package_id', $session->package_id);
                        }
                        // 📂 Check for category ID if available
                        if (!empty($session->category_id)) {
                            $query->where('orders.category_id', $session->category_id);
                        }
                    })
                    ->where('status', 'completed') // ✅ Consider only completed sessions
                    ->count(); // 🔢 Count total sessions

                // 🌍 Decode the location data
                $location = json_decode($session->location);
                if (json_last_error() === JSON_ERROR_NONE && isset($location->latitude) && isset($location->longitude)) {
                    // 📍 Create Google Maps URL for the session location
                    $address = "https://www.google.com/maps?q={$location->latitude},{$location->longitude}";
                } else {
                    // ❌ If decoding fails, set address to null
                    $address = null;
                }

                // ✨ Add the computed data to the session
                $session->total_sessions = $totalSessions; // 🔢 Total sessions
                $session->google_maps_url = $address; // 🗺️ Google Maps URL

                return $session; // 🚀 Return the modified session
            });

            return response()->json($sessions);  // 📤 Return the sessions as JSON
        }

        public function updateStatus(Request $request)
        {
            // 🔍 Retrieve the session using the provided session_id from the request
            $session = OrderSession::find($request->session_id);

            if ($session) { // ✅ Check if the session exists
                // 🔄 Update the status of the session with the new status from the request
                $session->status = $request->status;

                // 📝 If a reason is provided in the request, update the session notes
                if ($request->has('reason')) {
                    $session->notes = $request->reason; // ✍️ Save the reason as notes
                }

                // 💾 Save the updated session to the database
                $session->save();

                // 📤 Return a success response with a message indicating the session was updated successfully
                return response()->json(['success' => true, 'message' => 'Session updated successfully']);
            } else {
                // ❌ If the session was not found, return an error response with a 404 status code
                return response()->json(['success' => false, 'message' => 'Session not found'], 404);
            }
        }

    }
