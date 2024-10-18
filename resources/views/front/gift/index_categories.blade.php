@extends('front.layouts.app')

@section('title', __('nav.title', ['title' => __('nav.cart')]))
@section('head-front')
    <link rel="stylesheet" href="{{ asset('front/css/cart.css') }}">
    <style>
        /* Input field styling for gift section */
        .input-gift {
            /* Inner color */
            border: 1px solid #000000b6; /* Border color */
            padding: 8px; /* Spacing inside the input */
            border-radius: 5px; /* Slightly rounded corners */

            /* Remove 3D effect */
            box-shadow: none;
            outline: none;

            /* Font style */
            font-family: "Almarai", sans-serif; /* Custom font */
            font-weight: 700; /* Bold text */
            font-size: 16px; /* Font size */
            text-align: center; /* Center text alignment */
            margin-left: 20px; /* Left margin */
            margin-bottom: 10px; /* Bottom margin */
            color: #136e81; /* Text color */
            width: 100%; /* Full width */
        }

        /* Input field focus effect */
        .input-gift:focus {
            border-color: #000000; /* Change border color on focus */
            box-shadow: 0 0 5px rgba(70, 70, 70, 0.5); /* Add subtle shadow effect on focus */
        }

        /* Map container styling */
        .map-cont {
            border: 2px solid #136e81; /* Border for map container */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 4px 10px #136f812d, 0 10px 20px #136f812d; /* Layered shadow effect */
        }

        /* Location container */
        .location-container {
            background-color: transparent !important; /* Transparent background */
            padding: 0 !important; /* Remove default padding */
        }

        /* Cart notes text area styling */
        .cart-container .cart-notes textarea {
            border: 1px solid #020202; /* Solid black border */
            background-color: transparent !important; /* Transparent background for textarea */
        }

        /* Bold font styling */
        .font-bold {
            color: #000000 !important; /* Force text color to black */
        }
    </style>

@endsection

@section('content-front')
    @php
        $locale = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale();
          $locale_type = Session::has('service_type') ? Session::get('service_type') : request()->route('type');
        $price = $locale_type === 'homeServices' ? 'at_home' : 'at_spa';
    @endphp

    <div class="cart-container">
        <form id="bookingFormGiftCategory" action="{{ route('PaymentGIFT.MyFatoorah') }}" method="post">
            @csrf

            <div class="cart">
                <div class="cart-items-container">
                    <div style="height:110px !important;" class="location-container">
                        <div class="flex-auto d-col-flex gap-5px">
                            <div class="font-bold"> برجاء كتابة إسم المهدى له <span style="color: red">*</span></div>
                            <div id="location-desc">
                                <input class="input-gift" type="text" name="name_recipient" id="name_recipient" required
                                       value="">
                            </div>
                            <input type="hidden" name="phone_recipient" value="{{ $Data->phone }}" readonly>
                        </div>
                    </div>
                    <div style="height:110px !important;" class="location-container">
                        <div class="flex-auto d-col-flex gap-5px">
                            <div class="font-bold">رقم المهدى له</div>
                            <div class="unique-datepicker-container">
                                <input style="background-color: #4e4e4e13; color: #000000 ;   cursor: not-allowed; "
                                       type="text" class="input-gift" name="phone_recipient" id="phone_recipient"
                                       value="{{ $Data->phone }}" readonly>
                            </div>

                        </div>

                    </div>


                    <div class="cart-notes">
                        <label for="notes" class="title">{{ __('cart.addnotes') }}</label>
                        <textarea name="notes" placeholder="{{ __('cart.notes') }}"></textarea>
                    </div>

                    {{--                    <div class="cart-items">--}}
                    {{--                        <div class="title">--}}
                    {{--                            {{ __('cart.services') }}--}}
                    {{--                        </div>--}}
                    {{--                        <div class="items">--}}
                    {{--                            <div class="item">--}}
                    {{--                                <div class="info-container">--}}
                    {{--                                    <div class="service-image"--}}
                    {{--                                         style="background-image: url({{ asset('front/uploads/images/1717040735974333.webp') }})"--}}
                    {{--                                         title="service image"></div>--}}
                    {{--                                    <div class="service-info flex-auto">--}}
                    {{--                                        <div class="service-name">--}}
                    {{--                                            {{ $Data->category->getTranslation('name', $locale) }}--}}
                    {{--                                        </div>--}}
                    {{--                                        <div class="more">--}}
                    {{--                                            <i class="fa fa-circle"></i>--}}
                    {{--                                            <div>{{ $Data->category->duration_minutes }} {{ __('messages.minutes') }}</div>--}}
                    {{--                                        </div>--}}
                    {{--                                    </div>--}}
                    {{--                                    <div class="price">--}}
                    {{--                                        <span--}}
                    {{--                                            class="font-bold font-16">{{ $Data->category->discount ? $Data->category->discount->at_home ?? '0' : $Data->category->prices->at_home ?? '0' }}</span>--}}
                    {{--                                        {{ __('cart.sar') }}--}}
                    {{--                                    </div>--}}
                    {{--                                </div>--}}

                    {{--                            </div>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}
                </div>

                <div class="cart-footer-container">

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
                                <div class="font-bold">{{ __('cart.price') }}</div>
                                <div
                                    style="@if ($Data->category->discount) text-decoration-line: line-through; color: #6f6f6f @endif "><span
                                        class="weight-600 font-16">{{ $Data->category->prices->$price }}</span>
                                    {{ __('cart.sar') }}
                                </div>
                            </div>

                            @if ($Data->category->discount)
                                <div class="d-row-flex justify-between">
                                    <div class="font-bold">{{__('cart.price_offer')}}</div>
                                    <div><span
                                            class="weight-600 font-16">{{ $Data->category->discount->$price }} </span>
                                        {{ __('cart.sar') }}
                                    </div>
                                </div>

                            @endif
                            <div class="d-row-flex justify-between">
                                <div class="font-bold">{{ __('cart.discount') }}</div>
                                <div><span class="weight-600 font-16"
                                           id="discount_show">0.00</span> {{ __('cart.sar') }}
                                </div>
                            </div>
                            <div class="d-row-flex justify-between">
                                <div class="font-bold">{{ __('cart.vat') }}</div>
                                <div><span
                                        class="weight-600 font-16">{{ ($Data->category->discount ? $Data->category->discount->$price : $Data->category->prices->$price) * 0.15 }}</span>
                                    <input type="hidden" name="categoryId" value="{{ $Data->category->id }}">
                                    {{ __('cart.sar') }}
                                </div>
                            </div>

                            <div class="d-row-flex justify-between" id="tamaraExtraInfo" style="display: none;">
                                <div class="font-bold">ضريبة القيمة المضافة تمارا</div>
                                <div><span class="weight-600 font-16"
                                           id="texTamara">{{ ($Data->category->discount ? $Data->category->discount->$price : $Data->category->prices->$price) * 0.07 }}</span>
                                    {{ __('cart.sar') }}
                                </div>
                            </div>
                        </div>
                        <div class="d-row-flex justify-between padding-t-10">
                            <div class="font-bold">{{ __('cart.gtotal') }}</div>
                            <div><span class="weight-600 font-16"
                                       id="totalAmount">{{ $Data->category->discount ? $Data->category->discount->$price ?? '0' : $Data->category->prices->$price ?? '0' }}</span> {{ __('cart.sar') }}
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">{{ __('cart.complete') }}</button>
                </div>
            </div>
        </form>


        @endsection

        @section('scripts-front')
            <script src="{{ asset('front/js/cart.js') }}"></script>
            <script>

                document.addEventListener('DOMContentLoaded', function () {
                    const tamaraRadio = document.getElementById('tamaraRadio');
                    const tamaraExtraInfo = document.getElementById('tamaraExtraInfo');
                    const texTamara = document.getElementById('texTamara');
                    const totalAmountElement = document.getElementById('totalAmount');

                    // Function to update the total amount based on Tamara selection
                    function updateTotalAmount() {
                        // Read the current values
                        const originalAmount = parseFloat(totalAmountElement.getAttribute('data-original-amount'));
                        const tamaraVat = parseFloat(texTamara.innerText);

                        if (tamaraRadio.checked) {
                            tamaraExtraInfo.style.display = 'flex';
                            const newTotal = originalAmount + tamaraVat;
                            totalAmountElement.innerText = newTotal.toFixed(2);
                        } else {
                            tamaraExtraInfo.style.display = 'none';
                            totalAmountElement.innerText = originalAmount.toFixed(2);
                        }
                    }

                    // Initialize the original amount in a data attribute for reference
                    totalAmountElement.setAttribute('data-original-amount', parseFloat(totalAmountElement.innerText));

                    // Listen for changes on the Tamara radio button
                    tamaraRadio.addEventListener('change', updateTotalAmount);

                    // If there are other elements that may affect the total amount,
                    // such as other radio buttons or inputs, add listeners for them.
                    document.querySelectorAll('input[type=radio], input[type=checkbox]').forEach(function (input) {
                        input.addEventListener('change', updateTotalAmount);
                    });
                });
            </script>

            <script>
                $(document).ready(function () {
                    let originalAmount = parseFloat($('#totalAmount').text()); // السعر الأصلي
                    let discountedAmount = originalAmount; // المبلغ بعد الخصم

                    // تحديث المبلغ الكلي بناءً على البوابة المختارة والخصم
                    function updateTotalAmount() {
                        const tamaraVat = parseFloat($('#texTamara').text());
                        let finalAmount = discountedAmount; // نبدأ بالمبلغ المخفض بعد تطبيق الخصم

                        if ($('#tamaraRadio').is(':checked')) {
                            finalAmount += tamaraVat; // إضافة ضريبة تمارا إذا تم تحديدها
                            $('#tamaraExtraInfo').css('display', 'flex');
                            $('#bookingFormGiftCategory').attr('action', '{{ route('PaymentGIFT.TAMARA') }}');
                        } else {
                            $('#tamaraExtraInfo').css('display', 'none');
                            $('#bookingFormGiftCategory').attr('action', '{{ route('PaymentGIFT.MyFatoorah') }}');
                        }

                        $('#totalAmount').text(finalAmount.toFixed(2)); // تحديث عرض المبلغ الكلي
                    }

                    // عرض حقل القسيمة عند النقر على الزر
                    $('.show-coupon-input').on('click', function () {
                        $('#coupon').html(`
            <input type="text" name="coupon_code" id="coupon_code" placeholder="{{ __('cart.addcoupon') }}">
            <button type="button" id="apply-coupon" class="btn-addcoupon">{{ __('cart.apply') }}</button>
        `);
                    });

                    // التعامل مع تطبيق القسيمة
                    $(document).on('click', '#apply-coupon', function () {
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

                        const categoryId = {{ $Data->category->id }} || null;
                        const originalAmount = parseFloat($('#original_amount')
                            .text()); // تأكد من تعريف originalAmount بشكل صحيح
                        let discountedAmount = originalAmount;

                        $.ajax({
                            url: '{{ route('checkCouponAllGIFTS') }}',
                            method: 'POST',
                            data: {
                                coupon_code: couponCode,
                                category_id: categoryId,
                                time_available: timeAvailable, // إرسال الوقت المحدد
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
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
                            error: function (xhr) {
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
