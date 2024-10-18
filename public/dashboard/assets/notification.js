$(document).ready(function () {
    const notificationSound = new  Audio('{{ asset('dashboard/assets/sounds/notification.wav') }}');
    let lastNotificationCount = 0;

    function fetchNotifications() {
        $.ajax({
            url: '{{ route("admin.notifications.fetch") }}',
            method: 'GET',
            success: function (data) {
                updateNotificationList(data.notifications);
                updateNotificationCount(data.count);
            },
            error: function (xhr, status, error) {
                console.error("Error fetching notifications:", error);
            }
        });
    }

    function updateNotificationList(notifications) {
        const $notificationList = $('#notification-list');
        $notificationList.find('li:not(:first-child)').remove();

        if (notifications.length === 0) {
            $notificationList.append('<li><p class="align-content-center">لا توجد إشعارات جديدة</p></li>');
        } else {
            notifications.forEach(function (notification) {
                const notificationHtml = `
                    <li data-notification-id="${notification.id}" data-notification-type="${notification.type}">
                        <div class="media">
                            <img class="d-flex align-self-center" src="{{ asset('dashboard/assets/images/user.png') }}" alt="صورة افتراضية">
                            <div class="media-body">
                                <a href="${notification.url}">
                                    <h5 class="notification-user">${notification.title}</h5>
                                    <p class="notification-msg">${notification.message}</p>
                                </a>
                                <span class="notification-time">${notification.created_at}</span>
                            </div>
                        </div>
                    </li>
                `;
                $notificationList.append(notificationHtml);
            });
        }
    }

    function updateNotificationCount(count) {
        const $notificationCount = $('#notification-count');
        const $newLabel = $('#new-label');

        if (count > 0) {
            $notificationCount.text(count).show();
            $newLabel.show();

            if (count > lastNotificationCount) {
                notificationSound.play();
            }
        } else {
            $notificationCount.hide();
            $newLabel.hide();
        }

        lastNotificationCount = count;
    }

    function markNotificationAsRead(notificationId, notificationType) {
        $.ajax({
            url: '{{ route("admin.notifications.MarkAsRead") }}',
            method: 'POST',
            data: {
                id: notificationId,
                type: notificationType,
                _token:  '{{ csrf_token() }}'
            },
            success: function (data) {
                if (data.status === 'success') {
                    fetchNotifications();
                }
            },
            error: function (xhr, status, error) {
                console.error("Error marking notification as read:", error);
            }
        });
    }


    // Fetch notifications immediately when the page loads
    fetchNotifications();

    // Mark notification as read when clicked
    $(document).on('click', '#notification-list li[data-notification-id]', function () {
        const notificationId = $(this).data('notification-id');
        const notificationType = $(this).data('notification-type');
        markNotificationAsRead(notificationId, notificationType);
    });

    // Fetch notifications every 30 seconds
    setInterval(fetchNotifications, 30000);
});
