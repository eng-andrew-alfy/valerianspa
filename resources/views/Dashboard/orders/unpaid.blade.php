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
            margin-bottom: 30px; /* Adding space below the button */
        }

        .pointer {
            cursor: pointer;
        }

        .new-addition {
            background-color: #d4edda; /* Light green background for new items */
            color: #155724; /* Dark green text color */
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
@endsection

@section('title_page')
    الطلبات
@endsection

@section('link_name_page_back')
@endsection

@section('title_link_name_page_back')
    الطلبات الغبر مدفوعة
@endsection

@section('link_name_page')
@endsection

@section('title_link_name_page')
    قائمة الطلبات الغبر مدفوعة
@endsection

@section('page-body')
    <!-- Language File table start -->
    <div class="card">
        <div class="card-header">
            <br>
            <br>
            <h5>الطلبات الغبر مدفوعة</h5>

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
                        <th class="text-center">إســم العميل</th>
                        <th class="text-center">تاريخ الشراء</th>
                        <th class="text-center">السعر</th>

                        <th class="text-center">العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $order->user->name }}</td>
                            <td class="text-center">{{ $order->created_at->format('Y-m-d') }}</td>
                            <td class="text-center">{{ $order->total_price }}</td>

                            <td class="text-center">
                                <button class="btn btn-primary"
                                        onclick="selectPaymentType({{ $order->id }})">حدد
                                    نوع الدفع
                                </button>

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function selectPaymentType(orderId) {
            Swal.fire({
                title: 'اختر نوع الدفع',
                text: 'برجاء تحديد نوع الدفع لإتمام العملية',
                icon: 'info',
                input: 'select',
                inputOptions: {
                    'Tamara': 'Tamara',
                    'MyFatoorah': 'MyFatoorah',
                    'Tabby': 'Tabby',
                    'Cash': 'Cash',
                    'Bank Transfer': 'Bank Transfer'
                },
                inputPlaceholder: 'اختر نوع الدفع',
                showCancelButton: true,
                confirmButtonText: 'تأكيد الدفع',
                cancelButtonText: 'إلغاء',
                customClass: {
                    popup: 'shadow-lg border border-primary rounded-lg',
                    title: 'text-primary',
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                inputValidator: (value) => {
                    if (!value) {
                        return 'يجب اختيار نوع الدفع!';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    if (result.value === 'Tabby') {
                        Swal.fire({
                            title: 'نعتذر',
                            text: 'لم يتم إضافة بوابة الدفع Tabby في الوقت الحالي.',
                            icon: 'error',
                            customClass: {
                                popup: 'shadow-lg border border-danger rounded-lg',
                            }
                        });
                    } else {
                        $.ajax({
                            url: '{{ route("admin.UnpaidPaymentUpdate") }}',
                            type: 'POST',
                            data: {
                                order_id: orderId,
                                payment_type: result.value,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                Swal.fire({
                                    title: 'تمت العملية بنجاح',
                                    text: 'تم تحديث نوع الدفع إلى: ' + result.value,
                                    icon: 'success',
                                    customClass: {
                                        popup: 'shadow-lg border border-success rounded-lg',
                                    }
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function (xhr, status, error) {
                                Swal.fire({
                                    title: 'حدث خطأ',
                                    text: 'لم يتم تحديث نوع الدفع، يرجى المحاولة مرة أخرى.',
                                    icon: 'error',
                                    customClass: {
                                        popup: 'shadow-lg border border-danger rounded-lg',
                                    }
                                });
                            }
                        });
                    }
                }
            });
        }
    </script>


    <!-- Select 2 js -->
    <script type="text/javascript" src="{{asset('dashboard/assets/select2/js/select2.full.min.js')}}"></script>
    <!-- Multiselect js -->
    <script type="text/javascript"
            src="{{asset('dashboard/assets/bootstrap-multiselect/js/bootstrap-multiselect.js')}}">
    </script>
    <script type="text/javascript" src="{{asset('dashboard/assets/multiselect/js/jquery.multi-select.js')}}"></script>
    <script type="text/javascript" src="{{asset('dashboard/assets/js/jquery.quicksearch.js')}}"></script>

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

@endsection
