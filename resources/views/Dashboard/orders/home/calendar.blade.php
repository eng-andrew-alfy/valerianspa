@extends('Dashboard.layouts.master')

@section('css_dashboard')
    <!-- sweet alert framework -->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/sweetalert/css/sweetalert.css') }}">

    <!-- animation nifty modal window effects css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/css/component.css') }}"/>
    <style>
        .btn[disabled] {
            cursor: not-allowed;
        }

    </style>
@endsection

@section('title_page')
    التقويم
@endsection

@section('link_name_page_back')
@endsection

@section('title_link_name_page_back')
    التقويم
@endsection

@section('link_name_page')
@endsection

@section('title_link_name_page')
    قائمة التقويم
@endsection

@section('page-body')
    <div class="card">
        <div class="card-header">
        </div>
        <div class="card-block">
            <div class="row">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="md-modal md-effect-3" id="modal-event">
        <div class="md-content container" style="
        background-color: #f5f5f5; /* Light background color for the modal */
        border-radius: 8px; /* Rounded corners for a modern look */
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); /* Soft shadow effect for depth */
        padding: 20px; /* Padding around the content */
        max-width: 700px; /* Maximum width for the modal */
        margin: auto; /* Center the modal horizontally */
    ">
        <span class="pcoded-mtext"
              style="background-color: #0a6aa1; color: white; text-align: center; display: block; padding: 10px; font-size: 18px; font-weight: bold; border-radius: 4px;">
            تفاصيل الجلسة
        </span>
            <div class="card-block">
                <div class="j-wrapper j-wrapper-640">
                    <div class="j-content">
                        <div class="j-row">
                            <div class="j-input">
                                <label class="form-control-label"><code>*</code> إســم العميل</label>
                                <br>
                                <input type="text" id="eventTitle" class="form-control" readonly style="
                                background-color: #ffffff; /* White background for input */
                                color: #333; /* Dark text color */
                                border: 1px solid #ddd; /* Light border for input */
                                border-radius: 4px; /* Rounded corners for input */
                                padding: 8px; /* Padding inside the input */
                            ">
                                <br>
                                <label class="form-control-label"><code>*</code> معلومات الجلسة</label>
                                <br>
                                <div id="eventDetails" style="
                                background-color: #ffffff; /* White background for event details */
                                color: #333; /* Dark text color */
                                padding: 10px; /* Padding inside the event details */
                                border-radius: 8px; /* Rounded corners for event details */
                                box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); /* Soft shadow effect for event details */
                            "></div>
                                <input type="hidden" id="sessionId" value="">

                            </div>
                        </div>
                        <br>
                        <div class="modal-footer j-footer">
                            <button type="button" class="btn btn-primary" id="completedSession">تم الإنتهاء من الجلسة
                            </button>
                            <button type="button" class="btn btn-default waves-effect md-close">إلغاء</button>
                            <button type="button" class="btn btn-danger" id="cancelledSession">إلغاء موعد الجلسة
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script_dashboard')
    <!-- sweet alert js -->
    <script type="text/javascript"
            src="{{asset('dashboard/assets/sweetalert/js/sweetalert.min.js') }}"></script>
    <script type="text/javascript" src="{{asset('dashboard/assets/js/modal.js') }}"></script>
    <script type="text/javascript" src="{{asset('dashboard/assets/js/modalEffects.js') }}"></script>
    <script type="text/javascript" src="{{asset('dashboard/assets/js/classie.js') }}"></script>

    {{--    <script>--}}
    {{--        //======================================= START RESPONSIBLE FOR THE DELETION PROCESS AND CONFIRMATION OF DELETION ======================================= \\--}}

    {{--        document.querySelectorAll('.alert-confirm').forEach(button => {--}}
    {{--            button.onclick = function () {--}}
    {{--                const route = this.getAttribute('data-route');--}}
    {{--                const csrfToken = this.getAttribute('data-csrf-token');--}}
    {{--                const serviceName = this.getAttribute('data-name');--}}
    {{--                const row = this.closest('tr'); // Get the closest row element--}}

    {{--                // Display confirmation dialog--}}
    {{--                swal({--}}
    {{--                    title: "هل أنت متأكد؟",--}}
    {{--                    text: `لن تتمكن من استرجاع القسم الذى يحمل اسم:<br><strong style="color: red; font-weight: bold;">"${serviceName}"</strong>!`,--}}
    {{--                    type: "warning",--}}
    {{--                    html: true, // Enable HTML content--}}
    {{--                    showCancelButton: true,--}}
    {{--                    confirmButtonClass: "btn-danger",--}}
    {{--                    confirmButtonText: "نعم، احذفه!",--}}
    {{--                    cancelButtonText: "إلغاء",--}}
    {{--                    closeOnConfirm: false--}}
    {{--                }, function () {--}}
    {{--                    // Perform the DELETE request--}}
    {{--                    fetch(route, {--}}
    {{--                        method: 'DELETE',--}}
    {{--                        headers: {--}}
    {{--                            'X-CSRF-TOKEN': csrfToken,--}}
    {{--                            'Content-Type': 'application/json'--}}
    {{--                        }--}}
    {{--                    })--}}
    {{--                        .then(response => response.json())--}}
    {{--                        .then(data => {--}}
    {{--                            if (data.success) {--}}
    {{--                                swal({--}}
    {{--                                    title: "تم الحذف!",--}}
    {{--                                    text: `تم حذف القسم الذى يحمل اسم:<br><strong style="color: red; font-weight: bold;">"${serviceName}"</strong> بنجاح.`,--}}
    {{--                                    type: "success",--}}
    {{--                                    html: true // Enable HTML content--}}
    {{--                                });--}}
    {{--                                row.remove(); // Remove the row from the table--}}
    {{--                            } else {--}}
    {{--                                swal({--}}
    {{--                                    title: "فشل!",--}}
    {{--                                    text: `فشل حذف القسم الذى يحمل اسم:<br><strong style="color: red; font-weight: bold;">"${serviceName}"</strong>.`,--}}
    {{--                                    type: "error",--}}
    {{--                                    html: true // Enable HTML content--}}
    {{--                                });--}}
    {{--                            }--}}
    {{--                        })--}}
    {{--                        .catch(error => {--}}
    {{--                            console.error('Error:', error);--}}
    {{--                            swal({--}}
    {{--                                title: "خطأ!",--}}
    {{--                                text: `حدث خطأ أثناء حذف القسم الذى يحمل اسم:<br><strong style="color: red; font-weight: bold;">"${serviceName}"</strong>.`,--}}
    {{--                                type: "error",--}}
    {{--                                html: true // Enable HTML content--}}
    {{--                            });--}}
    {{--                        });--}}
    {{--                });--}}
    {{--            };--}}
    {{--        });--}}
    {{--        //======================================= END RESPONSIBLE FOR THE DELETION PROCESS AND CONFIRMATION OF DELETION ======================================= \\--}}


    {{--    </script>--}}
    <script>
        // 🎉 Initialization magic starts here!
        $(document).ready(function () {
            // 🌟 Making calendar events draggable!
            $("#external-events .fc-event").each(function () {
                $(this).data("event", {
                    title: $.trim($(this).text()),
                    stick: true,    // 🎈 Keep it sticky!
                });

                $(this).draggable({
                    zIndex: 999, // 🏆 Highest z-index to ensure visibility
                    revert: true, // 🔄 Revert back to original position if not dropped
                    revertDuration: 0 // ⚡ Instant revert
                });
            });

            // 📅 Set today's date in YYYY-MM-DD format
            var today = new Date();
            var formattedToday = today.toISOString().split('T')[0];

            // 🗓️ FullCalendar configuration starts here!
            $("#calendar").fullCalendar({
                header: {
                    left: "prev,next today", // 🔙🔜 Today controls
                    center: "title", // 🏷️ Center title
                    right: "month,agendaWeek,agendaDay" // 📅 Different views
                },
                defaultDate: formattedToday, // 🎯 Set default date
                editable: true, // ✏️ Allow editing of events
                events: function (start, end, timezone, callback) {
                    $.ajax({
                        url: "{{ route('admin.getCalendarData') }}", // 📡 Fetching events data
                        dataType: 'json',
                        success: function (data) {
                            // 🎊 Mapping data to calendar events
                            var events = data.map(function (event) {

                                var eventColor;

                                // 🎨 Set color based on event status
                                if (event.status === 'completed') {
                                    eventColor = '#008000'; // ✔️ Green for completed
                                } else if (event.status === 'canceled') {
                                    eventColor = 'rgba(139,0,0,0.85)'; // ❌ Red for canceled
                                } else {
                                    eventColor = event.color; // 🎨 Custom event color
                                }

                                var packageName = null;
                                var categoryName = null;
                                var employeeName = null;
                                var clientName = null;


                                // 🕒 Formatting session time
                                const sessionDate = event.session_date.split(' ')[1];
                                const [hours, minutes] = sessionDate.split(':');
                                let hoursIn12Format = hours % 12 || 12;
                                const ampm = hours >= 12 ? 'PM' : 'AM';
                                const formattedTime = `${hoursIn12Format}:${minutes} ${ampm}`;

                                try {
                                    packageName = event.package_name ? JSON.parse(event.package_name) : null;
                                } catch (e) {
                                    console.error("Invalid JSON in package_name:", event.package_name);
                                }

                                try {
                                    categoryName = event.category_name ? JSON.parse(event.category_name) : null;
                                } catch (e) {
                                    console.error("Invalid JSON in category_name:", event.category_name);
                                }

                                try {
                                    employeeName = event.employee_name ? JSON.parse(event.employee_name) : null;
                                } catch (e) {
                                    console.error("Invalid JSON in employee_name:", event.employee_name);
                                }
                                try {
                                    clientName = event.client_name ? event.client_name : null;
                                } catch (e) {
                                    console.error("Invalid JSON in client_name:", event.client_name);
                                }
                                // 💻 Attempt to parse various fields
                                var displayName = (packageName && packageName.ar) || (categoryName && categoryName.ar) || "Unknown Service";
                                const startDateTime = moment(event.session_date); // 🕒 Start time
                                const endDateTime = moment(startDateTime).add(1, 'hours'); // 🕒 End time
                                return {
                                    title: (clientName) || "Unknown clientName", // 👤 Client name

                                    start: startDateTime.format('YYYY-MM-DDTHH:mm:ss'),  // 📅 Start time in ISO
                                    end: endDateTime.format('YYYY-MM-DDTHH:mm:ss'),  // 📅 End time in ISO

                                    color: eventColor, // 🎨 Event color
                                    id: event.id,
                                    clientName: (clientName) || "Unknown clientName",
                                    employeeName: (employeeName && employeeName.ar) || "Unknown Employee",
                                    sessionDate: event.session_date.split(' ')[0],
                                    sectionTime: formattedTime,
                                    serviceName: displayName, // Use service name
                                    totalSessions: event.total_sessions,
                                    details: event.notes,
                                    status: event.status,
                                    address: event.google_maps_url || "غير متوفر", // 📍 Address
                                    googleMapsUrl: event.google_maps_url || "" // 📍 Google Maps URL
                                };

// Debugging

                            });

                            callback(events); // 🥳 Callback with events
                        },

                        error: function (xhr, status, error) {
                            console.log('Error fetching events:', error); // 🚫 Error handling
                            callback([]); // 👎 No events to show
                        }
                    });
                },
                // 🔗 Event click handler
                eventClick: function (event) {
                    $('#eventTitle').val(event.clientName);  // 📝 Display employee name

                    // ✨ Show event details in modal
                    $('#eventDetails').html(
                        `<div style="font-family: 'Trebuchet MS', Helvetica, Verdana, sans-serif; padding: 20px; border: 1px solid #ddd; border-radius: 10px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2); background: #ffffff;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr style="background-color: #0a6aa1; color: white; font-weight: bold; text-align: center;">
                <td colspan="2" style="padding: 15px; font-size: 18px;">تفاصيل الجلسة</td>
            </tr>
            <tr>
                <td style="width: 50%; padding: 15px; background-color: #f8f9fa;">تاريخ الجلسة:</td>
                <td style="width: 50%; padding: 15px; text-align: center; background-color: #ffffff; color: #333; border-left: 1px solid #ddd;">${event.sessionDate || 'غير متوفر'}</td>
            </tr>
 <tr>
                <td style="width: 50%; padding: 15px; background-color: #f8f9fa;">توقيت الجلسة:</td>
                <td style="width: 50%; padding: 15px; text-align: center; background-color: #ffffff; color: #333; border-left: 1px solid #ddd;">${event.sectionTime || 'غير متوفر'}</td>
            </tr>
            <tr>
                <td style="width: 50%; padding: 15px; background-color: #f8f9fa;">اسم الخدمة:</td>
                <td style="width: 50%; padding: 15px; text-align: center; background-color: #ffffff; color: #333; border-left: 1px solid #ddd;">${event.serviceName || 'غير متوفر'}</td>
            </tr>
            <tr>
                <td style="width: 50%; padding: 15px; background-color: #f8f9fa;">عنوان العميل:</td>
                <td style="width: 50%; padding: 15px; text-align: center; background-color: #ffffff; color: #333; border-left: 1px solid #ddd;">
                    <br>
                    ${event.googleMapsUrl ? `<a href="${event.googleMapsUrl}" target="_blank" style="color: #007bff;">فتح في خرائط Google</a>` : 'لا يوجد رابط للخريطة'}
                </td>
            </tr>
            <tr>
                <td style="width: 50%; padding: 15px; background-color: #f8f9fa;">اسم الموظف:</td>
                <td style="width: 50%; padding: 15px; text-align: center; background-color: #ffffff; color: #333; border-left: 1px solid #ddd;">${event.employeeName || 'غير متوفر'}</td>
            </tr>
            <tr>
                <td style="width: 50%; padding: 15px; background-color: #f8f9fa;">حالة الجلسة:</td>
                <td style="width: 50%; padding: 15px; text-align: center; border-left: 1px solid #ddd; ${getStatusStyle(event.status)}">
                    ${getStatusIcon(event.status)} ${getStatusText(event.status)}
                </td>
            </tr>
        </table>
    </div>`
                    );


                    function getStatusStyle(status) {
                        switch (status) {
                            case 'completed':
                                return 'background-color: #d4edda; color: #155724;'; // 🟢 Green
                            case 'canceled':
                                return 'background-color: #f8d7da; color: #721c24;'; // 🔴 Red
                            case 'pending':
                                return 'background-color: #fff3cd; color: #856404;'; // 🟡 Yellow
                            default:
                                return '';
                        }
                    }

                    function getStatusIcon(status) {
                        switch (status) {
                            case 'completed':
                                return '<i class="fa fa-check-circle" aria-hidden="true"></i>'; // ✔️
                            case 'canceled':
                                return '<i class="fa fa-times-circle" aria-hidden="true"></i>'; // ❌
                            case 'pending':
                                return '<i class="fa fa-hourglass-half" aria-hidden="true"></i>'; // ⏳
                            default:
                                return '';
                        }
                    }

                    function getStatusText(status) {
                        // 📝 Get text based on status
                        switch (status) {
                            case 'completed':
                                return 'مكتمل'; // Completed
                            case 'canceled':
                                return 'ملغى';  // Canceled
                            case 'pending':
                                return 'قيد الانتظار';// Pending
                            default:
                                return 'غير معروف'; // Unknown
                        }
                    }


                    $('#modal-event').addClass('md-show');
                    let sessionId;

                    function setSessionId(id) {
                        sessionId = id;
                    }

                    setSessionId(event.id);
                    $('#completedSession').prop('disabled', event.status === 'completed' || event.status === 'canceled');
                    $('#cancelledSession').prop('disabled', event.status === 'canceled' || event.status === 'completed');

                    $('#completedSession').on('click', function () {
                        console.log("Button #completedSession clicked");

                        $('#modal-event').removeClass('md-show');
                        console.log("#modal-event class md-show removed");

                        console.log("Session ID:", sessionId);

                        swal({
                            title: "هل أنت متأكد؟",
                            text: "يرجى إدخال سبب الإكمال:",
                            type: "input",
                            html: true,
                            showCancelButton: true,
                            confirmButtonClass: "btn-success",
                            confirmButtonText: "نعم، أكمل!",
                            cancelButtonText: "إلغاء",
                            closeOnConfirm: false,
                            inputPlaceholder: "اكتب السبب هنا"
                        }, function (inputValue) {
                            console.log("Swal callback triggered, inputValue:", inputValue);

                            if (inputValue === false) {
                                console.log("User cancelled the swal prompt");
                                return false;
                            }
                            if (inputValue === "") {
                                console.log("No reason provided, showing input error");
                                swal.showInputError("يجب إدخال سبب");
                                return false;
                            }

                            console.log("Proceeding with AJAX request, reason:", inputValue);
                            $.ajax({
                                url: '{{route('admin.updateStatusCalendar')}}',
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    session_id: sessionId,
                                    status: 'completed',
                                    reason: inputValue
                                },
                                success: function (response) {
                                    console.log("AJAX success response:", response);

                                    if (response.success) {
                                        console.log("Session completed successfully");

                                        swal({
                                            title: "تم!",
                                            text: "تم الإكمال بنجاح: " + inputValue,
                                            type: "success",
                                            html: true
                                        }, function () {
                                            updateCalendar();
                                        });
                                    } else {
                                        console.log("Session completion failed");

                                        swal({
                                            title: "فشل!",
                                            text: "فشل إكمال الجلسة.",
                                            type: "error",
                                            html: true
                                        });
                                    }
                                },
                                error: function (xhr, status, error) {
                                    console.log("AJAX error:", error);

                                    swal({
                                        title: "خطأ!",
                                        text: "حدث خطأ أثناء إكمال الجلسة.",
                                        type: "error",
                                        html: true
                                    });
                                }
                            });
                        });

                        console.log("Swal setup completed.");
                    });
                    $('#cancelledSession').on('click', function () {
                        $('#modal-event').removeClass('md-show');
                        swal({
                            title: "هل أنت متأكد؟",
                            text: "يرجى إدخال سبب الإلغاء:",
                            type: "input",
                            html: true, // Enable HTML content
                            showCancelButton: true,
                            confirmButtonClass: "btn-danger",
                            confirmButtonText: "نعم، الغي!",
                            cancelButtonText: "إلغاء",
                            closeOnConfirm: false, // Do not close on confirm to handle AJAX response
                            inputPlaceholder: "اكتب السبب هنا"
                        }, function (inputValue) {
                            if (inputValue === false) return false;  // User clicked 'Cancel'
                            if (inputValue === "") {
                                swal.showInputError("يجب إدخال سبب");  // Error if the input field is empty
                                return false;
                            }

                            // Perform the AJAX request to update the session status
                            $.ajax({
                                url: '{{route('admin.updateStatusCalendar')}}',  // Replace with your route URL
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',  // 🔒 CSRF token for security
                                    session_id: sessionId,  // Pass the session ID (replace 'sessionId' with actual variable)
                                    status: 'canceled',  // Status to update
                                    reason: inputValue  // Reason for cancellation
                                },
                                success: function (response) {
                                    if (response.success) {
                                        swal({  // 🥳 SweetAlert for success message
                                            title: "تم!",
                                            text: "تم الإلغاء بنجاح: " + inputValue,
                                            type: "success",
                                            html: true // Enable HTML content
                                        }, function () {
                                            updateCalendar();
                                        });
                                    } else {
                                        swal({   // ❌ SweetAlert for error message
                                            title: "فشل!",
                                            text: "فشل إلغاء الجلسة.",
                                            type: "error",
                                            html: true // Enable HTML content
                                        });
                                    }
                                },
                                error: function (xhr, status, error) {
                                    swal({
                                        title: "خطأ!",
                                        text: "حدث خطأ أثناء إلغاء الجلسة.",
                                        type: "error",
                                        html: true // Enable HTML content
                                    });
                                }
                            });
                        });
                    });

                    function updateCalendar() {
                        $('#calendar').fullCalendar('refetchEvents');
                    }
                }

            });

            $('.md-close').on('click', function () {
                $('#modal-event').removeClass('md-show');
            });


        });


    </script>

@endsection
