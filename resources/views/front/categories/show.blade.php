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

        /* حاوية الزر */
        .button-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 30px;
            width: 100%;
        }

        /* تصميم الزر */
        .tamara-btn {
            background: none;
            border: 1px solid #bbb !important;
            cursor: pointer;
            padding: 10px;
            width: 90%;
            max-width: 400px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            /* لتوسيع المحتوى إلى المنتصف داخل الزر */
        }

        /* تصميم نص الزر */
        .tamara-txt {
            flex-grow: 1;
            text-align: start;
            margin: 5px;
            font-family: 'Almarai', sans-serif;
            font-weight: 400 !important;
            font-size: 16px;

            line-height: 20px
        }

        /* تصميم صورة الزر */
        .tamara-img {
            margin-left: 5px;
            margin-right: 5px;
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
                    {{ $category->getTranslation('name', $locale) }}</div>
                <div class="subinfo">
                    <div class="price-container">
                        <div style="font-size: 16px; font-weight: 600; color:#000000; margin: 10px 0px 10px 0px"
                             class="title">
                            {{ $locale == 'en' ? 'Service Description' : 'وصف الخدمة' }}
                        </div>
                        <div style="font-weight: 300; font-size: 16px; margin: 10px 0px 10px 0px" class="duration">
                            <span style="font-weight: 400; font-size: 16px">
                                {{ $locale == 'en' ? 'Session duration:' : 'مدة الجلسة :' }}
                            </span>
                            {{ $category->duration_minutes }}
                            {{ $locale == 'en' ? 'minutes' : 'دقيقة' }}
                        </div>
                        <span>{{ __('general.start_from') }}</span>
                        <span class="price"
                              style="@if ($category->discount) text-decoration-line: line-through;color: #136e81; opacity: 0.6; @endif">{{ $category->prices->at_home }}
                        </span>
                        {{ __('general.sar') }}
                        &nbsp;
                        &nbsp;
                        @if ($category->discount)
                            <span class="price">{{ $category->discount->at_home }}</span>
                            {{ __('general.sar') }}
                        @endif
                    </div>
                </div>
                <div class="service-btns">

                    @if ($category->is_active == true)
                        <button class="addToCartButton btn-booknow" style="pointer-events: painted"
                                data-service-id="{{ $category->id }}"
                                data-service-name="{{ $category->getTranslation('name', $locale) }}"
                                data-service-price="{{ $category->discount ? $category->discount->at_home : $category->prices->at_home }}"
                                data-type="category">
                            <i class="icon fas fa-plus"></i>
                            {{ __('messages.added_to_cart') }}
                        </button>
                        <button class="btn-sendasgift item-gift" data-service-id="{{ $category->id }}"
                                data-service-name="{{ $category->getTranslation('name', $locale) }}"
                                data-service-price="{{ $category->discount ? $category->discount->at_home : $category->prices->at_home }}"
                                data-type="category" data-modal="#gift-modal">

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
                    {{ $category->getTranslation('description', $locale) }}
                </p>
                <h3>{{ __('general.benefits') }}</h3>
                <ul>
                    @foreach ($category->getTranslation('benefits', $locale) as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>


            </div>

            <div class="service-footer">
                @if ($category->is_active == true)
                    <button class="addToCartButton btn-booknow" style="pointer-events: painted"
                            data-service-id="{{ $category->id }}"
                            data-service-name="{{ $category->getTranslation('name', $locale) }}"
                            data-service-price="{{ $category->prices->at_home }}" data-type="category">
                        {{ __('messages.added_to_cart') }}
                    </button>
                    <button class="btn-sendasgift item-gift" data-service-id="{{ $category->id }}"
                            data-service-name="{{ $category->getTranslation('name', $locale) }}"
                            data-service-price="{{ $category->prices->at_home }}" data-type="category"
                            data-modal="#gift-modal">

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
        </div>

    </div>
    <div class="button-container">
        <tamara-widget type="tamara-summary" inline-type="5"></tamara-widget>

    </div>

@endsection

@section('scripts-front')
    <script src="{{ asset('front/vendor/OwlCarousel/owl.carousel.min.js') }}"></script>

@endsection
