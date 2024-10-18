@extends('Dashboard.layouts.master')
{{--@section('title_dashboard')--}}
{{--@endsection--}}
@section('css_dashboard')
    <!-- sweet alert framework -->
    <link rel="stylesheet" type="text/css" href="{{asset('dashboard/assets/sweetalert/css/sweetalert.css')}}">
    <!-- Data Table Css -->
    <link rel="stylesheet" type="text/css"
          href="{{asset('dashboard/assets/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css"
          href="{{asset('dashboard/assets/pages/data-table/css/buttons.dataTables.min.css')}}">
    <link rel="stylesheet" type="text/css"
          href="{{asset('dashboard/assets/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}">

@endsection
@section('title_page')
    الصفحة الإخطاء
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

    <!-- Language File table start -->
    <div class="card">
        <div class="card-header">
            <br>
            <br>
            <h5>الإخطاء</h5>

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
                        <th class="text-center">User Name</th>
                        <th class="text-center">URL</th>
                        <th class="text-center">IP Address</th>
                        <th class="text-center">Device</th>
                        <th class="text-center">Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($logs  as $log)

                        <tr>
                            <td class="text-center">{{$loop->iteration }}</td>
                            <td class="text-center">{{ $log->admin_id  }}</td>
                            <td class="text-center">{{ \Illuminate\Support\Str::limit($log->url, 50, '...') }}</td>
                            <td class="text-center">{{ $log->ip }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.error_logs.show', $log->id) }}"
                                   class="btn btn-primary">{{ $log->device }}</a>

                            </td>
                            <td class="text-center">{{ $log->created_at }}</td>

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
    <script src="{{asset('dashboard/assets/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('dashboard/assets/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('dashboard/assets/pages/data-table/js/jszip.min.js')}}"></script>
    <script src="{{asset('dashboard/assets/pages/data-table/js/pdfmake.min.js')}}"></script>
    <script src="{{asset('dashboard/assets/pages/data-table/js/vfs_fonts.js')}}"></script>
    <script src="{{asset('dashboard/assets/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{asset('dashboard/assets/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('dashboard/assets/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('dashboard/assets/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('dashboard/assets/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('dashboard/assets/pages/data-table/js/data-table-custom.js')}}"></script>

@endsection
