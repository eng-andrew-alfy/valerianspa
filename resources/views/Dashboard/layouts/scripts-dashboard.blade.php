{{--<script>--}}
{{--    // 🚫 Prevent right-click--}}
{{--    document.addEventListener('contextmenu', function (e) {--}}
{{--        e.preventDefault(); // Prevent the context menu from appearing--}}
{{--    });--}}

{{--    // 📋 Prevent copying--}}
{{--    document.addEventListener('copy', function (e) {--}}
{{--        e.preventDefault(); // Prevent copying content--}}
{{--    });--}}

{{--    // ✂️ Prevent pasting--}}
{{--    document.addEventListener('paste', function (e) {--}}
{{--        e.preventDefault(); // Prevent pasting content--}}
{{--    });--}}

{{--    // 🔒 Disable F12 key--}}
{{--    document.onkeydown = function (e) {--}}
{{--        if (e.keyCode === 123) { // F12--}}
{{--            return false; // Prevent F12 key--}}
{{--        }--}}
{{--    };--}}
{{--</script>--}}

<script type="text/javascript" src="{{asset('dashboard/assets/css/jquery/js/jquery.min.js')}}"></script>
<script src="{{asset('dashboard/assets/jquery-ui/js/jquery-ui.min.js')}}"></script>
<script type="text/javascript" src="{{asset('dashboard/assets/popper.js/js/popper.min.js')}}"></script>
<script type="text/javascript" src="{{asset('dashboard/assets/css/bootstrap/js/bootstrap.min.js')}}"></script>


<!-- jquery slimscroll js -->
<script type="text/javascript" src="{{asset('dashboard/assets/jquery-slimscroll/js/jquery.slimscroll.js')}}"></script>
<!-- modernizr js -->
<script type="text/javascript" src="{{asset('dashboard/assets/modernizr/js/modernizr.js')}}"></script>
<script type="text/javascript" src="{{asset('dashboard/assets/modernizr/js/css-scrollbars.js')}}"></script>
<!-- classie js -->
<script type="text/javascript" src="{{asset('dashboard/assets/classie/js/classie.js')}}"></script>

<!-- Syntax highlighter prism js -->
<script type="text/javascript" src="{{asset('dashboard/assets/pages/prism/custom-prism.js')}}"></script>

<!--classic JS-->
<script type="text/javascript" src="{{asset('dashboard/assets/js/classie.js')}}"></script>


<!-- calender js -->
<script type="text/javascript" src="{{asset('dashboard/assets/moment/js/moment.min.js') }}"></script>
<script type="text/javascript"
        src="{{asset('dashboard/assets/fullcalendar/js/fullcalendar.min.js') }}"></script>


<!-- i18next.min.js -->
<script type="text/javascript" src="{{asset('dashboard/assets/i18next/js/i18next.min.js')}}"></script>
<script type="text/javascript"
        src="{{asset('dashboard/assets/i18next-xhr-backend/js/i18nextXHRBackend.min.js')}}"></script>
<script type="text/javascript"
        src="{{asset('dashboard/assets/i18next-browser-languagedetector/js/i18nextBrowserLanguageDetector.min.js')}}"></script>
<script type="text/javascript" src="{{asset('dashboard/assets/jquery-i18next/js/jquery-i18next.min.js')}}"></script>
<script type="text/javascript"
        src="{{asset('dashboard/assets/pages/full-calender/calendar.js') }}"></script>
<!-- j-pro js -->
<script type="text/javascript" src="{{asset('dashboard/assets/pages/j-pro/js/jquery.ui.min.js')}}"></script>
<script type="text/javascript" src="{{asset('dashboard/assets/pages/j-pro/js/jquery.maskedinput.min.js')}}"></script>
<script type="text/javascript" src="{{asset('dashboard/assets/pages/j-pro/js/jquery.j-pro.js')}}"></script>
<!-- Custom js -->
<script type="text/javascript" src="{{asset('dashboard/assets/js/script.js')}}"></script>
<script src="{{asset('dashboard/assets/js/pcoded.min.js')}}"></script>


{{--<script src="{{asset('dashboard/assets/js/demo-12.js')}}"></script>--}}

<script src="{{asset('dashboard/assets/js/menu/menu-rtl.js')}}"></script>
<script type="text/javascript" src="{{asset('dashboard/assets/pages/advance-elements/custom-picker.js')}}"></script>
<script src="{{asset('dashboard/assets/js/jquery.mCustomScrollbar.concat.min.js')}}"></script>
<script src="{{asset('dashboard/assets/js/jquery.mousewheel.min.js')}}"></script>
<script type="text/javascript" src="{{asset('dashboard/assets/pages/advance-elements/select2-custom.js')}}"></script>


<script>
    @if(Session::has('message'))
        toastr.options =
        {
            "closeButton": true,
            "progressBar": true
        }
    toastr.success("{{ session('message') }}");

    @endif

        @if(Session::has('error'))
        toastr.options =
        {
            "closeButton": true,
            "progressBar": true
        }
    toastr.error("{{ session('error') }}");
    @endif

        @if(Session::has('info'))
        toastr.options =
        {
            "closeButton": true,
            "progressBar": true
        }
    toastr.info("{{ session('info') }}");
    @endif

        @if(Session::has('warning'))
        toastr.options =
        {
            "closeButton": true,
            "progressBar": true
        }
    toastr.warning("{{ session('warning') }}");
    @endif
</script>

{!! Toastr::message() !!}
{{--@if(Session::has('message'))--}}
{{--    <script>--}}

{{--                var audio = new Audio('{{ asset('/dashboard/assets/sounds/notification.wav') }}');--}}
{{--                audio.play();--}}

{{--    </script>--}}
{{--@endif--}}


{{--@livewireScripts--}}
<script>
    window.addEventListener('notification-received', event => {
        const audio = new Audio('{{ asset('dashboard/assets/sounds/notification.wav') }}');
        audio.play();
    });
</script>
@yield('script_dashboard')
