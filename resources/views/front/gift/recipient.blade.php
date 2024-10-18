@extends('front.layouts.app')

@section('title', __('nav.title', ['title' => __('nav.cart')]))
@section('head-front')
    <!-- animation nifty modal window effects css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/css/component.css') }}"/>
    {{--    <link rel="stylesheet" href="{{ asset('front/css/date-bicker.css') }}"> --}}
    {{--    <link rel="stylesheet" href="{{ asset('front/css/cart.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('front/css/ms-checkout.css') }}" defer>
    <style>
        :root {
            writing-mode: horizontal-tb;
            direction: rtl;
            text-orientation: upright;
        }

        body {

            font-weight: 600;
        }

        .md-modal {
            z-index: 1000 !important;

        }


        .locations-wrapper {
            display: flex;
            gap: 20px;

        }

        .location-container {
            display: flex;
            align-items: center;
            gap: 10px;
            /* border: 1px solid #ccc; */


        }


        .add-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr) !important;

            gap: 7px;


        }


        @media (max-width: 768px) {
            .add-container {
                grid-template-columns: 3fr !important;

            }

        }

        .padd {
            padding: 45px 80px !important;

        }

        @media (max-width: 768px) {
            .padd {
                padding: 45px 9px !important;
            }
        }

        .map-cont {
            border: 2px solid #136e81;
            border-radius: 10px;
            box-shadow: 0 4px 10px #136f812d, 0 10px 20px #136f812d;

        }


        .location-container {
            background-color: #ffffff;
            /* لون الخلفية */
            /* padding: 15px;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    border-radius: 10px; */
            position: relative;
            width: 300px;
            /* box-shadow: 0 4px 8px rgba(0, 0, 0, 0.041); */
            margin-top: 30px;
            /* إزاحة الشريط إلى الأسفل قليلاً */
            display: flex;
            align-items: center;
            gap: 10px;
            /* المسافة بين العناصر الداخلية */
            height: 100px;
            align-items: center;
        }

        .header {
            background-color: #006d76;
            /* لون الشريط الأخضر */
            padding: 5px 10px;
            border-radius: 10px 10px 0 0;
            /* display: flex; */
            align-items: center;
            justify-content: space-between;
            color: #fff;
            font-size: 16px;
            position: absolute;
            top: -23px;
            /* موقع الشريط بالنسبة للـ container */
            left: 0;
            right: 0;
            height: 35px;
            z-index: 1;
            /* جعل الشريط يظهر فوق العناصر الأخرى */
            width: 100%;


        }

        .circle {
            width: 30px;
            height: 30px;
            background-color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #006d76;
            position: absolute;
            right: -15px;
            top: -15px;
            border: 2px solid #006d76;
            /* حدود للدائرة */
            z-index: 2;
            /* جعل الدائرة تظهر فوق الشريط */
        }

        .header-text {

            font-weight: bold;

        }

        .flex-auto {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;

        }

        .font-18 {
            font-size: 18px;

        }

        .font-12 {
            font-size: 12px;

        }

        .btn-unset {
            background: none;
            border: none;
            cursor: pointer;

        }

        .and-item {
            display: flex;
            align-items: center;
            justify-content: center;

            color: #fff;
            border: none;
            padding: 7px 7px;

        }

        @media (max-width: 768px) {
            .location-container {
                width: 400px !important;

            }

            .and-item {
                width: 346px;
            }

            .unique-datepicker-calendar {

                left: -40px;
            !important;


            }
        }
    </style>

    <style>
        body {
            font-weight: 600;
        }

        .unique-datepicker-container {
            position: relative;
        }

        .unique-datepicker-input {
            padding: 10px;
            font-size: 16px;
            border: 1px solid var(--color-3);
            border-radius: 4px;
            cursor: pointer;

        }

        .unique-datepicker-modal {
            display: none;
            position: fixed;
            z-index: 10006;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 103, 117, 0.36);
            justify-content: center;
            align-items: flex-end;
        }

        .unique-datepicker-modal.show {
            display: flex;
        }

        .unique-datepicker-calendar {
            user-select: none;
            width: 100%;
            max-width: 525px;
            height: 80%;
            max-height: 730px;
            display: flex;
            flex-direction: column;
            background-color: var(--color-1);
            border: 1px solid var(--color-3);
            border-radius: 30px 30px 0 0;
            box-shadow: 0 0 5px #494949;
            transition: transform 0.3s ease-out;
            transform: translateY(100%);
        }

        .unique-datepicker-modal.show .unique-datepicker-calendar {
            transform: translateY(0);
        }

        .unique-datepicker-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            padding: 15px 10px 10px;
            font-size: 1rem;
            background-color: white;
            border-radius: 30px 30px 0 0;
        }

        #dtp-handle {
            background-color: #547f89;
            width: 60px;
            height: 8px;
            border-radius: 8px;
        }

        .unique-datepicker-month-year {
            font-weight: 600;
        }

        .unique-datepicker-days {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #e2edf0;
            padding: 10px 20px;
            flex-grow: 1;
            overflow-y: auto;
        }

        .unique-datepicker-days-header {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
        }

        .unique-datepicker-prev,
        .unique-datepicker-next {
            background-color: unset;
            border: unset;
            font-size: clamp(14px, 1.5vw, 16px);
            font-weight: 600;
            color: #000000;
            cursor: pointer;
        }

        .unique-datepicker-days-wrapper {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            width: 100%;
        }

        .unique-datepicker-day-name,
        .unique-datepicker-day {
            text-align: center;
            padding: 5px 0;
        }

        .unique-datepicker-day-name {
            font-size: clamp(14px, 1.5vw, 16px);
            font-weight: 600;
            color: #4e4e4e;
        }

        .unique-datepicker-day {
            width: 40px;
            height: 40px;
            background-color: #ffffff;
            border: none;
            border-radius: 50%;
            font-size: clamp(16px, 1.5vw, 18px);
            font-weight: 600;
            color: var(--color-2);
            cursor: pointer;
            margin: 2px auto;
        }

        .unique-datepicker-day.today {
            background-color: #136e82;
            color: white;
        }

        .unique-datepicker-day.selected {
            background-color: #136e82;
            color: white;
        }

        .unique-datepicker-day:hover:not(:disabled) {
            background-color: var(--color-primary);
            color: var(--color-1);
            transition: 0.2s ease-in-out;
        }

        .unique-datepicker-day:disabled {
            border: 2px solid #909fa2;
            opacity: 0.5;
            cursor: not-allowed;
        }

        @media screen and (min-width: 768px) {
            .unique-datepicker-modal {
                align-items: center;
            }

            .unique-datepicker-calendar {
                max-width: 525px;
                height: auto;
                max-height: 90vh;
                border-radius: 15px;
                transform: scale(0.9);
                opacity: 0;
            }

            .unique-datepicker-modal.show .unique-datepicker-calendar {
                transform: scale(1);
                opacity: 1;
            }

            .unique-datepicker-day {
                width: 45px;
                height: 45px;
                /*line-height: 45px;*/
            }


        }

        .unique-datepicker-day {
            background-color: white;
            color: #000000;
        }


        .cart-items,
        .items .item {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 10px;
            background-color: var(--color-2);
            -webkit-border-radius: 10px;
            -moz-border-radius: 10px;
            -ms-border-radius: 10px;
            -o-border-radius: 10px;
            border-radius: 10px;
            padding: 15px;
            font-size: 14px;
            border: 1px solid #1a19194d;
        }

        .service-info {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 5px;
        }

        .cart-items {

            border: none !important;
        }


        .more {
            display: flex;
            flex-direction: row;
            gap: 15px;
            font-size: 12px;
            color: var(--color-5);
            align-items: center;
            font-weight: bold;
        }

        .item,
        .info-container {
            display: flex;
            flex-direction: row;
            gap: 10px;
            width: 100%;

        }

        .cart-items,
        .items {
            width: 100%;
            display: flex;
            flex-direction: column;
            /* gap: 20px; */
            max-width: 600px;
        }

        .cart-items {
            margin-top: -2px
        }

        .service-image {
            height: 10vw;
            width: 10vw;
            max-width: 80px;
            max-height: 80px;
            overflow: hidden;
            -webkit-border-radius: 10px;
            -moz-border-radius: 10px;
            -ms-border-radius: 10px;
            -o-border-radius: 10px;
            border-radius: 10px;
            background-size: 100% 100%;
            background-position: center;
            background-repeat: no-repeat;
        }

        .coupon {
            display: flex;
            flex-direction: row;
            gap: 10px;
            align-items: center;
            justify-content: space-between;
            background-color: var(--color-7);
            padding: 15px 10px;
            border-radius: 5px;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            -ms-border-radius: 5px;
            -o-border-radius: 5px;
            width: 100%;
            font-size: 14px;
            font-weight: 600;
            color: var(--color-1);
            max-width: 600px;
        }

        .coupon div {
            display: flex;
            flex-direction: row;
            gap: 10px;
            align-items: center;
        }

        .coupon button {
            background-color: var(--color-2);
            color: var(--color-1);
            padding: 10px 15px;
            border: none;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            -ms-border-radius: 5px;
            -o-border-radius: 5px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            height: 40px;
        }

        .coupon input {
            padding: 10px 15px;
            border: none;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            -ms-border-radius: 5px;
            -o-border-radius: 5px;
            border-radius: 5px;
            width: 100%;
            outline: none;
            height: 40px;
            font-size: 14px;
            font-weight: 600;
            color: var(--color-1);
            font-family: var(--font-family-1);
        }

        .ms-price-card {
            padding: 0 30px 0 30px;
            max-width: 500px;
            width: 111%;
        }

        .table-title {
            font-weight: bold;
            color: #136e81;
        }

        .ms-main-btn-submit {
            border-radius: 5px;
            background-color: #136e82;
            color: #fff;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 500;
            width: max-content;
            border: none;
            outline: 0;
            cursor: pointer;
            transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
            width: 200px;
        }

        .map-mop {
            max-height: 100% !important;

        }
    </style>
    <style>
        @media screen and (-webkit-min-device-pixel-ratio: 0) {

            select,
            textarea,
            input {
                font-size: 16px;
            }
        }

        .unique-datepicker-input {
            font-size: 16px !important;
            touch-action: manipulation;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const datePickerInput = document.querySelector(".unique-datepicker-input");
            const datePickerModal = document.querySelector(".unique-datepicker-modal");
            const datePickerCalendar = document.querySelector(".unique-datepicker-calendar");
            const datePickerDays = document.querySelector(".unique-datepicker-days-wrapper");
            const datePickerMonthYear = document.querySelectorAll(".unique-datepicker-month-year");
            const prevButton = document.querySelector(".unique-datepicker-prev");
            const nextButton = document.querySelector(".unique-datepicker-next");

            const lang = document.documentElement.lang || "en";

            const months = {
                en: [
                    "January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                ],
                ar: [
                    "يناير", "فبراير", "مارس", "أبريل", "مايو", "يونيو",
                    "يوليو", "أغسطس", "سبتمبر", "أكتوبر", "نوفمبر", "ديسمبر"
                ]
            };

            const daysOfWeek = {
                en: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
                ar: ["الأحد", "الاثنين", "الثلاثاء", "الأربعاء", "الخميس", "الجمعة", "السبت"]
            };

            const placeholders = {
                en: "Select date",
                ar: "اختر التاريخ"
            };

            let currentDate = new Date();
            let selectedDateValue = null;

            // Set the input placeholder according to the language
            datePickerInput.placeholder = placeholders[lang];

            function renderCalendar(month, year) {
                datePickerDays.innerHTML = "";
                const firstDay = new Date(year, month, 1).getDay();
                const daysInMonth = new Date(year, month + 1, 0).getDate();

                // Update month and year display 🗓️
                datePickerMonthYear.forEach(el => {
                    el.textContent = `${months[lang][month]} ${year}`;
                });

                // Render days of the week headers 🗒️
                daysOfWeek[lang].forEach((day) => {
                    const dayName = document.createElement("div");
                    dayName.className = "unique-datepicker-day-name";
                    dayName.textContent = day;
                    datePickerDays.appendChild(dayName);
                });

                let today = new Date();
                today.setHours(0, 0, 0, 0); // Set the time to midnight for today comparison

                // Add empty divs for days before the first day of the month 📅
                for (let i = 0; i < firstDay; i++) {
                    datePickerDays.appendChild(document.createElement("div"));
                }

                // Render each day of the month 📆
                for (let i = 1; i <= daysInMonth; i++) {
                    const dayDate = new Date(year, month, i);
                    const dayElement = document.createElement("button");
                    dayElement.className = "unique-datepicker-day";
                    dayElement.textContent = i;

                    // Disable past dates 🚫
                    if (dayDate < today) {
                        dayElement.disabled = true;
                    } else {
                        dayElement.addEventListener("click", () => handleDayClick(i, month, year));
                    }

                    // Highlight today's date 🟢
                    if (
                        i === today.getDate() &&
                        month === today.getMonth() &&
                        year === today.getFullYear()
                    ) {
                        dayElement.classList.add("today");
                        dayElement.style.backgroundColor = "#547f89"; // Set today's background color
                    }

                    datePickerDays.appendChild(dayElement);
                }
            }

            function handleDayClick(day, month, year) {
                event.preventDefault();
                const selectedDate = new Date(year, month, day);
                const dayName = daysOfWeek[lang][selectedDate.getDay()];
                datePickerInput.value = `${dayName}, ${day}/${month + 1}/${year}`;

                // Remove previous selection, if any 🔄
                const prevSelected = datePickerDays.querySelector(".selected");
                if (prevSelected) {
                    prevSelected.classList.remove("selected");

                    // Restore the color for today if it was previously selected 🎨
                    if (prevSelected.classList.contains("today")) {
                        prevSelected.style.backgroundColor = "#547f89";
                    } else {
                        prevSelected.style.backgroundColor = "#ffffff"; // Reset non-today's background
                    }
                }

                // Add the 'selected' class to the new selected day 📍
                event.target.classList.add("selected");
                event.target.style.backgroundColor = "#136e82"; // New selected day color

                selectedDateValue = `${day}/${month + 1}/${year}`;
                closeDatePicker();
            }

            // Open the date picker 📅
            function openDatePicker() {
                datePickerModal.classList.add("show");
                setTimeout(() => datePickerCalendar.style.transform = "translateY(0)", 0);
            }

            // Close the date picker 🔒
            function closeDatePicker() {
                datePickerCalendar.style.transform = "";
                setTimeout(() => datePickerModal.classList.remove("show"), 300);

            }

            // Open on input click 🖱️
            datePickerInput.addEventListener("click", openDatePicker);


            // Close if modal is clicked outside the calendar ✖️
            datePickerModal.addEventListener("click", function (event) {
                if (event.target === datePickerModal) {
                    closeDatePicker();
                }
            });

            // Navigate to the previous month ⏮️
            prevButton.addEventListener("click", function (event) {
                event.preventDefault(); // Prevent the default submit behavior ❌
                currentDate.setMonth(currentDate.getMonth() - 1);
                renderCalendar(currentDate.getMonth(), currentDate.getFullYear());
            });

            // Navigate to the next month ⏭️
            nextButton.addEventListener("click", function (event) {
                event.preventDefault(); // Prevent the default submit behavior ❌
                currentDate.setMonth(currentDate.getMonth() + 1);
                renderCalendar(currentDate.getMonth(), currentDate.getFullYear());
            });

            // Initial render of the calendar 🖼️
            renderCalendar(currentDate.getMonth(), currentDate.getFullYear());
        });
    </script>

@endsection

@section('content-front')
    @php
        $locale = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale();
        $locale_type = Session::has('service_type') ? Session::get('service_type') : request()->route('type');
        $price = $locale_type === 'homeServices' ? 'at_home' : 'at_spa';
    @endphp

    <div class="cart-container">
        <form id="bookingForm" action="{{ route('saveOrderGift', $gift->id) }}" method="post">
            @csrf

            {{--            <h3 style="color: #136e81; margin-bottom: 15px;margin-top: 30px"> --}}
            {{--                <i class="fas fa-paper-plane" aria-hidden="true"></i> --}}
            {{--                اسم المرسل : --}}
            {{--                <span> {{ $gift->sender->name }}</span> --}}
            {{--            </h3> --}}

            <div class="ms-form-container">
                <div class="ms-form-section">
                    <div class="ms-section-title">
                        <div class="ms-title-num">1</div>
                        <div class="ms-title-text">{{ __('cart.information_location') }}</div>
                    </div>
                    <div class="ms-section-inputs">
                        <div class="flex-row gap-10 align-items-center ms-section-input waves-effect md-trigger"
                             data-modal="modal-map" id="edit-location-btn">
                            <div class="font-28 color-main">
                                <i class="fa-solid fa-map-location-dot"></i>
                            </div>
                            <div class="flex-auto" style="display:inline">
                                <a class="underline cursor-pointer"
                                   id="location-name">{{ __('cart.your_location') }}</a>
                            </div>
                            <input type="hidden" name="latitude" id="latitude">
                            <input type="hidden" name="longitude" id="longitude">
                        </div>
                        @error('location')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="ms-form-section">
                    <div class="ms-section-title">
                        <div class="ms-title-num">2</div>
                        <div class="ms-title-text">{{ __('cart.information_booking') }}</div>
                    </div>
                    <div class="ms-section-inputs">
                        <div class="ms-section-input ">
                            <label>{{ __('cart.date') }}</label>
                            {{-- start --}}
                            <div class="unique-datepicker-container">
                                <input type="text"
                                       style="text-align: inherit; direction: inherit; cursor: pointer; background-color: #f0f0f0;"
                                       class="unique-datepicker-input" inputmode="none" readonly id="date-input">
                                <input type="hidden" id="hidden-date-input" name="formattedDate">
                                @error('formattedDate')
                                <div style="color: red;font-weight: bold">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="unique-datepicker-modal">
                                <div class="unique-datepicker-calendar">
                                    <div class="unique-datepicker-header">
                                        <div id="dtp-handle"></div>
                                        <div class="unique-datepicker-month-year"></div>
                                    </div>
                                    <div class="unique-datepicker-days">
                                        <div class="unique-datepicker-days-header">
                                            <button class="unique-datepicker-prev">&lt;</button>
                                            <div class="unique-datepicker-month-year"></div>
                                            <button class="unique-datepicker-next">&gt;</button>
                                        </div>
                                        <div class="unique-datepicker-days-wrapper"></div>
                                    </div>
                                </div>
                            </div>
                            {{-- End --}}
                            @error('date')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="ms-section-input">
                            <label>{{ __('cart.time') }}</label>
                            <select class="unique-select" name="timeAvailable" id="time-select" readonly disabled>
                                <option selected disabled>{{ __('cart.select_time') }}</option>
                            </select>
                            @error('timeAvailable')
                            <div style="color: red;font-weight: bold">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="ms-section-input" id="employee-section">
                            <label>{{ __('cart.available_staff') }}</label>
                            <select class="unique-select" name="employeeAvailable" id="employee-select" readonly
                                    disabled>
                                <option selected disabled>{{ __('cart.select_staff') }}</option>
                            </select>
                            @error('employeeAvailable')
                            <div style="color: red;font-weight: bold">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="ms-form-section">
                    <div class="ms-section-title">
                        <div class="ms-title-num">3</div>
                        <div class="ms-title-text">{{ __('cart.addnotes') }}</div>
                    </div>
                    <div class="ms-section-inputs">
                        <div class="ms-section-input">
                            <label>{{ __('cart.addnotes') }}</label>
                            <textarea name="notes" placeholder="{{ __('cart.notes') }}"></textarea>
                            @error('note')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>


                <div style="font-weight: bold" class="text-center">
                    <button class="ms-main-btn-submit" type="submit">{{ __('gift.confirm_booking') }}</button>
                </div>
            </div>

        </form>

        <!--/** ******************** START MODEL MAP ******************** **\-->
        <div style=" padding-bottom: 50px !important" class="md-modal md-effect-3 map-mop" id="modal-map">
            <div style="padding:0 !important" class="md-content  map-cont ">
                <span class="pcoded-mtext"
                      style=" text-align: center; color: #136e81; width: 100%; display: block; padding: 10px 0; font-size: 18px; font-weight: bold;">
                    {{ __('general.selectLocation') }}
                </span>
                <div class="card-block">
                    <div class="ms-modal-body">
                        <div class="map-container">
                            <div style="margin-bottom: 3px" class="row">
                                <div class="ms-map-selected">

                                    <label class="form-control-label"><code>*</code>{{ __('general.find_address') }}
                                    </label>
                                    <br>
                                    <input type="text" id="addressSearch" class="form-control"
                                           placeholder="{{ __('general.enter_address') }}">
                                    <br>
                                    <button type="button" class="ms-btn-primary"
                                            onclick="geocodeAddress()">{{ __('general.search') }}
                                    </button>
                                </div>
                            </div>

                            <!-- Map centered on Riyadh -->
                            <div id="map" style="height: 250px; margin-bottom: 20px;"></div>
                            <input type="hidden" name="latitude" id="latitude">
                            <input type="hidden" name="longitude" id="longitude">
                            <div class="ms-modal-footer">
                                <button type="button" class="ms-btn-primary"
                                        id="saveLocation">{{ __('general.save') }}</button>
                                <br>
                                <button type="button"
                                        class="ms-btn-secondary waves-effect md-close">{{ __('general.cancel') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/** ******************** END MODEL MAP ******************** **\-->


        @endsection

        @section('scripts-front')


            <!--/🗺️/** ****************************************** START MODEL MAP ****************************************** **/🗺️/-->
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"/>
            <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
            <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

            <script>
                // 🗺️ Initialize the map
                var map = L.map('map').setView([24.7136, 46.6753], 10);
                var selectedCoordinates;

                // 🌍 Add the map layer
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© Eng. Andrew ©',
                    maxZoom: 18,
                }).addTo(map);


                // 📍 Define Riyadh boundaries
                var riadBounds = L.latLngBounds(
                    [24.4365, 46.6180],
                    [24.9642, 47.0441]
                );

                document.getElementById('edit-location-btn').addEventListener('click', function () {
                    getUserLocation();

                });

                // 🌐 Request the user's current location
                function getUserLocation() {
                    // SweetAlert to ask for permission to use the location
                    Swal.fire({
                        title: '{{ __('general.allowLocation') }}',
                        text: '{{ __('general.allowLocationDescription') }}',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: '{{ __('general.yes') }}',
                        cancelButtonText: '{{ __('general.no') }}'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (navigator.geolocation) {
                                navigator.geolocation.getCurrentPosition(function (position) {
                                    var lat = position.coords.latitude;
                                    var lon = position.coords.longitude;
                                    map.setView([lat, lon], 15);
                                    if (selectedCoordinates) {
                                        map.removeLayer(selectedCoordinates);
                                    }
                                    selectedCoordinates = L.marker([lat, lon]).addTo(map);
                                    document.getElementById('latitude').value = lat;
                                    document.getElementById('longitude').value = lon;

                                    // Use reverse geocoding to get the address
                                    reverseGeocode(lat, lon);
                                }, function (error) {
                                    // Show SweetAlert for location access error
                                    Swal.fire({
                                        title: 'خطأ',
                                        text: error.message ||
                                            'فشل في الحصول على الموقع الحالي. يرجى المحاولة لاحقاً.',
                                        icon: 'error',
                                    });
                                });
                            } else {
                                Swal.fire({
                                    title: 'خطأ',
                                    text: 'جهازك لا يدعم خاصية تحديد الموقع الجغرافي.',
                                    icon: 'error',
                                });
                            }
                        } else {
                            Swal.fire({
                                title: 'إلغاء',
                                text: 'لم يتم السماح بالوصول إلى الموقع.',
                                icon: 'info',
                            });
                        }
                    });
                }

                // 🖱️ Handle map click to manually select a location
                map.on('click', function (e) {
                    if (selectedCoordinates) {
                        map.removeLayer(selectedCoordinates);
                    }

                    var latlng = e.latlng;
                    var currentLang = '{{ App::getLocale() }}'; // Get the current site language
                    if (riadBounds.contains(latlng)) {
                        selectedCoordinates = L.marker(latlng).addTo(map);

                        document.getElementById('latitude').value = latlng.lat;
                        document.getElementById('longitude').value = latlng.lng;

                        // 🌐 Use Nominatim to get the address based on the current language
                        reverseGeocode(latlng.lat, latlng.lng, currentLang);

                        // 💾 Handle save location
                        document.getElementById('saveLocation').addEventListener('click', function () {
                            saveLocation(latlng.lat, latlng.lng);
                        });
                    } else {
                        Swal.fire({
                            title: 'موقعك خارج النطاق',
                            text: 'يرجى تحديد موقع داخل حدود الرياض.',
                            icon: 'error',
                        });
                    }
                });

                // 🔍 Reverse geocode the selected location
                function reverseGeocode(lat, lon, currentLang = 'en') {
                    var langParam = currentLang === 'ar' ? 'ar' : 'en'; // Determine language (Arabic or English)
                    $.getJSON('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' +
                        lon + '&accept-language=' + langParam,
                        function (data) {
                            if (data && data.display_name) {
                                document.getElementById('addressSearch').value = data.display_name;

                                // 🏠 Update the location name and description
                                var addressParts = data.display_name.split(',');
                                var firstPartOfAddress = addressParts[0].trim(); // Get the first part only

                                if (currentLang === 'ar') {
                                    // Display the address in Arabic
                                    document.getElementById('location-name').textContent = firstPartOfAddress;
                                    document.getElementById('location-desc').textContent =
                                        "هذا هو العنوان المختار على الخريطة.";
                                } else {
                                    // Display the address in English
                                    document.getElementById('location-name').textContent = firstPartOfAddress;
                                    document.getElementById('location-desc').textContent =
                                        "This is the selected location on the map.";
                                }

                                // 📍 Update hidden coordinates
                                document.getElementById('ms-latitude').value = lat;
                                document.getElementById('ms-longitude').value = lon;
                            } else {
                                alert('لم يتم العثور على العنوان.');
                            }
                        });
                }

                // 🔍 Search for address using Nominatim service
                function geocodeAddress() {
                    var address = document.getElementById('addressSearch').value;

                    if (address) {
                        $.getJSON('https://nominatim.openstreetmap.org/search?format=json&q=' + address, function (data) {
                            if (data && data.length > 0) {
                                var lat = data[0].lat;
                                var lon = data[0].lon;
                                map.setView([lat, lon], 15);

                                if (selectedCoordinates) {
                                    map.removeLayer(selectedCoordinates);
                                }

                                selectedCoordinates = L.marker([lat, lon]).addTo(map);
                                document.getElementById('latitude').value = lat;
                                document.getElementById('longitude').value = lon;

                                // 📝 Display the address in the input field
                                document.getElementById('addressSearch').value = data[0].display_name;
                            } else {
                                Swal.fire('خطأ', 'لم يتم العثور على العنوان.', 'error');
                            }
                        });
                    } else {
                        Swal.fire('تنبيه', 'يرجى إدخال عنوان للبحث.', 'warning');
                    }
                }

                // 💾 Save location function
                function saveLocation(lat, lon) {
                    if (lat && lon) {
                        Swal.fire({
                            title: '{{ __('general.areyousure') }}' + '?',
                            text: '{{ __('general.Select a location from the map') }}',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: '{{ __('general.yesconfirm') }}',
                            cancelButtonText: '{{ __('general.cancel') }}',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: '{{ route('front.availableEmployeesPlaces', ['type' => $locale_type]) }}',
                                    method: 'POST',
                                    data: {
                                        _token: '{{ csrf_token() }}',
                                        latitude: lat,
                                        longitude: lon,
                                    },
                                    success: function (response) {
                                        console.log('Available employees:', response.employees);
                                        document.querySelector('.md-close').click();

                                        Swal.fire({
                                            title: "نجاح",
                                            text: "تم حفظ الموقع بنجاح.",
                                            icon: "success",
                                        });
                                    },
                                    error: function (xhr, status, error) {
                                        console.error('An error occurred:', error);
                                        Swal.fire({
                                            title: "حدث خطأ",
                                            text: "فشل في حفظ الموقع. يرجى المحاولة لاحقاً.",
                                            icon: "error",
                                        });
                                    }
                                });
                            }
                        });
                    } else {
                        Swal.fire({
                            title: "بيانات مفقودة",
                            text: "يرجى تحديد موقع على الخريطة قبل الحفظ.",
                            icon: "warning",
                        });
                    }
                }
            </script>


            {{--      /** ****************************************** END MAP ****************************************** **/      --}}            <!--/🗺️/** ****************************************** END MODEL MAP ****************************************** **/🗺️/-->

            <script>
                $(document).ready(function () {
                    // console.log("Script is loaded and ready.");

                    var formattedDate = ''; // Initialize a variable to store the formatted date

                    function areAllFieldsFilled() {
                        var latitude = $('#latitude').val();
                        var longitude = $('#longitude').val();
                        var categoryId = null;
                        var packageId = null;

                        @if ($order->package->id != null)
                            packageId = {{ $order->package->id }};
                        console.log('Category ID:', packageId);
                        @else

                            categoryId = {{ $order->category->id }};
                        console.log('ID_Package:', categoryId);
                        @endif

                        console.log('Latitude:', latitude);
                        console.log('Longitude:', longitude);
                        console.log('Formatted Date:', formattedDate);

                        console.log('Latitude:', latitude);
                        console.log('Longitude:', longitude);
                        console.log('Formatted Date:', formattedDate);

                        // Check if either packageId or categoryId is present, not both
                        var idPresent = (packageId !== null || categoryId !== null) && !(packageId !== null &&
                            categoryId !== null);
                        console.log('ID Present:', idPresent);

                        var allFieldsFilled = latitude !== '' && longitude !== '' && idPresent && formattedDate !== '';
                        console.log('All Fields Filled:', allFieldsFilled);

                        return allFieldsFilled;
                    }

                    // دالة لتصفية الموظفين بناءً على المدخلات
                    function filterEmployees() {
                        if (areAllFieldsFilled()) {
                            var latitude = $('#latitude').val();
                            var longitude = $('#longitude').val();
                            var categoryId = null;
                            var packageId = null;

                            @if ($order->package->id != null)
                                packageId = {{ $order->package->id }};
                            console.log('Category ID:', packageId);
                            @else

                                categoryId = {{ $order->category->id }};
                            console.log('ID_Package:', categoryId);
                            @endif


                            // إرسال الطلب عبر AJAX
                            $.ajax({
                                url: '{{ route('front.filterEmployees', ['type' => $locale_type]) }}',
                                type: 'GET',
                                data: {
                                    latitude: latitude,
                                    longitude: longitude,
                                    category_id: categoryId,
                                    package_id: packageId,
                                    date: formattedDate
                                },
                                success: function (response) {
                                    console.log('Received response:', response);
                                    var timeAvailableSelect = $('select[name="timeAvailable"]');
                                    timeAvailableSelect.empty();
                                    var availableTimesSet = new Set();

                                    if (response.available_employees.length > 0) {
                                        timeAvailableSelect.append('<option value="">اختر وقت</option>');
                                        $.each(response.available_employees, function (index, employee) {
                                            $.each(employee.available_times, function (index, time) {
                                                availableTimesSet.add(time);
                                            });
                                        });
                                        var availableTimesArray = Array.from(availableTimesSet);
                                        $.each(availableTimesArray, function (index, time) {
                                            timeAvailableSelect.append('<option value="' + time + '">' +
                                                time + '</option>');
                                        });
                                    } else {
                                        timeAvailableSelect.append(
                                            '<option value="">لا يوجد أوقات متاحه</option>');
                                    }

                                    timeAvailableSelect.on('change', function () {
                                        var selectedTime = $(this).val();
                                        if (selectedTime !== '') {
                                            filterEmployeesByTime(selectedTime, response
                                                .available_employees);
                                        }
                                    });
                                },
                                error: function (xhr, status, error) {
                                    console.error('Error occurred:', {
                                        status: status,
                                        error: error,
                                        responseText: xhr.responseText
                                    });

                                    Swal.fire({
                                        title: "حدث خطأ",
                                        text: "فشل في جلب البيانات. يرجى المحاولة لاحقاً.",
                                        icon: "error"
                                    });
                                }
                            });
                        } else {
                            Swal.fire({
                                title: "تنبيه",
                                text: "يرجى ملء جميع الحقول قبل الفلترة.",
                                icon: "warning"
                            });
                        }
                    }

                    // دالة لتصفية الموظفين بناءً على الوقت المحدد
                    function filterEmployeesByTime(selectedTime, availableEmployees) {
                        var employeeAvailableSelect = $('select[name="employeeAvailable"]');
                        employeeAvailableSelect.empty();
                        employeeAvailableSelect.append('<option value="">اختر موظف</option>');

                        $.each(availableEmployees, function (index, employee) {
                            if (employee.available_times.includes(selectedTime)) {
                                employeeAvailableSelect.append('<option value="' + employee.employee_id + '">' +
                                    employee.employee_name + '</option>');
                            }
                        });
                    }

                    // استماع للأحداث عند تغيير المدخلات
                    $('#latitude, #longitude').on('change', function () {
                        console.log('Fields changed');
                        filterEmployees();
                    });

                    // تنسيق التاريخ إلى 'Y-m-d' قبل إرساله
                    $('.unique-datepicker-input').on('focusout', function () {
                        var $this = $(this);
                        setTimeout(function () {
                            var selectedDate = $this.val();
                            console.log("Original Date selected:", selectedDate);

                            // افتراض أن التاريخ في تنسيق 'dd/mm/yyyy'
                            var dateParts = selectedDate.split(', ')[1].split('/');
                            var day = dateParts[0].padStart(2, '0');
                            var month = dateParts[1].padStart(2, '0');
                            var year = dateParts[2];

                            // تنسيق التاريخ إلى 'Y-m-d'
                            formattedDate = year + '-' + month + '-' + day; // تحديث formattedDate
                            console.log("Formatted Date:", formattedDate);
                            $('#hidden-date-input').val(formattedDate);
                            // استدعاء الدالة لتحديث الفلترة بعد اختيار التاريخ
                            filterEmployees();
                        }, 100);
                    });
                });
            </script>

            <script>
                /** *************************** START REQUIRED *************************** **/

                document.addEventListener('DOMContentLoaded', function () {
                    const locationBtn = document.getElementById('edit-location-btn');
                    const dateInput = document.getElementById('date-input');
                    const timeSelect = document.getElementById('time-select');
                    const employeeSelect = document.getElementById('employee-select');

                    locationBtn.addEventListener('click', function () {
                        dateInput.disabled = false;
                        dateInput.readOnly = false;
                    });

                    dateInput.addEventListener('focus', function () {
                        if (dateInput.disabled) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'تنبيه',
                                text: 'يرجى اختيار الموقع أولا.',
                                confirmButtonText: 'موافق'
                            });
                        } else {
                            timeSelect.disabled = false;
                            timeSelect.readOnly = false;
                        }
                    });

                    timeSelect.addEventListener('focus', function () {
                        if (timeSelect.disabled) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'تنبيه',
                                text: 'يرجى اختيار التاريخ أولا.',
                                confirmButtonText: 'موافق'
                            });
                        } else {
                            employeeSelect.disabled = false;
                            employeeSelect.readOnly = false;
                        }
                    });

                    employeeSelect.addEventListener('focus', function () {
                        if (employeeSelect.disabled) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'تنبيه',
                                text: 'يرجى اختيار الوقت أولا.',
                                confirmButtonText: 'موافق'
                            });
                        }
                    });
                });
                /** *************************** END REQUIRED *************************** **/
            </script>



            <script type="text/javascript"
                    src="{{ asset('dashboard/assets/sweetalert/js/sweetalert.min.js') }}"></script>
            <script type="text/javascript" src="{{ asset('dashboard/assets/js/modal.js') }}"></script>
            <script type="text/javascript" src="{{ asset('dashboard/assets/js/modalEffects.js') }}"></script>
            <script type="text/javascript" src="{{ asset('dashboard/assets/js/classie.js') }}"></script>

@endsection
