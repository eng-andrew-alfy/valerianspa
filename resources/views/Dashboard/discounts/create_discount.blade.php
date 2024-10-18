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
    أضافة خصم جديدة
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
            <form id="addCouponForm" action="{{route('admin.storeAllDiscounts')}}" method="POST">
                @csrf

                <div class="row">
                    <!-- Coupon Type -->
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code> نوع الخصم<code>( برجاء إختيار نوع الخصم اولاً )</code></p>
                        <select name="booking_type" id="booking_type" class="js-example-rtl col-sm-12" required>
                            <option disabled selected>إختار نوع الخصم</option>
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
                        <p><code>*</code>السعر المنزلى</p>
                        <input type="text" name="at_home" id="at_home" class="form-control"
                               value="" required readonly>
                        <br>
                        @error('at_home')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code>السعر بالفرع</p>
                        <input type="text" name="at_spa" id="at_spa" class="form-control"
                               value="" required readonly>
                        <br>
                        @error('at_spa')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code>السعر المنزلى بعد الخصم</p>
                        <input type="text" name="discount_at_home" id="discount_at_home" class="form-control"
                               value="" required>
                        <br>
                        @error('at_home')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code>السعر بالفرع بعد الخصم</p>
                        <input type="text" name="discount_at_spa" id="discount_at_spa" class="form-control"
                               value="" required>
                        <br>
                        @error('discount_at_spa')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="display: block; margin: 0 auto;">إضافة خصم <i
                        class="icon-user-following"></i></button>
            </form>
        </div>
    </div>

@endsection

@section('script_dashboard')

    <!-- sweet alert js -->
    <script type="text/javascript" src="{{ asset('dashboard/assets/sweetalert/js/sweetalert.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/js/modal.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/js/modalEffects.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/js/classie.js') }}"></script>





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

            function toggleReadonlyFields() {
                var bookingType = $('#booking_type').val();

                // Reset both selections to the default option
                $('#categories').val($('#categories option:first').val()).trigger('change');
                $('#packages').val($('#packages option:first').val()).trigger('change');

                if (bookingType === 'package') {
                    // Enable packages and disable categories
                    $('#categories').prop('disabled', true);
                    $('#packages').prop('disabled', false);
                } else if (bookingType === 'category') {
                    // Enable categories and disable packages
                    $('#packages').prop('disabled', true);
                    $('#categories').prop('disabled', false);
                } else {
                    // Disable both and reset to the default options
                    $('#categories').prop('disabled', true);
                    $('#packages').prop('disabled', true);
                }

                // Always disable reservation status initially
                $('#reservation_status').prop('disabled', true);
            }

// Initialize fields on page load
            $(document).ready(function () {
                toggleReadonlyFields();

                // Trigger function on booking type change
                $('#booking_type').change(function () {
                    toggleReadonlyFields();
                });
            });


            $(document).ready(function () {
                // Event listener for categories
                $('#categories').change(function () {
                    let categoryId = $(this).val();
                    let url = `{{ route('admin.getCategoryPrice', ':id') }}`.replace(':id', categoryId);

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
                            // Update the prices in the input fields
                            $('#at_home').val(data.at_home); // Fill home price
                            $('#at_spa').val(data.at_spa); // Fill spa price
                        })
                        .catch(error => console.error('Categories error:', error));
                });

                // Event listener for packages
                $('#packages').change(function () {
                    let packageId = $(this).val();
                    let url = `{{ route('admin.getPackagePrice', ':id') }}`.replace(':id', packageId);

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
                            // Update the prices in the input fields
                            $('#at_home').val(data.at_home); // Fill home price
                            $('#at_spa').val(data.at_spa); // Fill spa price
                        })
                        .catch(error => console.error('Packages error:', error));
                });

                // Reset prices when changing booking type
                $('#booking_type').change(function () {
                    // Clear the prices
                    $('#at_home').val(''); // Clear home price
                    $('#at_spa').val(''); // Clear spa price
                });

                // Reset prices when changing categories
                $('#categories').change(function () {
                    // Clear the prices
                    $('#at_home').val(''); // Clear home price
                    $('#at_spa').val(''); // Clear spa price
                });

                // Reset prices when changing packages
                $('#packages').change(function () {
                    // Clear the prices
                    $('#at_home').val(''); // Clear home price
                    $('#at_spa').val(''); // Clear spa price
                });
            });

        });

    </script>
@endsection
