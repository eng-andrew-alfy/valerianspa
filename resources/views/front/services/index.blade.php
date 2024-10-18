@extends('front.layouts.app')

@php
    $locale = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale();
@endphp
@section('title', __('nav.title', ['title' => $service->getTranslation('name', $locale)]))

@section('head-front')

    <link rel="stylesheet" href="{{ asset('front/css/categories.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/items.css') }}">

    <style>
        .v0-carousel-2023 {
            position: relative;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            overflow: hidden;
        }

        .v0-carousel-container-2023 {
            position: relative;
            padding-top: 56.25%;
            /* 16:9 Aspect Ratio */
        }

        .v0-carousel-slide-2023 {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #ffffff;
            /* Light gray background */
        }

        .v0-carousel-slide-2023.active {
            opacity: 1;
        }

        .v0-carousel-slide-2023 img {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
            border-radius: 20px;
        }

        .v0-carousel-button-2023 {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0);
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-size: 20px;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .v0-carousel-button-2023:hover {
            background-color: rgba(0, 0, 0, 0.7);
        }

        .v0-carousel-button-prev-2023 {
            left: 10px;
        }

        .v0-carousel-button-next-2023 {
            right: 10px;
        }

        .v0-carousel-dots-2023 {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }

        .v0-carousel-dot-2023 {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #ccc;
            margin: 0 5px;
            cursor: pointer;
        }

        .v0-carousel-dot-2023.active {
            background-color: #333;
        }

        @media (max-width: 768px) {
            .v0-carousel-button-2023 {
                width: 30px;
                height: 30px;
                font-size: 16px;
            }

            .v0-carousel-container-2023 {
                padding: auto;
                margin: 10px;

            }


        }
    </style>
@endsection

@section('content-front')
    <div class="body">
        {{-- Start hero --}}

        <div class="v0-carousel-2023" id="v0Carousel2023">
            <div class="v0-carousel-container-2023">
                <div class="v0-carousel-slide-2023 active">
                    <img src="{{ asset('front/uploads/images/1722192756.webp') }}" alt="Image 1">
                </div>
                <div class="v0-carousel-slide-2023">
                    <img src="{{ asset('front/uploads/images/1722192793.webp') }}" alt="Image 2">
                </div>
                <div class="v0-carousel-slide-2023">
                    <img src="{{ asset('front/uploads/images/1722192781.webp') }}" alt="Image 3">
                </div>
                <div class="v0-carousel-slide-2023">
                    <img src="{{ asset('front/uploads/images/1722192830.webp') }}" alt="Image 4">
                </div>
                <div class="v0-carousel-slide-2023">
                    <img src="{{ asset('front/uploads/images/1722192854.webp') }}" alt="Image 5">
                </div>
                <div class="v0-carousel-slide-2023">
                    <img src="{{ asset('front/uploads/images/1722192881.webp') }}" alt="Image 5">
                </div>

            </div>
            <button class="v0-carousel-button-2023 v0-carousel-button-prev-2023" id="v0CarouselPrev2023"><i
                    class="fas fa-chevron-left"></i></button>
            <button class="v0-carousel-button-2023 v0-carousel-button-next-2023" id="v0CarouselNext2023"><i
                    class="fas fa-chevron-right"></i></button>
            <div style="display: none" class="v0-carousel-dots-2023" id="v0CarouselDots2023"></div>
        </div>
        {{-- End hero --}}
        <div class="items-container" style="background-color: #ffffff;">

            <div class="section-title">{{ $service->getTranslation('name', $locale) }}</div>
            <div class="items">
                @foreach ($categories as $category)
                    @php
                        $categoryName = strtolower(str_replace(' ', '_', $category->getTranslation('name', 'en')));
                        $type = request()->route('type') ?? '';

                    @endphp
                    <div class="item">
                        <a href="{{ route('showCategory', ['type' => $type, 'name' => $categoryName]) }}"
                           class="item-content">
                            <div class="item-title">{{ $category->getTranslation('name', $locale) }}</div>
                            <div class="item-number" style="text-align: {{ $locale == 'en' ? 'left' : 'right' }};">
                                {{ $locale == 'en' ? 'Session duration: ' : 'مدة الجلسة: ' }}
                                {{ $category->duration_minutes }}
                                {{ $locale == 'en' ? 'minutes' : 'دقيقة' }}
                            </div>

                            <div class="item-description">
                                {{ $category->getTranslation('description', $locale) }}
                            </div>


                        </a>
                        <div style="@if ($category->discount) text-decoration-line: line-through; color: #6f6f6f @endif"
                             class="item-price"><span class="weight-600">{{ $category->prices->at_home }}</span>
                            {{ __('general.sar') }}
                        </div>
                        @if ($category->discount)
                            <div class="item-price"><span class="weight-600">{{ $category->discount->at_home }}</span>
                                {{ __('general.sar') }}
                            </div>
                        @endif
                        <div style="margin-top: 8px" class="item-footer">
                            @if ($category->is_active == true)
                                <button class="addToCartButton item-book" style="pointer-events: painted"
                                        data-service-id="{{ $category->id }}"
                                        data-service-name="{{ $category->getTranslation('name', $locale) }}"
                                        data-service-price="{{ $category->discount ? $category->discount->at_home : $category->prices->at_home }}"
                                        data-type="category" data-locale-services="{{ request()->route('type') }}">
                                    {{ __('messages.added_to_cart') }}
                                </button>
                                <a href="#" class="item-gift" data-service-id="{{ $category->id }}"
                                   data-service-name="{{ $category->getTranslation('name', $locale) }}"
                                   data-service-price="{{ $category->discount ? $category->discount->at_home : $category->prices->at_home }}"
                                   data-type="category" data-modal="#gift-modal"
                                   data-locale-services="{{ request()->route('type') }}">

                                    <i class="fas fa-gift"></i>


                                </a>
                            @else
                                <button class="item-book" style="cursor: no-drop  ;background-color: darkred;" disabled>
                                    {{ __('general.not_available_service') }}

                                </button>
                                <a href="#" class="item-gift"
                                   style="cursor: no-drop ;background-color: darkred; color: whitesmoke">

                                    <i class="fas fa-gift"></i>


                                </a>
                            @endif

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('scripts-front')

    <script>
        $(document).ready(function () {
            $(".hero-container .owl-carousel").owlCarousel({
                margin: 10,
                padding: 0,
                loop: true,
                autoplay: true,
                autoplayTimeout: 4000,
                autoplayHoverPause: true,
                rtl: {{ app()->getLocale() == 'ar' ? 'true' : 'false' }},
                nav: true,
                dots: true,

                responsive: {
                    0: {
                        items: 1,
                        stagePadding: 20
                    },
                    768: {
                        items: 1,
                        stagePadding: 0
                    }
                },
                lazyLoad: false,
            });

            $(".categories-container .owl-carousel").owlCarousel({

                margin: 10,
                padding: 0,
                autoplayHoverPause: true,
                nav: true,
                rtl: {{ app()->getLocale() == 'ar' ? 'true' : 'false' }},
                responsive: {
                    0: {
                        items: 3,
                        stagePadding: 20
                    },
                    768: {
                        items: 5,
                        stagePadding: 0
                    },
                    1440: {
                        items: 7,
                        stagePadding: 0
                    }
                },
                lazyLoad: true,
            });


            $('.lazybackground').each(function () {
                if ($(document).width() < 768) {
                    $(this).css('background-image', 'url(' + $(this).data('s-image') + ')');
                } else {
                    $(this).css('background-image', 'url(' + $(this).data('l-image') + ')');
                }
            });

            @if (app()->getLocale() == 'ar')
            $('.categories-container .owl-next').html('<i class="fas fa-chevron-right"></i>');
            $('.categories-container .owl-prev').html('<i class="fas fa-chevron-left"></i>');
            @endif
        });
    </script>
    <script>
        // Carousel Kero
        $("#services-carousel").owlCarousel({
            loop: true, // تكرار السلايدر بدون فراغات
            margin: 0, // إزالة الهامش بين الصور أو تقليله لتجنب المساحات الزائدة
            nav: true,
            rtl: true,
            autoplay: true,
            autoplayTimeout: 1000,
            autoplayHoverPause: true,
            slideSpeed: 200,
            paginationSpeed: 800,
            navigation: true,
            navigationText: ["prev", "next"],
            pagination: true,
            center: true, // ضمان أن تكون العناصر في المنتصف دائماً
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 3
                },
                1000: {
                    items: 10
                }
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const carousel = document.getElementById('v0Carousel2023');
            if (!carousel) {
                console.error('Carousel element not found');
                return;
            }

            const slides = carousel.querySelectorAll('.v0-carousel-slide-2023');
            const prevButton = document.getElementById('v0CarouselPrev2023');
            const nextButton = document.getElementById('v0CarouselNext2023');
            const dotsContainer = document.getElementById('v0CarouselDots2023');

            if (!slides.length || !prevButton || !nextButton || !dotsContainer) {
                console.error('Required carousel elements not found');
                return;
            }

            let currentIndex = 0;
            const totalSlides = slides.length;

            // Create dots
            for (let i = 0; i < totalSlides; i++) {
                const dot = document.createElement('div');
                dot.classList.add('v0-carousel-dot-2023');
                dot.addEventListener('click', () => goToSlide(i));
                dotsContainer.appendChild(dot);
            }

            const dots = dotsContainer.querySelectorAll('.v0-carousel-dot-2023');

            function updateCarousel() {
                slides.forEach((slide, index) => {
                    slide.classList.toggle('active', index === currentIndex);
                });
                dots.forEach((dot, index) => {
                    dot.classList.toggle('active', index === currentIndex);
                });
            }

            function goToSlide(index) {
                currentIndex = index;
                updateCarousel();
            }

            function nextSlide() {
                currentIndex = (currentIndex + 1) % totalSlides;
                updateCarousel();
            }

            function prevSlide() {
                currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
                updateCarousel();
            }

            nextButton.addEventListener('click', nextSlide);
            prevButton.addEventListener('click', prevSlide);

            // Auto-play functionality
            let autoplayInterval = setInterval(nextSlide, 50000);

            carousel.addEventListener('mouseenter', () => clearInterval(autoplayInterval));
            carousel.addEventListener('mouseleave', () => {
                autoplayInterval = setInterval(nextSlide, 50000);
            });

            // Touch events for mobile swipe
            let touchStartX = 0;
            let touchEndX = 0;

            carousel.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
            });

            carousel.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                if (touchEndX < touchStartX) nextSlide();
                if (touchEndX > touchStartX) prevSlide();
            });

            // Initial update
            updateCarousel();
        });
    </script>
@endsection
