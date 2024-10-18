@extends('Dashboard.layouts.master')
{{-- @section('title_dashboard') --}}
{{-- @endsection --}}
@section('css_dashboard')
    <!-- sweet alert framework -->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/sweetalert/css/sweetalert.css') }}">
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
    أضافة طلب جديدة
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

    <div class="card">

        <div class="card-block">
            <form id="addCouponForm" action="{{ route('admin.orders.store') }}" method="POST">
                @csrf
                <div class="row">
                    <!-- Code -->
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code> كود الطلب <code>( هذا الكود هام جداً )</code></p>
                        <input type="text" name="order_code" id="order_code" class="form-control"
                               value="" required readonly>
                        <br>
                        @error('code')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Type -->
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code> العميل <code>( برجاء إختيار حالة العميل اولاً )</code></p>
                        <select name="client_status" id="client_status" class="js-example-rtl col-sm-12" required>
                            <option disabled selected>اختر حالة العميل</option>
                            <option value="new" @if (old('client_status') == 'new') selected @endif>عميل جديد</option>
                            <option value="existing" @if (old('client_status') == 'existing') selected @endif>عميل
                                حالى
                            </option>
                        </select>
                        <br>
                        @error('client_status')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-sm-12 col-xl-4 m-b-30" id="existing_client_fields">
                        <p><code>*</code> كود العميل الحالى</p>
                        <!-- Fields for existing clients -->
                        <input type="text" name="existing_client_code" id="existing_client_code" class="form-control"
                               placeholder="أدخل كود العميل الحالى"/>
                        <br>
                        @error('existing_client_code')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>


                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <p><code>تفاصيل العميل الجديد</code></p>
                        <hr>
                    </div>

                    <div class="col-sm-12 col-xl-3 m-b-30" id="new_client_fields">
                        <p><code>*</code> كود العميل </p>
                        <!-- Fields for new clients -->
                        <input type="text" name="client_code" id="client_code" class="form-control"
                               value="{{$user_code}}" readonly/>
                        <br>
                        @error('client_code')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-sm-12 col-xl-3 m-b-30" id="new_client_fields">
                        <p><code>*</code> إسم العميل </p>
                        <!-- Fields for new clients -->
                        <input type="text" name="client_name" id="client_name" class="form-control"
                               placeholder=" إسم العميل"/>
                        <br>
                        @error('client_name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-12 col-xl-3 m-b-30" id="new_client_fields">
                        <p><code>*</code> جوال العميل</p>
                        <!-- Fields for new clients -->
                        <input type="text" name="client_phone" id="client_phone" class="form-control"
                               placeholder="أدخل الجوال"/>
                        <br>
                        @error('client_phone')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
                <div class="row">

                    <div class="col-sm-12">
                        <p><code>تفاصيل الحجز</code></p>
                        <hr>
                    </div>
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code> حالة الحجز </p>
                        <select name="reservation_status" id="reservation_status" class="js-example-rtl col-sm-12">
                            <option value="" selected disabled>حدد حالة الحجر</option>

                            <option value="at_home" @if (old('reservation_status') == 'at_home') selected @endif>منزلى

                            </option>
                            <option value="at_spa" @if (old('reservation_status') == 'at_spa') selected @endif>بداخل
                                الفرع
                            </option>
                        </select>
                        <br>
                        @error('reservation_status')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-12 col-xl-3 m-b-30" id="location">
                        <p><code>*</code> العنوان <code>( برجاء اختيار العنوان حتى في حالة الحجز بداخل الفرع )</code>
                        </p>
                        <!-- Fields for new clients -->
                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">
                        <button type="button" class="btn btn-primary waves-effect md-trigger"
                                data-modal="modal-map" id="locationButton">
                            اختر الموقع
                        </button>

                        <br>
                        @error('client_code')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code> الحى</p>
                        <select name="neighborhoods" id="neighborhoods" class="js-example-rtl col-sm-12">
                            <option value="" selected disabled>اختر الحى</option>
                            @foreach($neighborhoods as $neighborhood)
                                <option value="{{$neighborhood}}">
                                    {{$neighborhood}}

                                </option>
                            @endforeach
                        </select>
                        <br>
                        @error('neighborhoods')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <!-- Coupon Type -->
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code> نوع الحجز<code>( برجاء إختيار نوع الحجز اولاً )</code></p>
                        <select name="booking_type" id="booking_type" class="js-example-rtl col-sm-12" required>
                            <option disabled selected>إختار نوع الحجز</option>
                            <option value="package" @if (old('booking_type') == 'package') selected @endif>باقات
                            </option>
                            <option value="category" @if (old('booking_type') == 'category') selected @endif>خدمة
                            </option>
                        </select>
                        <br>
                        @error('booking_type')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Categories -->
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p>الخدمة </p>
                        <select class="js-example-rtl col-sm-12" name="categories" id="categories">
                            <option value="" disabled selected>إختار الخدمة</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->getTranslation('name', 'ar') }}
                                </option>
                            @endforeach
                        </select>
                        <br>
                        @error('categories')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Packages -->
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p>الباقات </p>
                        <select class="js-example-rtl col-sm-12" name="packages" id="packages">
                            <option value="" disabled selected>إختار الباقة</option>
                            @foreach ($packages as $package)
                                <option value="{{ $package->id }}">
                                    {{ $package->getTranslation('name', 'ar') }}
                                </option>
                            @endforeach
                        </select>
                        <br>
                        @error('packages')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code>تاريخ الحجز </p>
                        <input type="text" name="date" class="form-control"
                               id="dropper-max-year"
                               readonly="readonly"
                        />
                        <br>
                        @error('date')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code> الوقت المتاح </p>
                        <select name="time_available" class="js-example-rtl col-sm-12" id="timeAvailable">
                            <option value="">اختر وقت</option>
                            <!-- These options will be made optional by JavaScript. -->
                        </select> <br>
                        @error('time_available')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code> الموظفين المتاحين </p>
                        <select name="employee_available" class="js-example-rtl col-sm-12" id="employeeAvailable">
                            <option value="" selected disabled>اختر موظف</option>
                            <!-- These options will be made optional by JavaScript. -->
                        </select> <br>
                        @error('employee_available')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code> حالة الدفع </p>
                        <select name="payment_status" id="payment_status" class="js-example-rtl col-sm-12">
                            <option value="" selected disabled>حدد حالة الدفع</option>

                            <option value="pending" @if (old('payment_status') == 'pending') selected @endif>قيد
                                الانتظار
                            </option>
                            <option value="paid" @if (old('payment_status') == 'paid') selected @endif>تم الدفع
                            </option>
                        </select>
                        <br>
                        @error('payment_status')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code> نوع الدفع </p>
                        <select name="payment_type" id="payment_type" class="js-example-rtl col-sm-12" disabled>
                            <option value="" selected disabled>حدد نوع الدفع</option>
                            <option value="cash" @if (old('payment_type') == 'cash') selected @endif>كاش</option>
                            <option value="bank_transfer" @if (old('payment_type') == 'bank_transfer') selected @endif>
                                تحويل بنكى
                            </option>
                        </select>
                        <br>
                        <br>
                        <div id="payment_type_error" class="text-danger" style="display:none;">في حالة انه قيد الأنتظار
                            لا يمكنك
                            تحديد نوع الدفع
                        </div>
                        @error('payment_type')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-sm-12 col-xl-12 m-b-30">
                        <p>ملاحظات</p>
                        <textarea name="notes" class="form-control" rows="5"></textarea>
                        <br>
                        @error('notes')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="display: block; margin: 0 auto;">إضافة الطلب <i
                        class="icon-user-following"></i></button>
            </form>
        </div>
    </div>


    <div class="md-modal md-effect-3" id="modal-map">
        <div class="md-content container">
            <span class="pcoded-mtext"
                  style="background-color: #0a6aa1; text-align: center; color: white; width: 100%; display: block; padding: 10px 0; font-size: 18px;">
                إضافة موقع
            </span>
            <div class="card-block">
                <div class="j-wrapper j-wrapper-640">
                    <div class="j-content">
                        <div class="j-row">
                            <div class="j-input">
                                <label class="form-control-label"><code>*</code>ابحث عن العنوان</label>
                                <br>
                                <input type="text" id="addressSearch" class="form-control"
                                       placeholder="أدخل اسم الشارع أو العنوان">
                                <br>
                                <button type="button" class="btn btn-primary" onclick="geocodeAddress()">بحث
                                </button>
                            </div>
                        </div>
                        <br>
                        <!-- Map centered on Riyadh -->
                        <div id="map" style="height: 300px; margin-bottom: 20px;"></div>
                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">

                        <br>
                        <br>
                        <br>
                        <div class="modal-footer j-footer">
                            <button type="button" class="btn btn-primary" id="saveLocation">حفظ</button>
                            <button type="button" class="btn btn-default waves-effect md-close">إلغاء</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script_dashboard')

    <!-- sweet alert js -->
    <script type="text/javascript" src="{{ asset('dashboard/assets/sweetalert/js/sweetalert.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/js/modal.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/js/modalEffects.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/js/classie.js') }}"></script>
    <!-- Include Leaflet library -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert@2"></script>



    <script>
        var map = L.map('map').setView([24.7136, 46.6753], 10);
        var selectedCoordinates;

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© Eng. Andrew ©',
            maxZoom: 18,
        }).addTo(map);

        var riadBounds = L.latLngBounds(
            [24.4365, 46.6180],
            [24.9642, 47.0441]
        );

        map.on('click', function (e) {
            if (selectedCoordinates) {
                map.removeLayer(selectedCoordinates);
            }

            var latlng = e.latlng;

            if (riadBounds.contains(latlng)) {
                selectedCoordinates = L.marker(latlng).addTo(map);
                var reservation_status = $('#reservation_status').val();

                document.getElementById('latitude').value = latlng.lat;
                document.getElementById('longitude').value = latlng.lng;
                document.getElementById('saveLocation').addEventListener('click', function () {
                    // Check if latitude and longitude are filled
                    var latitude = latlng.lat;
                    var longitude = latlng.lng;
                    if (latitude && longitude && reservation_status) {
                        // Show confirmation message with SweetAlert
                        swal({
                            title: "هل أنت متأكد؟",
                            text: "هل ترغب في حفظ الموقع المحدد؟",
                            type: "warning",
                            html: true,
                            showCancelButton: true,
                            closeOnConfirm: false,
                            confirmButtonText: "نعم",
                            cancelButtonText: "إلغاء",
                            dangerMode: true,
                        }, function (isConfirm) {
                            if (isConfirm) {
                                // Make AJAX request to save location
                                $.ajax({
                                    url: '{{route('admin.availableEmployeesPlaces')}}',
                                    method: 'POST',
                                    data: {
                                        _token: '{{ csrf_token() }}',
                                        latitude: latitude,
                                        longitude: longitude,
                                        reservation_status: reservation_status
                                    },
                                    success: function (response) {
                                        // Handle successful response
                                        console.log('Available employees:', response.employees);
                                        window.availableEmployees = response.employees;

                                        // Close the modal
                                        document.querySelector('.md-close').click();

                                        // Show success message
                                        swal({
                                            title: "نجاح",
                                            text: "تم حفظ الموقع بنجاح.",
                                            type: "success",
                                            html: true
                                        });
                                    },
                                    error: function (xhr, status, error) {
                                        // Handle error
                                        console.error('An error occurred:', error);
                                        swal({
                                            title: "حدث خطأ",
                                            text: "فشل في حفظ الموقع. يرجى المحاولة لاحقاً.",
                                            type: "error",
                                            html: true
                                        });
                                    }
                                });
                            }
                        });
                    } else {
                        swal({
                            title: "بيانات مفقودة",
                            text: "يرجى تحديد موقع على الخريطة قبل الحفظ.",
                            type: "warning",
                            html: true
                        });
                    }
                });


            } else {
                swal({
                    title: "الموقع خارج الحدود",
                    text: "الموقع المحدد خارج منطقة الرياض. يرجى اختيار موقع داخل الرياض.",
                    type: "error",
                    html: true
                });
            }
        });
    </script>

    <script>

        $(document).ready(function () {
            // Function to check if all values are complete
            function areAllFieldsFilled() {
                var latitude = $('#latitude').val();
                var longitude = $('#longitude').val();
                var categoryId = $('#categories').val();
                var packageId = $('#packages').val();
                var date = $('#dropper-max-year').val();
                var bookingType = $('#booking_type').val();
                var reservationStatus = $('#reservation_status').val();

                return (categoryId !== '' || packageId !== '') && date !== '' && bookingType !== null && reservationStatus !== null;
            }

            // Function to send filter request
            function filterEmployees() {
                if (areAllFieldsFilled()) {
                    var latitude = $('#latitude').val() ? $('#latitude').val() : null;
                    var longitude = $('#longitude').val() ? $('#longitude').val() : null;
                    var categoryId = $('#categories').val();
                    var packageId = $('#packages').val();
                    var date = $('#dropper-max-year').val();
                    var reservationStatus = $('#reservation_status').val();

                    // Convert date to correct format if necessary
                    var formattedDate = moment(date, 'MM/DD/YYYY').format('YYYY-MM-DD');

                    $.ajax({
                        url: '{{ route('admin.filterEmployees') }}',
                        type: 'GET',
                        data: {
                            latitude: latitude,
                            longitude: longitude,
                            category_id: categoryId,
                            package_id: packageId,
                            date: formattedDate,
                            reservation_status: reservationStatus
                        },
                        success: function (response) {
                            var timeAvailableSelect = $('#timeAvailable');
                            timeAvailableSelect.empty();
                            var availableTimesSet = new Set(); // Use a Set to avoid duplicates
                            if (response.available_employees.length > 0) {
                                timeAvailableSelect.append('<option value="">اختر وقت</option>');
                                $.each(response.available_employees, function (index, employee) {
                                    $.each(employee.available_times, function (index, time) {
                                        availableTimesSet.add(time); // Add time to the Set
                                    });
                                });
                                // Convert Set to array
                                var availableTimesArray = Array.from(availableTimesSet);
                                // No sorting applied
                                $.each(availableTimesArray, function (index, time) {
                                    timeAvailableSelect.append('<option value="' + time + '">' + time + '</option>');
                                });
                            } else {
                                timeAvailableSelect.append('<option value="">لا يوجد أوقات متاحه</option>');
                            }
                            // Activate the employee list when selecting the time
                            timeAvailableSelect.on('change', function () {
                                var selectedTime = $(this).val();
                                if (selectedTime !== '') {
                                    filterEmployeesByTime(selectedTime, response.available_employees);
                                }
                            });
                        },
                        error: function (xhr, status, error) {
                            swal({
                                title: "حدث خطأ",
                                text: "فشل في جلب البيانات. يرجى المحاولة لاحقاً.",
                                type: "error",
                                html: true
                            });
                        }
                    });
                } else {
                    swal({
                        title: "تنبيه",
                        text: "يرجى ملء جميع الحقول قبل الفلترة.",
                        type: "warning",
                        html: true
                    });
                }
            }

            // Function to filter employees based on specified time
            function filterEmployeesByTime(selectedTime, availableEmployees) {
                var employeeAvailableSelect = $('#employeeAvailable');
                employeeAvailableSelect.empty();
                employeeAvailableSelect.append('<option value="" selected disabled>اختر موظف</option>');

                $.each(availableEmployees, function (index, employee) {
                    if (employee.available_times.includes(selectedTime)) {
                        employeeAvailableSelect.append('<option value="' + employee.employee_id + '">' + employee.employee_name + '</option>');
                    }
                });
            }


            // Listen for changes in required fields and perform filtering
            $('#latitude, #longitude, #dropper-max-year, #booking_type , #reservation_status').on('change', filterEmployees);

            // Listen for changes in select2 for categories and packages
            $('#categories, #packages').on('select2:select', function () {
                if ($(this).val() !== null) {
                    filterEmployees();
                }
            });
        });


    </script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/select2/js/select2.full.min.js') }}"></script>
    <!-- Multiselect js -->
    <script type="text/javascript"
            src="{{ asset('dashboard/assets/bootstrap-multiselect/js/bootstrap-multiselect.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/multiselect/js/jquery.multi-select.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/js/jquery.quicksearch.js') }}"></script>
    <!-- Masking js -->
    <script src="{{ asset('dashboard/assets/pages/form-masking/inputmask.js') }}"></script>
    <script src="{{ asset('dashboard/assets/pages/form-masking/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('dashboard/assets/pages/form-masking/autoNumeric.js') }}"></script>
    <script src="{{ asset('dashboard/assets/pages/form-masking/form-mask.js') }}"></script>
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
    {{--    <!-- sweet alert js -->--}}
    <script type="text/javascript" src="{{ asset('dashboard/assets/sweetalert/js/sweetalert.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/js/modal.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/js/modalEffects.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/js/classie.js') }}"></script>
    <script>
        $(document).ready(function () {
            // Set all fields initially
            $('#client_code').val('').attr('readonly', true);
            $('#client_name').val('').attr('readonly', true);
            $('#client_phone').val('').attr('readonly', true);
            $('#existing_client_code').val('').attr('readonly', true);

            // jQuery to toggle field values based on selected client status
            $('#client_status').change(function () {
                var selectedValue = $(this).val();

                if (selectedValue === 'new') {
                    $('#client_code').val(generateRandomClientCode()).attr('readonly', true);
                    $('#client_name').attr('readonly', false);
                    $('#client_phone').attr('readonly', false);
                    $('#existing_client_code').val('').attr('readonly', true);
                } else if (selectedValue === 'existing') {
                    $('#client_code').val('').attr('readonly', true);
                    $('#client_name').attr('readonly', true);
                    $('#client_phone').attr('readonly', true);
                    $('#existing_client_code').attr('readonly', false);
                } else {
                    $('#client_code').val('').attr('readonly', true);
                    $('#client_name').val('').attr('readonly', true);
                    $('#client_phone').val('').attr('readonly', true);
                    $('#existing_client_code').val('').attr('readonly', true);
                }
            });

            // Function to generate a random client code (example implementation)
            function generateRandomClientCode() {
                return Math.floor(Math.random() * 900000) + 100000; // Generate a random 6-digit number
            }

            // Trigger change event on page load to apply the initial state
            $('#client_status').trigger('change');
        });


    </script>
    <script>
        $(document).ready(function () {
            $('#payment_status').change(function () {
                var paymentStatus = $(this).val();
                if (paymentStatus === 'paid') {
                    $('#payment_type').prop('disabled', false);
                    $('#payment_type_error').hide();
                } else if (paymentStatus === 'pending') {
                    $('#payment_type').prop('disabled', true);
                    $('#payment_type_error').show();
                } else {
                    $('#payment_type').prop('disabled', true);
                    $('#payment_type_error').hide();
                }
            });

            // Trigger change on page load to apply correct state
            $('#payment_status').trigger('change');
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#existing_client_code').on('blur', function () {
                var input = $(this).val();

                if (input.length > 0) {
                    $.ajax({
                        url: '{{ route('admin.check-client-code') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            input: input
                        },
                        success: function (data) {
                            if (data.client_name) {
                                const message = `${data.message}\n`;
                                const line = `\n `;
                                const clientName = `<br><strong style="color: red; font-weight: bold;">${data.client_name}</strong>.`;
                                swal({
                                    title: 'نجاح',
                                    text: message + line + clientName, // Use `html` for HTML content
                                    type: 'success', // Use `icon` instead of `type` in SweetAlert2
                                    html: true
                                });
                                $('#existing_client_code').val(data.client_code);
                                $('#client_status').change(function () {
                                    var selectedValue = $(this).val();
                                    var clientCode = '';

                                    // الحصول على كود العميل بناءً على حالة العميل
                                    if (selectedValue === 'new') {
                                        clientCode = $('#client_code').val();
                                    } else if (selectedValue === 'existing') {
                                        clientCode = $('#existing_client_code').val();
                                    }

                                    // التأكد من وجود كود عميل قبل إرسال الطلب
                                    if (clientCode) {
                                        // إرسال طلب AJAX للحصول على كود الفاتورة
                                        $.ajax({
                                            url: '{{ route('admin.generate-invoice-code') }}',
                                            method: 'POST',
                                            data: {
                                                client_code: clientCode,
                                                _token: '{{ csrf_token() }}' // تأكد من إضافة رمز CSRF
                                            },
                                            success: function (response) {
                                                // عرض كود الفاتورة في الحقل المناسب
                                                $('#order_code').val(response.invoice_code);
                                            },
                                            error: function (xhr) {
                                                console.error('An error occurred:', xhr.responseText);
                                            }
                                        });
                                    } else {
                                        console.warn('Client code is empty.');
                                    }
                                });

                                // Trigger the change event on page load to apply the initial state
                                $('#client_status').trigger('change');
                                $('#existing_client_code').prop('readonly', true);

                            } else {
                                $('#existing_client_code').val('');
                                var selectElement = document.getElementById('client_status');
                                selectElement.selectedIndex = 0;
                                $(selectElement).trigger('change');
                                swal({
                                    title: 'عذراً',
                                    text: data.message,
                                    type: 'warning'
                                });


                            }
                        },
                        error: function (xhr, status, error) {
                            swal({
                                title: 'خطأ',
                                text: 'حدث خطأ أثناء معالجة الطلب.',
                                type: 'error',
                                html: true
                            });
                        }
                    });
                }
            });
        });


    </script>
    <script>

        $(document).ready(function () {
            function toggleReadonlyFields() {
                var bookingType = $('#booking_type').val();
                $('#categories').val($('#categories option:first').val()).trigger('change');
                $('#packages').val($('#packages option:first').val()).trigger('change');
                if (bookingType === 'package') {
                    // Enable packages and disable categories
                    $('#categories').prop('disabled', true);
                    $('#packages').prop('disabled', false);

                    // Disable reservation status initially
                    // $('#reservation_status').prop('disabled', true);
                } else if (bookingType === 'category') {
                    // Enable categories and disable packages
                    $('#packages').prop('disabled', true);
                    $('#categories').prop('disabled', false);

                    // Disable reservation status initially
                    // $('#reservation_status').prop('disabled', true);
                } else {
                    // Disable both and reset to the default options
                    $('#categories').prop('disabled', true);
                    $('#packages').prop('disabled', true);


                    // Disable reservation status initially
                    //$('#reservation_status').prop('disabled', true);
                }
            }

            // Initialize fields on page load
            toggleReadonlyFields();

            //Trigger

            //function on

            //booking
            // type
            //change
            $(document).ready(function () {
                toggleReadonlyFields();

                // Trigger function on booking type change
                $('#booking_type').change(function () {
                    toggleReadonlyFields();
                });
            });

            // Event listener for categories
            $('#categories').change(function () {
                let categoryId = $(this).val();

                let url = `{{ route('admin.getCategoryAvailability', ':id') }}`.replace(':id', categoryId);

                fetch(url, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        updateReservationStatus(data);
                    })
                    .catch(error => console.error('Categories error:', error));
            });

            // Event listener for packages
            $('#packages').change(function () {
                let packageId = $(this).val();
                let url = `{{ route('admin.getPackageAvailability', ':id') }}`.replace(':id', packageId);

                fetch(url, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        updateReservationStatus(data);
                    })
                    .catch(error => console.error('Packages error:', error));
            });

            // Function to update reservation status
            // function updateReservationStatus(data) {
            //     const reservationStatusSelect = $('#reservation_status');
            //
            //     reservationStatusSelect.html('<option value="" selected disabled>حدد حالة الحجز</option>');
            //
            //     if (data.in_spa === 1 || data.in_spa === true) {
            //         reservationStatusSelect.append('<option value="at_spa">بداخل الفرع</option>');
            //     }
            //
            //     if (data.in_home === 1 || data.in_home === true) {
            //         reservationStatusSelect.append('<option value="at_home">منزلى</option>');
            //     }
            //
            //     // Enable reservation status only if there is a valid selection
            //     if ($('#categories').val() || $('#packages').val()) {
            //         reservationStatusSelect.prop('disabled', false);
            //     } else {
            //         reservationStatusSelect.prop('disabled', true);
            //     }
            // }
        });

    </script>
    <script>
        $(document).ready(function () {
            // تغيير الحدث عند تغيير حالة العميل
            $('#client_status').change(function () {
                var selectedValue = $(this).val();
                var clientCode = '';

                // الحصول على كود العميل بناءً على حالة العميل
                if (selectedValue === 'new') {
                    clientCode = $('#client_code').val();
                } else if (selectedValue === 'existing') {
                    clientCode = $('#existing_client_code').val();
                }

                // التأكد من وجود كود عميل قبل إرسال الطلب
                if (clientCode) {
                    // إرسال طلب AJAX للحصول على كود الفاتورة
                    $.ajax({
                        url: '{{ route('admin.generate-invoice-code') }}',
                        method: 'POST',
                        data: {
                            client_code: clientCode,
                            _token: '{{ csrf_token() }}' // تأكد من إضافة رمز CSRF
                        },
                        success: function (response) {
                            // عرض كود الفاتورة في الحقل المناسب
                            $('#order_code').val(response.invoice_code);
                        },
                        error: function (xhr) {
                            console.error('An error occurred:', xhr.responseText);
                        }
                    });
                } else {
                    console.warn('Client code is empty.');
                }
            });

            // Trigger the change event on page load to apply the initial state
            $('#client_status').trigger('change');
        });
    </script>
@endsection
