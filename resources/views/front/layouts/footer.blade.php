@php
    $locale_type = Session::has('service_type') ? Session::get('service_type') : request()->route('type');

@endphp
<footer>
    <div class="footer-container">
        <div class="footer-content">
            {{-- <div class="footer-logo">
                <img class="lazyimage" data-src="{{ asset('front/images/logo/logo1.webp') }}" alt="Valerian Spa Logo">
            </div> --}}
            <div class="footer-links">
                <a href="{{ route('index', ['type' => $locale_type]) }}">{{ __('nav.home') }}</a>
                <a href="{{ route('about') }}">{{ __('nav.about') }}</a>
                {{--                <a href="{{ route('terms') }}">{{ __('nav.services') }}</a>--}}
                <a href="{{ route('packages', ['type' => $locale_type]) }}">{{ __('nav.packages') }}</a>
                <a href="{{ route('terms') }}">{{ __('nav.policy') }}</a>
            </div>

        </div>
        <div style="border-top: 1px solid #000; padding-top: 1px; width: 100% !important;"></div>
        <div class="footer-copyright">
            <p>&copy;{{ __('nav.copyright') }}</p>
            <div style="margin: auto" class="footer-social">
                <ul style="font-family: 'Arima Madurai', cursive!important;font-size: 35px">
                    <li style="margin: 5px; font-family: 'Arima Madurai', cursive!important;" class="ms-icon fb_icon">
                        <a style="font-family: 'Arima Madurai', cursive!important;" title="Facebook"
                           href="https://www.facebook.com/profile.php?id=100086880867554&mibextid=dGKdO6"
                           target="_blank">
                            <i class="fa-brands fa-facebook"></i>
                        </a>
                    </li>
                    <li style="margin: 5px" class="ms-icon tik_icon">
                        <a href="https://www.tiktok.com/@valerianspa?_t=8k0rVd7KvnW&_r=1" title="Tiktok"
                           target="_blank">
                            <i class="fa-brands fa-tiktok"></i>
                        </a>
                    </li>

                    <li style="margin: 5px" class="ms-icon insta_icon">
                        <a title="Instagram" href="https://www.instagram.com/valerian.spa/" target="_blank">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                    </li>
                    <li style="margin: 5px" class="ms-icon x_icon">
                        <a title="X-Twitter" href="https://x.com/valerian_spa?t=UgzCRm18WbXZotRaJqp6IQ&s=09"
                           target="_blank">
                            <i class="fa-brands fa-x-twitter"></i>
                        </a>
                    </li>
                    <li style="margin: 5px" class="ms-icon snapchat_icon" style="hover {color: red;}">
                        <a title="Snapchat"
                           href="https://www.snapchat.com/add/valerianspa11?share_id=iUsLHjdD9TA&locale=en-US"
                           target="_blank">
                            <i class="fa-brands fa-square-snapchat"></i>
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </div>
</footer>
