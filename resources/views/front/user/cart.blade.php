@extends('layouts.app')

@section('title', __('nav.title', ['title' => __('nav.cart')]))

@section('head')
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
@endsection

@section('content')
    <div class="cart-container">
        <div class="cart">
            <div class="cart-items-container">
                <div class="location-container">
                    <div class="color-6 font-18">
                        <i class="fa fa-location-dot" aria-hidden="true"></i>
                    </div>
                    <div class="flex-auto d-col-flex gap-5px">
                        <div class="font-bold">this is the location name</div>
                        <div class="font-12 location-desc">this is the location on map this is the location on map this is
                            the location on map</div>
                    </div>
                    <div>
                        <button class="btn-unset color-6 font-18" type="button">
                            <i class="fa fa-edit"></i>
                        </button>
                    </div>
                </div>
                <div class="cart-notes">
                    <label for="notes" class="title">{{ __('cart.addnotes') }}</label>
                    <textarea name="notes" placeholder="{{ __('cart.notes') }}"></textarea>
                </div>
                <div class="cart-items">
                    <div class="title">
                        {{ __('cart.services') }}
                    </div>
                    <div class="items">
                        <div class="item">
                            <div class="info-container">
                                <div class="service-image"
                                    style="background-image: url({{ asset('uploads/images/1717040735974333.webp') }})"
                                    title="service image"></div>
                                <div class="service-info flex-auto">
                                    <div class="service-name">
                                        Relax massage & Sudanese delka
                                    </div>
                                    <div class="more">
                                        <div>90 minutes</div>
                                        <i class="fa fa-circle"></i>
                                        <div>specialist</div>
                                    </div>
                                </div>
                                <div class="price">
                                    <span class="font-bold font-16">299</span> {{ __('cart.sar') }}
                                </div>
                            </div>
                            <div class="item-footer">
                                <div class="service-date">
                                    <i class="fa-solid fa-calendar-days"></i>
                                    <div>Tuesday 27 Aug 2024 10:00 AM</div>
                                </div>
                                <div class="actions">
                                    <button type="button" class="btn-edit">{{ __('cart.edit') }}</button>
                                    <button type="button" class="btn-delete color-danger"><i
                                            class="fa fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="cart-footer-container">
                <div class="title">
                    {{ __('cart.receipt') }}
                </div>
                <div class="payment-method d-col-flex gap-10">
                    <div class="font-14 font-bold">{{ __('cart.paymethod') }}</div>
                    <div class="methods d-col-flex gap-20px">
                        <label class="ms-radio-label">
                            <input type="radio" name="payment_method" value="myfatoorah" checked>
                            <div class="ms-radio-label-content">
                                <div>{{ __('cart.paynow') }}</div>
                                <div class="ms-img">
                                    <img src="{{ asset('images/payment-logo/v.png') }}">
                                </div>
                                <div class="ms-img">
                                    <img src="{{ asset('images/payment-logo/m.png') }}">
                                </div>
                                <div class="ms-img">
                                    <img src="{{ asset('images/payment-logo/md.png') }}">
                                </div>
                            </div>

                        </label>
                        <label class="ms-radio-label">
                            <input type="radio" name="payment_method" value="tamara"
                                {{ old('payment_method') == 'tamara' ? 'checked' : '' }}>
                            <div class="ms-radio-label-content">
                                <div>{{ __('cart.tamara') }}</div>
                                <div class="ms-img tamara" style="width:70px;">
                                    <img src="{{ asset('images/payment-logo/tamara.png') }}">
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
                            <div class="font-bold">{{ __('cart.total') }}</div>
                            <div><span class="weight-600 font-16">999.00</span> {{ __('cart.sar') }}</div>
                        </div>
                        <div class="d-row-flex justify-between">

                            <div class="font-bold">{{ __('cart.discount') }}</div>
                            <div><span class="weight-600 font-16">0.00</span> {{ __('cart.sar') }}</div>

                        </div>
                        <div class="d-row-flex justify-between">
                            <div class="font-bold">{{ __('cart.vat') }}</div>
                            <div><span class="weight-600 font-16">20.00</span> {{ __('cart.sar') }}</div>
                        </div>
                    </div>
                    <div class="d-row-flex justify-between padding-t-10">
                        <div class="font-bold">{{ __('cart.gtotal') }}</div>
                        <div><span class="weight-600 font-16">999.00</span> {{ __('cart.sar') }}</div>
                    </div>
                </div>

                <button type="submit" class="btn-submit">{{ __('cart.complete') }}</button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script>
        $('.show-coupon-input').on('click', function() {
            $('#coupon').html(`<input type="text" name="coupon" id="" placeholder="{{ __('cart.addcoupon') }}">
                    <button type="button" class="btn-addcoupon">{{ __('cart.apply') }}</button>`);
        })
    </script>
@endsection
