@extends('Dashboard.layouts.master')

@section('css_dashboard')
    <!-- Data Table Css -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('dashboard/assets/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css"
          href="{{ asset('dashboard/assets/pages/data-table/css/buttons.dataTables.min.css') }}">
    {{--    <link rel="stylesheet" type="text/css" --}}
    {{--          href="{{ asset('dashboard/assets/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"> --}}
    <!-- animation nifty modal window effects css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/css/component.css') }}"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/sweetalert/css/sweetalert.css') }}">

    <style>
        .order-details {
            padding: 10px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            background-color: #f9f9f9;
            text-align: right;
            margin-right: 20px;
            /* Space to the right of the order details */
            flex: 1;
            /* Allow it to grow and shrink as needed */
        }

        .order-separator {
            border: 0;
            height: 1px;
            background: #d0d0d0;
            margin-bottom: 10px;
        }

        .order-date {
            color: #6c757d;
            /* Gray color */
            font-size: 16px;
            margin: 5px 0;
        }

        .order-date span {
            color: #343a40;
            /* Darker text color for the values */
            font-weight: bold;
        }

        /* Ensure table takes full width of the div */
        .table {
            width: 100%;
            table-layout: fixed;
            /* Ensures that the table will fill the container's width */
        }

        /* Make sure table cells and headers fill the width */
        .table th,
        .table td {
            text-align: center;
            vertical-align: middle;
            padding: 10px;
            /* Optional: Adjust padding as needed */
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn:disabled {
            cursor: not-allowed;
        }
    </style>

    <style>
        .btn-left {
            margin-bottom: 30px;
            /* Adding space below the button */
        }

        .pointer {
            cursor: pointer;
        }

        .new-addition {
            background-color: #d4edda;
            /* Light green background for new items */
            color: #155724;
            /* Dark green text color */
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

        .container {
            overflow: visible;
            /* لضمان عدم ظهور شريط التمرير */
            position: relative;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-button {
            background-color: #0073aa;
            color: white;
            padding: 7px 30px;
            border: 1px solid #007d96;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .dropdown-button:hover {
            background-color: #0073aa;
            border-color: #0073aa;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            border: 1px solid #007d96;
            border-radius: 5px;
            min-width: 160px;
            z-index: 1;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            top: 100%;
            /* لضبط موقع القائمة المنسدلة أسفل الزر */
            left: 0;
        }

        .dropdown-content a {
            color: #23282d;
            padding: 10px 20px;
            text-decoration: none;
            display: block;
            font-size: 14px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .dropdown-content a:hover {
            background-color: #0073aa;
            color: white;
        }

        .dropdown.show .dropdown-content {
            display: block;
        }

        .table-responsive {
            /* display:contents */
            overflow-x: visible
        }

        .select-refrash {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;

            background-color: #fff;
            border: 1px solid black;
            /* حدود العنصر باللون الأسود */
            padding: 10px;
            font-size: 16px;
            color: black;
            /* النص باللون الأسود */
            border-radius: 4px;
            cursor: pointer;
        }

        .select-refrash:focus {
            outline: none;
            border: 2px solid #0073aa;


        }

        .select-refrash option {
            background-color: white;
            /* اللون الافتراضي */
            color: black;
            /* لون النص الافتراضي */
        }


        .md-content {

            border-radius: 8px !important;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
    <!-- Date-time picker css -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('dashboard/assets/pages/advance-elements/css/bootstrap-datetimepicker.css') }}"/>
    <!-- Date-range picker css  -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('dashboard/assets/bootstrap-daterangepicker/css/daterangepicker.css') }}"/>
    <!-- Date-Dropper css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets//datedropper/css/datedropper.min.css') }}"/>
    <!-- animation nifty modal window effects css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/css/component.css') }}"/>
    <!-- Switch component css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/switchery/css/switchery.min.css') }}">
@endsection

@section('title_page')
    الطلبات
@endsection

@section('link_name_page_back')
@endsection

@section('title_link_name_page_back')
    الطلبات
@endsection

@section('link_name_page')
@endsection

@section('title_link_name_page')
    قائمة الطلبات
@endsection

@section('page-body')
    <!--profile cover start-->
    <div class="row">
        <div class="col-lg-12">
            <div class="cover-profile">
                <div class="profile-bg-img">
                    <img class="profile-bg-img img-fluid"
                         src="{{ asset('dashboard/assets/images/user-profile/bg-img1.jpg') }}" alt="bg-img">
                    <div class="card-block user-info">
                        <div class="col-md-12">
                            <div class="media-left">
                                <a href="#" class="profile-image">
                                    <img class="user-img img-circle"
                                         src="{{ asset('dashboard/assets/images/user-profile/user-img.jpg') }}"
                                         alt="user-img">
                                </a>
                            </div>
                            <div class="media-body row">
                                <div class="col-lg-12">
                                    <div class="user-title">
                                        <h2>{{ $user->name }}</h2>
                                    </div>
                                </div>
                                <div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--profile cover end-->
    <div class="row">
        <div class="col-lg-12">
            <!-- tab header start -->
            <div class="tab-header">
                <ul class="nav nav-tabs md-tabs tab-timeline" role="tablist" id="mytab"
                    style=" justify-content: center; align-items: center;">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#personal" role="tab">معلومات العميل</a>
                        <div class="slide"></div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#binfo" role="tab">الطلبات</a>
                        <div class="slide"></div>
                    </li>
                    {{--                    <li class="nav-item">--}}
                    {{--                        <a class="nav-link" data-toggle="tab" href="#contacts" role="tab">الجلسات</a>--}}
                    {{--                        <div class="slide"></div>--}}
                    {{--                    </li>--}}
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#review" role="tab">التقيم</a>
                        <div class="slide"></div>
                    </li>
                </ul>
            </div>
            <!-- tab header end -->
            <!-- tab content start -->
            <div class="tab-content">
                <!-- tab panel personal start -->
                <div class="tab-pane active" id="personal" role="tabpanel">
                    <!-- personal card start -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-header-text">عن العميل</h5>
                            <button id="edit-btn" type="button"
                                    class="btn btn-sm btn-primary waves-effect waves-light f-right">
                                <i class="icofont icofont-edit"></i>
                            </button>
                        </div>
                        <div class="card-block">
                            <div class="view-info">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="general-info">
                                            <div class="row">
                                                <div class="col-lg-12 col-xl-6">
                                                    <div class="table-responsive">
                                                        <table class="table m-0">
                                                            <tbody>
                                                            <tr>
                                                                <th scope="row">الأســم</th>
                                                                <td>{{ $user->name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">كود العميل</th>
                                                                <td>{{ $user->code }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">الجوال</th>
                                                                <td>{{ $user->phone }}</td>
                                                            </tr>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                            </div>
                                            <!-- end of row -->
                                        </div>
                                        <!-- end of general info -->
                                    </div>
                                    <!-- end of col-lg-12 -->
                                </div>
                                <!-- end of row -->
                            </div>
                            <!-- end of view-info -->
                            <div class="edit-info">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="general-info">
                                            <div class="row">
                                                <form id="customer-form"
                                                      action="{{route('admin.updateInformationCustomer')}}"
                                                      method="POST">
                                                    @csrf

                                                    <div class="col-lg-6">
                                                        <table class="table">
                                                            <tbody>
                                                            <tr>
                                                                <td>
                                                                    <div class="input-group">
                                                                    <span class="input-group-addon"><i
                                                                            class="icofont icofont-user"></i></span>
                                                                        <input type="text" class="form-control"
                                                                               placeholder="الأسم"
                                                                               value="{{ $user->name }}"
                                                                               name="Name_Customer">

                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <!-- end of table col-lg-6 -->
                                                    <div class="col-lg-6">
                                                        <table class="table">
                                                            <tbody>
                                                            <tr>
                                                                <td>
                                                                    <div class="input-group">
                                                                    <span class="input-group-addon"><i
                                                                            class="icofont icofont-mobile-phone"></i></span>
                                                                        <input type="text" class="form-control"
                                                                               placeholder="الجوال"
                                                                               value="{{ $user->phone }}"
                                                                               name="Phone_Customer">
                                                                        <input name="ID" type="hidden"
                                                                               value="{{$user->id}}">
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <!-- end of table col-lg-6 -->
                                                </form>

                                            </div>
                                            <!-- end of row -->
                                            <div class="text-center">
                                                <button type="submit"
                                                        class="btn btn-primary waves-effect waves-light m-r-20"
                                                        onclick="document.getElementById('customer-form').submit();">حفظ
                                                </button>
                                                {{--                                                <a href="#!"--}}
                                                {{--                                                   class="btn btn-primary waves-effect waves-light m-r-20">حفظ</a>--}}
                                                <a href="#!" id="edit-cancel"
                                                   class="btn btn-default waves-effect">الغاء</a>
                                            </div>
                                        </div>
                                        <!-- end of edit info -->
                                    </div>
                                    <!-- end of col-lg-12 -->
                                </div>
                                <!-- end of row -->
                            </div>
                            <!-- end of edit-info -->
                        </div>
                        <!-- end of card-block -->

                    </div>
                    <!-- personal card end-->
                </div>
                <!-- tab pane personal end -->
                <!-- tab pane info start -->
                <div class="tab-pane" id="binfo" role="tabpanel">
                    <!-- info card start -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-header-text">الطلبات</h5>
                        </div>
                        <div class="card-block">
                            <div class="row">

                                @php
                                    use Carbon\Carbon;
                                @endphp
                                <div class="data_table_main table-responsive dt-responsive">
                                    <table id="simpletable"
                                           class="table  table-striped table-bordered nowrap">
                                        <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">كود الطلب</th>
                                            <th class="text-center">إسم الطلب</th>
                                            <th class="text-center">تاريخ الطلب</th>
                                            <th class="text-center">الجلسات</th>
                                            <th class="text-center">العمليات</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($user->orders as $order)
                                            <tr data-order-id="{{ $order->id }}">
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-center"
                                                    style="font-size: x-small">{{ $order->order_code }}</td>
                                                <td class="text-center">
                                                    @if ($order->package_id != null && $order->package)
                                                        {{ $order->package->getTranslation('name', 'ar') }}
                                                    @elseif ($order->category)
                                                        {{ $order->category->getTranslation('name', 'ar') }}
                                                    @else
                                                        طلب غير محدد
                                                    @endif

                                                </td>

                                                <td class="text-center">

                                                                    <span
                                                                        style="background-color: #17a2b8;color: #fff;padding: 6px 12px;border-radius: 12px;font-size: 14px;font-weight: 600;display: inline-block;">
                                                                            {{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y') }}
                                                                        </span>
                                                </td>
                                                <td class="text-center date-cell">
                                                    @php
                                                        $sessions = $order->sessions; // الجلسات المرتبطة بالطلب الحالي
                                                        $allSessionsCompleted = $sessions->isNotEmpty() && $sessions->every(fn($session) => $session->status === 'completed');
                                                    @endphp

                                                    @if ($sessions->isNotEmpty())
                                                        @if ($allSessionsCompleted)
                                                            <span
                                                                style="background-color:#28a745;color: #fff;padding: 6px 12px;border-radius: 12px;font-size: 14px;font-weight: 600;display: inline-block;">
                 منتهية
            </span>
                                                        @else
                                                            <span
                                                                style="background-color: #b81717;color: #fff;padding: 6px 12px;border-radius: 12px;font-size: 14px;font-weight: 600;display: inline-block;">لم تنتهى
            </span>
                                                        @endif
                                                    @else
                                                        <span
                                                            style="background-color: #b81717;color: #fff;padding: 6px 12px;border-radius: 12px;font-size: 14px;font-weight: 600;display: inline-block;">
             لا توجد جلسات
        </span>
                                                    @endif

                                                </td>
                                                <td class="text-center">
                                                    {{--                                                    <a href="{{ route('admin.orders.edit', $order->order_code) }}"--}}
                                                    {{--                                                       class="btn btn-sm"--}}
                                                    {{--                                                       style="background-color: #17a2b8;color: whitesmoke">تعديل<i--}}
                                                    {{--                                                            class="fa fa-edit"></i>--}}
                                                    {{--                                                    </a>--}}
                                                    <a href="{{ route('admin.ShowSession', $order->order_code) }}"
                                                       class="btn btn-default btn-sm">عرض</a>
                                                    {{--                                                    <a href="{{ route('admin.ShowSession', $order->order_code) }}"--}}
                                                    {{--                                                       class="btn btn-sm"--}}
                                                    {{--                                                       style="background-color: #b81717;color: whitesmoke">حذف--}}
                                                    {{--                                                        <i class="icofont icofont-delete-alt"></i>--}}
                                                    {{--                                                    </a>--}}
                                                    <button
                                                        type="button"
                                                        class="btn btn-outline-danger alert-confirm m-b-10 pointer"
                                                        data-route="{{ route('admin.orderDestroy', $order->id) }}"
                                                        data-csrf-token="{{ csrf_token() }}"
                                                        data-name="{{ optional($order->category)->getTranslation('name', 'ar') ?? optional($order->package)->getTranslation('name', 'ar') }}"
                                                    >
                                                        <i class="icofont icofont-delete-alt"></i>
                                                    </button>

                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>

                                    </table>
                                </div>


                            </div>
                        </div>
                    </div>
                    <!-- info card end -->
                </div>
                <!-- tab pane info end -->
                <!-- tab pane contact start -->
                {{--                <div class="tab-pane" id="contacts" role="tabpanel">--}}
                {{--                    <div class="row">--}}
                {{--                        <div class="col-lg-3">--}}
                {{--                            <!-- user contact card left side start -->--}}
                {{--                            <div class="card">--}}
                {{--                                <div class="card-header contact-user">--}}

                {{--                                    <h4> المعلومات </h4>--}}
                {{--                                </div>--}}
                {{--                                <div class="card-block groups-contact">--}}

                {{--                                    <ul class="list-group" style="text-align: center">--}}
                {{--                                        <li class="list-group-item justify-content-between">--}}
                {{--                                            الطلبات--}}
                {{--                                            <span--}}
                {{--                                                class="badge badge-default badge-pill">{{ $user->orders->count() }}</span>--}}
                {{--                                        </li>--}}
                {{--                                        <li class="list-group-item justify-content-between">--}}
                {{--                                            الجلسات--}}
                {{--                                            <span class="badge badge-default badge-pill">{{ $totalSessions }}</span>--}}
                {{--                                        </li>--}}
                {{--                                        <li class="list-group-item d-flex justify-content-between align-items-center"--}}
                {{--                                            style="background-color: #eafaf1; border-left: 5px solid #28a745;">--}}
                {{--                                            <i class="ion-checkmark-circled"--}}
                {{--                                               style="color: #28a745; margin-right: 10px;"></i>--}}
                {{--                                            <strong>الجلسات الكاملة</strong>--}}
                {{--                                            <span class="badge badge-success badge-pill">{{ $completedSessions }}</span>--}}
                {{--                                        </li>--}}

                {{--                                        <li class="list-group-item d-flex justify-content-between align-items-center"--}}
                {{--                                            style="background-color: #faefef; border-left: 5px solid #dc3545;">--}}
                {{--                                            <i class="ion-close-circled"--}}
                {{--                                               style="color: #dc3545; margin-right: 10px;"></i>--}}
                {{--                                            <strong>الجلسات الملغاة</strong>--}}
                {{--                                            <span class="badge badge-danger badge-pill">{{ $canceledSessions }}</span>--}}
                {{--                                        </li>--}}

                {{--                                        <li class="list-group-item d-flex justify-content-between align-items-center"--}}
                {{--                                            style="background-color: #fef9e7; border-left: 5px solid #ffc107;">--}}
                {{--                                            <i class="ion-clock" style="color: #ffc107; margin-right: 10px;"></i>--}}
                {{--                                            <strong>الجلسات المعلقة</strong>--}}
                {{--                                            <span class="badge badge-warning badge-pill">{{ $pendingSessions }}</span>--}}
                {{--                                        </li>--}}

                {{--                                        <li class="list-group-item d-flex justify-content-between align-items-center"--}}
                {{--                                            style="background-color: #e9f7ff; border-left: 5px solid #007bff;">--}}
                {{--                                            <i class="ion-calendar" style="color: #007bff; margin-right: 10px;"></i>--}}
                {{--                                            <strong>الجلسات المتبقية</strong>--}}
                {{--                                            <span class="badge badge-primary badge-pill">{{ $remainingSessions }}</span>--}}
                {{--                                        </li>--}}
                {{--                                        @if ($order->package_id != null)--}}
                {{--                                            <li class="list-group-item justify-content-between">--}}
                {{--                                                الباقة--}}
                {{--                                                <span--}}
                {{--                                                    class="badge badge-default badge-pill">{{ $order->package->count() }}</span>--}}
                {{--                                            </li>--}}
                {{--                                        @else--}}
                {{--                                            <li class="list-group-item justify-content-between">--}}
                {{--                                                الخدمة--}}
                {{--                                                <span--}}
                {{--                                                    class="badge badge-default badge-pill">{{ $order->category->count() }}</span>--}}
                {{--                                            </li>--}}
                {{--                                        @endif--}}
                {{--                                    </ul>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                            <div class="card">--}}
                {{--                                <div class="card-header">--}}
                {{--                                    <h4 class="card-title">الموظفين</h4>--}}
                {{--                                </div>--}}
                {{--                                <div class="card-block">--}}
                {{--                                    <div class="connection-list">--}}
                {{--                                        <h5 class="card-header-text">{{ $order->employee->getTranslation('name', 'ar') }}--}}
                {{--                                        </h5>--}}

                {{--                                    </div>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                            <!-- user contact card left side end -->--}}
                {{--                        </div>--}}
                {{--                        <div class="col-lg-9">--}}
                {{--                            <div class="row">--}}
                {{--                                <div class="col-sm-12">--}}
                {{--                                    <!-- contact data table card start -->--}}
                {{--                                    <div class="card">--}}
                {{--                                        <div class="card-header">--}}
                {{--                                            <h5 class="card-header-text">الجلسات</h5>--}}
                {{--                                        </div>--}}
                {{--                                        <div class="card-block contact-details">--}}
                {{--                                            <div class="data_table_main table-responsive dt-responsive">--}}
                {{--                                                <table id="simpletable"--}}
                {{--                                                       class="table  table-striped table-bordered nowrap">--}}
                {{--                                                    <thead>--}}
                {{--                                                    <tr>--}}
                {{--                                                        <th class="text-center">#</th>--}}
                {{--                                                        <th class="text-center">إسم الجلسة</th>--}}
                {{--                                                        <th class="text-center">التاريخ</th>--}}
                {{--                                                        <th class="text-center">الحالة</th>--}}
                {{--                                                        <th class="text-center">الملاحظات</th>--}}
                {{--                                                        <th class="text-center">العمليات</th>--}}
                {{--                                                    </tr>--}}
                {{--                                                    </thead>--}}
                {{--                                                    <tbody>--}}
                {{--                                                    @foreach ($sessions as $session)--}}
                {{--                                                        <tr data-session-id="{{ $session->id }}">--}}

                {{--                                                            <td class="text-center">{{ $loop->iteration }}</td>--}}
                {{--                                                            @if ($order->package_id != null)--}}
                {{--                                                                <td class="text-center">--}}
                {{--                                                                    {{ $order->package->getTranslation('name', 'ar') }}--}}
                {{--                                                                </td>--}}
                {{--                                                            @else--}}
                {{--                                                                <td class="text-center">--}}
                {{--                                                                    {{ $order->category->getTranslation('name', 'ar') }}--}}
                {{--                                                                </td>--}}
                {{--                                                            @endif--}}
                {{--                                                            <td class="text-center date-cell">--}}
                {{--                                                                @if ($session->session_date != null)--}}
                {{--                                                                    <span--}}
                {{--                                                                        style="background-color: #17a2b8;color: #fff;padding: 6px 12px;border-radius: 12px;font-size: 14px;font-weight: 600;display: inline-block;">--}}
                {{--                                                                            {{ \Carbon\Carbon::parse($session->session_date)->format('d-m-Y') }}--}}
                {{--                                                                        </span>--}}
                {{--                                                                @else--}}
                {{--                                                                    <span--}}
                {{--                                                                        style="background-color: #b81717;color: #fff;padding: 6px 12px;border-radius: 12px;font-size: 14px;font-weight: 600;display: inline-block;">--}}
                {{--                                                                            لم يتم تحديد تاريخ--}}
                {{--                                                                @endif--}}
                {{--                                                            </td>--}}
                {{--                                                            <td class="text-center status-cell">--}}
                {{--                                                                @if ($session->status == 'completed')--}}
                {{--                                                                    <span class="badge badge-success">مكتملة</span>--}}
                {{--                                                                @elseif($session->status == 'canceled')--}}
                {{--                                                                    <span class="badge badge-danger">ملغاة</span>--}}
                {{--                                                                @elseif($session->status == 'pending')--}}
                {{--                                                                    <span class="badge badge-primary">متبقية</span>--}}
                {{--                                                            @endif--}}
                {{--                                                            <td class="text-center notes-cell">--}}
                {{--                                                                {{ \Illuminate\Support\Str::limit($session->notes, 15, '...') }}--}}
                {{--                                                            </td>--}}
                {{--                                                            <td class="text-center">--}}
                {{--                                                                <div class="dropdown">--}}
                {{--                                                                    <button class="dropdown-button">--}}
                {{--                                                                        <i class="bi bi-pencil-square"></i>--}}
                {{--                                                                    </button>--}}
                {{--                                                                    <div class="dropdown-content">--}}
                {{--                                                                        <a href="#" class="session-action-link"--}}
                {{--                                                                           data-modal="modal-3"--}}
                {{--                                                                           data-order-id="{{ $session->id }}"--}}
                {{--                                                                           data-action="update-{{ $session->id }}">اختيار--}}
                {{--                                                                            وقت الجلسة ⏰</a>--}}

                {{--                                                                        <a href="#" class="session-action-link"--}}
                {{--                                                                           data-order-id="{{ $session->id }}"--}}
                {{--                                                                           data-action="end">انهاء الجلسة</a>--}}
                {{--                                                                        <a href="#" class="session-action-link"--}}
                {{--                                                                           data-order-id="{{ $session->id }}"--}}
                {{--                                                                           data-action="cancel">الغاء الجلسة 🚫</a>--}}

                {{--                                                                        <a href="#" class="session-action-link"--}}
                {{--                                                                           data-order-id="{{ $session->id }}"--}}
                {{--                                                                           data-action="opened">فتح--}}
                {{--                                                                            الجلسة 🔓--}}
                {{--                                                                        </a>--}}
                {{--                                                                    </div>--}}

                {{--                                                                </div>--}}
                {{--                                                            </td>--}}
                {{--                                                        </tr>--}}
                {{--                                                    @endforeach--}}

                {{--                                                    </tbody>--}}

                {{--                                                </table>--}}
                {{--                                            </div>--}}
                {{--                                        </div>--}}
                {{--                                    </div>--}}
                {{--                                    <!-- contact data table card end -->--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                <!-- tab pane contact end -->
                {{--                <div class="tab-pane" id="review" role="tabpanel"> --}}
                {{--                    <div class="card"> --}}
                {{--                        <div class="card-header"> --}}
                {{--                            <h5 class="card-header-text">تقييم</h5> --}}
                {{--                        </div> --}}
                {{--                        <div class="card-block"> --}}
                {{--                            <ul class="media-list"> --}}
                {{--                                <li class="media"> --}}
                {{--                                    <div class="media-left"> --}}
                {{--                                        <h4 class="center">سوف تعمل قريباً</h4> --}}
                {{--                                    </div> --}}
                {{--                                </li> --}}
                {{--                            </ul> --}}
                {{--                        </div> --}}
                {{--                    </div> --}}
                {{--                </div> --}}
            </div>
            <!-- tab content end -->
        </div>
    </div>
    {{--    </div> --}}
    <!-- Language File table end -->
    <!-- Modal Structure -->
    <div class="md-modal md-effect-3" id="modal-3">
        <div class="md-content container">
            <header class="modal-header">
                <h4 class="modal-title">تحديث تاريخ الجلسة</h4>
                {{-- <button class="close md-close" aria-label="Close">
                    &times;
                </button> --}}
            </header>
            <div class="modal-body">
                <form action="" method="POST" id="update-session-form" novalidate>
                    @csrf <!-- Include CSRF token for security -->

                    <!-- تاريخ الحجز -->
                    <div class="form-group">
                        <label for="date"><code>*</code> تاريخ الحجز</label>
                        <input type="text" name="date" class="form-control" id="dropper-max-year"
                               readonly="readonly"/>
                    </div>
                    <!-- تغيير الموظف -->
                    <div class="form-group">
                        <label for="changeEmployee "><code>*</code>
                            تغيير الموظف</label>
                        <select name="change_employee" class="col-sm-12 select-refrash" id="changeEmployee" required>
                            <option value="no">لا</option>
                            <option value="yes">نعم</option>
                        </select>
                    </div>
                    <!-- الوقت المتاح -->
                    <div class="form-group">
                        <label for="timeAvailable"><code>*</code>
                            الوقت المتاح</label>
                        <select name="time_available" class="col-sm-12 select-refrash" id="timeAvailable" required>
                            <option value="">اختر وقت</option>
                            <!-- Options will be populated dynamically -->
                        </select>
                    </div>
                    <!-- الموظفين المتاحين -->
                    <div class="form-group">
                        <label for="employeeAvailable"><code>*</code>
                            الموظفين المتاحين</label>
                        <select name="employee_available" class="col-sm-12 select-refrash" id="employeeAvailable"
                                required>
                            <option value="">اختر موظف</option>
                            <!-- Options will be populated dynamically -->
                        </select>
                        @error('employee_available')
                        <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- أزرار الحفظ والإلغاء -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">حفظ
                        </button>
                        <button type="button" class="btn btn-secondary md-close">
                            إلغاء
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

        <?php
        $location = json_decode($order->location, true);
        ?>
@endsection

@section('script_dashboard')
    {{--    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>--}}


    <script>
        //======================================= START RESPONSIBLE FOR THE DELETION PROCESS AND CONFIRMATION OF DELETION ======================================= \\

        document.querySelectorAll('.alert-confirm').forEach(button => {
            button.onclick = function () {
                const route = this.getAttribute('data-route');
                const csrfToken = this.getAttribute('data-csrf-token');
                const serviceName = this.getAttribute('data-name');
                const row = this.closest('tr'); // Get the closest row element

                // Display confirmation dialog
                swal({
                    title: "هل أنت متأكد؟",
                    text: `لن تتمكن من استرجاع الطلب الذى يحمل اسم:<br><strong style="color: red; font-weight: bold;">"${serviceName}"</strong>!`,
                    type: "warning",
                    html: true, // Enable HTML content
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "نعم، احذفه!",
                    cancelButtonText: "إلغاء",
                    closeOnConfirm: false
                }, function () {
                    // Perform the DELETE request
                    fetch(route, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json'
                        }
                    })
                        .then(response => {
                            console.log('Raw response:', response); // Log raw response
                            return response.json();
                        })
                        .then(data => {
                            console.log('Parsed data:', data); // Log parsed data
                            if (data.success) {
                                swal({
                                    title: "تم الحذف!",
                                    text: `تم حذف الطلب الذى يحمل اسم:<br><strong style="color: red; font-weight: bold;">"${serviceName}"</strong> بنجاح.`,
                                    type: "success",
                                    html: true // Enable HTML content
                                });
                                row.remove(); // Remove the row from the table
                            } else {
                                swal({
                                    title: "فشل!",
                                    text: `فشل حذف الطلب الذى يحمل اسم:<br><strong style="color: red; font-weight: bold;">"${serviceName}"</strong>.`,
                                    type: "error",
                                    html: true // Enable HTML content
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            swal({
                                title: "خطأ!",
                                text: `حدث خطأ أثناء حذف الطلب الذى يحمل اسم:<br><strong style="color: red; font-weight: bold;">"${serviceName}"</strong>.`,
                                type: "error",
                                html: true // Enable HTML content
                            });
                        });
                });
            };
        });
        //======================================= END RESPONSIBLE FOR THE DELETION PROCESS AND CONFIRMATION OF DELETION ======================================= \\

        document.querySelectorAll('.session-action-link').forEach(link => {
            link.addEventListener('click', function (event) {
                event.preventDefault();
                const sessionId = this.getAttribute('data-order-id');
                const formAction = `{{ route('admin.dateSession', 'PLACEHOLDER') }}`.replace('PLACEHOLDER',
                    sessionId);
                const form = document.getElementById('update-session-form');
                form.action = formAction;
                const modalId = this.getAttribute('data-modal');
                document.getElementById(modalId).style.display = 'block';
            });
        });
    </script>






    <!-- Select 2 js -->
    <script type="text/javascript" src="{{ asset('dashboard/assets/select2/js/select2.full.min.js') }}"></script>
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
    <!-- sweet alert js -->
    <script type="text/javascript" src="{{ asset('dashboard/assets/sweetalert/js/sweetalert.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/js/modal.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/js/modalEffects.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/js/classie.js') }}"></script>
    <script src="{{ asset('dashboard/assets/pages/user-profile.js') }}"></script>
    <!-- Bootstrap date-time-picker js -->
    <script type="text/javascript"
            src="{{ asset('dashboard/assets/pages/advance-elements/moment-with-locales.min.js') }}">
    </script>
    <script type="text/javascript"
            src="{{ asset('dashboard/assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ asset('dashboard/assets/pages/advance-elements/bootstrap-datetimepicker.min.js') }}"></script>
    <!-- Date-range picker js -->
    <script type="text/javascript"
            src="{{ asset('dashboard/assets/bootstrap-daterangepicker/js/daterangepicker.js') }}">
    </script>
    <!-- Date-dropper js -->
    <script type="text/javascript" src="{{ asset('dashboard/assets/datedropper/js/datedropper.min.js') }}"></script>

    <!-- Custom js -->
    <script type="text/javascript"
            src="{{ asset('dashboard/assets/pages/advance-elements/custom-picker.js') }}"></script>

    <!-- Max-length js -->
    <script type="text/javascript" src="{{ asset('dashboard/assets/bootstrap-maxlength/js/bootstrap-maxlength.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/pages/advance-elements/swithces.js') }}"></script>
    <script>
        // التفاعل مع الزر لعرض القائمة المنسدلة
        document.querySelectorAll('.dropdown-button').forEach(button => {
            button.addEventListener('click', function () {
                this.parentElement.classList.toggle('show');
            });
        });

        // إغلاق القائمة عند النقر في أي مكان آخر في الصفحة
        window.onclick = function (event) {
            if (!event.target.matches('.dropdown-button') && !event.target.matches('.dropdown-button i')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.parentElement.classList.contains('show')) {
                        openDropdown.parentElement.classList.remove('show');
                    }
                }
            }
        }
    </script>
    {{--    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>--}}

@endsection
