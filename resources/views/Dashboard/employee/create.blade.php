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
@endsection
@section('title_page')
    أضافة موظف
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
        <div class="card-header">
            <form id="addPlaceForm" action="{{ route('admin.employees.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-sm-6 col-xl-3 m-b-30">
                        <p><code>*</code> كود الموظف </p>
                        <input type="text" name="employee_code" id="employee_code" class="form-control"
                            value="{{ rand(10000, 99999) }}" readonly required>
                    </div>
                    <div class="col-sm-6 col-xl-3 m-b-30">
                        <p><code>*</code> اسم الموظف بالعربية </p>
                        <input type="text" name="name_ar" id="name_ar" class="form-control"
                            placeholder="اسم الموظف بالعربية . مثل ( أندرو ألفى )" required>
                    </div>
                    <div class="col-sm-6 col-xl-3 m-b-30">
                        <p><code>*</code> اسم الموظف بالانجليزية </p>
                        <input type="text" name="name_en" id="name_en" class="form-control"
                            placeholder="اسم الموظف بالانجليزية . مثل ( Andrew Alfy )" required>
                    </div>
                    <div class="col-sm-6 col-xl-3 m-b-30">
                        <p><code>*</code> رقم الجوال ( واتساب فقط ) </p>
                        <input type="text" class="form-control mob_no" name="phone" id="phone"
                            data-mask="9999-999-999" placeholder="رقم الجوال ( واتساب فقط ) . مثل ( 05555555555 )" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code>جنسية الموظف </p>
                        <input type="text" name="employee_nationality" id="employee_nationality" class="form-control"
                            placeholder="جنسية الموظف  . مثل ( مصرى ) " required>
                    </div>
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code>البريد الإلكترونى</p>
                        <input type="email" name="email" id="email" class="form-control"
                            placeholder="االبريد الإلكترونى . مثل ( eng.andrew.alfy@gmail.com ) " required>
                    </div>
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code>رقم الهوية </p>
                        <input type="text" name="national_id" class="form-control" id="national_id"
                            placeholder="رقم الهوية . مثل ( 1015656595 ) " required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code> الخدمات </p>
                        <select class="js-example-rtl  col-sm-12" name="work_location">
                            <option value="home">خدمات منزلية</option>
                            <option value="spa">خدمات داخل الفرع</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code>أماكن العمل</p>
                        <select class="js-example-rtl  col-sm-12" multiple="multiple" style="width: 75%" name="place_ids[]">
                            <optgroup label="أماكن العمل">
                                @foreach ($places as $place)
                                    <option value="{{ $place->id }}">{{ $place->name }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code> أيام العمل </p>
                        <select class="js-example-rtl col-sm-12" name="working_days[]" multiple="multiple"
                            style="width: 75%" id="working_days">
                            <optgroup label="أيام العمل كل أسبوع">
                                @foreach ($daysOfWeek as $days)
                                    <option value="{{ $days->id }}">{{ $days->getTranslation('day_name', 'ar') }}
                                    </option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                </div>
                <div class="row">

                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code>البداية<code>أدخل الوقت بتنسيق AM/PM
                                HH:MM</code></p>


                        <input class="form-control" type="time" name="start_time" placeholder="09:00" />


                    </div>
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code>النهاية<code>أدخل الوقت بتنسيق AM/PM
                                HH:MM</code></p>
                        <input class="form-control" type="time" name="end_time" />


                    </div>
                    <div class="col-sm-12 col-xl-4 m-b-30">
                        <p><code>*</code> لون التقويم </p>
                        <input type="text" id="employee_color" name="employee_color" class="form-control demo"
                            data-control="saturation" value="{{ $color }}" readonly>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="display: block; margin: 0 auto;">أضافة موظف <i
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
@endsection
