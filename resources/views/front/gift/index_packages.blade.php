@extends('front.layouts.app')

@section('title', __('nav.title', ['title' => __('nav.cart')]))
@section('head-front')
    <link rel="stylesheet" href="{{ asset('front/css/modals.css') }}">

    <link rel="stylesheet" href="{{ asset('front/css/maps.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/date-bicker.css') }}">
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
        <form id="bookingFormGiftPackage" action="{{ route('PaymentGIFT.MyFatoorah') }}" method="POST">
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
                        {{-- <div>
                            <button class="btn-unset color-6 font-18" type="button" id="edit-location-btn">
                                <i class="fa fa-edit"></i>
                            </button>
                        </div> --}}
                    </div>


                    <div class="cart-notes">
                        <label for="notes" class="title">{{ __('cart.addnotes') }}</label>
                        <textarea name="notes" placeholder="{{ __('cart.notes') }}"></textarea>
                    </div>


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
                                    style="@if ($Data->package->discount) text-decoration-line: line-through; color: #6f6f6f @endif "><span
                                        class="weight-600 font-16">{{ $Data->package->prices->$price }}</span>
                                    {{ __('cart.sar') }}
                                </div>
                            </div>

                            @if ($Data->package->discount)
                                <div class="d-row-flex justify-between">
                                    <div class="font-bold">{{__('cart.price_offer')}}</div>
                                    <div><span
                                            class="weight-600 font-16">{{ $Data->package->discount->$price }} </span>
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
                                        class="weight-600 font-16">{{ ($Data->package->discount ? $Data->package->discount->$price : $Data->package->prices->$price) * 0.15 }}</span>
                                    <input type="hidden" name="packageId" value="{{ $Data->package->id }}">
                                    {{ __('cart.sar') }}
                                </div>
                            </div>

                            <div class="d-row-flex justify-between" id="tamaraExtraInfo" style="display: none;">
                                <div class="font-bold">ضريبة القيمة المضافة تمارا</div>
                                <div><span class="weight-600 font-16"
                                           id="texTamara">{{ ($Data->package->discount ? $Data->package->discount->$price : $Data->package->prices->$price) * 0.07 }}</span>
                                    {{ __('cart.sar') }}
                                </div>
                            </div>
                        </div>
                        <div class="d-row-flex justify-between padding-t-10">
                            <div class="font-bold">{{ __('cart.gtotal') }}</div>
                            <div><span class="weight-600 font-16"
                                       id="totalAmount">{{ $Data->package->discount ? $Data->package->discount->$price ?? '0' : $Data->package->prices->$price ?? '0' }}</span> {{ __('cart.sar') }}
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
                $(document).ready(function () {
                    let originalAmount = parseFloat($('#totalAmount').text());
                    let discountedAmount = originalAmount;

                    // تحديث المبلغ الكلي بناءً على البوابة المختارة والخصم
                    function updateTotalAmount() {
                        const tamaraVat = parseFloat($('#texTamara').text());
                        let finalAmount = discountedAmount; // نبدأ بالمبلغ المخفض بعد تطبيق الخصم

                        if ($('#tamaraRadio').is(':checked')) {
                            finalAmount += tamaraVat; // إضافة ضريبة تمارا إذا تم تحديدها
                            $('#tamaraExtraInfo').css('display', 'flex');
                            $('#bookingFormGiftPackage').attr('action', '{{ route('PaymentGIFT.TAMARA') }}');
                        } else {
                            $('#tamaraExtraInfo').css('display', 'none');
                            $('#bookingFormGiftPackage').attr('action', '{{ route('PaymentGIFT.MyFatoorah') }}');
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

                        if (!couponCode) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Please enter a coupon code.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                            return;
                        }
                        const packageId = {{ $Data->package->id }} || null;
                        const originalAmount = parseFloat($('#original_amount')
                            .text()); // تأكد من تعريف originalAmount بشكل صحيح
                        let discountedAmount = originalAmount;

                        $.ajax({
                            url: '{{ route('checkCouponAllGIFTS') }}',
                            method: 'POST',
                            data: {
                                coupon_code: couponCode,
                                package_id: packageId,
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
                                    title: 'خطأ!',
                                    text: 'حدث خطأ أثناء معالجة طلبك. يرجى المحاولة مرة أخرى.',
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
