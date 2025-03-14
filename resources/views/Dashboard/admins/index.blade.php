@extends('Dashboard.layouts.master')
{{--@section('title_dashboard')--}}
{{--@endsection--}}
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
    <style>
        .status-container {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .status-circle {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 8px; /* مسافة بين الدائرة والنص */
            animation: pulse 1.5s infinite;
        }

        .active {
            background-color: #28a745;
        }

        .inactive {
            background-color: #dc3545;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.2);
            }
        }
    </style>
@endsection
@section('title_page')
    الصفحة الإداريين
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
    @php
        use Illuminate\Support\Facades\Gate;
    @endphp

    @if(auth()->guard('admin')->user()->hasPermissionTo('admin.create'))
        <button type="button" class="btn btn-primary waves-effect md-trigger"
                onclick="location.href='{{ route('admin.admins.create') }}'">
            <i class="icofont icofont-architecture-alt"></i> أضافة مستخدم جديد
        </button>
    @endif
    <br>
    <br>
    <!-- Language File table start -->
    <div class="card">
        <div class="card-header">
            <br>
            <br>
            <h5>الإداريين</h5>

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
                        <th class="text-center">إسم المستخدم</th>
                        <th class="text-center">البريد الإلكترونى</th>
                        <th class="text-center">حالة المستخدم</th>
                        <th class="text-center">نوع المستخدم</th>
                        <th class="text-center">العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $user = auth('admin')->user();
                    ?>

                    @foreach($data as $admin)
                        @if ($user->hasRole('Developer') || !$admin->hasRole('Developer'))
                            <tr>
                                <td class="text-center">{{ $admin->name }}</td>
                                <td class="text-center">{{ $admin->email }}</td>
                                @if($admin->status === 'active')
                                    <td class="text-center">
                                        <div class="status-container">
                                            <div class="status-circle active"></div>
                                            &nbsp;&nbsp;مفعل
                                        </div>
                                    </td>
                                @else
                                    <td class="text-center">
                                        <div class="status-container">
                                            <div class="status-circle inactive"></div>
                                            &nbsp;&nbsp;غير مفعل
                                        </div>
                                    </td>
                                @endif
                                <td class="text-center">
                                    @foreach ($admin->getRoleNames() as $role)
                                        <label class="badge badge-primary py-2 px-2">{{ $role }}</label>
                                    @endforeach
                                </td>
                                <td class="text-center">
                                    @if ($user->hasRole('Developer') || !$admin->hasRole('Developer'))
                                        <button
                                            type="button"
                                            class="btn btn-outline-danger alert-confirm m-b-10"
                                            style="cursor: pointer;"
                                            data-route="{{ route('admin.admins.destroy', $admin->id) }}"
                                            data-csrf-token="{{ csrf_token() }}"
                                            data-name="{{ $admin->name }}">
                                            <i class="ion-trash-a"></i>
                                        </button>
                                        &nbsp;&nbsp;&nbsp;
                                        <button
                                            data-modal="edit-modal"
                                            type="button"
                                            class="btn btn-outline-info pointer edit-button"
                                            data-id="{{ $admin->id }}">
                                            <i class="ion-compose"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endif
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
    <script>
        //======================================= START RESPONSIBLE FOR THE DELETION PROCESS AND CONFIRMATION OF DELETION ======================================= \\

        document.querySelectorAll('.alert-confirm').forEach(button => {
            button.onclick = function () {
                const route = this.getAttribute('data-route');
                const csrfToken = this.getAttribute('data-csrf-token');
                const adminName = this.getAttribute('data-name');
                const row = this.closest('tr'); // Get the closest row element

                // Display confirmation dialog
                swal({
                    title: "هل أنت متأكد؟",
                    text: `لن تتمكن من استرجاع المستخدم الذى يحمل اسم:<br><strong style="color: red; font-weight: bold;">"${adminName}"</strong>!`,
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
                                    text: `تم حذف المستخدم الذى يحمل اسم:<br><strong style="color: red; font-weight: bold;">"${adminName}"</strong> بنجاح.`,
                                    type: "success",
                                    html: true // Enable HTML content
                                });
                                row.remove(); // Remove the row from the table
                            } else {
                                if (data.warning) {
                                    swal({
                                        title: "تحذير!",
                                        text: data.message, // رسالة التحذير
                                        type: "warning",
                                        html: true // Enable HTML content
                                    });
                                } else {
                                    swal({
                                        title: "فشل!",
                                        text: `فشل حذف المستخدم الذى يحمل اسم:<br><strong style="color: red; font-weight: bold;">"${adminName}"</strong>.`,
                                        type: "error",
                                        html: true // Enable HTML content
                                    });
                                }

                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            swal({
                                title: "خطأ!",
                                text: `حدث خطأ أثناء حذف المستخدم الذى يحمل اسم:<br><strong style="color: red; font-weight: bold;">"${adminName}"</strong>.`,
                                type: "error",
                                html: true // Enable HTML content
                            });
                        });
                });
            };
        });
        //======================================= END RESPONSIBLE FOR THE DELETION PROCESS AND CONFIRMATION OF DELETION ======================================= \\

    </script>
@endsection
