<title>@yield('title_dashboard',"لوحة تحكم - valerianspa")</title>
<!-- HTML5 Shim and Respond.js IE9 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<!-- Meta -->
<meta charset="utf-8">
{{--<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">--}}
{{--<meta http-equiv="X-UA-Compatible" content="IE=edge" />--}}
{{--<meta name="description" content="#">--}}
{{--<meta name="keywords" content="Flat ui, Admin , Responsive, Landing, Bootstrap, App, Template, Mobile, iOS, Android, apple, creative app">--}}
{{--<meta name="author" content="#">--}}

<!-- Favicon icon -->
<link rel="icon" href="{{asset('dashboard/assets/images/favicon.ico')}}" type="image/x-icon">
<!-- Google font-->
{{--    <link href="../../../fonts.googleapis.com/cssebfc.css?family=Mada:300,400,500,600,700" rel="stylesheet">--}}
<!-- Required Fremwork -->
<link rel="stylesheet" type="text/css" href="{{asset('dashboard/assets/css/bootstrap/css/bootstrap.min.css')}}">
<!-- themify icon -->
<link rel="stylesheet" type="text/css" href="{{asset('dashboard/assets/icon/themify-icons/themify-icons.css')}}">
<!-- typicon icon -->
<link rel="stylesheet" type="text/css" href="{{asset('dashboard/assets/icon/typicons-icons/css/typicons.min.css')}}">
<!-- ico font -->
<link rel="stylesheet" type="text/css" href="{{asset('dashboard/assets/icon/icofont/css/icofont.css')}}">
<!-- flag icon framework css -->
<link rel="stylesheet" type="text/css" href="{{asset('dashboard/assets/pages/flag-icon/flag-icon.min.css')}}">
<!-- Menu-Search css -->
<link rel="stylesheet" type="text/css" href="{{asset('dashboard/assets/pages/menu-search/css/component.css')}}">

<!-- jpro forms css -->
<link rel="stylesheet" type="text/css" href="{{asset('dashboard/assets/pages/j-pro/css/demo.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('dashboard/assets/pages/j-pro/css/font-awesome.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('dashboard/assets/pages/j-pro/css/j-pro-modern.css')}}">
<!-- Syntax highlighter Prism css -->
<link rel="stylesheet" type="text/css" href="{{asset('dashboard/assets/pages/prism/prism.css')}}">
<!-- Select 2 css -->
<link rel="stylesheet" href="{{asset('dashboard/assets/select2/css/select2.min.css')}}"/>
<!-- Multi Select css -->
<link rel="stylesheet" type="text/css"
      href="{{asset('dashboard/assets/bootstrap-multiselect/css/bootstrap-multiselect.css')}}"/>
<link rel="stylesheet" type="text/css" href="{{asset('dashboard/assets/multiselect/css/multi-select.css')}}"/>

<!-- Switch component css -->
<link rel="stylesheet" type="text/css" href="{{asset('dashboard/assets/switchery/css/switchery.min.css')}}">
<!-- Tags css -->
<link rel="stylesheet" type="text/css"
      href="{{asset('dashboard/assets/bootstrap-tagsinput/css/bootstrap-tagsinput.css')}}"/>


<!-- Calender css -->
<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/fullcalendar/css/fullcalendar.css') }}">
<link rel="stylesheet" type="text/css"
      href="{{ asset('dashboard/assets/fullcalendar/css/fullcalendar.print.css') }}" media='print'>

<!-- Style.css -->
<link rel="stylesheet" type="text/css" href="{{asset('dashboard/assets/css/style.css')}}">
<!--color css-->
{{--<link href="https://fonts.googleapis.com/css2?family=Lalezar&family=Mada&display=swap" rel="stylesheet">--}}


<link rel="stylesheet" type="text/css" href="{{asset('dashboard/assets/css/linearicons.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('dashboard/assets/css/simple-line-icons.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('dashboard/assets/css/ionicons.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('dashboard/assets/css/jquery.mCustomScrollbar.css')}}">

{{--<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/css/bootstrap.css" rel="stylesheet">--}}

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
@livewireStyles
@yield('css_dashboard')
