@extends('Dashboard.layouts.master')
{{-- @section('title_dashboard') --}}
{{-- @endsection --}}
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


    <style>
        .btn-left {
            margin-bottom: 30px;
        }

        .custom-td {
            position: relative;
            background: var(--employee-color);
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            color: #fff;
            font-weight: bold;
            transition: background 0.3s, transform 0.3s, filter 0.3s;
        }

        .custom-td::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            z-index: 0;
            transition: background 0.3s;
        }

        .custom-td:hover::before {
            background: rgba(0, 0, 0, 0.3);
        }

        .custom-td:hover {
            filter: brightness(90%);
            transform: scale(1.05);
            z-index: 1;
        }
    </style>
@endsection
@section('title_page')
    الموظفين
@endsection

@section('link_name_page_back')
@endsection

@section('title_link_name_page_back')
    الموظفين
@endsection

@section('link_name_page')
@endsection

@section('title_link_name_page')
    قائمة الموظفين
@endsection

@section('page-body')
    @if (auth('admin')->check() && auth('admin')->user()->hasPermissionTo('employee.create'))
        <button class="btn btn-primary  btn-left " onclick="location.href='{{ route('admin.employees.create') }}'"><i
                class="icofont icofont-user-alt-3"></i> أضافة موظف جديد
        </button>
    @endif

    <!-- Language File table start -->
    <div class="card">
        <div class="card-header">
            <br>
            <br>
            <h5>الموظفين</h5>

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
                        <th class="text-center">الأسـم</th>
                        <th class="text-center">الجوال</th>
                        <th class="text-center">لون التقويم</th>
                        <th class="text-center">الخدمات</th>
                        <th class="text-center">البريد الإلكترونى</th>
                        <th class="text-center">رقم الهوية</th>
                        <th class="text-center">العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($employees as $employee)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $employee->getTranslation('name', 'ar') }}</td>
                            <td class="text-center">{{ $employee->phone }}</td>
                            <td class="text-center custom-td"
                                style="--employee-color: {{ $employee->availability->color }};"></td>
                            <td class="text-center">
                                @if ($employee->work_location == 'home')
                                    خدمات منزلية
                                @else
                                    خدمات داخل الفرع
                                @endif
                            </td>
                            <td class="text-center">{{ $employee->email }}</td>
                            <td class="text-center">{{ $employee->identity_card }}</td>
                            <td class="text-center">
                                @if (auth('admin')->check() && auth('admin')->user()->hasPermissionTo('employee.delete'))
                                    <button type="button" class="btn btn-outline-danger alert-confirm m-b-10"
                                            style="cursor: pointer;"
                                            data-route="{{ route('admin.employees.destroy', $employee->id) }}"
                                            data-csrf-token="{{ csrf_token() }}"
                                            data-name="{{ $employee->getTranslation('name', 'ar') }}">
                                        <i class="ion-trash-a"></i>
                                    </button>
                                @endif
                                &nbsp&nbsp&nbsp
                                @if (auth('admin')->check() && auth('admin')->user()->hasPermissionTo('employee.edit'))
                                    <button type="button" class="btn btn-outline-info  m-b-10" style="cursor: pointer;"
                                            onclick="location.href='{{ route('admin.employees.edit', $employee->id) }}'">
                                        <i class="ion-compose"></i>
                                    </button>
                                @endif
                            </td>

                        </tr>
                    @endforeach

                    </tbody>
                    <tfoot>

                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <!-- Language File table end -->
@endsection

@section('script_dashboard')
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
    <!-- sweet alert modal.js intialize js -->
    <!-- modalEffects js nifty modal window effects -->
    <script type="text/javascript" src="{{ asset('dashboard/assets/js/modalEffects.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/js/classie.js') }}"></script>
    <script>
        // Add event listeners to all confirm delete buttons
        document.querySelectorAll('.alert-confirm').forEach(button => {
            button.onclick = function () {
                const route = this.getAttribute('data-route');
                const csrfToken = this.getAttribute('data-csrf-token');
                const employeeName = this.getAttribute('data-name');
                const row = this.closest('tr'); // Get the closest row element

                // Display confirmation dialog
                swal({
                    title: "هل أنت متأكد؟",
                    text: `لن تتمكن من استرجاع الموظف الذى يحمل اسم:<br><strong style="color: red; font-weight: bold;">"${employeeName}"</strong>!`,
                    type: "warning",
                    html: true, // Enable HTML content
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "نعم، احذفه!",
                    cancelButtonText: "إلغاء",
                    closeOnConfirm: false
                }, function () {
                    // Perform the DELETE request
                    fetch(route, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                swal({
                                    title: "تم الحذف!",
                                    text: `تم حذف الموظف الذى يحمل اسم:<br><strong style="color: red; font-weight: bold;">"${employeeName}"</strong> بنجاح.`,
                                    type: "success",
                                    html: true // Enable HTML content
                                });
                                row.remove(); // Remove the row from the table
                            } else {
                                swal({
                                    title: "فشل!",
                                    text: `فشل حذف الموظف الذى يحمل اسم:<br><strong style="color: red; font-weight: bold;">"${employeeName}"</strong>.`,
                                    type: "error",
                                    html: true // Enable HTML content
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            swal({
                                title: "خطأ!",
                                text: `حدث خطأ أثناء حذف الموظف الذى يحمل اسم:<br><strong style="color: red; font-weight: bold;">"${employeeName}"</strong>.`,
                                type: "error",
                                html: true // Enable HTML content
                            });
                        });
                });
            };
        });
    </script>
@endsection
