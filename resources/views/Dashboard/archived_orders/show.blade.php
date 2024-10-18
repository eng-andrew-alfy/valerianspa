@extends('Dashboard.layouts.master')

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
        .order-details {
            padding: 10px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            background-color: #f9f9f9;
            text-align: right;
            margin-right: 20px; /* Space to the right of the order details */
            flex: 1; /* Allow it to grow and shrink as needed */
        }

        .order-separator {
            border: 0;
            height: 1px;
            background: #d0d0d0;
            margin-bottom: 10px;
        }

        .order-date {
            color: #6c757d; /* Gray color */
            font-size: 16px;
            margin: 5px 0;
        }

        .order-date span {
            color: #343a40; /* Darker text color for the values */
            font-weight: bold;
        }

        /* Ensure table takes full width of the div */
        .table {
            width: 100%;
            table-layout: fixed; /* Ensures that the table will fill the container's width */
        }

        /* Make sure table cells and headers fill the width */
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
            padding: 10px; /* Optional: Adjust padding as needed */
        }


    </style>
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
                                        <h2>{{$user->name}}</h2>
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
                <ul class="nav nav-tabs md-tabs tab-timeline" role="tablist" id="mytab">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#personal" role="tab">معلومات العميل</a>
                        <div class="slide"></div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#binfo" role="tab">الطلبات</a>
                        <div class="slide"></div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#contacts" role="tab">الجلسات</a>
                        <div class="slide"></div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#review" role="tab">التقيم</a>
                        <div class="slide"></div>
                    </li>
                </ul>
            </div>
            <!-- tab header end -->
            <!-- tab content start -->
            <div class="tab-content">
                <!-- tab pane personal start -->
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
                                                                <td>{{$user->name}}</td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">كود العميل</th>
                                                                <td>{{$user->code}}</td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">الجوال</th>
                                                                <td>{{$user->phone}}</td>
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
                                @foreach($archivedOrders as $order)
                                    <div class="col-md-6">
                                        <div class="card b-l-success business-info services m-b-20">
                                            <div class="card-header">
                                                <div class="service-header">
                                                    <div
                                                        class="d-flex justify-content-between align-items-center w-100">
                                                        <table class="table table-bordered w-100">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center">اسم الطلب</th>
                                                                <th class="text-center">كود الطلب</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td class="text-center">
                                                                    @if($order->data['order']['package_id'] != null)
                                                                        <h5 class="card-header-text">{{ $order->data['order']['package_id'] }}</h5>
                                                                    @else
                                                                        <h5 class="card-header-text">{{ $order->data['order']['category_id']}}</h5>
                                                                    @endif
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ $order->data['order']['order_code'] }}
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-block">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        @if($order->data['order']['package_id'] != null)
                                                            <p class="task-detail">{{ $order->data['order']['package_id']}}</p>
                                                        @else
                                                            <p class="task-detail">{{ $order->data['order']['category_id']}}</p>
                                                        @endif

                                                        @php
                                                            $date = Carbon\Carbon::parse($order->data['order']['created_at']);
                                                            $formattedDate = $date->locale('ar')->translatedFormat('d/m/Y');
                                                        @endphp
                                                        <div class="order-details">
                                                            <p class="order-date">
                                                                التاريخ: <span>{{ $formattedDate }}</span>
                                                            </p>
                                                            <p class="order-date">
                                                                السعر:
                                                                <span>{{ $order->data['order']['total_price'] }}</span>
                                                            </p>
                                                            <p class="order-date">
                                                                طريقة الدفع:
                                                                <span>{{ $order->data['order']['payment_gateway'] }}</span>
                                                            </p>
                                                            <p class="order-date">
                                                                الحالة:
                                                                {{--                                                                <span>{{ $order->data['sessions']['status'] }}</span>--}}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <!-- end of row -->
                        </div>
                    </div>
                    <!-- info card end -->
                </div>
                <!-- tab pane info end -->
                <!-- tab pane contacts start -->
                <div class="tab-pane" id="contacts" role="tabpanel">
                    <!-- contacts card start -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-header-text">الجلسات</h5>
                        </div>
                        <div class="card-block">
                            <div class="row">
                                @foreach($sessions as $session)
                                    <div class="col-md-6">
                                        <div class="card b-l-primary business-info services m-b-20">
                                            <div class="card-header">
                                                <div class="service-header">
                                                    <div
                                                        class="d-flex justify-content-between align-items-center w-100">
                                                        <table class="table table-bordered w-100">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center">اسم الجلسة</th>
                                                                <th class="text-center">كود الجلسة</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td class="text-center">
                                                                    {{--                                                                    {{ $session->id }}--}}
                                                                </td>
                                                                <td class="text-center">
                                                                    {{--                                                                    {{ $session->code }}--}}
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-block">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        {{--                                                        <p class="task-detail">{{ $session->description }}</p>--}}
                                                        @php
                                                            $date = Carbon\Carbon::parse($session['created_at']);
                                                            $formattedDate = $date->locale('ar')->translatedFormat('d/m/Y');
                                                        @endphp
                                                        <div class="order-details">
                                                            <p class="order-date">
                                                                التاريخ: <span>{{ $formattedDate }}</span>
                                                            </p>
                                                            <p class="order-date">
                                                                السعر: <span>800</span>
                                                                {{--                                                                السعر: <span>{{ $session['total_price'] }}</span>--}}
                                                            </p>
                                                            <p class="order-date">
                                                                الحالة: <span>{{ $session['status'] }}</span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <!-- end of row -->
                        </div>
                    </div>
                    <!-- contacts card end -->
                </div>
                <!-- tab pane contacts end -->
                <!-- tab pane review start -->
                <div class="tab-pane" id="review" role="tabpanel">
                    <!-- review card start -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-header-text">التقيمات</h5>
                        </div>
                        <div class="card-block">
                            <!-- Add review content here -->
                        </div>
                    </div>
                    <!-- review card end -->
                </div>
                <!-- tab pane review end -->
            </div>
            <!-- tab content end -->
        </div>
    </div>
@endsection

@section('script_dashboard')
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

@endsection
