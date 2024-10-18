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
@endsection
@section('title_page')
    صفحة خصومات الخدمات
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
    <br>
    <br>
    <!-- Language File table start -->
    <div class="card">
        <div class="card-header">
            <br>
            <br>
            <h5>خصومات الخدمات</h5>

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
                        <th class="text-center">الخدمة</th>
                        <th class="text-center">السعر الاساسى المنزلى</th>
                        <th class="text-center">السعر الاساسى بالفرع</th>
                        <th class="text-center">سعر الخصم المنزلى</th>
                        <th class="text-center">سعر الخصم بالفرع</th>
                        <th class="text-center">تاريخ الانشاء</th>
                        <th class="text-center">إنشاء بواسطة</th>

                        <th class="text-center">العمليات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($discounts as $discount)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $discount->category->getTranslation('name', 'ar') }}</td>
                            <td class="text-center">{{ $discount->category->prices->at_home }}</td>
                            <td class="text-center">{{ $discount->category->prices->at_spa }}</td>
                            <td class="text-center">{{ $discount->at_home }}</td>
                            <td class="text-center">{{ $discount->at_spa }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($discount->created_at)->format('Y-m-d') }}</td>
                            <td class="text-center">{{ $discount->admin->name }}</td>

                            <td class="text-center">
                                {{--                                <a href="{{ route('admin.orders.show', $user->id) }}" class="btn btn-info">عرض--}}
                                {{--                                    التفاصيل</a>--}}
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
@endsection
