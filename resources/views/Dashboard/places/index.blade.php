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
    </style>
@endsection

@section('title_page')
    أماكن العمل
@endsection

@section('link_name_page_back')
@endsection

@section('title_link_name_page_back')
    الأماكن
@endsection

@section('link_name_page')
@endsection

@section('title_link_name_page')
    قائمة الأماكن
@endsection

@section('page-body')
    @can(`location.create`)
        <button class="btn btn-primary btn-left" onclick="location.href='{{ route('admin.places.create') }}'">
            <i class="icon-location-pin"></i> أضافة مكان جديد
        </button>
    @endcan
    <!-- Language File table start -->
    <div class="card">
        <div class="card-header">
            <br>
            <br>
            <h5>الأماكن</h5>

            <div class="card-header-right">
                <i class="icofont icofont-rounded-down"></i>
                <i class="icofont icofont-refresh"></i>
            </div>
        </div>

        <div class="card-block">
            <div class="table-responsive dt-responsive">
                <table id="lang-file" class="table table-striped table-bordered nowrap">
                    <thead>
                    <tr>
                        <th class="text-center">الأسـم</th>
                        <th class="text-center">إنشاء بواسطة</th>
                        <th class="text-center">تاريخ الإضافة</th>
                        <th class="text-center">الحالة</th>
                        <th class="text-center">العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($places as $place)
                        <tr>
                            <td class="text-center">{{ $place->name }}</td>
                            <td class="text-center">{{ $place->admin->name }}</td>
                            <td class="text-center">
                                {{ $place->created_at->format('d-m-Y') }}

                            </td>
                            <td class="text-center">
                                <input type="checkbox" class="status-checkbox pointer" data-id="{{ $place->id }}"
                                       data-name="{{ $place->name }}" {{ $place->is_active ? 'checked' : '' }}>
                            </td>
                            <td class="text-center">
                                @can(`location.delete`)
                                    <button type="button" class="btn btn-outline-danger alert-confirm m-b-10"
                                            style="cursor: pointer;"
                                            data-route="{{ route('admin.places.destroy', $place->id) }}"
                                            data-csrf-token="{{ csrf_token() }}" data-name="{{ $place->name }}">
                                        <i class="ion-trash-a"></i>
                                    </button>
                                @endcan
                                &nbsp;&nbsp;&nbsp;
                                @can(`location.edit`)
                                    <button type="button" class="btn btn-outline-info pointer"
                                            onclick="window.location.href='{{ route('admin.places.edit', $place->id) }}'">
                                        <i class="ion-compose"></i>
                                    </button>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th class="text-center">الأسـم</th>
                        <th class="text-center">إنشاء بواسطة</th>
                        <th class="text-center">تاريخ الإضافة</th>
                        <th class="text-center">الحالة</th>

                        <th class="text-center">العمليات</th>
                    </tr>
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
    <script type="text/javascript" src="{{ asset('dashboard/assets/js/modalEffects.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/assets/js/classie.js') }}"></script>

    <script>
        // Add event listeners to all confirm delete buttons
        document.querySelectorAll('.alert-confirm').forEach(button => {
            button.onclick = function () {
                const route = this.getAttribute('data-route');
                const csrfToken = this.getAttribute('data-csrf-token');
                const placeName = this.getAttribute('data-name');
                const row = this.closest('tr'); // Get the closest row element

                // Display confirmation dialog
                swal({
                    title: "هل أنت متأكد؟",
                    text: `لن تتمكن من استرجاع المكان الذى يحمل اسم:<br><strong style="color: red; font-weight: bold;">"${placeName}"</strong>!`,
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
                                    text: `تم حذف المكان الذى يحمل اسم:<br><strong style="color: red; font-weight: bold;">"${placeName}"</strong> بنجاح.`,
                                    type: "success",
                                    html: true // Enable HTML content
                                });
                                row.remove(); // Remove the row from the table
                            } else {
                                swal({
                                    title: "فشل!",
                                    text: `فشل حذف المكان الذى يحمل اسم:<br><strong style="color: red; font-weight: bold;">"${placeName}"</strong>.`,
                                    type: "error",
                                    html: true // Enable HTML content
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            swal({
                                title: "خطأ!",
                                text: `حدث خطأ أثناء حذف المكان الذى يحمل اسم:<br><strong style="color: red; font-weight: bold;">"${placeName}"</strong>.`,
                                type: "error",
                                html: true // Enable HTML content
                            });
                        });
                });
            };
        });

        // Add event listeners to all status checkboxes
        document.querySelectorAll('.status-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const id = this.getAttribute('data-id');
                const placeName = this.getAttribute('data-name');
                const isActive = this.checked;

                // Perform the POST request to update the status
                fetch('{{ route('admin.places.updateStatus', '') }}/' + id, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        is_active: isActive
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            swal({
                                title: "تم التحديث!",
                                text: `تم تحديث حالة المكان الذى يحمل اسم:<br><strong style="color: red; font-weight: bold;">"${placeName}"</strong>.`,
                                type: "success",
                                html: true // Enable HTML content
                            });
                        } else {
                            swal({
                                title: "فشل!",
                                text: `فشل تحديث حالة المكان الذى يحمل اسم:<br><strong style="color: red; font-weight: bold;">"${placeName}"</strong>.`,
                                type: "error",
                                html: true // Enable HTML content
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        swal({
                            title: "خطأ!",
                            text: `حدث خطأ أثناء تحديث حالة المكان الذى يحمل اسم:<br><strong style="color: red; font-weight: bold;">"${placeName}"</strong>.`,
                            type: "error",
                            html: true // Enable HTML content
                        });
                    });
            });
        });
    </script>
@endsection
