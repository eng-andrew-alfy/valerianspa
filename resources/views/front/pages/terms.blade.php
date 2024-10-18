@extends('front.layouts.app')


@section('title', __('nav.title', ['title' => 'Swedish massage']))

@section('head-front')
    <link rel="stylesheet" href="{{ asset('front/css/page.css') }}">
    <style>
        body {
            font-family: "Almarai", sans-serif !important
        }

        .info-container ul li {
            font-size: 18px;
            line-height: 35px;
            margin: 10px 0;
            font-weight: normal !important
        }

        .ms-container p {
            font-size: 18px !important;
            line-height: 35px;
            margin: 10px 0;
            font-family: "Almarai", sans-serif !important
        }


        .bg-about {
            background-image: url('../front/uploads/images/1722192752.webp');
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
@endsection

@section('content-front')
    <div style=" margin-top: 100px !important;" class="body">
        {{-- <div class="banner-container bg-about">
            {{ __('general.terms') }}
        </div> --}}
        <div style="font-weight: 900 !important ;font-size: 14px;" class="info-container ">
            <h2>{{ __('terms.terms_and_conditions.title') }}</h2>
            <h2>{{ __('terms.terms_and_conditions.subtitle') }}</h2>
            <ul>
                @foreach (__('terms.terms_and_conditions.points') as $point)
                    <li>{{ $point }}</li>
                @endforeach
            </ul>
            <h2 style="padding-bottom: 20px; ">{{ __('terms.policies_and_procedures.title') }}</h2>
            <h4 style="padding-bottom: 20px; line-height: 30px; font-size: 20px">
                {{ __('terms.policies_and_procedures.subtitle') }}</h4>
            <ul>
                @foreach (__('terms.policies_and_procedures.points') as $point)
                    <li>
                        <span style="font-weight: bold">{{ $point['title'] }}</span>
                        <p>{{ $point['content'] }}</p>
                    </li>
                @endforeach
            </ul>
        </div>

    </div>
@endsection

@section('scripts-front')

@endsection
