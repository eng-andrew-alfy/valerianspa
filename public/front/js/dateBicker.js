document.addEventListener("DOMContentLoaded", function () {
    const datePickerInput = document.querySelector(".unique-datepicker-input");
    const datePickerCalendar = document.querySelector(
        ".unique-datepicker-calendar"
    );
    const datePickerDays = document.querySelector(".unique-datepicker-days");
    const datePickerMonthYear = document.querySelector(
        ".unique-datepicker-month-year"
    );
    const prevButton = document.querySelector(".unique-datepicker-prev");
    const nextButton = document.querySelector(".unique-datepicker-next");

    const lang = document.documentElement.lang || "en";

    const months = {
        en: [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December",
        ],
        ar: [
            "يناير",
            "فبراير",
            "مارس",
            "أبريل",
            "مايو",
            "يونيو",
            "يوليو",
            "أغسطس",
            "سبتمبر",
            "أكتوبر",
            "نوفمبر",
            "ديسمبر",
        ],
    };

    const daysOfWeek = {
        en: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
        ar: [
            "الأحد",
            "الاثنين",
            "الثلاثاء",
            "الأربعاء",
            "الخميس",
            "الجمعة",
            "السبت",
        ],
    };

    const placeholders = {
        en: "Select date",
        ar: "اختر التاريخ",
    };

    let currentDate = new Date();
    let selectedDateValue = null;

    datePickerInput.placeholder = placeholders[lang];

    function formatDateForConsole(date) {
        const day = String(date.getDate()).padStart(2, "0");
        const month = String(date.getMonth() + 1).padStart(2, "0");
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
    }

    function renderCalendar(month, year) {
        datePickerDays.innerHTML = "";
        const firstDay = new Date(year, month).getDay(); // First day of the current month
        const daysInMonth = new Date(year, month + 1, 0).getDate(); // Number of days in the current month
        const daysInPrevMonth = new Date(year, month, 0).getDate(); // Number of days in the previous month

        datePickerMonthYear.textContent = `${months[lang][month]} ${year}`;

        daysOfWeek[lang].forEach((day) => {
            const dayName = document.createElement("div");
            dayName.className = "unique-datepicker-day-name";
            dayName.textContent = day;
            datePickerDays.appendChild(dayName);
        });

        let today = new Date();
        today.setHours(0, 0, 0, 0); // Set time to midnight

        // Add days from the previous month
        for (let i = firstDay; i > 0; i--) {
            const dayValue = daysInPrevMonth - i + 1; // Calculate day from the previous month
            const dayDate = new Date(year, month - 1, dayValue); // Get the date of the day from the previous month
            const emptyCell = document.createElement("div");
            emptyCell.className = "unique-datepicker-day";
            emptyCell.textContent = dayValue;

            // Allow selecting days from the previous month
            if (dayDate >= today) {
                emptyCell.classList.remove("faded");
                emptyCell.addEventListener("click", function () {
                    handleDayClick(emptyCell, month - 1, year, dayValue);
                });
            } else {
                emptyCell.classList.add("faded");
                emptyCell.style.pointerEvents = "none"; // Prevent clicking on past days
            }

            datePickerDays.appendChild(emptyCell);
        }

        // Add days of the current month
        for (let i = 1; i <= daysInMonth; i++) {
            const day = document.createElement("div");
            day.className = "unique-datepicker-day";
            day.textContent = i;

            const dayDate = new Date(year, month, i);

            if (dayDate < today) {
                day.classList.add("faded");
                day.style.pointerEvents = "none";
            } else {
                day.addEventListener("click", function () {
                    handleDayClick(day, month, year, i);
                });
            }

            if (
                i === today.getDate() &&
                month === today.getMonth() &&
                year === today.getFullYear()
            ) {
                day.classList.add("selected");
            }

            datePickerDays.appendChild(day);
        }

        // Add days from the next month if needed
        const remainingDays = 7 - ((firstDay + daysInMonth) % 7);
        for (let i = 1; i <= remainingDays; i++) {
            const dayDate = new Date(year, month + 1, i);
            const emptyCell = document.createElement("div");
            emptyCell.className = "unique-datepicker-day";
            emptyCell.textContent = i;

            // Allow selecting days from the next month
            if (dayDate >= today) {
                emptyCell.classList.remove("faded");
                emptyCell.addEventListener("click", function () {
                    handleDayClick(emptyCell, month + 1, year, i);
                });
            } else {
                emptyCell.classList.add("faded");
                emptyCell.style.pointerEvents = "none";
            }

            datePickerDays.appendChild(emptyCell);
        }
    }

    function handleDayClick(day, month, year, i) {
        const selectedDate = new Date(year, month, i);
        const dayName = daysOfWeek[lang][selectedDate.getDay()];
        datePickerInput.value = `${dayName}, ${i}/${month + 1}/${year}`;

        // Remove the highlight from the previously selected day
        const prevSelected = datePickerDays.querySelector(".selected");
        if (prevSelected) {
            prevSelected.classList.remove("selected");
        }

        // Add highlight to the newly selected day
        day.classList.add("selected");

        // Hide the calendar after selection
        selectedDateValue = `${i}/${month + 1}/${year}`;
        datePickerCalendar.style.display = "none";
    }

    datePickerInput.addEventListener("click", function () {
        datePickerCalendar.style.display = "block";
    });

    document.addEventListener("click", function (event) {
        if (
            !datePickerCalendar.contains(event.target) &&
            !datePickerInput.contains(event.target)
        ) {
            datePickerCalendar.style.display = "none";
        }
    });

    prevButton.addEventListener("click", function () {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar(currentDate.getMonth(), currentDate.getFullYear());
    });

    nextButton.addEventListener("click", function () {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar(currentDate.getMonth(), currentDate.getFullYear());
    });

    renderCalendar(currentDate.getMonth(), currentDate.getFullYear());
});
