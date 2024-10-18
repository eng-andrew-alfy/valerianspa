@extends('front.layouts.app')

@section('title', __('nav.title', ['title' => __('nav.login')]))

@section('head-front')
    <link rel="stylesheet" href="{{ asset('front/css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/modals.css') }}">

    <style>
        .login-form-container {
            background-color: rgb(255, 255, 255)
        }

        .form-group,
        .form-title {
            color: #000;
        }

        .form-group label {
            color: #000;
        }

        .login-form {
            background-color: rgba(255, 255, 255, 0.5);
            /* 🎨 Semi-transparent white for a modern touch */
            backdrop-filter: blur(10px);
            /* 🌫 Adds a frosted glass effect for a luxurious appearance */
            -webkit-backdrop-filter: blur(5px);
            /* 🧩 WebKit support for the blur effect */
            border-radius: 10px;
            /* 🟢 Rounded corners for an elegant look */
            padding: 20px;
            /* 📏 Padding for better spacing between content and edges */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* 🌘 Light shadow to enhance the overall appearance */
        }
    </style>

@endsection


@section('content-front')
    <div class="login-form-container" id="phone-form-container">
        <div class="login-form">
            <form method="POST" id="phone-form" novalidate>
                @csrf
                <div class="form-group form-title">
                    {{ __('auth.register') }}
                </div>
                <div class="form-group">
                    <label for="name">{{ __('auth.name') }}</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}"
                        placeholder="{{ __('auth.name') }}" required autofocus>
                    <label for="phone">{{ __('auth.phone') }}</label>
                    <input type="phone" name="phone" id="phone" class="form-control" value="{{ old('phone') }}"
                        placeholder="{{ __('auth.phone') }}" required autofocus>

                </div>

                <div class="form-group" id="otp-group" style="display: none;">
                    <label for="otp">{{ __('auth.otp') }}</label>
                    <input type="text" name="otp" id="otp" class="form-control"
                        placeholder="{{ __('auth.enter_otp') }}">
                </div>

                <div class="form-group form-footer">
                    <button type="submit" class="form-submit" id="submit-button">{{ __('auth.register') }}</button>
                    {{--                    <a href="{{ route('login') }}" class="form-link"> {{ __('auth.login') }}</a> --}}
                </div>

                <div class="form-terms">
                    {{ __('auth.terms-text') }} <a href="{{ route('terms') }}">{{ __('auth.terms') }}</a>.
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts-front')

    <script src="{{ asset('front/vendor/jquery/jquery.mask.min.js') }}"></script>



    <script>
        var messages = {
            otpSent: "{{ __('auth.otp_sent') }}",
            otpSentInfo: "{{ __('auth.otp_sent_info') }}",
            otpVerified: "{{ __('auth.otp_verified') }}",
            otpError: "{{ __('auth.otp_error') }}",
            generalError: "{{ __('auth.general_error') }}",
            errorTitle: "{{ __('auth.error_title') }}",
            confirmButton: "{{ __('auth.confirm_button') }}"
        };

        $(document).ready(function() {
            $('#otp-group').hide();

            $('#phone-form').on('submit', function(e) {
                e.preventDefault();

                if ($('#otp-group').is(':visible')) {
                    var otp = $('#otp').val();
                    var phone = $('#phone').val();

                    $.ajax({
                        url: "{{ route('verify-otp') }}",
                        method: "POST",
                        data: {
                            _token: '{{ csrf_token() }}',
                            otp: otp,
                            phone: phone
                        },
                        success: function(response) {
                            localStorage.setItem('jwt_token', response.token);
                            console.log('AJAX response:', response);
                            if (response.redirect_url) {
                                Swal.fire({
                                    title: messages.otpVerified,
                                    text: messages.otpVerified,
                                    icon: 'success',
                                    confirmButtonText: messages.confirmButton
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = response.redirect_url;
                                    }
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: messages.errorTitle,
                                text: messages.otpError,
                                confirmButtonText: messages.confirmButton
                            });
                        }
                    });

                } else {
                    var phone = $('#phone').val();
                    var name = $('#name').val();

                    $.ajax({
                        url: "{{ route('send-otp') }}",
                        method: "POST",
                        data: {
                            _token: '{{ csrf_token() }}',
                            phone: phone,
                            name: name
                        },
                        success: function(response) {
                            $('#phone').prop('readonly', true);
                            $('#name').prop('readonly', true);
                            $('#otp-group').show();
                            $('#otp').prop('required', true);
                            $('#submit-button').text('{{ __('auth.verify') }}');
                            console.log('response:', response);
                            Swal.fire({
                                icon: 'info',
                                title: messages.otpSent,
                                text: messages.otpSentInfo,
                                confirmButtonText: messages.confirmButton
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: messages.errorTitle,
                                text: messages.generalError,
                                confirmButtonText: messages.confirmButton
                            });
                        }
                    });
                }
            });
        });
    </script>


    {{--    <script> --}}
    {{--        $(document).ready(function () { --}}
    {{--            $('input[type="phone"]').mask('05XXXXXXXX', { --}}
    {{--                placeholder: '05XXXXXXXX', --}}
    {{--                translation: { --}}
    {{--                    'X': { --}}
    {{--                        pattern: /[0-9]/, --}}
    {{--                        optional: false, --}}
    {{--                    } --}}
    {{--                }, --}}
    {{--                clearIfNotMatch: true, --}}
    {{--            }); --}}

    {{--            $('form').on('submit', function (e) { --}}
    {{--                var mobile = $('input[type="phone"]').val(); --}}
    {{--                var mobilePattern = /^05\d{8}$/; --}}

    {{--                if (!mobilePattern.test(mobile)) { --}}
    {{--                    e.preventDefault(); --}}
    {{--                    alert("{{ __('auth.phone-format') }}"); --}}
    {{--                } --}}
    {{--            }); --}}
    {{--        }); --}}
    {{--    </script> --}}
@endsection
