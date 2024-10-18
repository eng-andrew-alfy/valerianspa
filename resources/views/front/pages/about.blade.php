@extends('front.layouts.app')


@section('title', __('nav.title', ['title' => 'Swedish massage']))
<style>
    @import url('https://fonts.googleapis.com/css2?family=Arimo:ital,wght@0,400..700;1,400..700&display=swap');
</style>
<style>
    .bg-about {
        background-image: url('../front/uploads/images/1722192752.webp');
        background-repeat: no-repeat;
        background-size: cover;
        /* لجعل الصورة تغطي كامل العنصر */
    }

    body {
        font-family: Almarai, sans-serif !important;
        font-weight: 300 !important;
        font-style: normal;
        line-height: 40px !important;

        /* font-size: 18px !important; */
    }

    .about-p p {
        font-family: Almarai, sans-serif !important;
        font-weight: 300 !important;
        font-style: normal;
        line-height: 40px !important;
        font-size: clamp(1rem, 1.5vw, 1.5rem) !important;



    }

    .about-p ul {
        font-family: 'Almarai', sans-serif !important;
        font-weight: 300 !important;
        font-style: normal;
        line-height: 20px !important;
        font-size: clamp(1rem, 1.5vw, 1.5rem) !important;
    }

    .info-container h2 {
        margin: 0 !important;
        font-family: 'Almarai', sans-serif !important;
        font-weight: 300 !important;
        margin-bottom: 20px !important;
    }
</style>
@section('head-front')
    <link rel="stylesheet" href="{{ asset('front/css/page.css') }}">
@endsection

@section('content-front')
    <div style=" margin-top: 100px !important;" class="body ">
        {{-- <div class="banner-container bg-about">
            {{ __('general.aboutus') }}
        </div> --}}
        <div class="info-container about-p">
            <h2> {{ __('general.whoweare') }}</h2>
            <p> {{ __('about.about') }} </p>

            </p>
            <h2> {{ __('general.ourservices') }}</h2>
            @php
                $services = __('about.our_services');
            @endphp
            <ul>
                @foreach ($services as $key => $service)
                    <li>{{ $service }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection

@section('scripts-front')

@endsection
