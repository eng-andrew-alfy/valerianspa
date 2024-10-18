@extends('Dashboard.layouts.master')
{{--@section('title_dashboard')--}}
{{--@endsection--}}
@section('css_dashboard')
    <!-- sweet alert framework -->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/sweetalert/css/sweetalert.css') }}">
    <!-- Data Table Css -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('dashboard/assets/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css"
          href="{{ asset('dashboard/assets/pages/data-table/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css"
          href="{{ asset('dashboard/assets/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}">
    <!-- animation nifty modal window effects css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/css/component.css') }}"/>
    <style>
        .btn-left {
            margin-bottom: 30px; /* Adding space below the button */
        }

        .pointer {
            cursor: pointer;
        }

        .new-addition {
            background-color: #d4edda; /* Light green background for new items */
            color: #155724; /* Dark green text color */
            padding: 5px;
            border-radius: 3px;
        }

        button:focus,
        button:active,
        .alert-confirm:focus,
        .alert-confirm:active,
        .status-checkbox:focus,
        .status-checkbox:active {
            outline: none;
            box-shadow: none;
        }

        /* إعدادات للحالة "مقروء" */
        .notification-read {
            background-color: #d4edda; /* خلفية خضراء فاتحة */
            color: #155724; /* لون النص أخضر داكن */
            font-weight: bold; /* جعل النص سميك */
            border-radius: 5px; /* زوايا مستديرة قليلاً */
            padding: 5px;
        }

        /* إعدادات للحالة "غير مقروء" */
        .notification-unread {
            background-color: #f8d7da; /* خلفية حمراء فاتحة */
            color: #721c24; /* لون النص أحمر داكن */
            font-weight: bold; /* جعل النص سميك */
            border-radius: 5px; /* زوايا مستديرة قليلاً */
            padding: 5px;
        }
    </style>

@endsection
@section('title_page')
    صفحة الإشعارات
@endsection

@section('link_name_page_back')
@endsection

@section('title_link_name_page_back')
@endsection

@section('link_name_page')
@endsection

@section('title_link_name_page')

@endsection

@section('page-body')

    <!-- Notification table start -->
    <div class="card">
        <div class="card-header">
            <h5>كل الإشعارات</h5>
            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
                <i class="icofont icofont-refresh"></i>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card-block">
            <div class="table-responsive dt-responsive">
                <table id="lang-file" class="table table-striped table-bordered nowrap">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">إسم العميلة</th>
                        <th class="text-center">حجز</th>
                        <th class="text-center">الوقت</th>
                        <th class="text-center">الحالة</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($notifications) > 0)
                        @foreach($notifications as $notification)
                            @php
                                $order =  \App\Models\Order::with('user')->where('order_code',$notification['title'])->first();
                                $clientName = $order->user->name;
//                                    $message = 'العميله [ <span style="color:  #2980b9 ;">' . $clientName . '</span> ] تم حجز ' . $notification['message'];

                            @endphp
                            <tr class="{{ $notification['read'] ? '' : 'unread' }}"
                                data-notification-id="{{ $notification['id'] }}"
                                data-notification-type="{{ $notification['type'] }}"
                                data-url="{{ $notification['url'] }}">
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $clientName }}</td>

                                <td class="text-center">{{$notification['message']}}</td>
                                <td class="text-center">{{ $notification['created_at'] }}</td>
                                <td class="text-center {{ $notification['read'] ? 'notification-read' : 'notification-unread' }}">
                                    {{ $notification['read'] ? 'مقروء' : 'غير مقروء' }}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center">لا توجد إشعارات</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Notification table end -->

@endsection

@section('script_dashboard')
    <!-- Select 2 js -->
    <script type="text/javascript" src="{{asset('dashboard/assets/select2/js/select2.full.min.js')}}"></script>
    <!-- Multiselect js -->
    <script type="text/javascript"
            src="{{asset('dashboard/assets/bootstrap-multiselect/js/bootstrap-multiselect.js')}}">
    </script>
    <script type="text/javascript" src="{{asset('dashboard/assets/multiselect/js/jquery.multi-select.js')}}"></script>
    <script type="text/javascript" src="{{asset('dashboard/assets/js/jquery.quicksearch.js')}}"></script>

    <!-- data-table js -->
    <script src="{{ asset('dashboard/assets/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/pages/data-table/js/jszip.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/pages/data-table/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/pages/data-table/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('dashboard/assets/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script
        src="{{ asset('dashboard/assets/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/pages/data-table/js/data-table-custom.js') }}"></script>
    <!-- sweet alert js -->
    <script type="text/javascript" src="{{ asset('dashboard/assets/sweetalert/js/sweetalert.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/js/modal.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/js/modalEffects.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/js/classie.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.notification-item').on('click', function () {
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
        });
    </script>
@endsection
