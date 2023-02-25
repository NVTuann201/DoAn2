<!doctype html>

<!--[if !IE]> -->
<html lang="en" class="not_ie">

<head>

    <title>Hệ thống Quản lý Đa Dạng Sinh Học Hà Nội</title>

    <meta charset="UTF-8">

    <!-- If IE use the latest rendering engine -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Set the page to the width of the device and set the zoom level -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <link rel="stylesheet" href="{{ asset('css/vendor.css') }}">

    <link rel="stylesheet" href="{{asset('css/change.css')}}">

    <link href="{{ asset('css/dist/vue-multiselect.min.css') }}" rel="stylesheet">
    @yield('css')

    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=PT+Serif:400i" rel="stylesheet">
    <link href="https://api.tiles.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.css" rel="stylesheet" />

</head>

<body class="hasTransparentMenu">
    <div class="site-wrapper" id="app">
        <header-component></header-component>
        @yield('content')
        <footer-component></footer-component>
    </div>
</body>

<script src="{{ asset('js/jquery.min.js') }}"></script>

<script src="{{asset('js/app.js')}}"></script>
<script>
    window.gb = window.gb || {};
    window.gb.locale = 'en';

    window.gb.urlPrefix = '';
    window.gb.buildVersion = '1543843732689';
    $(document).ready(function() {
        $(".check-language").each(function(index) {
            var a = $(".check-language")[index];
            if ((a.getAttribute('value') == $('#group_lang').attr('value'))) {
                a.classList.add("active")
            }
        });
        $(".mainMenu__parentItem").click(function() {
            if ($(this).parent().hasClass("mainMenu--childActive")) {
                $(this).parent().removeClass("mainMenu--childActive");
            } else {
                $(".mainMenu--childActive").removeClass("mainMenu--childActive");
                $(this).parent().addClass("mainMenu--childActive");
            }
        });
        $(".translate").click(function() {
            if ($("#languageMenu").hasClass("is-active")) {
                $("#languageMenu").removeClass("is-active");
            } else {
                $("#languageMenu").addClass("is-active");
            }
        });
        $(".active-menu").click(function() {
            if ($(".mainNavigation").hasClass("isActive")) {
                $(".mainNavigation").removeClass("isActive");
            } else {
                $(".mainNavigation").addClass("isActive");
            }
            if ($(".active-menu").hasClass("gb-icon-menu")) {
                $(".active-menu").removeClass("gb-icon-menu");
                $(".active-menu").addClass("gb-icon-close_L");
            } else {
                $(".active-menu").removeClass("gb-icon-close_L");
                $(".active-menu").addClass("gb-icon-menu");
            }
        });
        $(".menubox__close").click(function() {
            if ($("#languageMenu").hasClass("is-active")) {
                $("#languageMenu").removeClass("is-active");
            }
        });
        $(".check-language").click(function() {
            $(".check-language").removeClass("active");
            $(this).addClass("active");
            var ajax = new XMLHttpRequest();
            ajax.open("get", "/setlocale/" + $(this).attr('value') + "");
            ajax.send();
            ajax.addEventListener("load", function(e) {
                if (e.target.status == 200) {
                    location.reload();
                }
            })
        });
    });
</script>

@yield('script')

</html>
