@extends('front.layouts.app')


@section('title', __('nav.title', ['title' => 'نجاح الطلب']))

@section('head-front')
    <link rel="stylesheet" href="{{ asset('front/vendor/OwlCarousel/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/hero.css') }}">
    <style>
        .container {
            text-align: center;
            padding: 20px;
            margin: 50px auto;
            transition: transform 0.3s ease;
        }

        .container:hover {
            transform: scale(1.02);
        }

        .container h4 {
            margin-top: 30px;
            margin-bottom: 20px;
            color: #136e81;
            font-size: 2.2rem;
            font-weight: bold;
            text-align: center;
            text-shadow: 1px 1px 2px rgba(19, 110, 129, 0.1);
        }

        .container h2 {
            margin-top: 20px;
            color: #136e81;
            font-size: 1.5rem;
            font-weight: 800;
            text-align: center;
            line-height: 1.5;
            text-shadow: 1px 1px 2px rgba(19, 110, 129, 0.1);
        }

        .container img {
            width: 50%;
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .container img:hover {
            opacity: 0.9;
            transform: scale(1.05);
        }
    </style>
@endsection

@section('content-front')

    <div class="container order-suc">
        <h4>للأسف، رابط الهدية انتهى 💔.
            <br>
            إذا احتجتي أي مساعدة، تواصلي مع الدعم،
            <br>
            وحنا هنا عشان نخدمك!
        </h4>
        <img src="{{ asset('front/uploads/images/1722192742.webp') }}" alt="Client Photo">
        {{-- <h2>شكراً لك على طلبك. سيتم التواصل معك قريباً.</h2> --}}
    </div>
@endSection

@section('scripts-front')
    <script src="{{ asset('front/vendor/OwlCarousel/owl.carousel.min.js') }}"></script>

@endsection
