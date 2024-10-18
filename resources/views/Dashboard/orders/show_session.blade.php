@extends('Dashboard.layouts.master')
{{--@section('title_dashboard')--}}
{{--@endsection--}}
@section('css_dashboard')
    <!-- Data Table Css -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('dashboard/assets/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css"
          href="{{ asset('dashboard/assets/pages/data-table/css/buttons.dataTables.min.css') }}">
    {{--    <link rel="stylesheet" type="text/css" --}}
    {{--          href="{{ asset('dashboard/assets/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"> --}}
    <!-- animation nifty modal window effects css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/css/component.css') }}"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        .order-details {
            padding: 10px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            background-color: #f9f9f9;
            text-align: right;
            margin-right: 20px;
            /* Space to the right of the order details */
            flex: 1;
            /* Allow it to grow and shrink as needed */
        }

        .order-separator {
            border: 0;
            height: 1px;
            background: #d0d0d0;
            margin-bottom: 10px;
        }

        .order-date {
            color: #6c757d;
            /* Gray color */
            font-size: 16px;
            margin: 5px 0;
        }

        .order-date span {
            color: #343a40;
            /* Darker text color for the values */
            font-weight: bold;
        }

        /* Ensure table takes full width of the div */
        .table {
            width: 100%;
            table-layout: fixed;
            /* Ensures that the table will fill the container's width */
        }

        /* Make sure table cells and headers fill the width */
        .table th,
        .table td {
            text-align: center;
            vertical-align: middle;
            padding: 10px;
            /* Optional: Adjust padding as needed */
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn:disabled {
            cursor: not-allowed;
        }
    </style>

    <style>
        .btn-left {
            margin-bottom: 30px;
            /* Adding space below the button */
        }

        .pointer {
            cursor: pointer;
        }

        .new-addition {
            background-color: #d4edda;
            /* Light green background for new items */
            color: #155724;
            /* Dark green text color */
            padding: 5px;
            border-radius: 3px;
        }

        button:focus,
        button:active,
        .alert-confirm:focus,
        .alert-confirm:active,
        .status-checkbox:focus,
        .status-checkbox:active {
            outline: none;
            box-shadow: none;
        }

        .container {
            overflow: visible;
            /* لضمان عدم ظهور شريط التمرير */
            position: relative;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-button {
            background-color: #0073aa;
            color: white;
            padding: 7px 30px;
            border: 1px solid #007d96;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .dropdown-button:hover {
            background-color: #0073aa;
            border-color: #0073aa;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            border: 1px solid #007d96;
            border-radius: 5px;
            min-width: 160px;
            z-index: 1;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            top: 100%;
            /* لضبط موقع القائمة المنسدلة أسفل الزر */
            left: 0;
        }

        .dropdown-content a {
            color: #23282d;
            padding: 10px 20px;
            text-decoration: none;
            display: block;
            font-size: 14px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .dropdown-content a:hover {
            background-color: #0073aa;
            color: white;
        }

        .dropdown.show .dropdown-content {
            display: block;
        }

        .table-responsive {
            /* display:contents */
            overflow-x: visible
        }

        .select-refrash {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;

            background-color: #fff;
            border: 1px solid black;
            /* حدود العنصر باللون الأسود */
            padding: 10px;
            font-size: 16px;
            color: black;
            /* النص باللون الأسود */
            border-radius: 4px;
            cursor: pointer;
        }

        .select-refrash:focus {
            outline: none;
            border: 2px solid #0073aa;


        }

        .select-refrash option {
            background-color: white;
            /* اللون الافتراضي */
            color: black;
            /* لون النص الافتراضي */
        }


        .md-content {

            border-radius: 8px !important;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
    <!-- Date-time picker css -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('dashboard/assets/pages/advance-elements/css/bootstrap-datetimepicker.css') }}"/>
    <!-- Date-range picker css  -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('dashboard/assets/bootstrap-daterangepicker/css/daterangepicker.css') }}"/>
    <!-- Date-Dropper css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets//datedropper/css/datedropper.min.css') }}"/>
    <!-- animation nifty modal window effects css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/css/component.css') }}"/>
    <!-- Switch component css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/switchery/css/switchery.min.css') }}">
@endsection
@section('title_page')
    @if ($order->package_id != null)
        جلسات &nbsp;{{ $order->package->getTranslation('name', 'ar') }}&nbsp;للعميل&nbsp;{{ $order->user->name }}
    @else
        جلسات &nbsp;{{ $order->category->getTranslation('name', 'ar') }}&nbsp;للعميل&nbsp;{{ $order->user->name }}
    @endif

@endsection

@section('link_name_page_back')
@endsection

@section('title_link_name_page_back')
@endsection

@section('link_name_page')
@endsection

@section('title_link_name_page')



@endsection

@section('page-body')
    <div class="row">

        <div class="col-sm-12">
            <!-- contact data table card start -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-header-text">الجلسات</h5>
                </div>
                <div class="card-block contact-details">
                    <div class="data_table_main table-responsive dt-responsive">
                        <table id="simpletable"
                               class="table  table-striped table-bordered nowrap">
                            <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">إسم الجلسة</th>
                                <th class="text-center">التاريخ</th>
                                <th class="text-center">الحالة</th>
                                <th class="text-center">الملاحظات</th>
                                <th class="text-center">العمليات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($sessions as $session)
                                <tr data-session-id="{{ $session->id }}">

                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    @if ($order->package_id != null)
                                        <td class="text-center">
                                            {{ $order->package->getTranslation('name', 'ar') }}
                                        </td>
                                    @else
                                        <td class="text-center">
                                            {{ $order->category->getTranslation('name', 'ar') }}
                                        </td>
                                    @endif
                                    <td class="text-center date-cell">
                                        @if ($session->session_date != null)
                                            <span
                                                style="background-color: #17a2b8;color: #fff;padding: 6px 12px;border-radius: 12px;font-size: 14px;font-weight: 600;display: inline-block;">
                                                                                {{ \Carbon\Carbon::parse($session->session_date)->format('d-m-Y') }}
                                                                            </span>
                                        @else
                                            <span
                                                style="background-color: #b81717;color: #fff;padding: 6px 12px;border-radius: 12px;font-size: 14px;font-weight: 600;display: inline-block;">
                                                                                لم يتم تحديد تاريخ
                                        @endif
                                    </td>
                                    <td class="text-center status-cell">
                                        @if ($session->status == 'completed')
                                            <span class="badge badge-success">مكتملة</span>
                                        @elseif($session->status == 'canceled')
                                            <span class="badge badge-danger">ملغاة</span>
                                        @elseif($session->status == 'pending')
                                            <span class="badge badge-primary">متبقية</span>
                                    @endif
                                    <td class="text-center notes-cell">
                                        {{ \Illuminate\Support\Str::limit($session->notes, 15, '...') }}
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="dropdown-button">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <div class="dropdown-content">
                                                <a href="#" class="session-action-link"
                                                   data-modal="modal-3"
                                                   data-order-id="{{ $session->id }}"
                                                   data-action="update-{{ $session->id }}">اختيار
                                                    وقت الجلسة ⏰</a>

                                                <a href="#" class="session-action-link"
                                                   data-order-id="{{ $session->id }}"
                                                   data-action="end">انهاء الجلسة</a>
                                                <a href="#" class="session-action-link"
                                                   data-order-id="{{ $session->id }}"
                                                   data-action="cancel">الغاء الجلسة 🚫</a>

                                                <a href="#" class="session-action-link"
                                                   data-order-id="{{ $session->id }}"
                                                   data-action="opened">فتح
                                                    الجلسة 🔓
                                                </a>
                                            </div>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
            <!-- contact data table card end -->
        </div>


    </div>
    <!-- Modal Structure -->
    <div class="md-modal md-effect-3" id="modal-3">
        <div class="md-content container">
            <header class="modal-header">
                <h4 class="modal-title">تحديث تاريخ الجلسة</h4>
                {{-- <button class="close md-close" aria-label="Close">
                    &times;
                </button> --}}
            </header>
            <div class="modal-body">
                <form action="" method="POST" id="update-session-form" novalidate>
                    @csrf <!-- Include CSRF token for security -->

                    <!-- تاريخ الحجز -->
                    <div class="form-group">
                        <label for="date"><code>*</code> تاريخ الحجز</label>
                        <input type="text" name="date" class="form-control" id="dropper-max-year"
                               readonly="readonly"/>
                    </div>
                    <!-- تغيير الموظف -->
                    <div class="form-group">
                        <label for="changeEmployee "><code>*</code>
                            تغيير الموظف</label>
                        <select name="change_employee" class="col-sm-12 select-refrash" id="changeEmployee" required>
                            <option value="no">لا</option>
                            <option value="yes">نعم</option>
                        </select>
                    </div>
                    <!-- الوقت المتاح -->
                    <div class="form-group">
                        <label for="timeAvailable"><code>*</code>
                            الوقت المتاح</label>
                        <select name="time_available" class="col-sm-12 select-refrash" id="timeAvailable" required>
                            <option value="">اختر وقت</option>
                            <!-- Options will be populated dynamically -->
                        </select>
                    </div>
                    <!-- الموظفين المتاحين -->
                    <div class="form-group">
                        <label for="employeeAvailable"><code>*</code>
                            الموظفين المتاحين</label>
                        <select name="employee_available" class="col-sm-12 select-refrash" id="employeeAvailable"
                                required>
                            <option value="">اختر موظف</option>
                            <!-- Options will be populated dynamically -->
                        </select>
                        @error('employee_available')
                        <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <input type="hidden" name="employeeAvailable2" value="{{ $order->employee_id }}">

                    <!-- أزرار الحفظ والإلغاء -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">حفظ
                        </button>
                        <button type="button" class="btn btn-secondary md-close">
                            إلغاء
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

        <?php
        $location = json_decode($order->location, true);
        ?>
@endsection

@section('script_dashboard')
    {{--    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    {{--    --}}

    <script>
        document.querySelectorAll('.session-action-link').forEach(link => {
            link.addEventListener('click', function (event) {
                event.preventDefault();
                const sessionId = this.getAttribute('data-order-id');
                const formAction = `{{ route('admin.dateSession', 'PLACEHOLDER') }}`.replace('PLACEHOLDER',
                    sessionId);
                const form = document.getElementById('update-session-form');
                form.action = formAction;
                const modalId = this.getAttribute('data-modal');
                document.getElementById(modalId).style.display = 'block';
            });
        });
    </script>






    <!-- Select 2 js -->
    <script type="text/javascript" src="{{ asset('dashboard/assets/select2/js/select2.full.min.js') }}"></script>
    <!-- data-table js -->
    <script src="{{ asset('dashboard/assets/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/pages/data-table/js/jszip.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/pages/data-table/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/pages/data-table/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('dashboard/assets/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script
        src="{{ asset('dashboard/assets/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
    <!-- sweet alert js -->
    {{--    <script type="text/javascript" src="{{ asset('dashboard/assets/sweetalert/js/sweetalert.min.js') }}"></script> --}}
    <script type="text/javascript" src="{{ asset('dashboard/assets/js/modal.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/js/modalEffects.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/js/classie.js') }}"></script>
    <script src="{{ asset('dashboard/assets/pages/user-profile.js') }}"></script>
    <!-- Bootstrap date-time-picker js -->
    <script type="text/javascript"
            src="{{ asset('dashboard/assets/pages/advance-elements/moment-with-locales.min.js') }}">
    </script>
    <script type="text/javascript"
            src="{{ asset('dashboard/assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ asset('dashboard/assets/pages/advance-elements/bootstrap-datetimepicker.min.js') }}"></script>
    <!-- Date-range picker js -->
    <script type="text/javascript"
            src="{{ asset('dashboard/assets/bootstrap-daterangepicker/js/daterangepicker.js') }}">
    </script>
    <!-- Date-dropper js -->
    <script type="text/javascript" src="{{ asset('dashboard/assets/datedropper/js/datedropper.min.js') }}"></script>

    <!-- Custom js -->
    <script type="text/javascript"
            src="{{ asset('dashboard/assets/pages/advance-elements/custom-picker.js') }}"></script>

    <!-- Max-length js -->
    <script type="text/javascript" src="{{ asset('dashboard/assets/bootstrap-maxlength/js/bootstrap-maxlength.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/pages/advance-elements/swithces.js') }}"></script>
    <script>
        // التفاعل مع الزر لعرض القائمة المنسدلة
        document.querySelectorAll('.dropdown-button').forEach(button => {
            button.addEventListener('click', function () {
                this.parentElement.classList.toggle('show');
            });
        });

        // إغلاق القائمة عند النقر في أي مكان آخر في الصفحة
        window.onclick = function (event) {
            if (!event.target.matches('.dropdown-button') && !event.target.matches('.dropdown-button i')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.parentElement.classList.contains('show')) {
                        openDropdown.parentElement.classList.remove('show');
                    }
                }
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const handleSessionUrl = @json(route('admin.handleSession'));

        document.querySelectorAll('.session-action-link').forEach(function (link) {
            link.addEventListener('click', function (e) {
                    e.preventDefault();

                    let sessionOrderId = this.getAttribute('data-order-id');
                    let action = this.getAttribute('data-action');
                    // التعامل مع الإجراء "فتح الجلسة" مباشرةً


                    // التعامل مع الإجراء "فتح الجلسة" مباشرةً
                    if (action === 'opened') {
                        let requestData = {
                            sessionOrderId: sessionOrderId,
                            action: action
                        };

                        fetch(handleSessionUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(requestData)
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire('نجاح!', data.message, 'success');

                                    // تحديث واجهة المستخدم بعد فتح الجلسة
                                    updateSessionUI(sessionOrderId, data.action, data.notes);
                                } else {
                                    Swal.fire('خطأ!', data.error, 'error');
                                }
                            })
                            .catch((error) => {
                                Swal.fire('خطأ!', 'حدث خطأ أثناء تنفيذ الإجراء.', 'error');
                            });

                        return; // لا نحتاج إلى تنفيذ باقي الشيفرة بعد فتح الجلسة
                    }
                    let actionText = '';
                    if (action === 'end') {
                        actionText = 'السبب لإنهاء الجلسة';
                    } else if (action === 'cancel') {
                        actionText = 'السبب لإلغاء الجلسة';
                    } else {
                        return; // لا نفعل شيء إذا كان الإجراء غير معروف
                    }

                    Swal.fire({
                        title: 'أدخل ' + actionText,
                        input: 'textarea',
                        inputPlaceholder: actionText,
                        inputAttributes: {
                            'aria-label': 'سبب الإجراء'
                        },
                        showCancelButton: true,
                        confirmButtonText: 'تأكيد',
                        cancelButtonText: 'إلغاء',
                        inputValidator: (value) => {
                            if (!value) {
                                return 'السبب مطلوب!';
                            }
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let requestData = {
                                sessionOrderId: sessionOrderId,
                                action: action,
                                reason: result.value // السبب الذي أدخله المستخدم
                            };

                            fetch(handleSessionUrl, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify(requestData)
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire('نجاح!', data.message, 'success');

                                        // تحديث واجهة المستخدم
                                        updateSessionUI(sessionOrderId, data.action, data.notes);
                                    } else {
                                        Swal.fire('خطأ!', data.error, 'error');
                                    }
                                })
                                .catch((error) => {
                                    Swal.fire('خطأ!', 'حدث خطأ أثناء تنفيذ الإجراء.', 'error');
                                });
                        }
                    });
                }
            )
            ;
        });

        function updateSessionUI(sessionOrderId, action, notes) {
            // العثور على صف الجلسة بناءً على sessionOrderId
            let row = document.querySelector(`tr[data-session-id="${sessionOrderId}"]`);

            if (row) {
                // تحديث حالة الجلسة بناءً على الإجراء
                let statusCell = row.querySelector('.status-cell');
                let notesCell = row.querySelector('.notes-cell');

                if (action === 'end') {
                    statusCell.innerHTML = '<span class="badge badge-success">مكتملة</span>';
                } else if (action === 'cancel') {
                    statusCell.innerHTML = '<span class="badge badge-danger">ملغاة</span>';
                } else if (action === 'opened') {
                    statusCell.innerHTML = '<span class="badge badge-primary">متبقية</span>';
                }

                // تحديث نص الملاحظات ليظهر كلمتين أو ثلاث فقط
                if (notesCell) {
                    let notesText = notes.trim();
                    if (notesText.length > 30) { // يمكنك تعديل هذا الطول حسب الحاجة
                        notesCell.textContent = notesText.split(' ').slice(0, 3).join(' ') +
                            '...'; // إظهار أول ثلاث كلمات مع نقاط حذف
                    } else {
                        notesCell.textContent = notesText;
                    }
                }
            }
        }
    </script>
    <script>
        document.querySelectorAll('.session-action-link').forEach(function (link) {
            link.addEventListener('click', function (e) {
                e.preventDefault();

                let modalId = this.getAttribute('data-modal');
                let sessionOrderId = this.getAttribute('data-order-id');
                let action = this.getAttribute('data-action');

                // فتح المودال
                if (modalId) {
                    let modal = document.getElementById(modalId);
                    if (modal) {
                        modal.classList.add('md-show');
                    }
                }

                // يمكنك الآن ضبط النموذج بناءً على بيانات الجلسة إذا لزم الأمر
                // على سبيل المثال، إذا كنت بحاجة إلى تعيين ID الجلسة في نموذج
                // document.querySelector('input[name="session_id"]').value = sessionOrderId;
            });
        });

        // إغلاق المودال عند النقر على زر الإلغاء
        document.querySelectorAll('.md-close').forEach(function (closeButton) {
            closeButton.addEventListener('click', function (e) {
                e.preventDefault();
                let modal = this.closest('.md-modal');
                if (modal) {
                    modal.classList.remove('md-show');
                }
            });
        });
    </script>


    <script>
        $(document).ready(function () {
            // Function to check if all values are complete
            function areAllFieldsFilled() {
                var date = $('#dropper-max-year').val();
                return date !== '';
            }

            // Function to send filter request
            function filterEmployees() {
                if (areAllFieldsFilled()) {
                    var latitude = {{ $location['latitude'] }};
                    var longitude = {{ $location['longitude'] }};
                    var categoryId = {{ $order->category_id ?? 'null' }};
                    var packageId = {{ $order->package_id ?? 'null' }};
                    var date = $('#dropper-max-year').val();

                    // Convert date to correct format if necessary
                    var formattedDate = moment(date, 'MM/DD/YYYY').format('YYYY-MM-DD');

                    $.ajax({
                        url: '{{ route('admin.filterEmployees') }}',
                        type: 'GET',
                        data: {
                            latitude: latitude,
                            longitude: longitude,
                            category_id: categoryId,
                            package_id: packageId,
                            date: formattedDate,
                        },
                        success: function (response) {
                            var timeAvailableSelect = $('#timeAvailable');
                            timeAvailableSelect.empty();
                            var availableTimesSet = new Set(); // Use a Set to avoid duplicates

                            if (response.available_employees.length > 0) {
                                timeAvailableSelect.append('<option value="">اختر وقت</option>');
                                $.each(response.available_employees, function (index, employee) {
                                    $.each(employee.available_times, function (index, time) {
                                        availableTimesSet.add(
                                            time); // Add time to the Set
                                    });
                                });
                                var availableTimesArray = Array.from(availableTimesSet);
                                $.each(availableTimesArray, function (index, time) {
                                    timeAvailableSelect.append('<option value="' + time + '">' +
                                        time + '</option>');
                                });

                                // Activate the employee list when selecting the time
                                timeAvailableSelect.on('change', function () {
                                    var selectedTime = $(this).val();
                                    if (selectedTime !== '') {
                                        filterEmployeesByTime(selectedTime, response
                                            .available_employees);
                                    }
                                });
                            } else {
                                timeAvailableSelect.append(
                                    '<option value="">لا يوجد أوقات متاحه</option>');
                            }
                        },
                        error: function (xhr, status, error) {
                            swal({
                                title: "حدث خطأ",
                                text: "فشل في جلب البيانات. يرجى المحاولة لاحقاً.",
                                type: "error",
                                html: true
                            });
                        }
                    });
                } else {
                    swal({
                        title: "تنبيه",
                        text: "يرجى ملء جميع الحقول قبل الفلترة.",
                        type: "warning",
                        html: true
                    });
                }
            }

            // Function to filter employees based on specified time
            function filterEmployeesByTime(selectedTime, availableEmployees) {
                var employeeAvailableSelect = $('#employeeAvailable');
                var changeEmployee = $('#changeEmployee').val();
                var fixedEmployeeId = {{ $order->employee_id }};

                var fixedEmployeeName = '{{ optional($order->employee)->getTranslation('name', 'ar') ?? '' }}';

                employeeAvailableSelect.empty();
                employeeAvailableSelect.append('<option value="">اختر موظف</option>');

                if (changeEmployee === 'yes') {
                    // If "yes" is selected, show all available employees
                    $('#employeeAvailable').prop('disabled', false);
                    $.each(availableEmployees, function (index, employee) {
                        if (employee.available_times.includes(selectedTime)) {
                            employeeAvailableSelect.append('<option value="' + employee.employee_id + '">' +
                                employee.employee_name + '</option>');
                        }
                    });
                } else {
                    $('#employeeAvailable').empty().append('<option value="' + fixedEmployeeId + '">' +
                        fixedEmployeeName + '</option>').prop('disabled', true);
                }
            }

            // Function to update employee selection based on changeEmployee value
            function updateEmployeeSelection() {
                var changeEmployee = $('#changeEmployee').val();

                if (changeEmployee === 'yes') {
                    // If "yes" is selected, show all available employees
                    $('#employeeAvailable').prop('disabled', false);
                    filterEmployees(); // Fetch and display all available employees
                } else {
                    // If "no" is selected, show only the fixed employee
                    $('#employeeAvailable').empty().append('<option value="' + fixedEmployeeId + '">' +
                        fixedEmployeeName + '</option>').prop('disabled', true);
                }
            }

            // Listen for changes in "تغيير الموظف" selection
            $('#changeEmployee').on('change', updateEmployeeSelection);

            // Listen for changes in required fields and perform filtering
            $('#dropper-max-year, #changeEmployee').on('change', filterEmployees);

            // Listen for changes in select2 for categories and packages
            $('#categories, #packages').on('select2:select', function () {
                if ($(this).val() !== null) {
                    filterEmployees();
                }
            });

            // Initialize the page based on the initial changeEmployee value
            updateEmployeeSelection();
        });
    </script>
@endsection

