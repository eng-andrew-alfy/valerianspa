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
@endsection
@section('title_page')
    صفحة الباقات
@endsection

@section('link_name_page_back')
@endsection

@section('title_link_name_page_back')
    الخدمات
@endsection

@section('link_name_page')
@endsection

@section('title_link_name_page')
    صفحة الخدمات
@endsection

@section('page-body')
    <button type="button" class="btn btn-primary waves-effect md-trigger"
            onclick="location.href='{{ route('admin.categories.create') }}'">
        <i class="icon-location-pin"></i> أضافة خدمة جديد
    </button>
    <br>
    <br>
    <!-- Language File table start -->
    <div class="card">
        <div class="card-header">
            <br>
            <br>
            <h5>الخدمات</h5>

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
                        <th class="text-center"> السعر الفرع</th>
                        <th class="text-center">السعرع المنزلى</th>
                        <th class="text-center">منزلى</th>
                        <th class="text-center">فرع</th>
                        <th class="text-center">الحالة</th>
                        <th class="text-center">العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $category->getTranslation('name', 'ar') }}</td>
                            <td class="text-center">{{ $category->prices->at_spa }}</td>
                            <td class="text-center">{{ $category->prices->at_home }}</td>
                            <td class="text-center">
                                <input type="checkbox" value="منزلى" data-category-id="{{ $category->id }}"
                                       class="update-type-checkbox pointer" {{ $category->availability->in_home ? 'checked' : '' }}>
                            </td>
                            <td class="text-center">
                                <input type="checkbox" value="فرع" data-category-id="{{ $category->id }}"
                                       class="update-type-checkbox pointer" {{ $category->availability->in_spa ? 'checked' : '' }}>
                            </td>
                            <td class="text-center">
                                <input type="checkbox" class="status-checkbox pointer" data-id="{{ $category->id }}"
                                       data-name="{{ $category->getTranslation('name', 'ar') }}" {{ $category->is_active ? 'checked' : '' }}>
                            </td>
                            <td class="text-center">

                                <button
                                    type="button"
                                    class="btn btn-outline-danger alert-confirm m-b-10"
                                    style="cursor: pointer;"
                                    data-route="{{ route('admin.categoryDestroy', $category->id) }}"
                                    data-csrf-token="{{ csrf_token() }}"
                                    data-name="{{ $category->getTranslation('name', 'ar') }}">
                                    <i class="ion-trash-a"></i>
                                </button>
                                &nbsp;&nbsp;&nbsp;
                                <button
                                    type="button"
                                    class="btn btn-outline-info pointer edit-button"
                                    onclick="window.location.href='{{ route('admin.categories.edit',$category->id) }}'">
                                    <i class="ion-compose"></i>
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
        //======================================= START status checkbox IS ACTIVE ON category ======================================= \\
        document.querySelectorAll('.status-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const id = this.getAttribute('data-id');
                const serviceName = this.getAttribute('data-name');
                const isActive = this.checked;

                // Perform the POST request to update the status
                fetch('{{ route('admin.category.updateStatus', '') }}/' + id, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({is_active: isActive})
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            swal({
                                title: "تم التحديث!",
                                text: `تم تحديث حالة القسم الذى يحمل اسم:<br><strong style="color: red; font-weight: bold;">"${serviceName}"</strong>.`,
                                type: "success",
                                html: true // Enable HTML content
                            });
                        } else {
                            swal({
                                title: "فشل!",
                                text: `فشل تحديث حالة القسم الذى يحمل اسم:<br><strong style="color: red; font-weight: bold;">"${serviceName}"</strong>.`,
                                type: "error",
                                html: true // Enable HTML content
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        swal({
                            title: "خطأ!",
                            text: `حدث خطأ أثناء تحديث حالة القسم الذى يحمل اسم:<br><strong style="color: red; font-weight: bold;">"${serviceName}"</strong>.`,
                            type: "error",
                            html: true // Enable HTML content
                        });
                    });
            });
        });
        //======================================= END status checkbox IS ACTIVE ON category ======================================= \\

        //======================================= START RESPONSIBLE FOR THE DELETION PROCESS AND CONFIRMATION OF DELETION ======================================= \\

        document.querySelectorAll('.alert-confirm').forEach(button => {
            button.onclick = function () {
                const route = this.getAttribute('data-route');
                const csrfToken = this.getAttribute('data-csrf-token');
                const categoryName = this.getAttribute('data-name');
                const row = this.closest('tr'); // Get the closest row element

                // Display confirmation dialog
                swal({
                    title: "هل أنت متأكد؟",
                    text: `لن تتمكن من استرجاع الخدمة الذى يحمل اسم:<br><strong style="color: red; font-weight: bold;">"${categoryName}"</strong>!`,
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
                                    text: `تم حذف الخدمة الذى يحمل اسم:<br><strong style="color: red; font-weight: bold;">"${categoryName}"</strong> بنجاح.`,
                                    type: "success",
                                    html: true // Enable HTML content
                                });
                                row.remove(); // Remove the row from the table
                            } else {
                                swal({
                                    title: "فشل!",
                                    text: `فشل حذف الخدمة الذى يحمل اسم:<br><strong style="color: red; font-weight: bold;">"${categoryName}"</strong>.`,
                                    type: "error",
                                    html: true // Enable HTML content
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            swal({
                                title: "خطأ!",
                                text: `حدث خطأ أثناء حذف الخدمة الذى يحمل اسم:<br><strong style="color: red; font-weight: bold;">"${categoryName}"</strong>.`,
                                type: "error",
                                html: true // Enable HTML content
                            });
                        });
                });
            };
        });
        //======================================= END RESPONSIBLE FOR THE DELETION PROCESS AND CONFIRMATION OF DELETION ======================================= \\
        //======================================= START Update the availability of the category ======================================= \\
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.update-type-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    const categoryId = this.getAttribute('data-category-id');
                    const isHome = document.querySelector(`input[data-category-id="${categoryId}"][value="منزلى"]`).checked;
                    const isSpa = document.querySelector(`input[data-category-id="${categoryId}"][value="فرع"]`).checked;

                    // Perform the POST request to update the availability
                    fetch('{{ route('admin.updateCategoryAvailability') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            category_id: categoryId,
                            in_home: isHome,
                            in_spa: isSpa
                        })
                    })
                        .then(response => response.text()) // قراءة الاستجابة كنص
                        .then(text => {
                            console.log('Response Text:', text); // عرض الاستجابة في وحدة التحكم
                            try {
                                const data = JSON.parse(text); // محاولة تحويل النص إلى JSON
                                if (data.status === 'success') {
                                    swal({
                                        title: "تم التحديث!",
                                        text: "تم تحديث حالة الخدمة بنجاح.",
                                        type: "success",
                                        html: true
                                    });
                                } else {
                                    swal({
                                        title: "فشل!",
                                        text: "فشل تحديث حالة الخدمة.",
                                        type: "error",
                                        html: true
                                    });
                                }
                            } catch (e) {
                                console.error('JSON Parsing Error:', e);
                                swal({
                                    title: "خطأ!",
                                    text: "حدث خطأ أثناء تحديث حالة الخدمة.",
                                    type: "error",
                                    html: true
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            swal({
                                title: "خطأ!",
                                text: "حدث خطأ أثناء تحديث حالة الخدمة.",
                                type: "error",
                                html: true
                            });
                        });

                });
            });
        });
        //======================================= END Update the availability of the category ======================================= \\

    </script>

@endsection
