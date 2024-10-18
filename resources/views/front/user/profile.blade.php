@extends('front.layouts.app')

@section('title', __('nav.title', ['title' => __('nav.profile')]))

@section('head-front')
    <link rel="stylesheet" href="{{ asset('front/css/forms.css') }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap');
    </style>
    <style>
        /* Main Profile Container */
        .profile-container {

            display: flex;
            justify-content: center;
            align-items: center;
            flex-grow: 1;
            padding: 20px;
            background: linear-gradient(135deg, #d6f4fa, #ffffff);
            font-family: "Cairo", system-ui;
        }

        /* Profile Card */
        .profile-card {
            margin-top: 100px;
            margin-bottom: 60px;
            background: #ffffff;
            color: #333;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            padding: 30px 20px 20px;
            /* Adjusted top padding */
            text-align: center;
            position: relative;
            overflow: visible;
            /* Ensure overflow is visible */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .profile-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
        }

        /* Profile Picture */
        .profile-picture {
            position: absolute;
            top: -60px;
            /* Moves the picture above the card */
            left: 50%;
            transform: translateX(-50%);
            border-radius: 50%;
            width: 120px;
            height: 120px;
            /* border: 5px solid #007d96; */
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            z-index: 1;
            /* Ensure it's above other content */
        }

        .profile-picture img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Profile Info */
        .profile-info {
            margin-top: 60px;
            /* Ensure enough space for the picture */
        }

        .profile-info h2 {
            font-size: 2.5rem;
            color: #007d96;
            margin: 0;
            font-weight: 600;
        }

        .profile-info p {
            margin: 10px 0;
            font-size: 1.1rem;
            color: #333;
        }

        .profile-info p strong {
            color: #007d96;
            font-weight: 600;
        }

        /* Responsive Adjustments */
        @media (max-width: 600px) {
            .profile-picture {
                width: 100px;
                height: 100px;
                top: -40px;
                /* Adjusted for smaller screens */
            }

            .profile-info h2 {
                font-size: 2rem;
            }

            .profile-info p {
                font-size: 1rem;
            }
        }

        .btn-req {
            position: relative;
            transition: all 0.3s ease-in-out;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.11);
            padding-block: 0.5rem;
            padding-inline: 1.25rem;
            background-color: #007d96;
            border-radius: 9999px;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #ffff;
            font-weight: bold;
            border: 3px solid #007d96;
            outline: none;
            overflow: hidden;
            font-size: 15px;
            width: 200px;
            border: 0 solid transparent;
            box-sizing: border-box;
            transition: background-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out, transform 0.3s ease-in-out;
            margin: 20px
        }

        .btn-req:hover {
            background-color: #007d96;
            border: 5px solid #ffffff4d;
            box-sizing: border-box;
            transform: scale(1.05);
            color: #ffff;
        }
    </style>
@endsection

@section('content-front')
    <main class="profile-container">

        <div class="profile-card">
            <div style="border: 6px solid #ffffff4d;" class="profile-picture">
                <img src="{{ asset('front/uploads/images/1722192722.webp') }}" alt="Client Photo">
            </div>
            <div class="profile-info">
                <h2>
                    {{ $user->name }}
                </h2>
                <p><strong>{{ __('auth.client_code') }} :</strong> {{ $user->code }}</p>
                <p><strong>{{ __('auth.client_phone') }} :</strong> {{ $user->phone }}</p>
                {{-- <button type="button" class="btn-req">
                    <a href="{{ route('myOrders') }}" class="btn btn-primary">طلباتي</a>
                </button> --}}
                <button type="button" class="btn-req" onclick="window.location.href='{{ route('myOrders') }}'">
                    طلباتي
                </button>

                {{-- <a href="{{ route('myOrders') }}" class="btn btn-primary">طلباتي</a> --}}
            </div>
        </div>
    </main>

@endSection

@section('scripts-front')

@endsection
