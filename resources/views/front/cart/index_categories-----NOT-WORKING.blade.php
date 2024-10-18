@extends('front.layouts.app')

@section('title', __('nav.title', ['title' => __('nav.cart')]))
@section('head-front')
    <!-- animation nifty modal window effects css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/css/component.css') }}" />
    <link rel="stylesheet" href="{{ asset('front/css/date-bicker.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/cart.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/ms-checkout.css') }}" defer>

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
            align-items: center;
            gap: 10px;
            /* border: 1px solid #ccc; */


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
            /* لون الخلفية */
            /* padding: 15px;
                                                                                                                                                                                                                border-radius: 10px; */
            position: relative;
            width: 300px;
            /* box-shadow: 0 4px 8px rgba(0, 0, 0, 0.041); */
            margin-top: 30px;
            /* إزاحة الشريط إلى الأسفل قليلاً */
            display: flex;
            align-items: center;
            gap: 10px;
            /* المسافة بين العناصر الداخلية */
            height: 100px;
            align-items: center;
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
            align-items: center;
            justify-content: center;

            color: #fff;
            border: none;
            padding: 7px 7px;

        }

        @media (max-width: 768px) {
            .location-container {
                width: 400px !important;

            }

            .and-item {
                width: 346px;
            }

            .unique-datepicker-calendar {

                left: -40px;
                !important;


            }
        }
    </style>

@endsection

@section('content-front')
    @php
        $locale = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale();
    @endphp

    <div class="cart-container">
        <form id="bookingFormCategory" action="{{ route('PaymentMyFatoorah') }}" method="post">
            @csrf
            <div class="cart">
                <div class="cart-items-container">

                    {{-- ----------------- Start Location & Services -------------------- --}}

                    <div class="and-container add-container">

                        <div class="and-item">

                            <div class="location-container ">
                                <div class="header">
                                    <div class="circle">
                                        <span>1</span>
                                    </div>
                                    <span style="text-align: center" class="header-text">{{ __('cart.Location') }}</span>
                                </div>
                                <div class="color-6 font-18">
                                    <input type="hidden" name="latitude" id="latitude">
                                    <input type="hidden" name="longitude" id="longitude">

                                    <i class="fa fa-location-dot" aria-hidden="true"></i>
                                </div>

                                <div class="flex-auto d-col-flex gap-5px">

                                    <button style="font-size: 16px; font-weight: bold"
                                        class="btn-unset color-6 font-18 waves-effect md-trigger" type="button"
                                        data-modal="modal-map" id="edit-location-btn">
                                        <div class="font-bold" id="location-name">{{ __('cart.your_location') }}</div>
                                        <div class="font-12 location-desc" id="location-desc">

                                            {{ __('cart.select_your_location') }}
                                        </div>
                                    </button>
                                    @error('latitude')
                                        <div style="color: red;font-weight: bold">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <button class="btn-unset color-6 font-18 waves-effect md-trigger" type="button"
                                        data-modal="modal-map" id="edit-location-btn">
                                        <i class="fa fa-edit"></i>
                                    </button>

                                </div>

                            </div>
                        </div>
                        <div class="and-item" id="services-section">
                            <div class="location-container ">
                                <div class="header">
                                    <div class="circle">
                                        <span>2</span>
                                    </div>
                                    <span class="header-text">{{ __('cart.booking_place') }}</span>
                                </div>
                                <div class="color-6 font-18">
                                    <i class="fa-solid fa-spa" aria-hidden="true"></i>
                                </div>
                                <div class="flex-auto d-col-flex gap-5px">
                                    {{-- <div class="font-bold">مكان الحجز</div> --}}

                                    <select class="unique-select" name="servicesAvailable" id="services-section-select">
                                        <option selected disabled>{{ __('cart.select_your_booking_place') }}</option>
                                        <option value="at_home"
                                            {{ old('servicesAvailable') == 'at_home' ? 'selected' : '' }}>
                                            {{ __('cart.at_home') }}
                                        </option>
                                        <option value="at_spa"
                                            {{ old('servicesAvailable') == 'at_spa' ? 'selected' : '' }}>
                                            {{ __('cart.at_spa') }}
                                        </option>
                                    </select>
                                    @error('servicesAvailable')
                                        <div style="color: red;font-weight: bold">{{ $message }}</div>
                                    @enderror

                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ----------------- End Location & Services -------------------- --}}


                    <div class="and-container">
                        <div class="and-item">
                            <div class="location-container">
                                <div class="header">
                                    <div class="circle">
                                        <span>3</span>
                                    </div>
                                    <span class="header-text">{{ __('cart.date') }}</span>
                                </div>
                                <div class="color-6 font-18">
                                    <i class="fas fa-calendar-plus" aria-hidden="true"></i>
                                </div>
                                <div class="flex-auto d-col-flex gap-5px">
                                    {{-- <div class="font-bold" id="date-name">التاريخ</div> --}}
                                    <div class="font-12">
                                        <div style="background-color: red ; color: red" class="unique-datepicker-container">
                                            <input style="text-align: inherit;direction: inherit;"
                                                type="button"class="unique-datepicker-input" readonly id="date-input">

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
                        <div class="and-item">
                            <div class="location-container">
                                <div class="header">
                                    <div class="circle">
                                        <span>4</span>
                                    </div>
                                    <span class="header-text">{{ __('cart.time') }}</span>
                                </div>
                                <div class="color-6 font-18">
                                    <i class="fa-solid fa-clock" aria-hidden="true"></i>
                                </div>
                                <div class="flex-auto d-col-flex gap-5px">
                                    {{-- <div class="font-bold">الوقت</div> --}}

                                    <select class="unique-select" name="timeAvailable" id="time-select" readonly
                                        disabled>
                                        <option selected disabled>{{ __('cart.select_time') }}</option>
                                    </select>
                                    @error('timeAvailable')
                                        <div style="color: red;font-weight: bold">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="and-item" id="employee-section">
                            <div class="location-container">
                                <div class="header">
                                    <div class="circle">
                                        <span>5</span>
                                    </div>
                                    <span class="header-text">{{ __('cart.available_staff') }}</span>
                                </div>
                                <div class="color-6 font-18">
                                    <i class="fa-solid fa-user-clock" aria-hidden="true"></i>
                                </div>
                                <div class="flex-auto d-col-flex gap-5px">
                                    {{-- <div class="font-bold">الموظفين المتاحين</div> --}}

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
                    </div>

                    <div class="cart-notes">
                        <label for="notes" class="title">{{ __('cart.addnotes') }}</label>
                        <textarea name="notes" placeholder="{{ __('cart.notes') }}"></textarea>
                    </div>

                    <div class="cart-items">
                        <div class="title">
                            {{ __('cart.services') }}
                        </div>
                        <div class="items">
                            <div class="item">
                                <div class="info-container">
                                    <div class="service-image"
                                        style="background-image: url({{ asset('front/uploads/images/1717040735974333.webp') }})"
                                        title="service image"></div>
                                    <div style="align-items:flex-start" class="service-info flex-auto">
                                        <div class="service-name">
                                            {{ $Data->category->getTranslation('name', $locale) }}
                                        </div>
                                        <div class="more">
                                            <i class="fa fa-circle"></i>
                                            <div>{{ $Data->category->duration_minutes }} {{ __('messages.minutes') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="price">
                                        <span class="font-bold font-16">{{ $Data->category->prices->at_home }}</span>
                                        {{ __('cart.sar') }}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="cart-footer-container">
                    <div class="title">
                        {{ __('cart.receipt') }}
                        <p><strong style="font-size: 15px">{{ __('cart.invoice_num') }}&nbsp;:&nbsp;</strong> &nbsp;
                            <strong style="font-size: 12px" class="font-14 font-bold">{{ 21 }}</strong>
                        </p>
                    </div>

                    <div class="payment-method d-col-flex gap-10">
                        <div class="font-14 font-bold">{{ __('cart.paymethod') }}</div>
                        <div class="methods d-col-flex gap-20px">
                            <label class="ms-radio-label">
                                <input type="radio" name="payment_method" value="myfatoorah" checked>
                                <div class="ms-radio-label-content">
                                    <div>{{ __('cart.paynow') }}</div>
                                    <div class="ms-img">
                                        <img src="{{ asset('front/images/payment-logo/v.png') }}">
                                    </div>
                                    <div class="ms-img">
                                        <img src="{{ asset('front/images/payment-logo/m.png') }}">
                                    </div>
                                    <div class="ms-img">
                                        <img src="{{ asset('front/images/payment-logo/md.png') }}">
                                    </div>
                                </div>
                            </label>
                            <label class="ms-radio-label">
                                <input type="radio" name="payment_method" value="tamara" id="tamaraRadio"
                                    {{ old('payment_method') == 'tamara' ? 'checked' : '' }}>
                                <div class="ms-radio-label-content">
                                    <div>{{ __('cart.tamara') }}</div>
                                    <div class="ms-img tamara" style="width:70px;">
                                        <img src="{{ asset('front/images/payment-logo/tamara.png') }}">
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="coupon" id="coupon">
                        <div><i class="fa-solid fa-ticket"></i>
                            <div>{{ __('cart.addcoupon') }}</div>
                        </div>
                        <button type="button" class="btn-addcoupon show-coupon-input">{{ __('cart.add') }}</button>
                    </div>

                    <div class="price-sum d-col-flex font-14">
                        <div class="d-col-flex gap-10px border-b padding-b-10">
                            <div class="d-row-flex justify-between">
                                <div class="font-bold">{{ __('cart.total') }}</div>
                                <div><span
                                        class="weight-600 font-16">{{ $Data->category->prices->at_home - $Data->category->prices->at_home * 0.15 }}</span>
                                    {{ __('cart.sar') }}
                                </div>
                            </div>
                            <div class="d-row-flex justify-between">
                                <div class="font-bold">{{ __('cart.discount') }}</div>
                                <div><span class="weight-600 font-16">0.00</span> {{ __('cart.sar') }}</div>
                            </div>
                            <div class="d-row-flex justify-between">
                                <div class="font-bold">{{ __('cart.vat') }}</div>
                                <div><span class="weight-600 font-16">{{ $Data->category->prices->at_home * 0.15 }}</span>
                                    <input type="hidden" name="categoryId" value="{{ $Data->category->id }}">
                                    {{ __('cart.sar') }}
                                </div>
                            </div>

                            <div class="d-row-flex justify-between" id="tamaraExtraInfo" style="display: none;">
                                <div class="font-bold">{{ __('cart.vat_tamara') }}</div>
                                <div><span class="weight-600 font-16"
                                        id="texTamara">{{ $Data->category->prices->at_home * 0.07 }}</span>
                                    {{ __('cart.sar') }}
                                </div>
                            </div>
                        </div>
                        <div class="d-row-flex justify-between padding-t-10">
                            <div class="font-bold">{{ __('cart.gtotal') }}</div>
                            <div><span class="weight-600 font-16"
                                    id="totalAmount">{{ $Data->category->prices->at_home }}</span> {{ __('cart.sar') }}
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">{{ __('cart.complete') }}</button>
                </div>
            </div>
        </form>


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


    @endsection


    @section('scripts-front')
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

                    @if ($Data->category->id != null)
                        categoryId = {{ $Data->category->id }};
                    @else

                        packageId = $('#packages').val();
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

                        @if ($Data->category->id != null)
                            categoryId = {{ $Data->category->id }};
                        @else

                            packageId = $('#packages').val();
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
        <script>
            $(document).ready(function() {
                let originalAmount = parseFloat($('#totalAmount').text()); // السعر الأصلي
                let discountedAmount = originalAmount; // المبلغ بعد الخصم

                // تحديث المبلغ الكلي بناءً على البوابة المختارة والخصم
                function updateTotalAmount() {
                    const tamaraVat = parseFloat($('#texTamara').text());
                    let finalAmount = discountedAmount; // نبدأ بالمبلغ المخفض بعد تطبيق الخصم

                    if ($('#tamaraRadio').is(':checked')) {
                        finalAmount += tamaraVat;
                        $('#tamaraExtraInfo').css('display', 'flex');
                        $('#bookingFormCategory').attr('action', '{{ route('PaymentTAMARA') }}');
                    } else {
                        $('#tamaraExtraInfo').css('display', 'none');
                        $('#bookingFormCategory').attr('action', '{{ route('PaymentMyFatoorah') }}');
                    }


                    $('#totalAmount').text(finalAmount.toFixed(2)); // تحديث عرض المبلغ الكلي
                }

                // عرض حقل القسيمة عند النقر على الزر
                $('.show-coupon-input').on('click', function() {
                    $('#coupon').html(`
            <input type="text" name="coupon_code" id="coupon_code" placeholder="{{ __('cart.addcoupon') }}">
            <button type="button" id="apply-coupon" class="btn-addcoupon">{{ __('cart.apply') }}</button>
        `);
                });

                // التعامل مع تطبيق القسيمة
                $(document).on('click', '#apply-coupon', function() {
                    const couponCode = $('#coupon_code').val();
                    const timeAvailable = $('#time-select').val(); // الحصول على الوقت من القائمة المنسدلة

                    if (!couponCode) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Please enter a coupon code.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }

                    if (!timeAvailable) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Please select a time.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }

                    const categoryId = {{ $Data->category->id }} || null;
                    const originalAmount = parseFloat($('#original_amount')
                        .text()); // تأكد من تعريف originalAmount بشكل صحيح
                    let discountedAmount = originalAmount;

                    $.ajax({
                        url: '{{ route('checkCoupon') }}',
                        method: 'POST',
                        data: {
                            coupon_code: couponCode,
                            category_id: categoryId,
                            time_available: timeAvailable, // إرسال الوقت المحدد
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                let discountText = '';
                                let discountValue = parseFloat(response.discount_value);

                                if (response.discount_type === 'fixed') {
                                    discountText = `${discountValue.toFixed(2)} `;
                                    discountedAmount -= discountValue;
                                } else if (response.discount_type === 'percentage') {
                                    discountText = `${discountValue.toFixed(2)}%`;
                                    discountedAmount -= (discountedAmount * discountValue / 100);
                                }

                                $('#discount_show').text(discountText);
                                $('#total_amount').text(discountedAmount.toFixed(
                                    2)); // تأكد من وجود العنصر وإعادة تعيين المبلغ الإجمالي

                                $('#coupon_code').prop('readonly', true);
                                $('#apply-coupon').prop('disabled', true);

                                Swal.fire({
                                    title: 'Success!',
                                    text: `Discount Applied: ${discountText}`,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: response.message,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'An error occurred while processing your request.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                });

                // متابعة تغييرات بوابة الدفع وتحديث السعر الكلي
                $('#tamaraRadio').on('change', updateTotalAmount);

                // في حالة وجود عناصر أخرى قد تؤثر على السعر الكلي
                $('input[type=radio], input[type=checkbox]').on('change', updateTotalAmount);
            });
        </script>

    @endsection
