@extends('Dashboard.layouts.master')
{{-- @section('title_dashboard') --}}
{{-- @endsection --}}
@section('css_dashboard')
    <!-- Date-time picker css -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('dashboard/assets/pages/advance-elements/css/bootstrap-datetimepicker.css') }}" />
    <!-- Date-range picker css  -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('dashboard/assets/bootstrap-daterangepicker/css/daterangepicker.css') }}" />
    <!-- Date-Dropper css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets//datedropper/css/datedropper.min.css') }}" />
    <!-- Color Picker css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/spectrum/css/spectrum.css') }}" />
    <!-- Mini-color css -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('dashboard/assets/pages/jquery-minicolors/css/jquery.minicolors.css') }}" />
    <!-- Switch component css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/switchery/css/switchery.min.css') }}">
@endsection
@section('title_page')
    أضافة كوبون جديد
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
            <form id="addCouponForm" action="{{ route('admin.coupons.store') }}" method="POST">
                @csrf
                <div class="row">
                    <!-- Code -->
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code> كود الكوبون </p>
                        <input type="text" name="code" id="code" class="form-control"
                            placeholder="يرجى إدخال كود الكوبون" value="{{ old('code') }}" required>
                        <br>
                        @error('code')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Type -->
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code> نوع الخصم </p>
                        <select name="discount_type" id="discount_type" class="js-example-rtl col-sm-12" required>
                            <option disabled selected>إختار نوع الخصم</option>

                            <option value="fixed" @if (old('discount_type') == 'fixed') selected @endif>خصم ثابت
                            </option>
                            <option value="percentage" @if (old('discount_type') == 'percentage') selected @endif>
                                خصم
                                نسبي
                            </option>
                        </select>
                        <br>
                        @error('type')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Value -->
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code> قيمة الخصم </p>
                        <input type="text" name="value" id="value" class="form-control"
                            placeholder="يرجى إدخال قيمة الخصم" pattern="\d+(\.\d{1,2})?" title="يرجى إدخال قيمة صحيحة"
                            value="{{ old('value') }}" required>
                        <br>
                        @error('value')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <!-- Usage Type -->
                    <div class="col-sm-6 col-xl-3 m-b-30">
                        <p><code>*</code> نوع الاستخدام</p>
                        <select name="usage_type" id="usage_type" class="js-example-rtl col-sm-12" required>
                            <option disabled selected>إختار نوع الاستخدام</option>
                            <option value="unlimited">بدون حدود</option>
                            <option value="limited">محدد</option>
                        </select>
                        <br>
                        @error('usage_type')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Usage Limit -->
                    <div class="col-sm-6 col-xl-3 m-b-30">
                        <p><code>*</code> حد الاستخدام</p>
                        <input type="text" name="usage_limit" id="usage_limit" class="form-control"
                            placeholder="يرجى إدخال حد الاستخدام" pattern="\d*" title="يرجى إدخال أرقام فقط"
                            value="{{ old('usage_limit') }}">
                        <br>
                        @error('usage_limit')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Coupon Type -->
                    <div class="col-sm-6 col-xl-3 m-b-30">
                        <p><code>*</code> نوع الكوبون</p>
                        <select name="coupon_type" id="coupon_type" class="js-example-rtl col-sm-12" required>
                            <option disabled selected>إختار نوع الكوبون</option>
                            <option value="infinite" @if (old('coupon_type') == 'infinite') selected @endif>لا نهائى
                            </option>
                            <option value="temporary" @if (old('coupon_type') == 'temporary') selected @endif>مؤقت
                            </option>
                        </select>
                        <br>
                        @error('coupon_type')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Date Range -->
                    <div class="col-sm-6 col-xl-3 m-b-30">
                        <p>تاريخ البداية و النهاية</p>
                        <input type="text" name="daterange" class="form-control" id="daterange"
                            value="{{ $dateRange }}" />
                        <br>
                        @error('daterange')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>


                <div class="row">
                    <!-- Categories -->
                    <div class="col-sm-12 col-xl-6 m-b-30">
                        <p>الفئات </p>
                        <select class="js-example-rtl col-sm-12" name="categories[]" multiple>
                            <!-- Populate this with categories from your database -->
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @if (is_array(old('categories')) && in_array($category->id, old('categories'))) selected @endif>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <br>
                        @error('categories')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Packages -->
                    <div class="col-sm-12 col-xl-6 m-b-30">
                        <p>الباقات </p>
                        <select class="js-example-rtl col-sm-12" name="packages[]" multiple>
                            <!-- Populate this with packages from your database -->
                            @foreach ($packages as $package)
                                <option value="{{ $package->id }}" @if (is_array(old('packages')) && in_array($package->id, old('packages'))) selected @endif>
                                    {{ $package->name }}
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
                        <p>التوقيت </p>
                        <select class="js-example-rtl col-sm-12" name="time_statues" id="time_statues">
                            <!-- Populate this with categories from your database -->
                            <option selected disabled>أختر التوقيت</option>
                            <option value="unlimited">بدون قيود</option>
                            <option value="restricted">بقيود</option>
                        </select>
                        <br>
                        @error('time_statues')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code> توقيت البدء <code>أدخل الوقت بتنسيق AM/PM HH:MM</code></p>
                        <input class="form-control" type="time" name="start_time" id="start_time" />

                    </div>
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code> توقيت الانتهاء <code>أدخل الوقت بتنسيق AM/PM HH:MM</code></p>
                        <input class="form-control" type="time" name="end_time" id="end_time" />

                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="display: block; margin: 0 auto;">إضافة كوبون <i
                        class="icon-user-following"></i></button>
            </form>


        </div>
    </div>
@endsection

@section('script_dashboard')
    <!-- Select 2 js -->
    <script type="text/javascript" src="{{ asset('dashboard/assets/select2/js/select2.full.min.js') }}"></script>
    <!-- Multiselect js -->
    <script type="text/javascript" src="{{ asset('dashboard/assets/bootstrap-multiselect/js/bootstrap-multiselect.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/multiselect/js/jquery.multi-select.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/js/jquery.quicksearch.js') }}"></script>
    <!-- Masking js -->
    <script src="{{ asset('dashboard/assets/pages/form-masking/inputmask.js') }}"></script>
    <script src="{{ asset('dashboard/assets/pages/form-masking/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('dashboard/assets/pages/form-masking/autoNumeric.js') }}"></script>
    <script src="{{ asset('dashboard/assets/pages/form-masking/form-mask.js') }}"></script>
    <!-- Bootstrap date-time-picker js -->
    <script type="text/javascript" src="{{ asset('dashboard/assets/pages/advance-elements/moment-with-locales.min.js') }}">
    </script>
    <script type="text/javascript"
        src="{{ asset('dashboard/assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script type="text/javascript"
        src="{{ asset('dashboard/assets/pages/advance-elements/bootstrap-datetimepicker.min.js') }}"></script>
    <!-- Date-range picker js -->
    <script type="text/javascript" src="{{ asset('dashboard/assets/bootstrap-daterangepicker/js/daterangepicker.js') }}">
    </script>
    <!-- Date-dropper js -->
    <script type="text/javascript" src="{{ asset('dashboard/assets/datedropper/js/datedropper.min.js') }}"></script>
    <!-- Color picker js -->
    <script type="text/javascript" src="{{ asset('dashboard/assets/spectrum/js/spectrum.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/jscolor/js/jscolor.js') }}"></script>
    <!-- Mini-color js -->
    <script type="text/javascript"
        src="{{ asset('dashboard/assets/pages/jquery-minicolors/js/jquery.minicolors.min.js') }}"></script>
    <!-- Custom js -->
    <script type="text/javascript" src="{{ asset('dashboard/assets/pages/advance-elements/custom-picker.js') }}"></script>

    <!-- Max-length js -->
    <script type="text/javascript" src="{{ asset('dashboard/assets/bootstrap-maxlength/js/bootstrap-maxlength.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/pages/advance-elements/swithces.js') }}"></script>

    <script>
        $(document).ready(function() {
            const $coupon = $('#coupon_type');
            const $dateRange = $('#daterange');

            // Check the current value when the page loads
            function updateDateRange() {
                if ($coupon.val() === 'infinite') {
                    $dateRange.prop('disabled', true); // Disable the field
                    $dateRange.val(''); // Clear the field value
                } else {
                    $dateRange.prop('disabled', false); // Enable the field
                    // Get the current date
                    const currentDate = new Date();
                    // Add 10 days to the current date
                    const endDate = new Date();
                    endDate.setDate(currentDate.getDate() + 10);

                    // Function to format the date as 'YYYY-MM-DD'
                    const formatDate = (date) => {
                        const year = date.getFullYear();
                        const month = String(date.getMonth() + 1).padStart(2,
                            '0'); // Months are zero-indexed in JavaScript
                        const day = String(date.getDate()).padStart(2, '0');
                        return `${year}-${month}-${day}`;
                    };

                    // Generate the result in the format 'YYYY-MM-DD to YYYY-MM-DD'
                    const result = `${formatDate(currentDate)} to ${formatDate(endDate)}`;

                    $dateRange.val('{{ $dateRange ?? `${result}` }}'); // Set the field value
                }
            }

            // Update the status when the page loads
            updateDateRange();

            // Listen for changes in the coupon type selection
            $coupon.on('change', function() {
                updateDateRange();
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Function to update the fields based on the selected option
            function updateFields() {
                var timeStatues = $('#time_statues').val();

                if (timeStatues === 'unlimited') {
                    // Disable and make read-only
                    $('#start_time').prop('disabled', true).prop('readonly', true);
                    $('#end_time').prop('disabled', true).prop('readonly', true);
                } else if (timeStatues === 'restricted') {
                    // Enable and make editable
                    $('#start_time').prop('disabled', false).prop('readonly', false);
                    $('#end_time').prop('disabled', false).prop('readonly', false);
                }
            }

            // Initial check on page load
            updateFields();

            // Event listener for when the select value changes
            $('#time_statues').on('change', function() {
                updateFields();
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Function to update the usage limit field based on the selected option
            function updateUsageLimit() {
                var usageType = $('#usage_type').val();

                if (usageType === 'unlimited') {
                    // Disable and make read-only
                    $('#usage_limit').prop('disabled', true).prop('readonly', true).val('');
                } else if (usageType === 'limited') {
                    // Enable and make editable
                    $('#usage_limit').prop('disabled', false).prop('readonly', false);
                }
            }

            // Initial check on page load
            updateUsageLimit();

            // Event listener for when the select value changes
            $('#usage_type').on('change', function() {
                updateUsageLimit();
            });
        });
    </script>
@endsection
