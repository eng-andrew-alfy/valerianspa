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
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<nav class="desktop">
    <div class="nav-content d-width">

        <div class="nav-logo">
            <a href="{{ route('index', ['type' => request()->route('type')]) }}">
                <img src="{{ asset('front/images/logo/logo1.webp') }}" alt="Valerian Spa Logo">
            </a>
        </div>

        <div class="nav-links">

            <ul>
                <li class="{{ Route::is('index') ? 'active' : '' }}"><a
                        href="{{ route('index', ['type' => request()->route('type')]) }}">{{ __('nav.home') }}</a></li>
                <li class="nav-dropdown" data-target="desktop-services-dropdown">
                    <span href="">
                        {{ __('nav.services') }}
                        <i class="fa fa-caret-down"></i>
                    </span>
                    <div class="nav-dropdown-links" id="desktop-services-dropdown">
                        @php
                        $locale = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale();

                        $type = request()->route('type');
                        @endphp
                        @foreach (\App\Models\Services::with('serviceAvailability')->get() as $services)
                        @php
                        $serviceName = strtolower(str_replace(' ', '_', $services->getTranslation('name', 'en')));
                        $type = request()->route('type');

                        @endphp
                        <a href="{{ route('getAllCategoriesByServices', ['type' => $type, 'services' => $serviceName]) }}"
                           style="display: flex; align-items: center;">
                            <img src="{{ asset('storage/uploads/services/' . $services->image) }}"
                                 style="width: 20px; height: 20px; border-radius: 50%; margin-right: 10px; margin-left: 6px">
                            <span>{{ $services->getTranslation('name', $locale) }}</span>
                        </a>
                        @endforeach
                    </div>

                </li>
                <li class="{{ Route::is('packages') ? 'active' : '' }}"><a
                        href="{{ route('packages', ['type' => request()->route('type')]) }}">{{ __('nav.packages')
                        }}</a>
                </li>
                <li class="{{ Route::is('about') ? 'active' : '' }}"><a
                        href="{{ route('about') }}">{{ __('nav.about') }}</a></li>
            </ul>
        </div>

        <div class="nav-user-links">

            <div class="nav-user-profile">
                <span class="flex align-center gap-10">
                    <i class="fa-regular fa-user"></i>
                    {{ __('nav.profile') }}
                    <i class="fa fa-caret-down"></i>
                </span>

                <div class="nav-user-profile-dropdown" id="profile-dropdown">
                    @if (!$user)
                    <a href="{{ route('login') }}">{{ __('nav.login') }}</a>
                    @else
                    <a href="{{ route('me') }}">{{ __('nav.profile') }}</a>
                    <a href="{{ route('logout') }}">{{ __('nav.logout') }}</a>
                    @endif
                </div>
            </div>

            <div class="nav-user-lang">

                @if (Route::currentRouteName() != 'ajaxCart')
                @if (app()->getLocale() == 'en')
                <a href="{{ LaravelLocalization::getLocalizedURL('ar', null, [], true) }}">
                    العربية
                    <i class="fa-solid fa-globe"></i>
                </a>
                @else
                <a href="{{ LaravelLocalization::getLocalizedURL('en', null, [], true) }}">
                    English
                    <i class="fa-solid fa-globe"></i>
                </a>
                @endif
                @endif
                @if (Route::currentRouteName() == 'ajaxCart')

                @if (app()->getLocale() == 'en')
                <a href="javascript:void(0)"
                   onclick="confirmLanguageChange('{{ LaravelLocalization::getLocalizedURL('ar', route('index', ['type' => request()->route('type')])) }}')">
                    العربية
                    <i class="fa-solid fa-globe"></i>
                </a>
                @else
                <a href="javascript:void(0)"
                   onclick="confirmLanguageChange('{{ LaravelLocalization::getLocalizedURL('en', route('index', ['type' => request()->route('type')])) }}')">
                    English
                    <i class="fa-solid fa-globe"></i>
                </a>
                @endif
                @endif
            </div>

            {{-- <a href="" class="nav-user-cart">
                <i class="fa-solid fa-cart-shopping"></i>

            </a> --}}


        </div>
    </div>
</nav>

<nav class="mobile">
    <div class="nav-content d-width">
        <div class="nav-left-side">
            <div class="nav-user-links" data-target="nav-mobile" id="nav-toggle">
                <i class="fa-solid fa-bars"></i>
            </div>
        </div>
        {{--
        <div class="nav-center">
            <div class="nav-logo">
                <a href="{{ route('index', ['type' => request()->route('type')]) }}">
                    <img class="responsive-logo" src="{{ asset('front/images/logo/logo1.webp') }}"
                         alt="Valerian Spa Logo">
                </a>
            </div>
            --}}
        </div>
        <div class="nav-right-side">
            {{-- <a href="" class="nav-user-cart">
                <i class="fa-solid fa-cart-shopping"></i>
            </a> --}}
            <div class="nav-logo">
                <a href="{{ route('index', ['type' => request()->route('type')]) }}">
                    <img class="responsive-logo" src="{{ asset('front/images/logo/logo1.webp') }}"
                         alt="Valerian Spa Logo">
                </a>
            </div>
        </div>
    </div>
</nav>


<div style="color: #000000" class="nav-mobile-menu" id="nav-mobile">
    <div data-target="nav-mobile" id="nav-toggle-2">
        <i class="fa-solid fa-bars"></i>
    </div>
    <div class="nav-mobile-links">
        <a href="{{ route('index', ['type' => request()->route('type')]) }}" class="nav-link">
            {{ __('nav.home') }}
            <i class="fa fa-angle-right" aria-hidden="true"></i>
        </a>
        <div href="" class="nav-link nav-dropdown" data-target="mobile-services-dropdown">
            <div class="nav-mobile-title">
                {{ __('nav.services') }}
                <i class="fa fa-angle-right" aria-hidden="true"></i>
            </div>
            <div class="nav-dropdown-links" id="mobile-services-dropdown">
                @php
                $locale = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale();
                @endphp
                @foreach (\App\Models\Services::with('serviceAvailability')->get() as $services)
                @php
                $serviceName = strtolower(str_replace(' ', '_', $services->getTranslation('name', 'en')));
                $type = request()->route('type');

                @endphp
                <a href="{{ route('getAllCategoriesByServices', ['type' => $type, 'services' => $serviceName]) }}">
                    <span>{{ $services->getTranslation('name', $locale) }}</span>
                </a>
                @endforeach

            </div>
        </div>
        <a href="{{ route('packages', ['type' => request()->route('type')]) }}" class="nav-link">
            {{ __('nav.packages') }}
            <i class="fa fa-angle-right" aria-hidden="true"></i>
        </a>
        <a href="{{ route('about') }}" class="nav-link">
            {{ __('nav.about') }}
            <i class="fa fa-angle-right" aria-hidden="true"></i>
        </a>
        <div href="" class="nav-link nav-dropdown" data-target="mobile-profile-dropdown">
            <div class="nav-mobile-title">
                <span class="flex gap-10 align-center">
                    <i class="fa-regular fa-user" aria-hidden="true"></i>
                    {{ __('nav.profile') }}
                </span>
                <i class="fa fa-angle-right" aria-hidden="true"></i>
            </div>
            <div class="nav-dropdown-links" id="mobile-profile-dropdown">
                @if (!$user)
                <a href="{{ route('login') }}">{{ __('nav.login') }}</a>
                @else
                <a href="{{ route('me') }}">{{ __('nav.profile') }}</a>
                <a href="{{ route('logout') }}">{{ __('nav.logout') }}</a>
                @endif
            </div>


        </div>
        @if (Route::currentRouteName() != 'ajaxCart')
        @if (app()->getLocale() == 'en')
        <a href="{{ LaravelLocalization::getLocalizedURL('ar', null, [], true) }}" class="nav-link">
                    <span class="flex gap-10 align-center">
                        <i class="fa-solid fa-globe"></i>
                        العربية
                    </span>
            <i class="fa fa-angle-right" aria-hidden="true"></i>
        </a>
        @else
        <a href="{{ LaravelLocalization::getLocalizedURL('en', null, [], true) }}" class="nav-link">
                    <span class="flex gap-10 align-center">
                        <i class="fa-solid fa-globe"></i>
                        English
                    </span>
            <i class="fa fa-angle-right" aria-hidden="true"></i>
        </a>
        @endif
        @endif
        @if (Route::currentRouteName() == 'ajaxCart')

        @if (app()->getLocale() == 'en')
        <a href="javascript:void(0)"
           onclick="confirmLanguageChange('{{ LaravelLocalization::getLocalizedURL('ar', route('index', ['type' => request()->route('type')])) }}')"
           class="nav-link">
                    <span class="flex gap-10 align-center">
                        <i class="fa-solid fa-globe"></i>
                        العربية
                    </span>
            <i class="fa fa-angle-right" aria-hidden="true"></i>
        </a>
        @else
        <a href="javascript:void(0)"
           onclick="confirmLanguageChange('{{ LaravelLocalization::getLocalizedURL('en', route('index', ['type' => request()->route('type')])) }}')"
           class="nav-link">
                    <span class="flex gap-10 align-center">
                        <i class="fa-solid fa-globe"></i>
                        English
                    </span>
            <i class="fa fa-angle-right" aria-hidden="true"></i>
        </a>
        @endif
        @endif
    </div>
</div>
