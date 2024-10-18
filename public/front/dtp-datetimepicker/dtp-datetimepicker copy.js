function Dtpcalendar(
    input,
    year,
    month,
    day,
    selectedDate = "",
    selectedTime = "",
    timeTable = [],
    lang = "ar",
    d = 0
) {
    var nameText = $(input).attr("name");

    var input = $(input);
    var date = new Date(year, month, 1);
    var daysInMonth = new Date(year, month + 1, 0).getDate();
    var firstDay = date.getDay();

    var months_en = [
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
    ];
    var months_ar = [
        "يناير",
        "فبراير",
        "مارس",
        "أبريل",
        "مايو",
        "يونيو",
        "يوليو",
        "اغسطس",
        "سبتمبر",
        "اكتوبر",
        "نوفمبر",
        "ديسمبر",
    ];

    var months = {
        en: months_en,
        ar: months_ar,
    };

    var currentMonth = month;
    var currentYear = year;
    var currentDay = day;

    var days_en = [
        "Saturday",
        "Sunday",
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
    ];
    var days_ar = [
        "السبت",
        "الاحد",
        "الاثنين",
        "الثلاثاء",
        "الاربعاء",
        "الخميس",
        "الجمعة",
    ];

    var days = {
        en: days_en,
        ar: days_ar,
    };

    var days_en_abbr = ["Sat", "Sun", "Mon", "Tue", "Wed", "Thu", "Fri"];
    var days_ar_abbr = [
        "سبت",
        "احد",
        "اثنين",
        "ثلاثاء",
        "اربعاء",
        "خميس",
        "جمعة",
    ];

    var days_abbr = {
        en: days_en_abbr,
        ar: days_ar_abbr,
    };

    var dayNum = [];
    var dayNumStatus = [];

    var prevDayNum = [];

    if (date.getDay() != 6) {
        for (var i = 0; i < date.getDay() + 1; i++) {
            prevDayNum.push(new Date(year, month, 0).getDate() - i);
        }
    }

    for (var i = prevDayNum.length - 1; i >= 0; i--) {
        dayNum.push(prevDayNum[i]);
        dayNumStatus.push(-1);
    }

    for (var i = 1; i <= daysInMonth; i++) {
        dayNum.push(i);
        dayNumStatus.push(0);
    }

    for (var i = 1; 0 < 42 - dayNum.length; i++) {
        dayNum.push(i);
        dayNumStatus.push(1);
    }

    var todayDate = new Date();

    var dayNumRow = "";

    for (var i = 0; i < 6; i++) {
        dayNumRow += "<tr>";
        for (var j = 0; j < 7; j++) {
            if (dayNumStatus[i * 7 + j] == -1) {
                var datei = new Date(
                    currentYear,
                    currentMonth - 1,
                    dayNum[i * 7 + j]
                );
                dayNumRow +=
                    '<td><button type="button" class="disabled" disabled>' +
                    dayNum[i * 7 + j] +
                    "</button></td>";
                continue;
            }

            if (dayNumStatus[i * 7 + j] == 1) {
                var datei = new Date(
                    currentYear,
                    currentMonth + 1,
                    dayNum[i * 7 + j]
                );
                dayNumRow +=
                    '<td><button type="button" class="disabled" disabled>' +
                    dayNum[i * 7 + j] +
                    "</button></td>";
                continue;
            }

            var datei = new Date(currentYear, currentMonth, dayNum[i * 7 + j]);

            if (
                datei.getDate() == todayDate.getDate() &&
                datei.getMonth() == todayDate.getMonth() &&
                datei.getFullYear() == todayDate.getFullYear()
            ) {
                dayNumRow +=
                    '<td><button type="button" class="today selected" data-day="' +
                    datei.getDate() +
                    '" data-month="' +
                    (datei.getMonth() + 1) +
                    '" data-year="' +
                    datei.getFullYear() +
                    '">' +
                    dayNum[i * 7 + j] +
                    "</button></td>";
                continue;
            }
            if (todayDate > datei) {
                dayNumRow +=
                    '<td><button type="button" class="disabled" disabled>' +
                    dayNum[i * 7 + j] +
                    "</button></td>";
                continue;
            }
            dayNumRow +=
                '<td><button type="button" data-day="' +
                datei.getDate() +
                '" data-month="' +
                (datei.getMonth() + 1) +
                '" data-year="' +
                datei.getFullYear() +
                '">' +
                dayNum[i * 7 + j] +
                "</button></td>";
        }
        dayNumRow += "</tr>";
    }

    var weekDayNames = [];

    if (lang === "en") {
        if ($(window).width() < 320) {
            weekDayNames = days_en_abbr;
        } else {
            weekDayNames = days_en_abbr;
        }

        var currentYearRow = year.toLocaleString("en-US", {
            style: "decimal",
            useGrouping: false,
        });
        if (d === 0) {
            var selectedDate = new Date();
            selectedDay = selectedDate.getDate();
            selectedMonth = selectedDate.getMonth() + 1;
            selectedYear = selectedDate.getFullYear();
            selectedDate =
                selectedDay + "/" + selectedMonth + "/" + selectedYear;
        }
    } else if (lang === "ar") {
        if ($(window).width() < 420) {
            weekDayNames = days_ar_abbr;
        } else {
            weekDayNames = days_ar;
        }

        var currentYearRow = year.toLocaleString("ar-EG", {
            style: "decimal",
            useGrouping: false,
        });

        $("#dtp-calendar-table thead tr").html("");
        for (var i = 0; i < 7; i++) {
            $("#dtp-calendar-table thead tr").append(
                "<th>" + days_abbr.ar[i] + "</th>"
            );
        }

        if (d === 0) {
            var selectedDate = new Date();
            selectedDay = selectedDate.getDate();
            selectedMonth = selectedDate.getMonth() + 1;
            selectedYear = selectedDate.getFullYear();
            selectedDate =
                selectedDay + "/" + selectedMonth + "/" + selectedYear;
        }
    }

    var weekDayNamesRow = "";
    for (var i = 0; i < weekDayNames.length; i++) {
        weekDayNamesRow += "<td>" + weekDayNames[i] + "</td>";
    }

    input.on("click", function () {
        $("#dtp-datetime-picker-container")
            .addClass("show")
            .removeClass("hide");
    });

    var TimeNum = timeTable;

    var TimeNumRow = "";

    for (var i = 0; i < TimeNum.length / 4; i++) {
        TimeNumRow += "<tr>";
        for (var j = 0; j < 4; j++) {
            if (i === 0 && j === 0) {
                TimeNumRow +=
                    '<td><button type="button" class="selected" data-time="' +
                    TimeNum[i * 4 + j] +
                    '">' +
                    getDtpTimeText(TimeNum[i * 4 + j], lang) +
                    "</button></td>";
                continue;
            }
            TimeNumRow +=
                '<td><button type="button" data-time="' +
                TimeNum[i * 4 + j] +
                '">' +
                getDtpTimeText(TimeNum[i * 4 + j], lang) +
                "</button></td>";

            if (i * 4 + j === TimeNum.length - 1) {
                break;
            }
        }
        TimeNumRow += "</tr>";
    }

    var picker = $("#dtp-datetime-picker-container").html(
        `
        <div id="dtp-background"></div>
        <div id="dtp-datetime-picker">
            <div id="dtp-header">
                <div id="dtp-handle" title="handle"></div>
                <div id="dtp-title">` +
            getDtpText("Select date and time", lang) +
            `</div>
            </div>
            <div id="dtp-body">
                <div id="dtp-calendar">
                    <div id="dtp-calendar-header">
                        <button type="button" id="dtp-prev-month" title="` +
            getDtpText("Previous Month", lang) +
            `">
                            ` +
            getDtpText('<i class="fa-solid fa-chevron-left"></i>', lang) +
            `
                        </button>
                        <button type="button" id="dtp-month-year" title="` +
            getDtpText("The Month and The Year", lang) +
            `">` +
            currentYearRow +
            " " +
            months[lang][currentMonth] +
            `</button>
                        <button type="button" id="dtp-next-month" title="` +
            getDtpText("Next Month", lang) +
            `">
                            ` +
            getDtpText('<i class="fa-solid fa-chevron-right"></i>', lang) +
            `
                        </button>
                    </div>
                    <div id="dtp-calendar-body" class="">
                        <table id="dtp-calendar-table">
                            <thead>
                                <tr>
                                    ` +
            weekDayNamesRow +
            `
                                </tr>
                            </thead>
                            <tbody>
                            ` +
            dayNumRow +
            `
                            </tbody>
                        </table>
                        <div id="dtp-calendar-extra">
                            ` +
            getDtpText("Show the rest", lang) +
            `
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                    </div>
                </div>
                <div id="dtp-time">
                    <div id="dtp-time-header">
                        <div id="dtp-time-title">` +
            getDtpText("Choose an appropriate time", lang) +
            `</div>
                    </div>
                    <div id="dtp-time-body">
                        <table id="dtp-time-table">
                            <tbody>
                                ` +
            TimeNumRow +
            `
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div id="dtp-footer">
                <button type="button" id="dtp-select-button">` +
            getDtpText("Select", lang) +
            `</button>
                <div id="dtp-footer-handle"></div>
            </div>
        </div>
    `
    );

    if (lang == "en") {
    } else if (lang == "ar") {
        $("#dtp-calendar-table tbody button").each(function () {
            var num = $(this).text();
            var arNum = parseInt(num).toLocaleString("ar-EG", {
                style: "decimal",
                useGrouping: true,
            });

            $(this).text(arNum);
        });
    }

    if (d === 0) {
        var selectedTime = TimeNum[0];
    } else {
        var selectedTime = selectedTime;
    }

    $("#dtp-calendar #dtp-next-month").on("click", function () {
        var date = new Date(currentYear, currentMonth + 1, 1);

        var day = date.getDate();
        var month = date.getMonth();
        var year = date.getFullYear();

        Dtpcalendar(
            (input = input),
            (year = year),
            (month = month),
            (day = day),
            (selectedDate = getDtpDateText(selectedDate)),
            (selectedTime = selectedTime),
            (timeTable = timeTable),
            (lang = lang),
            (d = 1)
        );
    });

    $("#dtp-calendar #dtp-prev-month").on("click", function () {
        var date = new Date(currentYear, currentMonth - 1, 1);

        var day = date.getDate();
        var month = date.getMonth();
        var year = date.getFullYear();

        Dtpcalendar(
            (input = input),
            (year = year),
            (month = month),
            (day = day),
            (selectedDate = getDtpDateText(selectedDate)),
            (selectedTime = selectedTime),
            (timeTable = timeTable),
            (lang = lang),
            (d = 1)
        );
    });

    $("#dtp-calendar #dtp-calendar-extra").on("click", function () {
        if ($("#dtp-calendar-body").hasClass("extra")) {
            $("#dtp-calendar-body").removeClass("extra");
        } else {
            $("#dtp-calendar-body").addClass("extra");
        }
    });

    $("#dtp-calendar #dtp-calendar-table tbody button").each(function (
        index,
        btn
    ) {
        $(btn).on("click", function () {
            $(btn).addClass("selected");
            //remove class from parent
            $(btn).parent().siblings().find("button").removeClass("selected");
            $(btn)
                .parent()
                .parent()
                .siblings()
                .find("button")
                .removeClass("selected");

            selectedDate =
                $(btn).data("day") +
                "/" +
                $(btn).data("month") +
                "/" +
                $(btn).data("year");

            $(input).val(
                getDtpDateText(selectedDate, lang) +
                    " " +
                    getDtpTimeText(selectedTime, lang)
            );
            $(input).trigger("change");
        });
    });

    $("#dtp-time #dtp-time-table tbody button").each(function (index, btn) {
        $(btn).on("click", function () {
            $(btn).addClass("selected");
            //remove class from parent
            $(btn).parent().siblings().find("button").removeClass("selected");
            $(btn)
                .parent()
                .parent()
                .siblings()
                .find("button")
                .removeClass("selected");

            selectedTime = $(btn).data("time");

            $(input).val(
                getDtpDateText(selectedDate, lang) +
                    " " +
                    getDtpTimeText(selectedTime, lang)
            );
            $(input).trigger("change");
        });
    });

    $("#dtp-datetime-picker-container #dtp-background").on(
        "click",
        function () {
            $("#dtp-datetime-picker-container")
                .removeClass("show")
                .addClass("hide");
        }
    );

    $("#dtp-select-button").on("click", function () {
        $("#dtp-datetime-picker-container")
            .removeClass("show")
            .addClass("hide");
    });

    $(input).val(
        getDtpDateText(selectedDate, lang) +
            " " +
            getDtpTimeText(selectedTime, lang)
    );
    $(input).trigger("change");
}

function getDtpText(text = {}, lang = "en") {
    var textTranslate = {
        Select: "اختار",
        Today: "اليوم",
        Month: "شهر",
        Year: "سنة",
        Close: "اغلاق",
        "Next Month": "الشهر القادم",
        "Previous Month": "الشهر السابق",
        "Show the rest": "اظهار الباقي",
        "Hide the rest": "اخفاءالباقي",
        '<i class="fa-solid fa-chevron-right"></i>':
            '<i class="fa-solid fa-chevron-left"></i>',
        '<i class="fa-solid fa-chevron-left"></i>':
            '<i class="fa-solid fa-chevron-right"></i>',
        "Select date and time": "اختار التاريخ والوقت",
        "The Month and The Year": "الشهر والسنة",
        "Choose an appropriate time": "اختر وقت مناسب",
    };

    if (lang === "en") {
        return text;
    } else if (lang === "ar") {
        return textTranslate[text];
    }
}

function getDtpTimeText(time, lang = "en") {
    var nums = {
        "00": "٠٠",
        "01": "٠١",
        "02": "٠٢",
        "03": "٠٣",
        "04": "٠٤",
        "05": "٠٥",
        "06": "٠٦",
        "07": "٠٧",
        "08": "٠٨",
        "09": "٠٩",
        10: "١٠",
        11: "١١",
        12: "١٢",
        13: "١٣",
        14: "١٤",
        15: "١٥",
        16: "١٦",
        17: "١٧",
        18: "١٨",
        19: "١٩",
        20: "٢٠",
        21: "٢١",
        22: "٢٢",
        23: "٢٣",
        24: "٢٤",
    };

    if (lang === "en") {
        var fullTime = time.split(" ");
        var time = fullTime[0].split(":");

        var hours = time[0];
        var meridiem = fullTime[1];

        return hours + ":00 " + meridiem;
    } else if (lang === "ar") {
        var fullTime = time.split(" ");
        var time = fullTime[0].split(":");
        var hours = time[0];
        var meridiem = fullTime[1];

        if (meridiem === "AM") {
            meridiem = "ص";
        } else if (meridiem === "PM") {
            meridiem = "م";
        }

        return nums[hours] + ":٠٠ " + meridiem;
    }
}

function getDtpDateText(date, lang = "en") {
    var date = date.split("/");

    var day = date[0];
    var month = date[1];
    var year = date[2];

    if (lang == "ar") {
        date =
            parseInt(year).toLocaleString("ar-EG", {
                style: "decimal",
                useGrouping: false,
            }) +
            "/" +
            parseInt(month).toLocaleString("ar-EG", {
                style: "decimal",
                useGrouping: false,
            }) +
            "/" +
            parseInt(day).toLocaleString("ar-EG", {
                style: "decimal",
                useGrouping: false,
            });
    } else {
        date = day + "/" + month + "/" + year;
    }

    return date;
}
