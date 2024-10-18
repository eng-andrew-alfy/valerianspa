<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description"
        content="Book your spa and salon services online with ease. Enjoy our professional services and relax in style.">
    <meta name="keywords" content="Spa, Salon, Booking, Online, Reservation, Relaxation, Beauty">

    <title>@yield('title', 'Valerian Spa')</title>

    <link rel="icon" href="{{ asset('front/favicon.ico') }}" type="image/x-icon">

    <link rel="stylesheet" href="{{ asset('front/vendor/font-awesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/main.css') }}">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    {{--    <!-- sweet alert framework --> --}}
    {{--    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/sweetalert/css/sweetalert.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('front/css/items.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/modals.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/maps.css') }}">
    <style>
        .addToCartButton {
            cursor: pointer;
            /* يظهر مؤشر اليد عند مرور الماوس */
            transition: background-color 0.3s, transform 0.3s;
            /* تأثيرات انتقال سلسة */
        }

        .addToCartButton:hover {
            background-color: #f0f0f0;
            /* تغيير اللون الخلفي عند المرور */
            transform: scale(1.05);
            /* تكبير الزر قليلاً */
        }
    </style>
    <style>
        .categories-container .owl-carousel .owl-nav .owl-next,
        .categories-container .owl-carousel .owl-nav .owl-prev {
            background-color: #d9eff5;

        }

        .ms-modal {
            display: none;
            /* Hide the modal by default */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            /* Optional background overlay */
            z-index: 1000;
            /* Ensure it's on top of other content */
        }

        .ms-modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            padding: 20px;
            border-radius: 8px;
        }

        .model-gift {
            border-radius: 10px !important;
            border: 3px solid #136e81;
            -webkit-box-shadow: 32px 26px 28px -43px rgba(0, 129, 138, 0.226);
            -moz-box-shadow: 32px 26px 28px -43px rgba(0, 129, 138, 0.226);
            box-shadow: 32px 26px 28px -43px rgba(0, 129, 138, 0.226);
        }


        @media only screen and (max-width: 600px) {

            .ms-modal,
            .ms-modal-content {
                width: 95%;
                height: 100%;
            }

            .ms-modal-footer {
                margin: auto
            }
        }
    </style>
    @yield('head-front')

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if (app()->getLocale() == 'en')
        <style>
            body {
                font-family: "Arimo", sans-serif;
            }
        </style>
    @endif
</head>

<body dir="{{ app()->getLocale() == 'en' ? 'ltr' : 'rtl' }}">

    <div class="app">
        @include('front.layouts.nav')

        <div class="main-content">
            @yield('content-front')

        </div>
        <!-- Add GIFT modal -->
        <div class="ms-modal " id="gift-modal">
            <div class="ms-modal-content model-gift">
                <div class="ms-modal-header">
                    <button class="btn-no-style ms-modal-close">
                        <i style="color:  #136e81" class="icon fa-solid fa-times"></i>
                    </button>
                    <div class="ms-modal-title">
                        {{ __('gift.gift_to') }}
                    </div>
                </div>
                <div class="ms-modal-body">
                    <div class="map-container">
                        <div style="margin-top: -20px" class="ms-map-selected ">
                            <label style="font-weight: bold"><i class="fa-solid fa-phone"></i>
                                {{ __('gift.phone_number') }}</label>
                            <input type="text" name="phone" id="ms-phone-number">
                            <span id="phone-number-error" style="color: red;"></span>

                        </div>
                        <div class="phone-message">
                            <p style="text-align: justify; line-height: 24px">{{ __('gift.sms_info') }}</p>
                        </div>
                    </div>
                </div>
                <div class="ms-modal-footer">
                    <button class="ms-btn-primary" id="confirm-gift">{{ __('gift.confirm_gift') }}</button>
                </div>
            </div>
        </div>
        @include('front.layouts.footer')
        <a href="https://wa.me/966560476021" class="whatsapp-btn" target="_blank">
            <i class="fab fa-whatsapp"></i>
        </a>
    </div>

    <script src="{{ asset('front/vendor/font-awesome/js/all.min.js') }}"></script>
    <script src="{{ asset('front/vendor/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('front/js/maps.js') }}"></script>
    <script src="{{ asset('front/js/modals.js') }}"></script>

    {{-- <!-- sweet alert js --> --}}
    {{-- <script type="text/javascript" src="{{ asset('dashboard/assets/sweetalert/js/sweetalert.min.js') }}"></script> --}}
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('#nav-toggle').click(function() {
                $('#nav-mobile').toggleClass('active');
                $('body').toggleClass('no-scroll');
            });

            $('#nav-toggle-2').click(function() {
                $('#nav-mobile').toggleClass('active');
                $('body').toggleClass('no-scroll');
            });

            $('.nav-dropdown').each(function() {
                $(this).click(function() {
                    $('.nav-dropdown-links#' + $(this).data('target')).toggleClass('active');
                });
            });

            $('nav.desktop .nav-dropdown').each(function() {
                $(this).on('mouseover', function() {
                    $('.nav-dropdown-links#' + $(this).data('target')).addClass('active');
                })

                $(this).on('mouseleave', function() {
                    $('.nav-dropdown-links#' + $(this).data('target')).removeClass('active');
                })
            });


            $('.nav-user-profile').each(function() {
                $(this).on('mouseover', function() {
                    $(this).addClass('active')
                });

                $(this).on('mouseleave', function() {
                    $(this).removeClass('active')
                })
            });

            $('.lazyimage').each(function() {
                $(this).attr('src', $(this).data('src'));
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select all elements with the class "item-gift"
            const giftLinks = document.querySelectorAll('.item-gift');

            giftLinks.forEach(link => {
                link.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevent the default link behavior

                    // Get the modal ID from the data attribute
                    const modalId = this.getAttribute('data-modal');

                    // Select the modal element
                    const modal = document.querySelector(modalId);

                    if (modal) {
                        modal.style.display = 'block'; // Show the modal

                        // Optionally, add event listener to close the modal
                        const closeButton = modal.querySelector('.ms-modal-close');
                        if (closeButton) {
                            closeButton.addEventListener('click', function() {
                                modal.style.display = 'none'; // Hide the modal
                            });
                        }
                    }
                });
            });
        });
    </script>

    <script>
        @if (Session::has('message'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            }
            toastr.success("{{ session('message') }}");
        @endif

        @if (Session::has('error'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            }
            toastr.error("{{ session('error') }}");
        @endif

        @if (Session::has('info'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            }
            toastr.info("{{ session('info') }}");
        @endif

        @if (Session::has('warning'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            }
            toastr.warning("{{ session('warning') }}");
        @endif
    </script>

    {!! Toastr::message() !!}


    <script>
        const translations = {
            added_to_cart: @json(__('messages.added_to_cart')),
            added_service: @json(__('messages.added_service')),
            go_to_cart: @json(__('messages.go_to_cart')),
            continue_browsing: @json(__('messages.continue_browsing')),
            confirm_message: @json(__('messages.confirm_message')),
            confirm_replacement: @json(__('messages.confirm_replacement')),
            error_adding_to_cart: @json(__('messages.error_adding_to_cart')),
            error_updating_cart: @json(__('messages.error_updating_cart'))
        };

        // document.querySelectorAll('.addToCartButton').forEach(button => {
        //     button.addEventListener('click', function() {
        //         const serviceId = this.getAttribute('data-service-id');
        //         const serviceName = this.getAttribute('data-service-name');
        //         const serviceType = this.getAttribute('data-type');

        //         // بناء الـ URL مع المعطيات
        //         const url =
        //             `{{ route('ajaxCart') }}?serviceId=${serviceId}&serviceName=${encodeURIComponent(serviceName)}&serviceType=${serviceType}`;

        //         fetch(url, {
        //                 method: 'POST',
        //                 headers: {
        //                     'Content-Type': 'application/json',
        //                     'X-CSRF-TOKEN': '{{ csrf_token() }}'
        //                 },
        //                 // يمكن ترك الـ body فارغًا أو إضافة معطيات إضافية إن لزم
        //                 body: JSON.stringify({})
        //             })
        //             .then(response => response.json())
        //             .then(data => {
        //                 console.log('Response Data:', data);
        //                 if (data.success) {
        //                     Swal.fire({
        //                         title: translations.added_to_cart,
        //                         html: translations.added_service + '<br>' + translations
        //                             .confirm_message,
        //                         icon: "success",
        //                         showCancelButton: true,
        //                         confirmButtonColor: "#3085d6",
        //                         cancelButtonColor: "#d33",
        //                         confirmButtonText: translations.go_to_cart,
        //                         cancelButtonText: translations.continue_browsing
        //                     }).then((result) => {
        //                         if (result.isConfirmed) {
        //                             window.location.href = data.redirect_url;
        //                         }
        //                     });
        //                 } else {
        //                     Swal.fire({
        //                         title: translations.error_adding_to_cart,
        //                         text: 'Failed to add item to cart.',
        //                         icon: 'error'
        //                     });
        //                 }
        //             })
        //             .catch(error => {
        //                 console.error('Error:', error);
        //                 Swal.fire({
        //                     title: translations.error_updating_cart,
        //                     text: 'An error occurred while updating the cart.',
        //                     icon: 'error'
        //                 });
        //             });
        //     });
        // });

        // الحصول على التوكن من الكوكيز
        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
        }

        // إعداد طلبات AJAX
        $(document).ready(function() {
            const token = getCookie('token');

            $.ajaxSetup({
                headers: {
                    'Authorization': 'Bearer ' + token
                }
            });

            document.querySelectorAll('.addToCartButton').forEach(button => {
                button.addEventListener('click', function() {
                    const serviceId = this.getAttribute('data-service-id');
                    const serviceName = this.getAttribute('data-service-name');
                    const serviceType = this.getAttribute('data-type');
                    const localeServices = this.getAttribute('data-locale-services') || '';
                    const currentLocale = '{{ app()->getLocale() }}';

                    // عرض رسالة تأكيد للمستخدم
                    Swal.fire({
                        title: translations.added_to_cart,
                        html: translations.added_service + '<br>' + translations
                            .confirm_message,
                        icon: "success",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: translations.go_to_cart,
                        cancelButtonText: translations.continue_browsing
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // إنشاء نموذج مؤقت وإرساله عبر POST
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = localeServices ?
                                (currentLocale === 'ar' ? '{{ url('/ar') }}/' +
                                    localeServices + '/cart' : '{{ url('/en') }}/' +
                                    localeServices + '/cart') :
                                '{{ route('ajaxCart', ['type' => '']) }}';


                            const csrfToken = document.createElement('input');
                            csrfToken.type = 'hidden';
                            csrfToken.name = '_token';
                            csrfToken.value = '{{ csrf_token() }}';
                            form.appendChild(csrfToken);

                            const serviceIdInput = document.createElement('input');
                            serviceIdInput.type = 'hidden';
                            serviceIdInput.name = 'serviceId';
                            serviceIdInput.value = serviceId;
                            form.appendChild(serviceIdInput);

                            const serviceNameInput = document.createElement('input');
                            serviceNameInput.type = 'hidden';
                            serviceNameInput.name = 'serviceName';
                            serviceNameInput.value = serviceName;
                            form.appendChild(serviceNameInput);

                            const serviceTypeInput = document.createElement('input');
                            serviceTypeInput.type = 'hidden';
                            serviceTypeInput.name = 'serviceType';
                            serviceTypeInput.value = serviceType;
                            form.appendChild(serviceTypeInput);

                            // تخزين بيانات السلة في الجلسة
                            sessionStorage.setItem('cartData', JSON.stringify({
                                serviceId: serviceId,
                                serviceName: serviceName,
                                serviceType: serviceType,
                                localeServices: localeServices
                            }));

                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                });
            });

            // التحقق من وجود بيانات في الجلسة بعد تغيير اللغة وإعادة إرسال POST
            const cartData = sessionStorage.getItem('cartData');

            if (cartData) {
                const parsedCartData = JSON.parse(cartData);

                // إعداد نموذج POST
                const form = document.createElement('form');
                const currentLocale = '{{ app()->getLocale() }}';

                form.method = 'POST';
                form.action = parsedCartData.localeServices ?
                    (currentLocale === 'ar' ? '{{ url('/ar') }}/' + parsedCartData.localeServices + '/cart' :
                        '{{ url('/en') }}/' + parsedCartData.localeServices + '/cart') :
                    '{{ route('ajaxCart', ['type' => '']) }}';

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                const serviceIdInput = document.createElement('input');
                serviceIdInput.type = 'hidden';
                serviceIdInput.name = 'serviceId';
                serviceIdInput.value = parsedCartData.serviceId;
                form.appendChild(serviceIdInput);

                const serviceNameInput = document.createElement('input');
                serviceNameInput.type = 'hidden';
                serviceNameInput.name = 'serviceName';
                serviceNameInput.value = parsedCartData.serviceName;
                form.appendChild(serviceNameInput);

                const serviceTypeInput = document.createElement('input');
                serviceTypeInput.type = 'hidden';
                serviceTypeInput.name = 'serviceType';
                serviceTypeInput.value = parsedCartData.serviceType;
                form.appendChild(serviceTypeInput);

                document.body.appendChild(form);
                form.submit();

                // مسح البيانات بعد تقديم الطلب
                sessionStorage.removeItem('cartData');
            }
        });


        function updateCartCount(count) {
            document.querySelector('.nav-user-cart-count').textContent = count;
        }
    </script>


    <script>
        function confirmLanguageChange(url) {
            Swal.fire({
                title: '@lang('nav.language_change_confirm')',
                text: '@lang('nav.language_change_text')',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '@lang('nav.confirm_button')',
                cancelButtonText: '@lang('nav.cancel_button')'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }
    </script>
    @php
        $serviceType = Session::has('service_type') ? Session::get('service_type') : request()->route('type');
    @endphp
    <script>
        $(document).ready(function() {
            let selectedData = {};
            const token = getCookie('token');

            // إعداد إعدادات طلبات AJAX
            $.ajaxSetup({
                headers: {
                    'Authorization': 'Bearer ' + token
                }
            });

            // عند النقر على رابط الهدايا
            $('.item-gift').on('click', function(event) {
                event.preventDefault(); // منع الانتقال إلى الرابط

                // جلب البيانات من خصائص data-*
                const $this = $(this);
                selectedData = {
                    serviceId: $this.data('service-id'),
                    serviceName: $this.data('service-name'),
                    servicePrice: $this.data('service-price'),
                    serviceType: $this.data('type')
                };

                // عرض الـ Modal
                const modalId = $this.data('modal');
                $(modalId).show(); // يمكنك استخدام مكتبة أو طريقة مناسبة لعرض الـ Modal
            });

            // عند النقر على زر تأكيد الهدية
            $('#confirm-gift').on('click', function() {
                const phoneNumber = $('#ms-phone-number').val();
                const errorMessageSpan = $('#phone-number-error');

                errorMessageSpan.text('');
                // تأكد من أن رقم الهاتف ليس فارغًا
                if (phoneNumber.trim() === '') {
                    const translations = {
                        phoneNumberRequired: '{{ __('gift.phone_number_required') }}'
                    };
                    errorMessageSpan.text(translations.phoneNumberRequired);
                    return;
                }

                // جلب الترجمات من Laravel
                {{-- const translations = { --}}
                {{--    confirmPhoneTitle: '{{ __("gift.confirm_phone_title") }}', --}}
                {{--    confirmPhoneText: '{{ __("gift.confirm_phone_text", ["phoneNumber" => ""]) }}'.replace('', phoneNumber), --}}
                {{--    confirm: '{{ __("gift.confirm") }}', --}}
                {{--    edit: '{{ __("gift.edit") }}' --}}
                {{-- }; --}}
                const translations = {
                    confirmPhoneTitle: '{{ __('gift.confirm_phone_title') }}',
                    confirm: '{{ __('gift.confirm') }}',
                    edit: '{{ __('gift.edit') }}',
                    intro: '{{ __('gift.confirm_phone_intro') }}',
                    confirmPhoneQuestion: '{{ __('gift.confirm_phone_question') }}',
                };
                // عرض رسالة تأكيد للمستخدم
                Swal.fire({
                    title: translations.confirmPhoneTitle,
                    html: `
                <div style="text-align: center;">
                    <p style="font-size: 16px; color: #333;">${translations.intro}</p>
                    <p style="font-size: 20px; font-weight: bold; color: #3085d6;">
                        <i class="fas fa-phone"></i> ${phoneNumber}
                    </p>
                    <p>${translations.confirmPhoneQuestion}</p>
                </div>
            `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: translations.confirm,
                    cancelButtonText: translations.edit
                }).then((result) => {
                    if (result.isConfirmed) {
                        // إعداد نموذج POST
                        const form = document.createElement('form');
                        form.method = 'POST';

                        form.action = '{{ route('getGift', ['type' => '__SERVICE_TYPE__']) }}'
                            .replace('__SERVICE_TYPE__', '{{ $serviceType }}');

                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = '{{ csrf_token() }}';
                        form.appendChild(csrfToken);

                        const serviceIdInput = document.createElement('input');
                        serviceIdInput.type = 'hidden';
                        serviceIdInput.name = 'serviceId';
                        serviceIdInput.value = selectedData.serviceId;
                        form.appendChild(serviceIdInput);

                        const serviceNameInput = document.createElement('input');
                        serviceNameInput.type = 'hidden';
                        serviceNameInput.name = 'serviceName';
                        serviceNameInput.value = selectedData.serviceName;
                        form.appendChild(serviceNameInput);

                        const servicePriceInput = document.createElement('input');
                        servicePriceInput.type = 'hidden';
                        servicePriceInput.name = 'servicePrice';
                        servicePriceInput.value = selectedData.servicePrice;
                        form.appendChild(servicePriceInput);

                        const serviceTypeInput = document.createElement('input');
                        serviceTypeInput.type = 'hidden';
                        serviceTypeInput.name = 'serviceType';
                        serviceTypeInput.value = selectedData.serviceType;
                        form.appendChild(serviceTypeInput);

                        // إضافة رقم الهاتف إلى النموذج
                        const phoneNumberInput = document.createElement('input');
                        phoneNumberInput.type = 'hidden';
                        phoneNumberInput.name = 'phoneNumber';
                        phoneNumberInput.value = phoneNumber;
                        form.appendChild(phoneNumberInput);

                        document.body.appendChild(form);
                        form.submit();

                        // إخفاء الـ Modal بعد تقديم الطلب
                        $('#gift-modal').hide();
                    }
                    // إذا تم النقر على زر "تعديل"، لا نحتاج إلى إضافة أي كود هنا
                });
            });

            // إغلاق الـ Modal عند النقر على زر الإغلاق
            $('.ms-modal-close').on('click', function() {
                $('#gift-modal').hide();
            });

        });
    </script>
    {{-- <script> --}}
    {{--    // 🚫 Prevent right-click --}}
    {{--    document.addEventListener('contextmenu', function (e) { --}}
    {{--        e.preventDefault(); // Prevent the context menu from appearing --}}
    {{--    }); --}}

    {{--    // 📋 Prevent copying --}}
    {{--    document.addEventListener('copy', function (e) { --}}
    {{--        e.preventDefault(); // Prevent copying content --}}
    {{--    }); --}}

    {{--    // ✂️ Prevent pasting --}}
    {{--    document.addEventListener('paste', function (e) { --}}
    {{--        e.preventDefault(); // Prevent pasting content --}}
    {{--    }); --}}

    {{--    // 🔒 Disable F12 key --}}
    {{--    document.onkeydown = function (e) { --}}
    {{--        if (e.keyCode === 123) { // F12 --}}
    {{--            return false; // Prevent F12 key --}}
    {{--        } --}}
    {{--    }; --}}
    {{-- </script> --}}
    @yield('scripts-front')
</body>

</html>
