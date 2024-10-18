function SimpleDatePicker(input, lang = "en") {
    const datePickerInput = document.querySelector(input);
    const datePickerContainer = document.createElement('div');
    datePickerContainer.className = 'simple-date-picker';
    document.body.appendChild(datePickerContainer);

    const months = {
        en: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
        ar: ["يناير", "فبراير", "مارس", "أبريل", "مايو", "يونيو", "يوليو", "أغسطس", "سبتمبر", "أكتوبر", "نوفمبر", "ديسمبر"]
    };

    const days = {
        en: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
        ar: ['أحد', 'إثنين', 'ثلاثاء', 'أربعاء', 'خميس', 'جمعة', 'سبت']
    };

    let currentDate = new Date();
    let currentMonth = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();

    function renderCalendar() {
        const firstDay = new Date(currentYear, currentMonth, 1).getDay();
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

        let calendarHTML = `
            <div class="calendar-header">
                <button class="prev-month">${lang === 'ar' ? '&rarr;' : '&larr;'}</button>
                <span class="current-month-year">${months[lang][currentMonth]} ${currentYear}</span>
                <button class="next-month">${lang === 'ar' ? '&larr;' : '&rarr;'}</button>
            </div>
            <table class="calendar-table">
                <thead>
                    <tr>
                        ${days[lang].map(day => `<th>${day}</th>`).join('')}
                    </tr>
                </thead>
                <tbody>
        `;

        let date = 1;
        for (let i = 0; i < 6; i++) {
            calendarHTML += '<tr>';
            for (let j = 0; j < 7; j++) {
                if (i === 0 && j < firstDay) {
                    calendarHTML += '<td></td>';
                } else if (date > daysInMonth) {
                    break;
                } else {
                    const isToday = date === currentDate.getDate() && currentMonth === currentDate.getMonth() && currentYear === currentDate.getFullYear();
                    calendarHTML += `<td><button class="date-button ${isToday ? 'today' : ''}" data-date="${date}">${lang === 'ar' ? convertToArabicNumerals(date) : date}</button></td>`;
                    date++;
                }
            }
            calendarHTML += '</tr>';
            if (date > daysInMonth) {
                break;
            }
        }

        calendarHTML += `
                </tbody>
            </table>
        `;

        datePickerContainer.innerHTML = calendarHTML;
        attachEventListeners();
    }

    function attachEventListeners() {
        const prevMonthBtn = datePickerContainer.querySelector('.prev-month');
        const nextMonthBtn = datePickerContainer.querySelector('.next-month');
        const dateButtons = datePickerContainer.querySelectorAll('.date-button');

        prevMonthBtn.addEventListener('click', () => {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            renderCalendar();
        });

        nextMonthBtn.addEventListener('click', () => {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            renderCalendar();
        });

        dateButtons.forEach(button => {
            button.addEventListener('click', () => {
                const selectedDate = new Date(currentYear, currentMonth, parseInt(button.dataset.date));
                datePickerInput.value = formatDate(selectedDate, lang);
                datePickerContainer.style.display = 'none';
            });
        });
    }

    function formatDate(date, lang) {
        const day = date.getDate();
        const month = date.getMonth() + 1;
        const year = date.getFullYear();
        if (lang === 'ar') {
            return `${convertToArabicNumerals(year)}/${convertToArabicNumerals(month)}/${convertToArabicNumerals(day)}`;
        }
        return `${day}/${month}/${year}`;
    }

    function convertToArabicNumerals(num) {
        const arabicNumerals = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        return num.toString().split('').map(digit => arabicNumerals[parseInt(digit)]).join('');
    }

    datePickerInput.addEventListener('click', () => {
        datePickerContainer.style.display = 'block';
        renderCalendar();
    });

    document.addEventListener('click', (event) => {
        if (!datePickerContainer.contains(event.target) && event.target !== datePickerInput) {
            datePickerContainer.style.display = 'none';
        }
    });

    renderCalendar();
}

// Usage
SimpleDatePicker('.unique-datepicker-input', 'en'); // or 'ar' for Arabic
