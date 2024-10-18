@php
    use Tymon\JWTAuth\Facades\JWTAuth;
    use Illuminate\Support\Facades\Cookie; // استيراد فئة الكوكيز

    // افتراضيًا، لا يوجد مستخدم
    $user = null;

    try {
        // جلب التوكن من الكوكيز
        $token = Cookie::get('token');

        // تحقق من صحة التوكن
        if ($token) {
            $user = JWTAuth::setToken($token)->authenticate();
        }
    } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
        // التعامل مع انتهاء صلاحية التوكن
        $user = null;
    } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
        // التعامل مع التوكن غير الصالح
        $user = null;
    } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
        // التعامل مع غياب التوكن
        $user = null;
    }
@endphp

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <!-- Start Meta -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="VALERIAN SPA" />
    <meta name="keywords" content="Creative, Digital, multipage, landing, freelancer template" />
    <meta name="author" content="ThemeOri">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&display=swap');
    </style>
    <style>
        .navbar-nav {
            font-weight: normal !important;
        }

        /* public/css/custom-bootstrap.css */
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #f8f9fa;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            /* للسماح بالتفاف العناصر */
        }

        .navbar-brand img {
            width: 100px;
        }

        .navbar-nav {
            display: flex;
            list-style: none;
            padding: 0;
        }

        .nav-item {
            position: relative;
            margin-left: 20px;
            /* المسافة بين العناصر */
        }

        .nav-link {
            text-decoration: none;
            color: #007bff;
            padding: 8px 15px;
            display: block;
            /* لجعل المساحة قابلة للنقر بالكامل */
        }

        .nav-link:hover {
            background-color: #e2e6ea;
            /* تمييز عند التمرير */
            border-radius: 5px;
            /* زوايا دائرية */
        }

        .dropdown-menu {
            display: none;
            /* خفي بشكل افتراضي */
            position: absolute;
            background-color: white;
            border: 1px solid #ccc;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            /* لضمان ظهور القائمة فوق العناصر الأخرى */
        }

        .dropdown:hover .dropdown-menu {
            display: block;
            /* يظهر القائمة عند التمرير */
        }

        .dropdown-item {
            padding: 10px 15px;
            color: #007bff;
            text-decoration: none;
            display: block;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            /* تمييز عند التمرير */
        }

        /* استعلامات الوسائط لجعل التصميم متجاوب */
        @media (max-width: 768px) {
            .navbar-nav {
                flex-direction: column;
                /* تغيير اتجاه العناصر في الشاشات الصغيرة */
                width: 100%;
                /* توسيع العناصر لتملأ العرض */
                display: none;
                /* إخفاء القائمة بشكل افتراضي */
            }

            .navbar-nav.show {
                display: flex;
                /* عرض القائمة عند تفعيلها */
            }

            .nav-item {
                margin-left: 0;
                /* إزالة المسافة بين العناصر */
                width: 100%;
                /* توسيع العناصر لتملأ العرض */
            }

            .navbar-toggler {
                display: block;
                /* إظهار زر القائمة المنسدلة */
            }

            .navbar-brand img {
                width: 73px;
                margin-top: 15px;
                margin-bottom: 8px !important;
                margin-right: 40px;
            }

        }

        .navbar-toggler {
            display: none;
            /* إخفاء زر القائمة المنسدلة على الشاشات الكبيرة */
        }

        /* إظهار الزر على الشاشات الصغيرة */
        @media (max-width: 768px) {
            .navbar-toggler {
                display: block;
                /* إظهار الزر */
            }

            .dropdown-menu {
                margin: 0px 0px 0px 0px !important;
            }

            .navbar-nav,
            .show {
                margin-top: 30px
            }

            .navbar-nav ul li a {
                text-decoration: none !important;
                color: rgba(0, 0, 0, .55)
            }

            .dropdown-menu {
                display: none;
                /* جعل القائمة مغلقة بشكل افتراضي */
                position: relative;
                padding: 0;
                border: none;
                box-shadow: none;
                background-color: #f8f9fa;

            }


        }

        .navbar-toggler {
            background-color: transparent !important;
            color: rgba(0, 0, 0, .55);
            border: none;
            padding: 5px 5px;
            border-radius: 5px;
            cursor: pointer;
            border: 1px solid rgba(0, 0, 0, .1);
            font-size: 25px;
            font-weight: 300;

        }

        .nav-link,
        .dropdown-item {
            text-decoration: none;
            color: rgba(0, 0, 0, .55);
            font-size: 16px;
            font-family: "Almarai", sans-serif;
        }

        .container {
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }

        @media (min-width: 768px) {
            .container {
                width: 750px;
            }
        }

        @media (min-width: 992px) {
            .container {
                width: 970px;
            }
        }

        @media (min-width: 1200px) {
            .container {
                width: 1170px;
            }
        }


        @media (min-width: 768px) {

            /* ابدأ في تطبيق التنسيق على الشاشات الكبيرة */
            .dropdown-item {


                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;

                /* اجعل عرض العناصر يتناسب مع المحتوى */
            }
        }


        /* لعرض القائمة عند تفعيلها */
        .dropdown-menu.show {
            display: block;
            /* عرض القائمة عند تفعيلها */
        }

        .hamburger {
            width: 30px;
            /* عرض الأيقونة */
            height: 18px;
            /* ارتفاع الأيقونة */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            /* للتباعد بين الخطوط */
            margin: 7px;
            /* مسافة حول الأيقونة */
        }

        .line {
            height: 2px;
            /* ارتفاع الخط */
            background-color: rgba(0, 0, 0, .55);
            /* لون الخط */
            border-radius: 2px;
            /* لجعل الخطوط ذات حواف مستديرة */

        }
    </style>
</head>

<body>
    @php
        $locale_type = Session::has('service_type') ? Session::get('service_type') : request()->route('type');
        $locale = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale();
    @endphp
    <div style="background-color: #f8f9fa">
        <nav class="navbar container">
            <a class="navbar-brand" href="{{ route('index', ['type' => $locale_type]) }}" title="Valerian Spa">
                <img src="{{ asset('front/images/logo/logo1.webp') }}" alt="Valerian Spa">
            </a>
            <button class="navbar-toggler" type="button" onclick="toggleNavbar()">
                {{-- &#9776; <!-- أيقونة قائمة --> --}}

                <div class="hamburger">
                    <div class="line"></div>
                    <div class="line"></div>
                    <div class="line"></div>
                </div>
            </button>
            <ul class="navbar-nav" id="navbarNav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('index', ['type' => $locale_type]) }}">{{ __('nav.home') }}</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" onclick="toggleDropdown(event)">
                        {{ __('nav.services') }} <span class="caret">&#9662;</span>
                    </a>
                    <div style="margin: 0px" class="dropdown-menu" id="servicesDropdown">
                        @foreach (\App\Models\Services::with('serviceAvailability')->get() as $services)
                            <a class="dropdown-item"
                                href="{{ route('getAllCategoriesByServices', ['type' => $locale_type, 'services' => strtolower(str_replace(' ', '_', $services->getTranslation('name', 'en')))]) }}">{{ $services->getTranslation('name', $locale) }}</a>
                        @endforeach
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                        href="{{ route('packages', ['type' => $locale_type]) }}">{{ __('nav.packages') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('about') }}">{{ __('nav.about') }}</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link" href="#">
                        {{ __('nav.profile') }} <span class="caret">&#9662;</span>
                    </a>
                    <div class="dropdown-menu">
                        @if (!$user)
                            <a class="dropdown-item" href="{{ route('login') }}">{{ __('nav.login') }}</a>
                        @else
                            <a class="dropdown-item" href="{{ route('me') }}">{{ __('nav.profile') }}</a>
                            <a class="dropdown-item" href="{{ route('logout') }}">{{ __('nav.logout') }}</a>
                        @endif
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#">
                        {{ __('general.language') }} <span class="caret">&#9662;</span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item"
                            href="{{ LaravelLocalization::getLocalizedURL('ar', null, [], true) }}">العربية</a>
                        <a class="dropdown-item"
                            href="{{ LaravelLocalization::getLocalizedURL('en', null, [], true) }}">English</a>
                    </div>
                </li>
            </ul>
        </nav>

    </div>

    <!-- تحميل ملفات JavaScript الخاصة بـ jQuery و Bootstrap -->
    {{-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
</script> --}}
    <script>
        function toggleDropdown(event) {
            event.preventDefault(); // منع الروابط من التحميل
            const dropdown = document.getElementById('servicesDropdown');

            // تحقق مما إذا كانت القائمة مفتوحة أم لا
            if (dropdown.classList.contains('show')) {
                dropdown.classList.remove('show'); // أغلق القائمة
            } else {
                dropdown.classList.add('show'); // افتح القائمة
            }
        }

        // إغلاق القائمة عند النقر في مكان آخر
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('servicesDropdown');
            const target = event.target.closest('.dropdown');

            // إذا كان النقر خارج الدروب داون، أغلق القائمة
            if (!target) {
                dropdown.classList.remove('show');
            }
        });


        function toggleNavbar() {
            const navbar = document.getElementById('navbarNav');
            if (navbar.classList.contains('show')) {
                navbar.classList.remove('show');
            } else {
                navbar.classList.add('show');
            }
        }
    </script>

</body>

</html>
