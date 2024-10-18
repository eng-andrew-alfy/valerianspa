@php
    $locale = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale();
@endphp
@extends('front.layouts.app')


@section('title', __('nav.title', ['title' => 'Packages']))

@section('head-front')
    <link rel="stylesheet" href="{{ asset('front/css/service.css') }}">
    <style>
        .body {
            font-family: 'Almarai', sans-serif !important
        }

        .description p {
            font-family: 'Almarai', sans-serif !important
            font-size: 18px;
            font-weight: 300;
            margin-bottom: 20px;
            font-size: 16px;
            line-height: 32px;
            color: hsl(0, 0%, 0%) !important;
        }

        .description li {
            font-family: 'Almarai', sans-serif !important
            font-size: 18px;
            font-weight: 300;
            margin-bottom: 20px;
            font-size: 16px;
            line-height: 32px;
            color: hsl(0, 0%, 0%) !important;
        }

        .tamara-btn {
            background: none;
            border: 1px solid #bbb !important;
            cursor: pointer;
            padding: 10px;
            width: 100%;
            border-radius: 10px;
            display: flex;
            align-items: center;
        }

        .tamara-txt {
            flex-grow: 1;
            text-align: start;
            margin: 5px;
            font-family: 'Almarai', sans-serif;
            font-weight: 400;
            font-size: 16px
        }

        .tamara-img {
            margin-right: auto;
            margin: 5px
        }
    </style>
    <script>
        var tamaraWidgetConfig = {
            lang: 'en', // Language. Default is Arabic. We support [ar|en]
            country: 'SA', // Country code. User ISO format.
            publicKey: '1afacd70-15e5-4220-a14a-64d842d40618', // Public key that you can get from tamara system.
            style: { // Optional to define CSS variable,
                cardSnippet: {
                    title: {
                        fontSize: '12px',
                        lineHeight: '19px'
                        large: {
                            fontSize: '14px',
                            lineHeight: '22px'
                        }
                    }
                    subTitle: {
                        fontSize: '12px',
                        lineHeight: '19px'
                        large: {
                            fontSize: '14px',
                            lineHeight: '22px'
                        }
                    }
                    rtl: {
                        title: {
                            fontSize: '12px',
                            lineHeight: '19px'
                            large: {
                                fontSize: '14px',
                                lineHeight: '22px'
                            }
                        }
                        subTitle: {
                            fontSize: '12px',
                            lineHeight: '19px'
                            large: {
                                fontSize: '14px',
                                lineHeight: '22px'
                            }
                        }
                    }
                }
            }
        };
    </script>

    <script defer src="https://cdn.tamara.co/widget-v2/tamara-widget.js"></script>
@endsection

@section('content-front')
    @php
        $locale_type = Session::has('service_type') ? Session::get('service_type') : request()->route('type');
        $price = $locale_type === 'homeServices' ? 'at_home' : 'at_spa';

    @endphp
    <div class="body">
        <div class="banner-container">
            <img src="{{ asset('front/uploads/images/1722192830.webp') }}" alt="">
        </div>
        <div class="service-container">
            <div class="title-container">
                <div style="font-size: 18px ; font-weight: 600; color:#000000" class="title">
                    {{ $package->getTranslation('name', $locale) }}</div>
                <div class="subinfo">
                    <div class="price-container">
                        <div style="font-size: 16px; font-weight: 600; color:#000000; margin: 10px 0px 10px 0px"
                             class="title">
                            {{ $locale == 'en' ? 'Package Description' : 'وصف الباقة' }}
                        </div>

                        <div style="font-weight: 300; font-size: 16px; margin: 10px 0px 10px 0px" class="duration">
                            <span style="font-weight: 400; font-size: 16px">
                                {{ $locale == 'en' ? 'Session duration:' : 'مدة الجلسة :' }}
                            </span>
                            {{ $package->duration_minutes }}
                            {{ $locale == 'en' ? 'minutes' : 'دقيقة' }}
                        </div>

                        <span>
                            @if ($package->discount)
                                {{ $locale == 'en' ? 'Before : ' : 'قبل : ' }}
                            @else
                                {{ __('general.start_from') }}
                            @endif
                        </span>
                        <span class="price"
                              style="@if ($package->discount) text-decoration-line: line-through;color: #136e81; opacity: 0.6; @endif">{{ $package->prices->$price }}
                        </span>
                        {{ __('general.sar') }}
                        &nbsp;
                        &nbsp;
                        @if ($package->discount)
                            <span class="price">{{ $package->discount->$price }}</span>
                            {{ __('general.sar') }}
                        @endif
                    </div>
                    {{-- <div class="duration">{{ $package->duration_minutes }} {{ __('general.minutes') }}</div> --}}
                </div>
                <div class="service-btns">

                    @if ($package->is_active == true)
                        <button class="addToCartButton btn-booknow" style="pointer-events: painted"
                                data-service-id="{{ $package->id }}"
                                data-service-name="{{ $package->getTranslation('name', $locale) }}"
                                data-service-price="{{ $package->discount ? $package->discount->$price : $package->prices->$price }}"
                                data-type="category" data-locale-services="{{ request()->route('type') }}">

                            <i class="icon fas fa-plus"></i>
                            {{ __('messages.added_to_cart') }}
                        </button>
                        <button class="btn-sendasgift item-gift" data-service-id="{{ $package->id }}"
                                data-service-name="{{ $package->getTranslation('name', $locale) }}"
                                data-service-price="{{ $package->discount ? $package->discount->$price : $package->prices->$price }}"
                                data-type="category" data-modal="#gift-modal"
                                data-locale-services="{{ request()->route('type') }}">

                            <i class="icon fas fa-gift"></i>
                            {{ __('general.sendasgift') }}

                        </button>
                    @else
                        <button class="btn-booknow" style="cursor: no-drop  ;background-color: darkred;" disabled>
                            <i class="icon fas fa-plus"></i> {{ __('general.booknow') }}
                        </button>
                        <button class="btn-sendasgift item-gift"
                                style="cursor: no-drop ;background-color: darkred; color: whitesmoke"><i
                                class="icon fas fa-gift"></i> {{ __('general.sendasgift') }}
                        </button>
                    @endif
                </div>
            </div>

            <div class="description">
                <p>
                    {{ $package->getTranslation('description', $locale) }}
                </p>
                <h3>{{ __('general.benefits') }}</h3>
                <ul>
                    @foreach ($package->getTranslation('benefits', $locale) as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>

            </div>
            <div class="service-footer">
                @if ($package->is_active == true)
                    <button class="addToCartButton btn-booknow" style="pointer-events: painted"
                            data-service-id="{{ $package->id }}"
                            data-service-name="{{ $package->getTranslation('name', $locale) }}"
                            data-service-price="{{ $package->discount ? $package->discount->$price : $package->prices->$price }}"
                            data-type="category" data-locale-services="{{ $locale_type }}">
                        {{ __('messages.added_to_cart') }}
                    </button>
                    <button class="btn-sendasgift item-gift" data-service-id="{{ $package->id }}"
                            data-service-name="{{ $package->getTranslation('name', $locale) }}"
                            data-service-price="{{ $package->discount ? $package->discount->$price : $package->prices->$price }}"
                            data-type="category" data-modal="#gift-modal" data-locale-services="{{ $locale_type }}">

                        <i class="fas fa-gift"></i>
                        {{ __('general.sendasgift') }}

                    </button>
                @else
                    <button class="btn-booknow" style="cursor: no-drop  ;background-color: darkred;" disabled>
                        {{ __('general.not_available_service') }}

                    </button>
                    <button class="btn-sendasgift item-gift"
                            style="cursor: no-drop ;background-color: darkred; color: whitesmoke">

                        <i class="fas fa-gift"></i>
                        {{ __('general.sendasgift') }}
                    </button>
                @endif

            </div>
            <tamara-widget type="tamara-summary" inline-type="5"></tamara-widget>

        </div>
    </div>
@endsection

@section('scripts-front')
    <script src="{{ asset('front/vendor/OwlCarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('front/js/modals.js') }}"></script>
    <script src="{{ asset('front/js/maps.js') }}"></script>
@endsection
