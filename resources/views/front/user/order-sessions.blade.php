@extends('front.layouts.app')


@section('title', __('nav.title', ['title' => __('general.orders')]))

@section('head-front')
    <link rel="stylesheet" href="{{ asset('front/css/modals.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/css/component.css') }}" />

    <link rel="stylesheet" href="{{ asset('front/css/date-bicker.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/cart.css') }}">
    <style>
        .ms-modal-content {
            background-image: url('{{ asset('front/images/502.jpg') }}');
            background-size: cover;
            background-position: center;
            padding: 20px;
            /* إضافة مساحة داخلية إذا لزم الأمر */
            border-radius: 8px;
            /* إذا كنت تريد زوايا دائرية */
        }
    </style>
    <style>
        .container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            align-items: center;
        }

        .section-title {
            text-align: center;
            padding: 20px;
            padding-bottom: 0;

        }

        .table-container {
            width: 100%;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            max-width: 1350px;
        }

        .table-container table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #e0e0e0;
            display: none;
        }

        .table-container table th,
        .table-container table td {
            padding: 15px 10px;
            text-align: center;
            border: 1px solid #e0e0e0;
            border-left: 0;
            border-right: 0;
        }

        .table-container table th {
            background-color: #f7f7f7;
            font-weight: bold;
            font-size: 14px;
        }


        .table-container .table-cards {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .table-container .table-cards .card {
            width: 100%;
            background-color: #f7f7f7;
            border-radius: 10px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .table-container .table-cards .card-header,
        .table-container .table-cards .card-body {
            width: 100%;
            display: flex;
            flex-direction: column;
            min-width: max-content;
        }

        .table-container .table-cards .card-header .title,
        .table-container .table-cards .card-body .info {
            font-size: 14px;
            height: 40px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            padding: 0 10px;
            width: 100%;
        }

        .table-container .table-cards .card-header .title:last-child,
        .table-container .table-cards .card-body .info:last-child {
            border-bottom: none;
        }

        .table-container .table-cards .card-header .title {
            font-weight: bold;
        }

        .card-row {
            display: flex;
            flex-direction: row;
        }

        .card-footer {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            /* border-top: 1px solid #e0e0e0;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        padding-top: 20px; */
        }

        .card-footer a {
            text-decoration: none;
            color: #136e81;
            font-weight: bold;
            font-size: 14px;
            display: flex;
            align-items: center;
            /* gap: 5px; */
        }


        @media screen and (min-width: 768px) {
            .table-container table {
                display: table;
            }

            .table-container .table-cards {
                display: none;
            }
        }

        .status-pending {
            color: #ff9800;
            font-weight: bold;
        }

        .status-completed {
            color: #4caf50;
            font-weight: bold;
        }

        .status-canceled {
            color: #f44336;
            font-weight: bold;
        }

        /* Style the dropdown button */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-button {
            background-color: #007d96;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            font-size: 18px;
        }

        .dropdown-button:hover {
            background-color: rgba(10, 106, 161, 0.73);
        }

        /* Dropdown content (hidden by default) */
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown-content .dropdown-item {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content .dropdown-item:hover {
            background-color: #ddd;
        }

        /* Show the dropdown content when the button is hovered over */
        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* CSS لمودال */
        .ms-modal {
            display: none;
            /* إخفاء المودال افتراضيًا */
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            /* خلفية مظلمة */

        }

        .ms-modal-content {
            background-color: #fefefe;
            /* margin: 15% auto; */
            padding: 20px;
            border: 1px solid #888;
            width: 80%;

        }

        .ms-modal .ms-modal-content {
            height: 80%;
            border-radius: 10px
        }

        .ms-modal-header,
        .ms-modal-footer {
            padding: 10px;
            text-align: right;

        }

        .ms-modal .ms-modal-header {
            padding: 0px !important;
            margin: auto
        }

        .ms-modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
        }


        /* ------------- Phone Drop ----------------- */
        /* القاعدة الأساسية - على الكمبيوتر والشاشات الكبيرة لا يتغير شيء */
        /* .dropdown-button {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            display: inline-block;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            background-color: transparent;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            border: none;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            cursor: pointer;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            padding: 0;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            background-color: #007d96;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        } */

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        /* media query: لتطبيق القواعد فقط على الهواتف */
        @media only screen and (max-width: 600px) {
            .dropdown-content {
                position: static;
                /* عرض القائمة المنسدلة بشكل ثابت على الموبايل */
            }


            .dropdown-button {
                display: block;
                /* ضمان عرض الزر بشكل صحيح على الموبايل */
                width: 100%;
                /* تغطية العرض بالكامل على الموبايل */
            }

            .dropdown-button i {
                transition: none;
                /* منع أي تحريك للأيقونة عند النقر */
            }

            /* على الموبايل، عند النقر على الزر، تعرض القائمة */
            .dropdown-button:focus+.dropdown-content,
            .dropdown-button:active+.dropdown-content {
                display: block;
            }
        }

        .dropdown-content {
            display: none;
            /* إخفاء القائمة افتراضيًا */
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1000;
            background: white;
            border: 1px solid #ddd;
        }


        /* إخفاء الزر بشكل افتراضي */
        .circular-btn {
            display: none;
        }

        /* عرض الزر فقط على الشاشات الصغيرة (مثل الهواتف المحمولة) */
        @media (max-width: 768px) {
            .circular-btn {
                display: inline-block;
                background-color: transparent;
                border: 3px solid #007d96;

                border-radius: 50%;
                width: 25px;
                height: 25px;

                cursor: pointer;
                transition: all 0.3s ease;
                font-size: 0;
                margin-right: 60%
                    /* إخفاء النص الداخلي إن وجد */
            }

            .circular-btn:hover {
                background-color: #3498db;
                color: white;
            }

            .table-container .table-cards .card-header .title,
            .table-container .table-cards .card-body .info {
                color: #007d96;
                font-size: 18px;
                font-family: 'Almarai', sans-serif;

            }
        }

        .unique-datepicker-input {
            background-color: #ffffff !important;
            width: 100%;
        }

        .ms-modal-body .ms-map-selected input {
            width: 130% !important;
            height: 40px !important;
            font-weight: bold
        }

        .ms-modal-body .ms-map-selected {
            gap: 0px !important
        }

        .unique-select {
            width: 90%;
            background-color: #ffffff;

        }

        .ms-modal-body .ms-map-selected div {
            font-size: 18px
        }

        .ms-modal .ms-modal-footer {
            border-top: 0px;
            padding: 0 !important;
            margin-bottom: 300px
        }
    </style>
    <style>
        .md-modal {
            z-index: 1000 !important;

        }


        .locations-wrapper {
            display: flex;
            gap: 20px;

        }

        .location-container {
            display: flex;
            border: none !important;
            background-color: transparent !important;
            gap: 0px
        }


        .add-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr) !important;

            gap: 7px;


        }


        @media (max-width: 768px) {
            .add-container {
                grid-template-columns: 3fr !important;

            }
        }

        .padd {
            padding: 45px 80px !important;

        }

        @media (max-width: 768px) {
            .padd {
                padding: 45px 9px !important;
            }
        }

        .map-cont {
            border: 2px solid #136e81;
            border-radius: 10px;
            box-shadow: 0 4px 10px #136f812d, 0 10px 20px #136f812d;

        }


        .location-container {
            background-color: #ffffff;
            border-radius: 10px;
            position: relative;
            /* width: 300px; */
            display: flex;
            height: 100px;
            padding: 0px !important
        }

        /* Kero */
        .ms-modal-body {
            display: flex !important;
            justify-content: flex-start !important;
        }

        .ms-modal .ms-modal-body .map-container {
            padding: 0 20px;
            align-items: start;
            gap: 5px;
        }

        .header {
            background-color: #006d76;
            /* لون الشريط الأخضر */
            padding: 5px 10px;
            border-radius: 10px 10px 0 0;
            /* display: flex; */
            align-items: center;
            justify-content: space-between;
            color: #fff;
            font-size: 16px;
            position: absolute;
            top: -23px;
            /* موقع الشريط بالنسبة للـ container */
            left: 0;
            right: 0;
            height: 35px;
            z-index: 1;
            /* جعل الشريط يظهر فوق العناصر الأخرى */
            width: 100%;


        }

        .circle {
            width: 30px;
            height: 30px;
            background-color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #006d76;
            position: absolute;
            right: -15px;
            top: -15px;
            border: 2px solid #006d76;
            /* حدود للدائرة */
            z-index: 2;
            /* جعل الدائرة تظهر فوق الشريط */
        }

        .header-text {

            font-weight: bold;
            margin-right: -10px
        }

        .flex-auto {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;

        }

        .font-18 {
            font-size: 18px;

        }

        .font-12 {
            font-size: 12px;

        }

        .btn-unset {
            background: none;
            border: none;
            cursor: pointer;

        }

        .and-item {
            display: flex;
            color: #fff;
            border: none;
            margin: -30px;

        }

        @media (max-width: 768px) {
            .location-container {
                width: 600px !important;

            }
        }

        .location-container .location-desc {
            overflow: visible;
        }

        .ms-modal-body .ms-map-selected div {
            font-size: 14px !important
        }

        .location-2 {
            display: flex;
            background-color: #ffffff;
            align-items: center;
            border: 1px solid #136e81;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            width: 300px;
            /* text-align: center !important; */
            justify-content: center
        }

        .btn-unset {
            font-size: 16px;
            font-weight: bold;
            background: none;
            border: none;
            padding: 0;
            margin: 0;
            cursor: pointer;
        }
    </style>

@endsection

@section('content-front')
    @php
        $locale = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale();

    @endphp
    <div class="container">
        @if ($orders->package_id != null)
            <div class="section-title">{{ $orders->package->getTranslation('name', $locale) }}</div>
        @else
            <div class="section-title">{{ $orders->category->getTranslation('name', $locale) }}</div>
        @endif
        {{--        <div class="section-title">{{ __('general.orders') }}</div> --}}
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>{{ __('user.sessions') }}</th>
                        <th>{{ __('user.date') }}</th>
                        <th>{{ __('user.time') }}</th>
                        <th>{{ __('user.status') }}</th>
                        <th>{{ __('user.notes') }}</th>
                        <th>{{ __('user.edit') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ordersWithSessions as $order)
                        @foreach ($order->sessions as $session)
                            <tr>
                                <td>&nbsp;<strong>
                                        {{ __('user.session') }} &nbsp; {{ $loop->iteration }}</strong></td>
                                @if ($session->session_date != null)
                                    <td>{{ \Carbon\Carbon::parse($session->session_date)->format('Y-m-d') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($session->session_date)->format('h:i A') }}</td>
                                @else
                                    <td>{{ __('user.no_things') }}</td>
                                    <td>{{ __('user.no_things') }}</td>
                                @endif
                                <td
                                    class="{{ $session->status === 'pending'
                                        ? 'status-pending'
                                        : ($session->status === 'completed'
                                            ? 'status-completed'
                                            : 'status-canceled') }}">
                                    {{ ucfirst($session->status) }}
                                </td>
                                <td>{{ $session->notes }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="dropdown-button">
                                            <i class="fas fa-cogs"></i>
                                        </button>
                                        <div class="dropdown-content">

                                            @if ($session->session_date != null)
                                                @if ($session->status == 'completed')
                                                    <span class="dropdown-item"
                                                        style="color: #0F9E5E">{{ __('user.completed_session_message') }}</span>
                                                @endif
                                                @if ($session->status != 'canceled' && $session->status != 'completed')
                                                    <a href="#" class="dropdown-item" id="cancelOrder"
                                                        data-sessions-id="{{ $session->id }}">{{ __('user.cancel_booking') }}</a>
                                                @elseif($session->status == 'canceled')
                                                    <span class="dropdown-item"
                                                        style="color: red">{{ __('user.booking_cancelled') }}</span>
                                                @endif

                                                @if ($session->status == 'canceled' || $session->session_date > \Carbon\Carbon::now()->subDays(2))
                                                    <a href="{{ route('showUpdateSessions', $session->id) }}"
                                                        class="dropdown-item">{{ __('user.reschedule_appointment') }}</a>
                                                @endif
                                            @else
                                                <a href="{{ route('showUpdateSessions', $session->id) }}"
                                                    class="dropdown-item">{{ __('user.select_appointment') }}</a>
                                                {{--                                            <a href="#" class="dropdown-item" data-modal="#date-modal" --}}
                                                {{--                                               data-session-id="{{ $session->id }}">{{ __('user.select_appointment') }}</a> --}}
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach

                </tbody>
            </table>

            {{-- Start Tabel Mobile --}}
            <div class="table-cards">
                @foreach ($ordersWithSessions as $order)
                    @foreach ($order->sessions as $session)
                        <div class="card">
                            <div class="card-row">
                                <div class="card-header">
                                    <div class="title">
                                        &nbsp;<strong>
                                            {{ __('user.session') }} &nbsp; {{ $loop->iteration }}</strong></div>

                                </div>

                                <div class="card-body">
                                    <div class="card-footer">
                                        <button class="circular-btn"
                                            onclick="window.location.href='{{ route('showUpdateSessions', $session->id) }}'"></button>

                                    </div>
                                </div>

                            </div>
                    @endforeach
                @endforeach


            </div>
            {{-- End Tabel Mobile --}}
        </div>

    </div>
    </div>
    <!-- Date modal -->
    <div class="ms-modal" id="date-modal">
        <div class="ms-modal-content">
            {{-- Start Titel Modal --}}
            <div class="ms-modal-header">
                <div class="ms-modal-title">تحديد موعد الجلسة</div>
            </div>
            {{-- End Titel Modal --}}
            <div class="ms-modal-body">
                <div class="map-container">
                    {{-- Start Title Sections  --}}
                    <div>
                        <span style="text-align: center" class="header-text">{{ __('cart.Location') }}</span>
                    </div>
                    {{-- End Title Sections  --}}
                    {{-- Start Select Map  --}}
                    <div class="ms-map-selected">
                        <div style="margin: -20px -30px -19px 0;" class="location-container ">
                            <div class="d-col-flex ">
                                <div class="location-2" id="location-input-div">
                                    <button class="btn-unset color-6 font-18 waves-effect md-trigger" type="button"
                                        data-modal="modal-map" id="edit-location-btn">
                                        <div
                                            style="display: flex; align-items: center; justify-content: space-between; width: 100%; font-size:14px !important">

                                            <div class="font-bold font-12 location-desc" id="location-name"
                                                style="white-space: nowrap;">
                                                {{ __('cart.your_location') }}
                                            </div>
                                        </div>
                                    </button>
                                </div>
                                @error('latitude')
                                    <div style="color: red;font-weight: bold">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                            </div>
                        </div>
                    </div>
                    {{-- End Select Map  --}}

                    {{-- Start Title Sections  --}}
                    <div>
                        <span class="header-text">{{ __('cart.date') }}</span>
                    </div>
                    {{-- End Title Sections  --}}

                    {{-- Start Select Date  --}}
                    <div style="align-items: flex-start ; text-align:right !important" class="and-item">
                        <div class="location-container">
                            <div style="width: 300px">
                                <div class="font-12">
                                    <div class="unique-datepicker-container">
                                        <input style="text-align: inherit;direction: inherit;" type="button"class="unique-datepicker-input" readonly id="date-input">
                                        <input type="hidden" id="hidden-date-input" name="formattedDate">
                                        @error('formattedDate')
                                            <div style="color: red;font-weight: bold">{{ $message }}</div>
                                        @enderror
                                        <div class="unique-datepicker-calendar">
                                            <div class="unique-datepicker-header">
                                                <button type="button" class="unique-datepicker-prev">&lt;</button>
                                                <span
                                                    class="unique-datepicker-month-year">{{ \Carbon\Carbon::now()->format('F Y') }}</span>
                                                <button type="button" class="unique-datepicker-next">&gt;</button>
                                            </div>
                                            <div class="unique-datepicker-days">
                                                <!-- Days will be populated here by JavaScript -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Start Select Date  --}}


                    {{-- Start Time --}}
                    <div>
                        <span class="header-text">{{ __('cart.time') }}</span>
                    </div>
                    <div class="and-item">
                        <div class="location-container">

                            <div style="width: 331px" class="d-col-flex">
                                <select class="unique-select" name="timeAvailable" id="time-select" readonly disabled>
                                    <option selected disabled>{{ __('cart.select_time') }}</option>
                                </select>
                                @error('timeAvailable')
                                    <div style="color: red;font-weight: bold">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    {{-- End Time --}}


                    {{-- Start employee --}}
                    <div>
                        <span class="header-text">{{ __('cart.available_staff') }}</span>
                    </div>
                    <div class="and-item" id="employee-section">
                        <div class="location-container">
                            <div class="d-col-flex" style="width: 331px">
                                <select class="unique-select" name="employeeAvailable" id="employee-select" readonly
                                    disabled>
                                    <option selected disabled>{{ __('cart.select_staff') }}</option>
                                </select>
                                @error('employeeAvailable')
                                    <div style="color: red;font-weight: bold">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                    </div>
                    {{-- End employee --}}
                </div>
            </div>
            <div class="ms-modal-footer">
                <button class="ms-btn-primary" id="confirm-session">تأكيد الجلسة</button>
            </div>
        </div>
    </div>

    <!--/** ******************** START MODEL MAP ******************** **\-->
    <div style=" padding-bottom: 50px !important" class="md-modal md-effect-3 map-mop" id="modal-map">
        <div style="padding:0 !important" class="md-content  map-cont ">
            <span class="pcoded-mtext"
                style=" text-align: center; color: #136e81; width: 100%; display: block; padding: 10px 0; font-size: 18px; font-weight: bold;">
                {{ __('general.selectLocation') }}
            </span>
            <div class="card-block">
                <div class="ms-modal-body">
                    <div class="map-container">
                        <div style="margin-bottom: 3px" class="row">
                            <div class="ms-map-selected">

                                <label class="form-control-label"><code>*</code>{{ __('general.find_address') }}
                                </label>
                                <br>
                                <input type="text" id="addressSearch" class="form-control"
                                    placeholder="{{ __('general.enter_address') }}">
                                <br>
                                <button type="button" class="ms-btn-primary"
                                    onclick="geocodeAddress()">{{ __('general.search') }}
                                </button>
                            </div>
                        </div>

                        <!-- Map centered on Riyadh -->
                        <div id="map" style="height: 250px; margin-bottom: 20px;"></div>
                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">
                        <div class="ms-modal-footer">
                            <button type="button" class="ms-btn-primary"
                                id="saveLocation">{{ __('general.save') }}</button>
                            <br>
                            <button type="button"
                                class="ms-btn-secondary waves-effect md-close">{{ __('general.cancel') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/** ******************** END MODEL MAP ******************** **\-->
        <?php
        $location = json_decode($order->location, true);
        ?>
    @endSection

    @section('scripts-front')
        <script src="{{ asset('front/js/dateBicker.js') }}"></script>
        {{--    <script src="{{ asset('front/js/modals.js') }}"></script> --}}

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('[data-modal]').forEach(function(trigger) {
                    trigger.addEventListener('click', function(e) {
                        e.preventDefault();

                        const modalId = trigger.getAttribute('data-modal');
                        const modal = document.querySelector(modalId);

                        if (modal) {
                            modal.style.display = 'block';
                        }
                    });
                });

                document.querySelectorAll('.ms-modal-close').forEach(function(closeButton) {
                    closeButton.addEventListener('click', function() {
                        const modal = closeButton.closest('.ms-modal');
                        if (modal) {
                            modal.style.display = 'none';
                        }
                    });
                });

                window.addEventListener('click', function(event) {
                    document.querySelectorAll('.ms-modal').forEach(function(modal) {
                        if (event.target === modal) {
                            modal.style.display = 'none';
                        }
                    });
                });
            });
        </script>
        <script>
            /** *************************** START FILTER *************************** **/

            $(document).ready(function() {

                var formattedDate = ''; // Initialize a variable to store the formatted date

                function areAllFieldsFilled() {
                    var latitude = {{ $location['latitude'] }};
                    var longitude = {{ $location['longitude'] }};
                    var categoryId = null;
                    var packageId = null;
                    var employeeId = {{ $order->employee_id }};

                    @if ($order->package_id != null)
                        packageId = {{ $order->package_id }};
                    @else
                        categoryId = {{ $order->category_id }};
                    @endif



                    var idPresent = (packageId !== null || categoryId !== null) && !(packageId !== null &&
                        categoryId !== null);

                    var allFieldsFilled = latitude !== '' && longitude !== '' && idPresent && formattedDate !== '';

                    return allFieldsFilled;
                }

                function filterEmployees() {
                    if (areAllFieldsFilled()) {
                        var latitude = {{ $location['latitude'] }};
                        var longitude = {{ $location['longitude'] }};
                        var categoryId = null;
                        var packageId = null;
                        var employeeId = {{ $order->employee_id }};

                        @if ($order->package_id != null)
                            packageId = {{ $order->package_id }};
                            // console.log('ID_Package:', packageId);
                        @else
                            categoryId = {{ $order->category_id }};
                        @endif


                        $.ajax({
                            url: '{{ route('admin.filterEmployees') }}',
                            type: 'GET',
                            data: {
                                latitude: latitude,
                                longitude: longitude,
                                category_id: categoryId,
                                package_id: packageId,
                                employee_id: employeeId,
                                date: formattedDate
                            },
                            success: function(response) {
                                //console.log('Received response:', response);
                                var timeAvailableSelect = $('select[name="timeAvailable"]');
                                timeAvailableSelect.empty();
                                var availableTimesSet = new Set();

                                if (response.available_employees.length > 0) {
                                    timeAvailableSelect.append('<option value="">اختر وقت</option>');
                                    $.each(response.available_employees, function(index, employee) {
                                        if (employee.employee_id == employeeId) {
                                            $.each(employee.available_times, function(index, time) {
                                                availableTimesSet.add(time);
                                            });
                                        }
                                    });
                                    var availableTimesArray = Array.from(availableTimesSet);
                                    $.each(availableTimesArray, function(index, time) {
                                        timeAvailableSelect.append('<option value="' + time + '">' +
                                            time + '</option>');
                                    });
                                } else {
                                    timeAvailableSelect.append(
                                        '<option value="">لا يوجد أوقات متاحه</option>');
                                }

                                timeAvailableSelect.on('change', function() {
                                    var selectedTime = $(this).val();
                                    if (selectedTime !== '') {
                                        filterEmployeesByTime(selectedTime, response
                                            .available_employees);
                                    }
                                });
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    title: "حدث خطأ",
                                    text: "فشل في جلب البيانات. يرجى المحاولة لاحقاً.",
                                    icon: "error"
                                });
                            }
                        });
                    } else {
                        Swal.fire({
                            title: "تنبيه",
                            text: "يرجى ملء جميع الحقول قبل الفلترة.",
                            icon: "warning"
                        });
                    }
                }

                $('.unique-datepicker-input').on('focusout', function() {
                    var $this = $(this);
                    setTimeout(function() {
                        var selectedDate = $this.val();

                        var dateParts = selectedDate.split(', ')[1].split('/');
                        var day = dateParts[0].padStart(2, '0');
                        var month = dateParts[1].padStart(2, '0');
                        var year = dateParts[2];

                        formattedDate = year + '-' + month + '-' + day;
                        $('#hidden-date-input').val(formattedDate);

                        filterEmployees();
                    }, 100);
                });
            });

            /** *************************** END FILTER *************************** **/

            /** *************************** START EDIT TIME *************************** **/
            $(document).ready(function() {

                var formattedDate = ''; // Initialize a variable to store the formatted date
                var selectedTime = ''; // Store the selected time
                var sessionId = null; // سيتم تعيينه عند فتح المودال

                // عند فتح المودال، نأخذ الـ sessionId من الرابط
                $('[data-modal="#date-modal"]').on('click', function(e) {
                    e.preventDefault();
                    sessionId = $(this).data('session-id');
                    //console.log("Session ID:", sessionId);

                    // هنا يمكنك فتح المودال بالطريقة التي تريدها
                    $('#date-modal').show(); // على سبيل المثال
                });


                // تنسيق التاريخ بعد اختياره
                $('.unique-datepicker-input').on('focusout', function() {
                    var $this = $(this);
                    setTimeout(function() {
                        var selectedDate = $this.val();
                        //console.log("Original Date selected:", selectedDate);

                        var dateParts = selectedDate.split(', ')[1].split('/');
                        var day = dateParts[0].padStart(2, '0');
                        var month = dateParts[1].padStart(2, '0');
                        var year = dateParts[2];

                        formattedDate = year + '-' + month + '-' + day;
                        //console.log("Formatted Date:", formattedDate);
                        $('#hidden-date-input').val(formattedDate);
                    }, 100);
                });

                // حفظ الوقت المختار
                $('#time-select').on('change', function() {
                    selectedTime = $(this).val();
                    //console.log("Selected Time:", selectedTime);
                });

                // استدعاء AJAX عند الضغط على زر "تأكيد الجلسة"
                $('#confirm-session').on('click', function() {
                    if (formattedDate && selectedTime && sessionId) {
                        // تصحيح التنسيقات
                        formattedDate = formattedDate.trim();
                        selectedTime = selectedTime.trim();
                        sessionId = parseInt(sessionId); // تأكد من أن sessionId هو عدد صحيح

                        // console.log("Formatted Date:", formattedDate);
                        // console.log("Selected Time:", selectedTime);
                        // console.log("Session ID:", sessionId);

                        $.ajax({
                            url: '{{ route('createOrderSessions') }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                session_id: sessionId,
                                date: formattedDate,
                                time: selectedTime
                            },
                            success: function(response) {
                                //console.log("Response received:", response);

                                // تأكد من أن الاستجابة هي في شكل JSON
                                if (typeof response === 'string') {
                                    try {
                                        response = JSON.parse(response);
                                    } catch (e) {
                                        //console.error("Failed to parse response JSON:", response);
                                        Swal.fire({
                                            title: "حدث خطأ",
                                            text: "لم نتمكن من تأكيد الجلسة. يرجى المحاولة لاحقاً.",
                                            icon: "error"
                                        });
                                        return;
                                    }
                                }

                                // تحقق من وجود الخاصية success
                                if (response.success) {
                                    Swal.fire({
                                        title: "تم تأكيد الجلسة",
                                        text: response.message,
                                        icon: "success"
                                    }).then(function() {
                                        $('#date-modal').hide();
                                    });
                                } else {
                                    Swal.fire({
                                        title: "تنبيه",
                                        text: response.message || 'حدث خطأ غير متوقع.',
                                        icon: "warning"
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                // console.error("Error updating session:", {
                                //     status: status,
                                //     error: error,
                                //     responseText: xhr.responseText
                                // });

                                // محاولة تحليل استجابة الخطأ إذا كانت بصيغة JSON
                                var errorMessage =
                                    "لم نتمكن من تأكيد الجلسة. يرجى المحاولة لاحقاً.";
                                try {
                                    var errorResponse = JSON.parse(xhr.responseText);
                                    errorMessage = errorResponse.message || errorMessage;
                                } catch (e) {
                                    //console.error("Failed to parse error response JSON:", xhr.responseText);
                                }

                                Swal.fire({
                                    title: "حدث خطأ",
                                    text: errorMessage,
                                    icon: "error"
                                });
                            }


                        });
                    } else {
                        Swal.fire({
                            title: "تنبيه",
                            text: "يرجى اختيار التاريخ والوقت قبل تأكيد الجلسة.",
                            icon: "warning"
                        });
                    }
                });
            });
            /** *************************** END EDIT TIME *************************** **/

            /** *************************** START CANSEL SESSION *************************** **/
            $(document).ready(function() {
                // Use event delegation to handle dynamically loaded elements
                $(document).on('click', '#cancelOrder', function(e) {
                    e.preventDefault(); // Prevent the default action of the link

                    var sessionId = $(this).data('sessions-id'); // Get the session ID
                    console.log('Session ID:', sessionId);

                    // Show the SweetAlert dialog
                    Swal.fire({
                        title: 'إلغاء الموعد', // Title of the alert
                        text: 'يرجى إدخال سبب الإلغاء:', // Prompt text
                        input: 'textarea', // Input type
                        inputLabel: 'سبب الإلغاء', // Label for the input
                        inputPlaceholder: 'أدخل سبب الإلغاء هنا...', // Placeholder text
                        showCancelButton: true, // Show the cancel button
                        confirmButtonText: 'تأكيد', // Text for the confirm button
                        cancelButtonText: 'إلغاء', // Text for the cancel button
                        inputValidator: (value) => {
                            if (!value) {
                                return 'يجب إدخال سبب الإلغاء!'; // Error message if input is empty
                            }
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var reason = result.value; // Get the reason for cancellation
                            console.log('Reason:', reason);

                            // Send AJAX request to cancel the booking
                            $.ajax({
                                url: '{{ route('cancelOrderSessions') }}', // Route to send the request
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}', // CSRF token for security
                                    session_id: sessionId, // Session ID
                                    reason: reason // Cancellation reason
                                },
                                success: function(response) {
                                    console.log('Response:', response);
                                    if (response.success) {
                                        Swal.fire({
                                            title: 'تم الإلغاء', // Success title
                                            text: response
                                                .message, // Success message
                                            icon: 'success' // Success icon
                                        }).then(() => {
                                            // Perform additional actions after successful cancellation (e.g., reload the page)
                                            location.reload();
                                        });
                                    } else {
                                        Swal.fire({
                                            title: 'تنبيه', // Warning title
                                            text: response.message ||
                                                'حدث خطأ غير متوقع.', // Warning message
                                            icon: 'warning' // Warning icon
                                        });
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error("Error cancelling order:", {
                                        status: status,
                                        error: error,
                                        responseText: xhr.responseText
                                    });

                                    // Parse the JSON response text to handle it correctly
                                    try {
                                        let parsedResponse = JSON.parse(xhr.responseText);
                                        let errorMessage = parsedResponse.message ||
                                            'لم نتمكن من إلغاء الموعد. يرجى المحاولة لاحقاً.';

                                        Swal.fire({
                                            title: 'حدث خطأ', // Error title
                                            text: errorMessage, // Error message
                                            icon: 'error' // Error icon
                                        });
                                    } catch (e) {
                                        Swal.fire({
                                            title: 'حدث خطأ', // Error title
                                            text: 'لم نتمكن من إلغاء الموعد. يرجى المحاولة لاحقاً.', // Default error message
                                            icon: 'error' // Error icon
                                        });
                                    }
                                }
                            });
                        }
                    });
                });
            });
            /** *************************** END CANSEL SESSION *************************** **/
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var dropdownButtons = document.querySelectorAll('.dropdown-button');

                dropdownButtons.forEach(function(button) {
                    button.addEventListener('click', function(event) {
                        event.stopPropagation();
                        var dropdownContent = this.nextElementSibling;
                        var allDropdownContents = document.querySelectorAll('.dropdown-content');

                        // إخفاء جميع القوائم
                        allDropdownContents.forEach(function(content) {
                            if (content !== dropdownContent) {
                                content.style.display = 'none';
                            }
                        });

                        // تبديل عرض القائمة الحالية
                        dropdownContent.style.display = (dropdownContent.style.display === 'block') ?
                            'none' : 'block';
                    });
                });

                document.addEventListener('click', function(event) {
                    var dropdownContents = document.querySelectorAll('.dropdown-content');
                    dropdownContents.forEach(function(content) {
                        if (!content.contains(event.target) && !event.target.matches(
                                '.dropdown-button')) {
                            content.style.display = 'none'; // إخفاء جميع القوائم
                        }
                    });
                });
            });
        </script>



        <script src="{{ asset('front/js/dateBicker.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const selectElement = document.getElementById('services-section-select');

                selectElement.addEventListener('change', function() {
                    if (selectElement.value === 'at_spa') {
                        Swal.fire({
                            icon: 'info',
                            title: @json(__('messages.info_title')),
                            text: @json(__('messages.info_text')),
                            confirmButtonText: @json(__('messages.confirm_button'))
                        });

                        selectElement.value = 'at_home';
                    }
                });
            });
        </script>

        <!-- Include Leaflet library -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>




        {{--      /** ****************************************** START MAP ****************************************** **/      --}}
        <script>
            // 🗺️ Initialize the map
            var map = L.map('map').setView([24.7136, 46.6753], 10);
            var selectedCoordinates;

            // 🌍 Add the map layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© Eng. Andrew ©',
                maxZoom: 18,
            }).addTo(map);

            // 📍 Define Riyadh boundaries
            var riadBounds = L.latLngBounds(
                [24.4365, 46.6180],
                [24.9642, 47.0441]
            );

            // 🔍 Search for address using Nominatim service
            function geocodeAddress() {
                var address = document.getElementById('addressSearch').value;

                if (address) {
                    $.getJSON('https://nominatim.openstreetmap.org/search?format=json&q=' + address, function(data) {
                        if (data && data.length > 0) {
                            var lat = data[0].lat;
                            var lon = data[0].lon;
                            map.setView([lat, lon], 15);

                            if (selectedCoordinates) {
                                map.removeLayer(selectedCoordinates);
                            }

                            selectedCoordinates = L.marker([lat, lon]).addTo(map);
                            document.getElementById('latitude').value = lat;
                            document.getElementById('longitude').value = lon;

                            // 📝 Display the address in the input field
                            document.getElementById('addressSearch').value = data[0].display_name;
                        } else {
                            alert('لم يتم العثور على العنوان.');
                        }
                    });
                } else {
                    alert('يرجى إدخال عنوان للبحث.');
                }
            }

            // 🖱️ Handle map click to select a location
            map.on('click', function(e) {
                if (selectedCoordinates) {
                    map.removeLayer(selectedCoordinates);
                }

                var latlng = e.latlng;
                var currentLang = '{{ App::getLocale() }}'; // Get the current site language
                if (riadBounds.contains(latlng)) {
                    if (selectedCoordinates) {
                        map.removeLayer(selectedCoordinates);
                    }
                    selectedCoordinates = L.marker(latlng).addTo(map);

                    document.getElementById('latitude').value = latlng.lat;
                    document.getElementById('longitude').value = latlng.lng;

                    // 🌐 Use Nominatim to get the address based on the current language
                    var langParam = currentLang === 'ar' ? 'ar' : 'en'; // Determine language (Arabic or English)

                    $.getJSON('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + latlng.lat + '&lon=' +
                        latlng.lng + '&accept-language=' + langParam,
                        function(data) {
                            if (data && data.display_name) {
                                document.getElementById('addressSearch').value = data.display_name;

                                // 🏠 Update the location name and description
                                var addressParts = data.display_name.split(',');
                                var firstPartOfAddress = addressParts[0].trim(); // Get the first part only

                                if (currentLang === 'ar') {
                                    // Display the address in Arabic
                                    document.getElementById('location-name').textContent = firstPartOfAddress;
                                    document.getElementById('location-desc').textContent =
                                        "هذا هو العنوان المختار على الخريطة.";
                                } else {
                                    // Display the address in English
                                    document.getElementById('location-name').textContent = firstPartOfAddress;
                                    document.getElementById('location-desc').textContent =
                                        "This is the selected location on the map.";
                                }

                                // 📍 Update hidden coordinates
                                document.getElementById('ms-latitude').value = latlng.lat;
                                document.getElementById('ms-longitude').value = latlng.lng;
                            } else {
                                alert('لم يتم العثور على العنوان.');
                            }
                        });

                    // 💾 Handle save location
                    document.getElementById('saveLocation').addEventListener('click', function() {
                        var latitude = latlng.lat;
                        var longitude = latlng.lng;
                        if (latitude && longitude) {
                            Swal.fire({
                                title: '{{ __('general.areyousure') }}' + '?',
                                text: '{{ __('general.Select a location from the map') }}',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: '{{ __('general.yesconfirm') }}',
                                cancelButtonText: '{{ __('general.cancel') }}'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        url: '{{ route('front.availableEmployeesPlaces') }}',
                                        method: 'POST',
                                        data: {
                                            _token: '{{ csrf_token() }}',
                                            latitude: latitude,
                                            longitude: longitude
                                        },
                                        success: function(response) {
                                            console.log('Available employees:', response
                                                .employees);
                                            window.availableEmployees = response.employees;

                                            document.querySelector('.md-close').click();

                                            Swal.fire({
                                                title: "نجاح",
                                                text: "تم حفظ الموقع بنجاح.",
                                                icon: "success"
                                            });
                                        },
                                        error: function(xhr, status, error) {
                                            console.error('An error occurred:', error);
                                            Swal.fire({
                                                title: "حدث خطأ",
                                                text: "فشل في حفظ الموقع. يرجى المحاولة لاحقاً.",
                                                icon: "error"
                                            });
                                        }
                                    });
                                }
                            });
                        } else {
                            Swal.fire({
                                title: "بيانات مفقودة",
                                text: "يرجى تحديد موقع على الخريطة قبل الحفظ.",
                                icon: "warning"
                            });
                        }
                    });
                } else {
                    Swal.fire({
                        title: '{{ __('general.yourLocation') }}',
                        text: '{{ __('general.notAvailable') }}',
                        icon: "error"
                    });
                }
            });
        </script>
        {{--      /** ****************************************** END MAP ****************************************** **/      --}}




        <script>
            $(document).ready(function() {
                // console.log("Script is loaded and ready.");
                var formattedDate = ''; // Initialize a variable to store the formatted date
                function areAllFieldsFilled() {
                    var latitude = $('#latitude').val();
                    var longitude = $('#longitude').val();
                    var categoryId = null;
                    var packageId = null;

                    @if ($orders->category_id != null)
                        categoryId = {{ $orders->category->id }};
                    @else

                        packageId = {{ $orders->package->id }};
                    @endif

                    console.log('Latitude:', latitude);
                    console.log('Longitude:', longitude);
                    console.log('Formatted Date:', formattedDate);

                    console.log('Latitude:', latitude);
                    console.log('Longitude:', longitude);
                    console.log('Formatted Date:', formattedDate);

                    // Check if either packageId or categoryId is present, not both
                    var idPresent = (packageId !== null || categoryId !== null) && !(packageId !== null &&
                        categoryId !== null);
                    console.log('ID Present:', idPresent);

                    var allFieldsFilled = latitude !== '' && longitude !== '' && idPresent && formattedDate !== '';
                    console.log('All Fields Filled:', allFieldsFilled);

                    return allFieldsFilled;
                }

                // دالة لتصفية الموظفين بناءً على المدخلات
                function filterEmployees() {
                    if (areAllFieldsFilled()) {
                        var latitude = $('#latitude').val();
                        var longitude = $('#longitude').val();
                        var categoryId = null;
                        var packageId = null;

                        @if ($orders->category_id != null)
                            categoryId = {{ $orders->category->id }};
                        @else

                            packageId = {{ $orders->package->id }};
                        @endif

                        // إرسال الطلب عبر AJAX
                        $.ajax({
                            url: '{{ route('front.filterEmployees') }}',
                            type: 'GET',
                            data: {
                                latitude: latitude,
                                longitude: longitude,
                                category_id: categoryId,
                                package_id: packageId,
                                date: formattedDate
                            },
                            success: function(response) {
                                console.log('Received response:', response);
                                var timeAvailableSelect = $('select[name="timeAvailable"]');
                                timeAvailableSelect.empty();
                                var availableTimesSet = new Set();

                                if (response.available_employees.length > 0) {
                                    timeAvailableSelect.append('<option value="">اختر وقت</option>');
                                    $.each(response.available_employees, function(index, employee) {
                                        $.each(employee.available_times, function(index, time) {
                                            availableTimesSet.add(time);
                                        });
                                    });
                                    var availableTimesArray = Array.from(availableTimesSet);
                                    $.each(availableTimesArray, function(index, time) {
                                        timeAvailableSelect.append('<option value="' + time + '">' +
                                            time + '</option>');
                                    });
                                } else {
                                    timeAvailableSelect.append(
                                        '<option value="">لا يوجد أوقات متاحه</option>');
                                }

                                timeAvailableSelect.on('change', function() {
                                    var selectedTime = $(this).val();
                                    if (selectedTime !== '') {
                                        filterEmployeesByTime(selectedTime, response
                                            .available_employees);
                                    }
                                });
                            },
                            error: function(xhr, status, error) {
                                console.error('Error occurred:', {
                                    status: status,
                                    error: error,
                                    responseText: xhr.responseText
                                });

                                Swal.fire({
                                    title: "حدث خطأ",
                                    text: "فشل في جلب البيانات. يرجى المحاولة لاحقاً.",
                                    icon: "error"
                                });
                            }
                        });
                    } else {
                        Swal.fire({
                            title: "تنبيه",
                            text: "يرجى ملء جميع الحقول قبل الفلترة.",
                            icon: "warning"
                        });
                    }
                }

                // دالة لتصفية الموظفين بناءً على الوقت المحدد
                function filterEmployeesByTime(selectedTime, availableEmployees) {
                    var employeeAvailableSelect = $('select[name="employeeAvailable"]');
                    employeeAvailableSelect.empty();
                    employeeAvailableSelect.append('<option value="">اختر موظف</option>');

                    $.each(availableEmployees, function(index, employee) {
                        if (employee.available_times.includes(selectedTime)) {
                            employeeAvailableSelect.append('<option value="' + employee.employee_id + '">' +
                                employee.employee_name + '</option>');
                        }
                    });
                }

                // استماع للأحداث عند تغيير المدخلات
                $('#latitude, #longitude').on('change', function() {
                    console.log('Fields changed');
                    filterEmployees();
                });

                // تنسيق التاريخ إلى 'Y-m-d' قبل إرساله
                $('.unique-datepicker-input').on('focusout', function() {
                    var $this = $(this);
                    setTimeout(function() {
                        var selectedDate = $this.val();
                        console.log("Original Date selected:", selectedDate);

                        // افتراض أن التاريخ في تنسيق 'dd/mm/yyyy'
                        var dateParts = selectedDate.split(', ')[1].split('/');
                        var day = dateParts[0].padStart(2, '0');
                        var month = dateParts[1].padStart(2, '0');
                        var year = dateParts[2];

                        // تنسيق التاريخ إلى 'Y-m-d'
                        formattedDate = year + '-' + month + '-' + day; // تحديث formattedDate
                        console.log("Formatted Date:", formattedDate);
                        $('#hidden-date-input').val(formattedDate);
                        // استدعاء الدالة لتحديث الفلترة بعد اختيار التاريخ

                        filterEmployees();
                    }, 100);

                });

            });
        </script>




        <script type="text/javascript" src="{{ asset('dashboard/assets/sweetalert/js/sweetalert.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('dashboard/assets/js/modal.js') }}"></script>
        <script type="text/javascript" src="{{ asset('dashboard/assets/js/modalEffects.js') }}"></script>
        <script type="text/javascript" src="{{ asset('dashboard/assets/js/classie.js') }}"></script>
        <script>
            /** *************************** START REQUIRED *************************** **/

            document.addEventListener('DOMContentLoaded', function() {
                const locationBtn = document.getElementById('edit-location-btn');
                const dateInput = document.getElementById('date-input');
                const timeSelect = document.getElementById('time-select');
                const employeeSelect = document.getElementById('employee-select');

                locationBtn.addEventListener('click', function() {
                    dateInput.disabled = false;
                    dateInput.readOnly = false;
                });

                dateInput.addEventListener('focus', function() {
                    if (dateInput.disabled) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'تنبيه',
                            text: 'يرجى اختيار الموقع أولا.',
                            confirmButtonText: 'موافق'
                        });
                    } else {
                        timeSelect.disabled = false;
                        timeSelect.readOnly = false;
                    }
                });

                timeSelect.addEventListener('focus', function() {
                    if (timeSelect.disabled) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'تنبيه',
                            text: 'يرجى اختيار التاريخ أولا.',
                            confirmButtonText: 'موافق'
                        });
                    } else {
                        employeeSelect.disabled = false;
                        employeeSelect.readOnly = false;
                    }
                });

                employeeSelect.addEventListener('focus', function() {
                    if (employeeSelect.disabled) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'تنبيه',
                            text: 'يرجى اختيار الوقت أولا.',
                            confirmButtonText: 'موافق'
                        });
                    }
                });
            });
            /** *************************** END REQUIRED *************************** **/
        </script>
    @endsection
