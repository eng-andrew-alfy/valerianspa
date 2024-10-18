<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'en' ? 'ltr' : 'rtl' }}">

<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="VALERIAN SPA" />
    <meta name="keywords" content="Eng.Andrew ,Valerian ,VALERIAN SPA , SPA " />
    <meta name="author" content="ThemeOri">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link defer
        href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&family=Arimo:ital,wght@0,400..700;1,400..700&display=swap"
        rel="stylesheet">
    <meta name="google-site-verification" content="-iN-d1EtWdWBceVdg8ymyJilm5bNzT8vtR78P-DkYhU" />
    <!-- Title of Site -->
    <title>VALERIAN SPA</title>
    <!-- Favicons -->
    <link rel="icon" type="image/ico" href="{{ asset('front/images/favicon.ico') }}">
    <!-- Bootstrap CSS -->


    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arimo', sans-serif;
        }

        .text-danger {
            color: #dc3545;
        }

        .container {
            width: 100%;
            height: 100vh;
            display: flex;
            align-items: center;
            flex-direction: column;
            padding-bottom: 30px;
            gap: 20px;
            background-color: #edfffd;
            overflow: hidden;
            background: url({{ asset('front/images/home-mobile.webp') }}) no-repeat;
            background-size: cover;
            background-position: left top;

        }

        .items {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
            gap: 15px;
            width: max-content;
            flex: auto;
        }

        a {
            text-decoration: none;
        }

        .item {
            background-color: #fafafa;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: 3px 3px 5px #ccc;
            width: max-content;
            padding: 7px 20px;
            border-radius: 20px;
            color: #136e81;
            font-size: 22px;
            font-weight: 600;
            gap: 3px;
            width: 100%;
            ;
            border: 1px solid #136e81;
            min-width: 250px;

        }

        .item:hover {
            background-color: #136f81;
            color: #fff;
        }

        .green {
            background-color: green;
        }

        .blue {
            background-color: blue;
        }

        .logo {
            display: flex;
            justify-content: left;
            margin-top: 20px;
            width: 100%;
            padding: 0 20px;
        }


        .logo img {
            height: 120px;
        }

        .logo {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            width: 100%;
            padding: 0 20px;
        }

        .ms-langs {
            display: flex;
            gap: 10px;
            padding-top: 20px;
            align-items: flex-start;
        }

        .ms-langs a {
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            height: max-content;
        }

        .ms-langs a:hover {
            color: #136e81;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            color: #136e81;
            text-align: center;
            line-height: 10px;
        }

        .terms {
            display: flex;
            flex-direction: column;
            justify-content: end;
        }

        .terms a {
            text-decoration: none;
            color: #000;
            font-weight: 600;
            font-size: 16px;
        }

        .terms a:hover {
            text-decoration: underline;
            color: #136e81;
        }

        @media screen and (min-width: 580px) {

            .container {
                background: url('{{ asset('front/images/home-desktop.webp') }}') no-repeat;
                background-size: cover;
                background-position: top center;
            }

            .logo {
                height: 180px;
            }

            .logo img {
                height: 160px;
            }

            .logo .ms-langs {
                align-items: flex-start;
            }

            .logo .ms-langs a {
                color: #136e81;
            }

            .logo .ms-langs a:hover {
                border-bottom: #136e81 1px solid;
            }

        }
    </style>
    @if (app()->getLocale() === 'ar')
        <style>
            * {
                font-family: "Almarai", sans-serif !important;
                font-style: normal;
            }
        </style>
    @endif


</head>

<body>
    <div class="container">
        <div class="logo">
            <div class="ms-langs">
                @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                    <a class="ms-lang" hreflang="{{ $localeCode }}"
                        href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                        {{ $properties['native'] }}
                    </a>
                @endforeach
            </div>
            <img src="{{ asset('front/images/logo/FullLogo.webp') }}" alt="Valerian Spa">
        </div>
        <div class="items">
            <a href="{{ route('index', ['type' => 'homeServices']) }}" class="item">
                {{ __('general.home_services') }}
            </a>
            <a href="{{ route('index', ['type' => 'SPA']) }}" class="item">
                {{ __('general.SPA') }}
            </a>
        </div>
        <div class="terms">
            <a href="{{ route('terms') }}" class="para">{{ __('general.Terms and conditions apply') }}</a>
        </div>
    </div>


</body>


</html>
