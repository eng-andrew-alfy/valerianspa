@extends('Dashboard.layouts.master')
{{--@section('title_dashboard')--}}
{{--@endsection--}}
@section('css_dashboard')
    <!-- Date-time picker css -->
    <link rel="stylesheet" type="text/css"
          href="{{asset('dashboard/assets/pages/advance-elements/css/bootstrap-datetimepicker.css')}}"/>
    <!-- Date-range picker css  -->
    <link rel="stylesheet" type="text/css"
          href="{{asset('dashboard/assets/bootstrap-daterangepicker/css/daterangepicker.css')}}"/>
    <!-- Date-Dropper css -->
    <link rel="stylesheet" type="text/css" href="{{asset('dashboard/assets//datedropper/css/datedropper.min.css')}}"/>
    <!-- Color Picker css -->
    <link rel="stylesheet" type="text/css" href="{{asset('dashboard/assets/spectrum/css/spectrum.css')}}"/>
    <!-- Mini-color css -->
    <link rel="stylesheet" type="text/css"
          href="{{asset('dashboard/assets/pages/jquery-minicolors/css/jquery.minicolors.css')}}"/>
@endsection
@section('title_page')
    أضافة مستخدم
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
            <div class="row">
                <!-- col -->
                <div class="col-lg-4">
                    <ul id="treeview1">
                        <li><a href="#">{{ $role->name }}</a>
                            <ul>
                                @if(!empty($rolePermissions))
                                    @foreach($rolePermissions as $v)
                                        <li>{{ $v->name }}</li>
                                    @endforeach
                                @endif
                            </ul>
                        </li>
                    </ul>
                </div>
                <!-- /col -->
            </div>
        </div>
    </div>

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
    <!-- Masking js -->
    <script src="{{asset('dashboard/assets/pages/form-masking/inputmask.js')}}"></script>
    <script src="{{asset('dashboard/assets/pages/form-masking/jquery.inputmask.js')}}"></script>
    <script src="{{asset('dashboard/assets/pages/form-masking/autoNumeric.js')}}"></script>
    <script src="{{asset('dashboard/assets/pages/form-masking/form-mask.js')}}"></script>
    <!-- Bootstrap date-time-picker js -->
    <script type="text/javascript"
            src="{{asset('dashboard/assets/pages/advance-elements/moment-with-locales.min.js')}}"></script>
    <script type="text/javascript"
            src="{{asset('dashboard/assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script type="text/javascript"
            src="{{asset('dashboard/assets/pages/advance-elements/bootstrap-datetimepicker.min.js')}}"></script>
    <!-- Date-range picker js -->
    <script type="text/javascript"
            src="{{asset('dashboard/assets/bootstrap-daterangepicker/js/daterangepicker.js')}}"></script>
    <!-- Date-dropper js -->
    <script type="text/javascript" src="{{asset('dashboard/assets/datedropper/js/datedropper.min.js')}}"></script>
    <!-- Color picker js -->
    <script type="text/javascript" src="{{asset('dashboard/assets/spectrum/js/spectrum.js')}}"></script>
    <script type="text/javascript" src="{{asset('dashboard/assets/jscolor/js/jscolor.js')}}"></script>
    <!-- Mini-color js -->
    <script type="text/javascript"
            src="{{asset('dashboard/assets/pages/jquery-minicolors/js/jquery.minicolors.min.js')}}"></script>
    <!-- Custom js -->
    <script type="text/javascript" src="{{asset('dashboard/assets/pages/advance-elements/custom-picker.js')}}"></script>
    <script>
        function validatePasswords() {
            var password = document.getElementById('password-2').value;
            var confirmPassword = document.getElementById('confirm-2').value;
            var errorMessage = document.getElementById('validation-message');
            var successMessage = document.getElementById('validation-success');

            // Check if any field is empty
            if (password === '' || confirmPassword === '') {
                errorMessage.style.display = 'none';
                successMessage.style.display = 'none';
                return;
            }

            // Check if password length is at least 6 characters
            if (password.length < 6) {
                errorMessage.textContent = 'كلمة المرور يجب أن تكون أطول من 5 أحرف';
                errorMessage.style.display = 'block';
                successMessage.style.display = 'none';
                return;
            }

            // Check if passwords match
            if (password !== confirmPassword) {
                errorMessage.textContent = 'كلمة المرور غير متطابقة';
                errorMessage.style.display = 'block';
                successMessage.style.display = 'none';
            } else {
                errorMessage.style.display = 'none';
                successMessage.style.display = 'block';
            }
        }

    </script>
@endsection
