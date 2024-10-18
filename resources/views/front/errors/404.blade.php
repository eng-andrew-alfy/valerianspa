@extends('front.layouts.app')


@section('title', __('nav.title', ['title' => '404']))

@section('head-front')
    <link rel="stylesheet" href="{{ asset('front/vendor/OwlCarousel/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/hero.css') }}">

    <style>
        /* body.custom-404-body {
                            margin: 0;
                            padding: 0;
                            background-color: white;
                            font-family: 'Montserrat', sans-serif;
                            color: #000;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            height: 100vh;
                            overflow: hidden;
                            position: relative;

                        } */

        .custom-404-container {
            font-family: 'Montserrat', sans-serif;
            text-align: center;
            z-index: 2;
            margin-top: 80px;
            margin-bottom: 40px;
        }

        h1.custom-404-title {
            font-size: 10rem;
            color: #007d96;
            margin: 0;
            animation: custom-404-float 3s infinite ease-in-out;
            text-shadow: 3px 3px 10px rgba(0, 0, 0, 0.2);
        }

        h2.custom-404-subtitle {
            font-size: 2.5rem;
            color: #4fc2d3;
        }

        p.custom-404-text {
            font-size: 1.2rem;
            color: black;
            margin: 20px 0;
        }

        a.custom-404-link {
            padding: 15px 30px;
            background-color: #007d96;
            color: white;
            text-decoration: none;
            font-size: 1.2rem;
            border-radius: 50px;
            transition: background-color 0.3s, transform 0.3s;
        }

        a.custom-404-link:hover {
            background-color: #4fc2d3;
            transform: scale(1.1);
        }

        .custom-404-circle {
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background-color: rgba(0, 125, 150, 0.1);
            position: absolute;
            top: -50px;
            left: -100px;
            z-index: 1;
            animation: custom-404-move 6s infinite alternate ease-in-out;
        }

        .custom-404-circle2 {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background-color: rgba(79, 194, 211, 0.1);
            position: absolute;
            bottom: -75px;
            right: -100px;
            z-index: 1;
            animation: custom-404-move2 8s infinite alternate ease-in-out;
        }

        svg.custom-404-wave {
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            height: 200px;
            z-index: -1;
        }

        @keyframes custom-404-float {
            0% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }

            100% {
                transform: translateY(0);
            }
        }

        @keyframes custom-404-move {
            0% {
                transform: translate(0);
            }

            100% {
                transform: translate(50px, 50px);
            }
        }

        @keyframes custom-404-move2 {
            0% {
                transform: translate(0);
            }

            100% {
                transform: translate(-50px, -50px);
            }
        }
    </style>
@endsection

@section('content-front')

    <body class="custom-404-body">
        <div class="custom-404-container">
            <h1 class="custom-404-title">404</h1>
            <h2 class="custom-404-subtitle">Oops! Page not found</h2>
            <p class="custom-404-text">The page you're looking for doesn't exist.</p>
            <a href="/" class="custom-404-link">Return Home</a>
        </div>
        <div class="custom-404-circle"></div>
        <div class="custom-404-circle2"></div>
        <svg class="custom-404-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#007d96" fill-opacity="1" d="M0,192L1440,160L1440,320L0,320Z"></path>
        </svg>
    </body>
@endSection

@section('scripts-front')
    <script src="{{ asset('front/vendor/OwlCarousel/owl.carousel.min.js') }}"></script>

@endsection
