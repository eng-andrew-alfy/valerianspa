@php use App\Models\DashboardNotification; @endphp
<style>
    .notifications-footer {
        width: 100%;
        position: fixed;
        bottom: 0;
        left: 0;
        padding: 5px 0;
        background-color: transparent;
    }

    .btn-notifications {
        display: inline-block;
        padding: 8px 16px;
        font-size: 14px;
        background-color: transparent;
        color: inherit;
        text-align: center;
        text-decoration: none;
        border: none;
    }

    .btn-notifications:hover {
        background-color: transparent;
        color: inherit;
    }
</style>
<nav class="navbar header-navbar pcoded-header">
    <div class="navbar-wrapper">
        <div class="navbar-logo">
            <a class="mobile-menu" id="mobile-collapse" href="#!">
                <i class="ti-menu"></i>
            </a>
            <a class="mobile-search morphsearch-search" href="#">
                <i class="ti-search"></i>
            </a>
            <a href="">
                <img class="img-fluid" style="width: 55px; height: 55px"
                     src="{{ asset('dashboard/assets/images/logo-stroke.png') }}" alt="Valerianspa-Logo"/>
            </a>
            <a class="mobile-options">
                <i class="ti-more"></i>
            </a>
        </div>
        <div class="navbar-container container-fluid">
            <div>
                <ul class="nav-left">
                    <li>
                        <div class="sidebar_toggle"><a href="javascript:void(0)"><i class="ti-menu"></i></a></div>
                    </li>
                    <li>
                        <a href="#!" onclick="javascript:toggleFullScreen()">
                            <i class="ti-fullscreen"></i>
                        </a>
                    </li>

                </ul>
                {{--                @php use App\Models\DashboardNotification; @endphp--}}
                <ul class="nav-right">
                    <li class="header-notification">
                        <a href="#!" id="notification-bell" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="ti-bell"></i>
                            <span id="notification-count" class="badge bg-danger" style="display: none;"></span>
                        </a>
                        <ul class="show-notification" id="notification-list">
                            <li>
                                <h6>الإشعارات</h6>
                                <button id="mark-all-read" class="btn btn-sm btn-primary float-right">تعليم الكل
                                    كمقروء
                                </button>
                            </li>
                            <!-- Notifications will be dynamically inserted here -->
                            <li class="text-center" id="no-notifications" style="display: none;">
                                <p>لا توجد إشعارات جديدة</p>
                            </li>
                            <li class="text-center notifications-footer">
                                <a href="{{ route('admin.notificationsFetchAll') }}"
                                   class="btn btn-notifications btn-sm">
                                    عرض كل الإشعارات
                                </a>
                            </li>

                        </ul>
                    </li>


                    <li class="user-profile header-notification">
                        <a href="#!">
                            <img src="{{ asset('dashboard/assets/images/user.png') }}" alt="User-Profile-Image">
                            <span>{{ Auth::guard('admin')->user()->name }}</span>
                            <i class="ti-angle-down"></i>
                        </a>
                        <ul class="show-notification profile-notification">
                            <li>
                                <a href="{{ route('admin.logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="ti-layout-sidebar-left"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        const notificationSound = new Audio('{{ asset('dashboard/assets/sounds/notification.wav') }}');
        let lastNotificationCount = 0;

        function fetchNotifications() {
            $.ajax({
                url: '{{ route("admin.notifications.fetchLatest") }}',
                method: 'GET',
                success: function (data) {
                    updateNotificationList(data.notifications);
                    updateNotificationCount(data.totalUnread);
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching notifications:", error);
                }
            });
        }

        function updateNotificationList(notifications) {
            const $notificationList = $('#notification-list');
            $notificationList.find('li:not(:first-child):not(:last-child)').remove();
            if (notifications.length === 0) {
                $('#no-notifications').show();
            } else {
                $('#no-notifications').hide();
                notifications.forEach(function (notification) {
                    const notificationHtml = `
                <li data-notification-id="${notification.id}" data-notification-type="${notification.type}" data-url="${notification.url}" class="${notification.read ? 'read' : 'unread'}">
                    <div class="media">
                        <div class="media-body">
                            <h5 class="notification-user">${notification.title}</h5>
                            <p class="notification-msg">${notification.message}</p>
                            <span class="notification-time">${notification.created_at}</span>
                        </div>
                    </div>
                </li>
            `;
                    $notificationList.find('li:last').before(notificationHtml);
                });
            }
        }

        function updateNotificationCount(count) {
            const $notificationCount = $('#notification-count');
            if (count > 0) {
                $notificationCount.text(count).show();
                if (count > lastNotificationCount) {
                    notificationSound.play().catch(error => {
                        console.error("Error playing sound:", error);
                    });
                }
            } else {
                $notificationCount.hide();
            }
            lastNotificationCount = count;
        }

        function markAllAsRead() {
            $.ajax({
                url: '{{ route("admin.notifications.markAllAsRead") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (data) {
                    if (data.status === 'success') {
                        fetchNotifications();
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error marking all notifications as read:", error);
                }
            });
        }

        $(document).on('click', '#notification-list li[data-notification-id]', function () {
            const notificationId = $(this).data('notification-id');
            const notificationType = $(this).data('notification-type');
            const url = $(this).data('url');
            $.ajax({
                url: '{{ route("admin.notifications.MarkAsRead") }}',
                method: 'POST',
                data: {
                    id: notificationId,
                    type: notificationType,
                    _token: '{{ csrf_token() }}'
                },
                success: function (data) {
                    if (data.status === 'success') {
                        window.location.href = url;
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error marking notification as read:", error);
                }
            });
        });

        $('#mark-all-read').on('click', function (e) {
            e.preventDefault();
            markAllAsRead();
        });

        fetchNotifications();
        setInterval(fetchNotifications, 30000);
    });
</script>
{{--<script>--}}
{{--    $(document).ready(function () {--}}
{{--        const notificationSound = new Audio('{{ asset('dashboard/assets/sounds/notification.wav') }}');--}}
{{--        let lastNotificationCount = 0;--}}

{{--        function fetchNotifications() {--}}
{{--            $.ajax({--}}
{{--                url: '{{ route("admin.notifications.fetchLatest") }}',--}}
{{--                method: 'GET',--}}
{{--                success: function (data) {--}}
{{--                    updateNotificationList(data.notifications);--}}
{{--                    updateNotificationCount(data.count);--}}
{{--                },--}}
{{--                error: function (xhr, status, error) {--}}
{{--                    console.error("Error fetching notifications:", error);--}}
{{--                }--}}
{{--            });--}}
{{--        }--}}

{{--        function updateNotificationList(notifications) {--}}
{{--            const $notificationList = $('#notification-list');--}}
{{--            $notificationList.find('li:not(:first-child):not(:last-child)').remove();--}}
{{--            if (notifications.length === 0) {--}}
{{--                $('#no-notifications').show();--}}
{{--            } else {--}}
{{--                $('#no-notifications').hide();--}}
{{--                notifications.forEach(function (notification) {--}}
{{--                    const notificationHtml = `--}}
{{--               <li data-notification-id="${notification.id}" data-notification-type="${notification.type}" data-url="${notification.url}" class="${notification.read ? 'read' : 'unread'}">--}}
{{--                    <div class="media">--}}
{{--                        <div class="media-body">--}}
{{--                            <h5 class="notification-user">${notification.title}</h5>--}}
{{--                            <p class="notification-msg">${notification.message}</p>--}}
{{--                            <span class="notification-time">${notification.created_at}</span>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </li>--}}
{{--            `;--}}
{{--                    $notificationList.find('li:last').before(notificationHtml);--}}
{{--                });--}}
{{--            }--}}
{{--        }--}}

{{--        function updateNotificationCount(count) {--}}
{{--            const $notificationCount = $('#notification-count');--}}
{{--            if (count > 0) {--}}
{{--                $notificationCount.text(count).show();--}}
{{--                if (count > lastNotificationCount) {--}}
{{--                    notificationSound.play().catch(error => {--}}
{{--                        console.error("Error playing sound:", error);--}}
{{--                    });--}}
{{--                }--}}
{{--            } else {--}}
{{--                $notificationCount.hide();--}}
{{--            }--}}
{{--            lastNotificationCount = count;--}}
{{--        }--}}

{{--        --}}{{--function markNotificationAsRead(notificationId, notificationType) {--}}
{{--        --}}{{--    return $.ajax({--}}
{{--        --}}{{--        url: '{{ route("admin.notifications.MarkAsRead") }}',--}}
{{--        --}}{{--        method: 'POST',--}}
{{--        --}}{{--        data: {--}}
{{--        --}}{{--            id: notificationId,--}}
{{--        --}}{{--            type: notificationType,--}}
{{--        --}}{{--            _token: '{{ csrf_token() }}'--}}
{{--        --}}{{--        }--}}
{{--        --}}{{--    });--}}
{{--        --}}{{--}--}}

{{--        --}}{{--// Fetch notifications immediately when the page loads--}}
{{--        --}}{{--fetchNotifications();--}}

{{--        --}}{{--// Mark notification as read and navigate when clicked--}}
{{--        --}}{{--$(document).on('click', '#notification-list li[data-notification-id]', function () {--}}
{{--        --}}{{--    const notificationId = $(this).data('notification-id');--}}
{{--        --}}{{--    const notificationType = $(this).data('notification-type');--}}
{{--        --}}{{--    const url = $(this).data('url');--}}

{{--        --}}{{--    markNotificationAsRead(notificationId, notificationType).done(function (data) {--}}
{{--        --}}{{--        if (data.status === 'success') {--}}
{{--        --}}{{--            // Redirect to the notification URL--}}
{{--        --}}{{--            window.location.href = url;--}}
{{--        --}}{{--        }--}}
{{--        --}}{{--    }).fail(function (xhr, status, error) {--}}
{{--        --}}{{--        console.error("Error marking notification as read:", error);--}}
{{--        --}}{{--    });--}}
{{--        --}}{{--});--}}

{{--        --}}{{--// Fetch notifications every 30 seconds--}}
{{--        --}}{{--setInterval(fetchNotifications, 30000);--}}
{{--        function markAllAsRead() {--}}
{{--            $.ajax({--}}
{{--                url: '{{ route("admin.notifications.markAllAsRead") }}',--}}
{{--                method: 'POST',--}}
{{--                data: {--}}
{{--                    _token: '{{ csrf_token() }}'--}}
{{--                },--}}
{{--                success: function (data) {--}}
{{--                    if (data.status === 'success') {--}}
{{--                        fetchNotifications();--}}
{{--                    }--}}
{{--                },--}}
{{--                error: function (xhr, status, error) {--}}
{{--                    console.error("Error marking all notifications as read:", error);--}}
{{--                }--}}
{{--            });--}}
{{--        }--}}

{{--        $(document).on('click', '#notification-list li[data-notification-id]', function () {--}}
{{--            const notificationId = $(this).data('notification-id');--}}
{{--            const notificationType = $(this).data('notification-type');--}}
{{--            const url = $(this).data('url');--}}
{{--            $.ajax({--}}
{{--                url: '{{ route("admin.notifications.MarkAsRead") }}',--}}
{{--                method: 'POST',--}}
{{--                data: {--}}
{{--                    id: notificationId,--}}
{{--                    type: notificationType,--}}
{{--                    _token: '{{ csrf_token() }}'--}}
{{--                },--}}
{{--                success: function (data) {--}}
{{--                    if (data.status === 'success') {--}}
{{--                        window.location.href = url;--}}
{{--                    }--}}
{{--                },--}}
{{--                error: function (xhr, status, error) {--}}
{{--                    console.error("Error marking notification as read:", error);--}}
{{--                }--}}
{{--            });--}}
{{--        });--}}

{{--        $('#mark-all-read').on('click', function (e) {--}}
{{--            e.preventDefault();--}}
{{--            markAllAsRead();--}}
{{--        });--}}

{{--        fetchNotifications();--}}
{{--        setInterval(fetchNotifications, 30000);--}}
{{--    });--}}
{{--</script>--}}

{{--<script src="{{ asset('dashboard/assets/notification.js') }}"></script>--}}
