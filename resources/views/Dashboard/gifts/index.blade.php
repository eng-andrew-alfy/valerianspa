@extends('Dashboard.layouts.master')

@section('css_dashboard')
    <!-- sweet alert framework -->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/sweetalert/css/sweetalert.css') }}">
    <!-- Data Table Css -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('dashboard/assets/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css"
          href="{{ asset('dashboard/assets/pages/data-table/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css"
          href="{{ asset('dashboard/assets/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}">
    <!-- animation nifty modal window effects css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/css/component.css') }}"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
    </style>
@endsection

@section('title_page')
    الهدايا
@endsection

@section('link_name_page_back')
@endsection

@section('title_link_name_page_back')
    الهدايا
@endsection

@section('link_name_page')
@endsection

@section('title_link_name_page')
    قائمة الهدايا
@endsection

@section('page-body')

    <br>
    <br>
    <!-- Language File table start -->
    <div class="card">
        <div class="card-header">
            <br>
            <br>
            <h5>الهدايا</h5>

            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
                <i class="icofont icofont-refresh"></i>
            </div>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card-block">
            <div class="table-responsive dt-responsive">

                <table id="lang-file" class="table table-striped table-bordered nowrap">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">إســم المرسل</th>
                        <th class="text-center">إســم المرسل إليه</th>
                        <th class="text-center">تاريخ الشراء</th>
                        <th class="text-center">السعر</th>
                        <th class="text-center">الحالة</th>
                        <th class="text-center">العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($gifts as $gift)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $gift->sender->name }}</td>
                            <td class="text-center">{{ $gift->recipient->name }}</td>
                            <td class="text-center">{{ $gift->order->created_at->format('Y-m-d') }}</td>
                            <td class="text-center">{{ $gift->order->total_price }}</td>
                            <td id="gift-status-{{ $gift->id }}" class="text-center">
                                @if ($gift->used == true || $gift->used ==  false && $gift->order->location == null && Carbon\Carbon::now()->greaterThan(Carbon\Carbon::parse($gift->expiry_date)))
                                    <span class="badge badge-danger">انتهى وقت صلاحية الهدية</span>
                                @elseif ($gift->used == true)
                                    <span class="badge badge-success">تم التنفيذ</span>
                                @elseif ($gift->used == false)
                                    <span class="badge badge-warning">قيد التنفيذ</span>
                                @endif
                            </td>

                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="dropdown-button">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <div class="dropdown-content">
                                        <a href="#" id="update-expiry-link" data-gift-id="{{ $gift->id }}">تحديث وقت
                                            الصلاحية</a>
                                        <a href="{{route('admin.gifts.edit',$gift->id)}}">اكمال الطلب</a>
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
    <!-- Language File table end -->

@endsection

@section('script_dashboard')
    <!-- Select 2 js -->
    <script type="text/javascript" src="{{ asset('dashboard/assets/select2/js/select2.full.min.js') }}"></script>
    <!-- Multiselect js -->
    <script type="text/javascript"
            src="{{ asset('dashboard/assets/bootstrap-multiselect/js/bootstrap-multiselect.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/multiselect/js/jquery.multi-select.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/js/jquery.quicksearch.js') }}"></script>

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
    <script src="{{ asset('dashboard/assets/pages/data-table/js/data-table-custom.js') }}"></script>
    <!-- sweet alert js -->
    <script type="text/javascript" src="{{ asset('dashboard/assets/sweetalert/js/sweetalert.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/js/modal.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/js/modalEffects.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/js/classie.js') }}"></script>


    <script>
        $(document).on('click', '#update-expiry-link', function (e) {
            e.preventDefault(); // لمنع إعادة التوجيه الافتراضية

            const giftId = $(this).data('gift-id');

            $.ajax({
                url: '{{ route('admin.gifts.updateExpiry') }}',
                method: 'POST',
                data: {
                    gift_id: giftId,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        swal({
                            title: 'تم بنجاح!',
                            text: 'تم تحديث تاريخ انتهاء الصلاحية لمدة 9 ساعات إضافية.',
                            type: 'success',
                            confirmButtonText: 'حسناً'
                        }, function () {
                            // تحديث العنصر في الصفحة بعد النجاح
                            const statusTd = $('#gift-status-' + giftId); // تأكد من أن لديك معرف فريد للعنصر
                            statusTd.html('<span class="badge badge-warning">قيد التنفيذ</span>'); // تحديث الحالة وفقاً للتحديث
                        });
                    } else {
                        swal({
                            title: 'خطأ!',
                            text: response.message,
                            type: 'error',
                            confirmButtonText: 'حسناً'
                        });
                    }
                },
                error: function (xhr) {
                    swal({
                        title: 'خطأ!',
                        text: 'حدث خطأ أثناء معالجة الطلب.',
                        type: 'error',
                        confirmButtonText: 'حسناً'
                    });
                }
            });
        });
    </script>


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
@endsection
