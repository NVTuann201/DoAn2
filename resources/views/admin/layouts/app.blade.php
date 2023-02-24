
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <title>Hệ thống Quản lý Đa Dạng Sinh Học Hà Nội Đa Dạng Sinh Học Thành phố Hà Nội</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- bootstrap css -->
    <link href="{{ asset('css/dist/bootstrap.min.css') }}" rel="stylesheet">
    {{--vue-multiselect--}}
    <link href="{{ asset('css/dist/vue-multiselect.min.css') }}" rel="stylesheet">
    <!-- Menu CSS -->
    <link href="{{ asset('css/sidebar-nav.min.css') }}" rel="stylesheet">
    <!-- toast CSS -->
    <link href="{{ asset('css/dist/jquery.toast.min.css') }}" rel="stylesheet">
    <!-- morris CSS -->
    <link href="{{ asset('css/morris.css') }}" rel="stylesheet">
    <!-- chartist CSS -->
    <link href="{{ asset('css/dist/chartist.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/chartist-plugin-tooltip.css') }}" rel="stylesheet">
    <!-- Calendar CSS -->
    <link href="{{ asset('css/dist/fullcalendar.min.css') }}" rel="stylesheet">
    <!-- animation CSS -->
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <!-- color CSS -->
    <link href="{{ asset('css/default.css') }}" id="theme" rel="stylesheet">
    <!-- Custom SCSS -->
    <link href="{{ asset('css/admin-app.css') }}" rel="stylesheet">
    @yield('css')
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body class="fix-header">
    <!-- ============================================================== -->
    <!-- Preloader -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
    </div>
    <!-- ============================================================== -->
    <!-- Wrapper -->
    <!-- ============================================================== -->
    <div class="wrapper" id="app">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        @include('admin.layouts.header')
        <!-- End Top Navigation -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        @include('admin.layouts.sidebar')
        <!-- ============================================================== -->
        <!-- End Left Sidebar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page Content -->
        <!-- ============================================================== -->
        <div id="page-wrapper">
            @yield('content')
            <!-- /.container-fluid -->
            <footer class="footer text-center"> <strong>Copyright &copy; {{date("Y")}} <a href="http://sotnmt.hanoi.gov.vn/" target="_blank">Chi cục Bảo vệ môi trường Hà Nội  (Sở TN&MT Hà Nội)</a>.</strong></footer>
        </div>
        <!-- ============================================================== -->
        <!-- End Page Content -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->

    <script src="{{asset('js/app.js')}}"></script>

    <script src="{{ asset('js/min/jquery.min.js') }}"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="{{ asset('js/min/bootstrap.min.js') }}"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="{{ asset('js/sidebar-nav.min.js') }}"></script>
    <!--slimscroll JavaScript -->
    <script src="{{ asset('js/min/jquery.slimscroll.min.js') }}"></script>
    <!--Wave Effects -->
    <script src="{{ asset('js/waves.js') }}"></script>
    <!-- chartist chart -->
    <script src="{{ asset('js/min/chartist.min.js') }}"></script>
    <script src="{{ asset('js/chartist-plugin-tooltip.js') }}"></script>
    <!-- Sparkline chart JavaScript -->
    <script src="{{ asset('js/min/jquery.sparkline.min.js') }}"></script>
    <!-- Custom Theme JavaScript -->
    <script src="{{ asset('js/custom-admin.js') }}"></script>
    <script src="{{ asset('js/min/jquery.toast.min.js') }}"></script>
    <!--Style Switcher -->
    <script src="{{ asset('js/jQuery.style.switcher.js') }}"></script>
    @yield('script')
</body>

</html>
