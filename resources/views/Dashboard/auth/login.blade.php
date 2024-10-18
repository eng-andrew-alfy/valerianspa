<!DOCTYPE html>
<html lang="en">

<head>
    @include('Dashboard.layouts.head')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap');
    </style>
    <style>
        * {
            font-family: "Cairo", sans-serif;
        }
    </style>
</head>

<body class="fix-menu">
    <section class="login p-fixed d-flex text-center bg-primary common-img-bg">
        <!-- Container-fluid starts -->
        <div class="container">

            <div dir="rtl" class="row">
                <div class="col-sm-12">
                    <!-- Authentication card start -->
                    <div class="login-card card-block auth-body m-auto">
                        <form class="md-float-material" id="loginForm" action="{{ route('admin.login') }}" method="POST">
                            @csrf
                            <div class="text-center">
                                <img src="{{ asset('dashboard/assets/images/logo.png') }}" alt="logo.png" />
                            </div>

                            <div style="background-color: rgba(255, 255, 255, 0.5);backdrop-filter: blur(10px);"
                                class="auth-box">
                                <div class="row m-b-20">
                                    <div class="col-md-12">
                                        <h3 style="color: rgb(32, 32, 32); font-weight: 700; "
                                            class="text-center txt-primary">
                                            تسجيل دخول خاص
                                            بالموظفين
                                        </h3>
                                    </div>

                                </div>
                                <hr />
                                @if ($errors->any())
                                    <div style="color: red;text-align: center">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                        <br>
                                    </div>
                                @endif
                                <div class="input-group">
                                    <input type="email" class="form-control " id="email" name="email"
                                        value="{{ old('email') }}" required
                                        placeholder="برجاء كتابة البريد الالكتروني" />



                                </div>

                                <div class="input-group">
                                    <input type="password" class="form-control " placeholder="برجاء كتابة كلمة السر"
                                        id="password" name="password" required />

                                </div>
                                <div class="row m-t-20 text-right">
                                    <div class="col-sm-7 col-xs-12">
                                        <div class="checkbox-fade fade-in-primary">
                                            <label>
                                                <input type="checkbox" id="remember" name="remember" />
                                                <span class="cr"><i
                                                        class="cr-icon icofont icofont-ui-check txt-primary"></i></span>
                                                <span class="text-inverse">تذكرنى</span>
                                            </label>
                                        </div>
                                    </div>

                                </div>
                                <div class="row m-t-10">
                                    <div class="col-md-12">
                                        <button type="submit" id="loginButton"
                                            class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20">Sign
                                            in</button>
                                    </div>
                                </div>
                                <hr />
                                <div dir="ltr" class="row">
                                    <div class="col-9">
                                        <p class="text-inverse text-left m-b-0">Thank you and enjoy our website.</p>
                                        <p class="text-inverse text-left"><b>Your Autentification Team</b></p>
                                    </div>
                                    <div class="col-3">
                                        <img src="{{ asset('dashboard/assets/images/auth/Logo-small-bottom.png') }}"
                                            alt="small-logo.png" />
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- end of form -->
                    </div>
                    <!-- Authentication card end -->
                </div>
                <!-- end of col-sm-12 -->
            </div>
            <!-- end of row -->
        </div>
        <!-- end of container-fluid -->
    </section>
    {{-- <script> --}}
    {{--    document.addEventListener('DOMContentLoaded', function() { --}}
    {{--        const loginButton = document.getElementById('loginButton'); --}}
    {{--        const loginForm = document.getElementById('loginForm'); --}}

    {{--        if (loginButton && loginForm) { --}}
    {{--            loginButton.addEventListener('click', function(event) { --}}
    {{--                // تأكد من عدم حدوث أي عملية افتراضية --}}
    {{--                event.preventDefault(); --}}

    {{--                // إرسال النموذج --}}
    {{--                loginForm.submit(); --}}
    {{--            }); --}}
    {{--        } --}}
    {{--    }); --}}
    {{-- </script> --}}

    @include('Dashboard.layouts.scripts-dashboard')
</body>

</html>
