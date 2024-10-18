@extends('front.layouts.app')


@section('title', __('nav.title', ['title' => __('general.orders')]))

@section('head-front')
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/css/component.css') }}" />

    <link rel="stylesheet" href="{{ asset('front/css/date-bicker.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/cart.css') }}">
    <style>
        .md-modal {
            z-index: 1000 !important;

        }

        .main-container {
            background-image: url('{{ asset('front/images/1221.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: bottom center;
            height: 100vh;
            width: 100%;
            align-items: center;
            justify-content: center;

        }

        @media only screen and (max-width: 600px) {
            .main-container {
                background-size: cover;
                background-position: bottom;
                background-attachment: scroll;
            }
        }

        .btn-unset {
            border: 1px solid #137083;
            background-color: white;
            cursor: pointer;
            width: 50%;
            height: 50px;
            border-radius: 5px;
            align-content: center
        }

        .location-desc {
            white-space: nowrap;
            font-size: 14px;
            font-weight: bold;

        }

        .title-center {
            display: block;
            margin: 30px;
            justify-content: center;
            display: flex;
            font-size: 16px;
            font-weight: bold;
        }

        .btn-center {
            display: block;
            justify-content: center;
            display: flex;
            margin: auto
        }

        .mini-title-center {
            display: block;
            margin: 6px 0 6px 43%;
            justify-content: center;
            display: flex;
            font-size: 16px;
            font-weight: bold;

        }

        :dir(ltr) .mini-title-center {
            display: block;
            margin: 6px 26%;
            justify-content: flex-start;
            display: flex;
            font-size: 16px;
            font-weight: bold;
            text-align: left;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }

        .unique-datepicker-input {
            background-color: #ffffff !important;
            width: 50%;
            display: block;
            justify-content: center;
            display: flex;
            margin: auto;
            height: 50px;
            color: #000000
        }

        .unique-select {
            background-color: #ffffff !important;
            width: 50%;
            /* display: block; */
            justify-content: center;
            display: flex;
            margin: auto;
            height: 50px;
            color: #000000
        }

        .unique-datepicker-calendar {
            left: 30% !important;
        }

        @media (max-width: 768px) {
            .unique-datepicker-calendar {
                left: 10% !important;
            }
        }

        .submit-button {
            border: 1px solid #137083;
            background: linear-gradient(45deg, #137083, #0a5b64);
            cursor: pointer;
            width: 50%;
            height: 50px;
            color: #ffffff;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            margin: auto;
            transition: background 0.4s ease, transform 0.3s ease, box-shadow 0.4s ease;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .submit-button:hover {
            background: linear-gradient(45deg, #19a2b8, #0a5b64);
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3), 0 4px 8px rgba(0, 0, 0, 0.1);
            text-shadow: 0px 1px 3px rgba(0, 0, 0, 0.2);
        }


        @media (max-width: 768px) {
            .btn-unset {
                border: 1px solid #137083;
                background-color: white;
                cursor: pointer;
                width: 80%;
                height: 50px;
                border-radius: 5px;
                align-content: center
            }

            .unique-datepicker-input {
                background-color: #ffffff !important;
                width: 80%;
                display: block;
                justify-content: center;
                display: flex;
                margin: auto;
                height: 50px;
                color: #000000
            }

            .submit-button {
                border: 1px solid #137083;
                background-color: #137083;
                cursor: pointer;
                width: 80%;
                height: 50px;
                color: #ffffff;
                border-radius: 5px;
                align-content: center;
                font-size: 14px;
                font-weight: bold;
                margin: auto;
            }

            .unique-select {
                background-color: #ffffff !important;
                width: 80%;
                /* display: block; */
                justify-content: center;
                display: flex;
                margin: auto;
                height: 50px;
                color: #000000
            }

            :dir(ltr) .mini-title-center {
                display: block;
                margin: 6px 11%;
                justify-content: flex-start;
                display: flex;
                font-size: 16px;
                font-weight: bold;
                text-align: left;
                width: 100%;
                max-width: 100%;
                box-sizing: border-box;
            }


        }
    </style>

@endsection

@section('content-front')
    @php
        $locale = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale();

    @endphp
    <div class="container main-container">
        <div class="title-center">تحديد موعد الجلسة
        </div>
        {{-- ------- Selet Date ------- --}}
        <div class="location-input">
            <h2 class="mini-title-center">{{ __('cart.Location') }}</h2>
            <div class="location-2 btn-center" id="location-input-div">
                <button class="btn-unset  md-trigger" type="button" data-modal="modal-map" id="edit-location-btn">
                    <div class="location-desc" id="location-name">
                        {{ __('cart.your_location') }}
                    </div>
                </button>
            </div>
            @error('latitude')
                <div style="color: red;font-weight: bold">{{ $message }}</div>
            @enderror
        </div>
        {{-- ------- Selet Date ------- --}}


        <div>
            <h2 class="mini-title-center"> {{ __('cart.date') }}</h2>
            <div class="unique-datepicker-container">

                <input style="text-align: inherit;direction: inherit;" type="button"class="unique-datepicker-input"
                    readonly id="date-input" onclick="this.blur();>
                <input type="hidden"
                    id="hidden-date-input" name="formattedDate">
                @error('formattedDate')
                    <div style="color: red;font-weight: bold">{{ $message }}</div>
                @enderror
                <div class="unique-datepicker-calendar">
                    <div class="unique-datepicker-header">
                        <button type="button" class="unique-datepicker-prev">&lt;</button>
                        <span class="unique-datepicker-month-year">{{ \Carbon\Carbon::now()->format('F Y') }}</span>
                        <button type="button" class="unique-datepicker-next">&gt;</button>
                    </div>
                    <div class="unique-datepicker-days">
                        <!-- Days will be populated here by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
        {{-- ---------------------------------------------------------------- --}}

        <div>
            <h2 class="mini-title-center"> {{ __('cart.time') }}</h2>
            <div>
                <select class="unique-select" name="timeAvailable" id="time-select" readonly disabled>
                    <option selected disabled>{{ __('cart.select_time') }}</option>
                </select>
                @error('timeAvailable')
                    <div style="color: red;font-weight: bold">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- ---------------------------------------------------------------- --}}
        <div>
            <h2 class="mini-title-center"> {{ __('cart.available_staff') }}</h2>
            <div>
                <div>
                    <select class="unique-select" name="employeeAvailable" id="employee-select" readonly disabled>
                        <option selected disabled>{{ __('cart.select_staff') }}</option>
                    </select>
                    @error('employeeAvailable')
                        <div style="color: red;font-weight: bold">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- ---------------------------------------------------------------- --}}

            <div style="display: flex;margin-top:30px;">
                <button class="submit-button" id="confirm-session">تأكيد الجلسة</button>
            </div>

            {{-- ---------------------------------------------------------------- --}}


        </div>
    </div>
    </div>


    {{--    Update Map --}}
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

    // $location = json_decode($order->location, true);

    @php     $orderID = $session->order_id ; @endphp

    @php    $order = \App\Models\Order::find($orderID); @endphp
@endSection
@section('scripts-front')
    <script src="{{ asset('front/js/dateBicker.js') }}"></script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        /** *************************** START FILTER *************************** **/
        $(document).ready(function() {
            // console.log("Script is loaded and ready.");

            var formattedDate = ''; // Initialize a variable to store the formatted date

            function areAllFieldsFilled() {
                var latitude = $('#latitude').val();
                var longitude = $('#longitude').val();
                var categoryId = null;
                var packageId = null;

                @if ($order->category != null && $order->category->id != null)
                    categoryId = {{ $order->category->id }};
                @else
                    packageId = {{ $order->package->id }};
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

                    @if ($order->category != null && $order->category->id != null)
                        categoryId = {{ $order->category->id }};
                    @else
                        packageId = {{ $order->package->id }};
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


        /** *************************** END FILTER *************************** **/
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
