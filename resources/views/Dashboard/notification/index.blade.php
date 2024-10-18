@extends('Dashboard.layouts.master')
{{--@section('title_dashboard')--}}
{{--@endsection--}}
@section('css_dashboard')
@endsection
@section('title_page')
    صفحة الإشعارات
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
    <div>
        <h1>{{ $notification->message }}</h1>
    </div>
@endsection

@section('script_dashboard')
@endsection
