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
    الأقسام
@endsection

@section('link_name_page_back')
@endsection

@section('title_link_name_page_back')
    الأقسام
@endsection

@section('link_name_page')
@endsection

@section('title_link_name_page')
    قائمة الأقسام
@endsection

@section('page-body')
    @can(`service.create`)
        <button type="button" class="btn btn-primary waves-effect md-trigger" data-modal="modal-3">
            <i class="icon-location-pin"></i> أضافة قسم جديد
        </button>
    @endcan
    <br>
    <br>
    <!-- Language File table start -->
    <div class="card">
        <div class="card-header">
            <br>
            <br>
            <h5>الأقسام</h5>

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
                        <th class="text-center">الأسـم</th>
                        <th class="text-center">إنشاء بواسطة</th>
                        <th class="text-center">تاريخ الإضافة</th>
                        <th class="text-center">منزلى</th>
                        <th class="text-center">فرع</th>
                        <th class="text-center">الحالة</th>
                        <th class="text-center">العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($services as $service)
                        <tr>
                            <td class="text-center">{{ $service->getTranslation('name', 'ar') }}</td>
                            <td class="text-center">{{ $service->admin->name }}</td>
                            <td class="text-center">{{ $service->created_at->format('d-m-Y') }}</td>
                            <td class="text-center">
                                <input type="checkbox" value="منزلى" data-service-id="{{ $service->id }}"
                                       class="update-type-checkbox pointer"
                                    {{ $service->serviceAvailability->in_home ? 'checked' : '' }}>
                            </td>
                            <td class="text-center">
                                <input type="checkbox" value="فرع" data-service-id="{{ $service->id }}"
                                       class="update-type-checkbox pointer"
                                    {{ $service->serviceAvailability->in_spa ? 'checked' : '' }}>
                            </td>
                            <td class="text-center">
                                <input type="checkbox" class="status-checkbox pointer" data-id="{{ $service->id }}"
                                       data-name="{{ $service->getTranslation('name', 'ar') }}"
                                    {{ $service->is_active ? 'checked' : '' }}>
                            </td>
                            <td class="text-center">
                                @can(`service.delete`)
                                    <button type="button" class="btn btn-outline-danger alert-confirm m-b-10 pointer"
                                            style="cursor: pointer;"
                                            data-route="{{ route('admin.services.destroy', $service->id) }}"
                                            data-csrf-token="{{ csrf_token() }}"
                                            data-name="{{ $service->getTranslation('name', 'ar') }}">
                                        <i class="ion-trash-a"></i>
                                    </button>
                                @endcan
                                &nbsp;&nbsp;&nbsp;
                                @can(`service.edit`)
                                    <button data-modal="edit-modal" type="button"
                                            class="btn btn-outline-info pointer edit-button"
                                            data-id="{{ $service->id }}">
                                        <i class="ion-compose"></i>
                                    </button>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <!-- Language File table end -->

    <!-- Add modal start -->
    <div class="md-modal md-effect-3" id="modal-3">
        <div class="md-content container">
            <span class="pcoded-mtext"
                  style="background-color: #0a6aa1; text-align: center; color: white; width: 100%; display: block; padding: 10px 0; font-size: 18px;">
                إضافة قسم جديد
            </span>
            <div class="card-block">
                <div class="j-wrapper j-wrapper-640">
                    <!-- Adjust the form action to the desired route -->
                    <form action="{{ route('admin.services.store') }}" method="POST" class="j-pro" id="j-pro"
                          enctype="multipart/form-data" novalidate>
                        @csrf <!-- Include CSRF token for security -->

                        <div class="j-content">
                            <!-- start name -->
                            <div class="j-row">
                                <div class="j-input">
                                    <label class="form-control-label"><code>*</code>الأسم باللغة العربية</label>
                                    <br>
                                    <input type="text" class="form-control" placeholder="المساج" name="arabic_name"
                                           required>
                                </div>
                            </div>
                            <br><br><br>
                            <div class="j-row">
                                <div class="j-input">
                                    <label class="form-control-label"><code>*</code>الأسم باللغة الانجليزية</label>
                                    <br>
                                    <input type="text" class="form-control" placeholder="Massage" name="english_name"
                                           required>
                                </div>
                            </div>
                            <!-- end name -->

                            <!-- start select -->
                            <br><br><br>
                            <div class="j-unit" id="services">
                                <div class="j-input" id="services">
                                    <label class="form-control-label"><code>*</code>اختر الخدمة</label>
                                    <br>
                                    <select class="js-example-rtl-model col-sm-12" id="services" multiple="multiple"
                                            name="services[]" required>
                                        <optgroup label="خدمات" id="services">
                                            <option value="home_services">خدمات منزلية</option>
                                            <option value="branch_services">خدمات داخل الفرع</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <!-- end select -->

                            <div class="j-divider j-gap-bottom-25"></div>

                            <!-- start file -->
                            <div class="j-unit">
                                <div class="j-input j-append-big-btn">
                                    <label class="j-icon-left" for="file_input">
                                        <i class="icofont icofont-download"></i>
                                    </label>
                                    <div class="j-file-button">
                                        Browse
                                        <input type="file" name="file_name"
                                               onchange="document.getElementById('file_input').value = this.value;"/>
                                    </div>
                                    <input type="text" id="file_input" readonly=""
                                           placeholder="no file selected"/>
                                    <span class="j-hint">Only: jpg / png / doc, less 1Mb</span>
                                </div>
                            </div>
                            <!-- end file -->

                        </div>
                        <!-- end /.content -->
                        <!-- Add Save and Close buttons -->
                        <div class="modal-footer j-footer">
                            <button type="submit" class="btn btn-success">حفظ</button>
                            <button type="button" class="btn btn-default waves-effect md-close">إلغاء</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Add modal end -->
    {{--    <!-- Edit modal start --> --}}
    {{--    <div class="md-modal md-effect-3" id="edit-modal"> --}}
    {{--        <div class="md-content container"> --}}
    {{--        <span class="pcoded-mtext" --}}
    {{--              style="background-color: #0a6aa1; text-align: center; color: white; width: 100%; display: block; padding: 10px 0; font-size: 18px;"> --}}
    {{--            تعديل القسم --}}
    {{--        </span> --}}
    {{--            <div class="card-block"> --}}
    {{--                <div class="j-wrapper j-wrapper-640"> --}}
    {{--                    <form id="edit-form" method="POST" class="j-pro" enctype="multipart/form-data"> --}}
    {{--                        @csrf --}}
    {{--                        @method('PUT') --}}
    {{--                        <div class="j-content"> --}}
    {{--                            <!-- Start name --> --}}
    {{--                            <div class="j-row"> --}}
    {{--                                <div class="j-input"> --}}
    {{--                                    <label class="form-control-label"><code>*</code>الأسم باللغة العربية</label> --}}
    {{--                                    <br> --}}
    {{--                                    <input type="text" id="edit-arabic-name" class="form-control" name="arabic_name" --}}
    {{--                                           required> --}}
    {{--                                </div> --}}
    {{--                            </div> --}}
    {{--                            <br><br><br> --}}
    {{--                            <div class="j-row"> --}}
    {{--                                <div class="j-input"> --}}
    {{--                                    <label class="form-control-label"><code>*</code>الأسم باللغة الانجليزية</label> --}}
    {{--                                    <br> --}}
    {{--                                    <input type="text" id="edit-english-name" class="form-control" name="english_name" --}}
    {{--                                           required> --}}
    {{--                                </div> --}}
    {{--                            </div> --}}
    {{--                            <!-- End name --> --}}
    {{--                            <!-- Start file --> --}}
    {{--                            <div class="j-unit"> --}}
    {{--                                <div class="j-input j-append-big-btn"> --}}
    {{--                                    <label class="j-icon-left" for="file_input"> --}}
    {{--                                        <i class="icofont icofont-download"></i> --}}
    {{--                                    </label> --}}
    {{--                                    <div class="j-file-button"> --}}
    {{--                                        Browse --}}
    {{--                                        <input type="file" name="file_name" id="edit-image" --}}
    {{--                                               onchange="document.getElementById('file_input').value = this.value;"/> --}}
    {{--                                    </div> --}}
    {{--                                    <input type="text" id="file_input" readonly="" placeholder="no file selected"/> --}}
    {{--                                    <span class="j-hint">Only: jpg / png / doc, less 1Mb</span> --}}
    {{--                                </div> --}}
    {{--                                <!-- Thumbnail display --> --}}
    {{--                                <div class="j-input"> --}}
    {{--                                    <label class="form-control-label">Current Image</label> --}}
    {{--                                    <br> --}}
    {{--                                    <img id="edit-image-preview" src="" alt="Current Image" --}}
    {{--                                         style="max-width: 15%; height: auto;"> --}}
    {{--                                </div> --}}
    {{--                            </div> --}}
    {{--                            <!-- End file --> --}}
    {{--                        </div> --}}
    {{--                        <div class="modal-footer j-footer"> --}}
    {{--                            <button type="submit" class="btn btn-success">حفظ</button> --}}
    {{--                            <button type="button" class="btn btn-default waves-effect md-close">إلغاء</button> --}}
    {{--                        </div> --}}
    {{--                    </form> --}}
    {{--                </div> --}}
    {{--            </div> --}}
    {{--        </div> --}}
    {{--    </div> --}}
    {{--    <!-- Edit modal end --> --}}

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
        //======================================= START RESPONSIBLE FOR THE DELETION PROCESS AND CONFIRMATION OF DELETION ======================================= \\

        document.querySelectorAll('.alert-confirm').forEach(button => {
            button.onclick = function () {
                const route = this.getAttribute('data-route');
                const csrfToken = this.getAttribute('data-csrf-token');
                const serviceName = this.getAttribute('data-name');
                const row = this.closest('tr'); // Get the closest row element

                // Display confirmation dialog
                swal({
                    title: "هل أنت متأكد؟",
                    text: `لن تتمكن من استرجاع القسم الذى يحمل اسم:<br><strong style="color: red; font-weight: bold;">"${serviceName}"</strong>!`,
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
                                    text: `تم حذف القسم الذى يحمل اسم:<br><strong style="color: red; font-weight: bold;">"${serviceName}"</strong> بنجاح.`,
                                    type: "success",
                                    html: true // Enable HTML content
                                });
                                row.remove(); // Remove the row from the table
                            } else {
                                swal({
                                    title: "فشل!",
                                    text: `فشل حذف القسم الذى يحمل اسم:<br><strong style="color: red; font-weight: bold;">"${serviceName}"</strong>.`,
                                    type: "error",
                                    html: true // Enable HTML content
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            swal({
                                title: "خطأ!",
                                text: `حدث خطأ أثناء حذف القسم الذى يحمل اسم:<br><strong style="color: red; font-weight: bold;">"${serviceName}"</strong>.`,
                                type: "error",
                                html: true // Enable HTML content
                            });
                        });
                });
            };
        });
        //======================================= END RESPONSIBLE FOR THE DELETION PROCESS AND CONFIRMATION OF DELETION ======================================= \\

        //======================================= START status checkbox IS ACTIVE ON service ======================================= \\
        document.querySelectorAll('.status-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const id = this.getAttribute('data-id');
                const serviceName = this.getAttribute('data-name');
                const isActive = this.checked;

                // Perform the POST request to update the status
                fetch('{{ route('admin.services.updateStatus', '') }}/' + id, {
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
        //======================================= END status checkbox IS ACTIVE ON service ======================================= \\

        //======================================= START EDIT the MODEL of the service ======================================= \\
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.edit-button').forEach(button => {
                button.addEventListener('click', function () {
                    const serviceId = this.getAttribute('data-id');
                    // AJAX request to get the service data
                    fetch(`{{ route('admin.services.edit', ':id') }}`.replace(':id', serviceId), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            console.log(data); // Check the data structure in the console
                            if (data.success) {
                                // Fill the modal with data
                                document.getElementById('edit-arabic-name').value = data
                                    .services.arabic_name;
                                document.getElementById('edit-english-name').value = data
                                    .services.english_name;
                                // Update the image preview
                                const imagePreview = document.getElementById(
                                    'edit-image-preview');
                                if (data.services.image) {
                                    imagePreview.src = data.services.image;
                                    imagePreview.style.display = 'block'; // Show the image
                                } else {
                                    imagePreview.src = ''; // Or set a placeholder image
                                    imagePreview.style.display = 'none'; // Hide the image
                                }
                                // Set form action URL
                                document.getElementById('edit-form').setAttribute('action',
                                    `{{ route('admin.services.update', ':id') }}`.replace(
                                        ':id', serviceId));

                                // Show the modal
                                const modal = document.querySelector('#edit-modal');
                                if (modal) {
                                    modal.classList.add('md-show');
                                } else {
                                    console.error('Modal not found');
                                }
                            } else {
                                console.error('Service not found.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });
            });
        });
        // Close modal functionality
        document.querySelectorAll('.md-close').forEach(button => {
            button.addEventListener('click', function () {
                document.getElementById('edit-modal').classList.remove('md-show');
            });
        });
        //======================================= END EDIT the MODEL of the service ======================================= \\

        //======================================= START Update the availability of the service ======================================= \\
        document.querySelectorAll('.update-type-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const serviceId = this.getAttribute('data-service-id');
                const isHome = document.querySelector(
                    `input[data-service-id="${serviceId}"][value="منزلى"]`).checked;
                const isBranch = document.querySelector(
                    `input[data-service-id="${serviceId}"][value="فرع"]`).checked;

                // Perform the POST request to update the availability
                fetch('{{ route('admin.services.updateAvailability') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        service_id: serviceId,
                        home_services: isHome,
                        branch_services: isBranch
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            swal({
                                title: "تم التحديث!",
                                text: `تم تحديث حالة الخدمة بنجاح.`,
                                type: "success",
                                html: true // Enable HTML content
                            });
                        } else {
                            swal({
                                title: "فشل!",
                                text: `فشل تحديث حالة الخدمة.`,
                                type: "error",
                                html: true // Enable HTML content
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        swal({
                            title: "خطأ!",
                            text: `حدث خطأ أثناء تحديث حالة الخدمة.`,
                            type: "error",
                            html: true // Enable HTML content
                        });
                    });
            });
        });
        //======================================= END Update the availability of the service ======================================= \\
    </script>
@endsection
