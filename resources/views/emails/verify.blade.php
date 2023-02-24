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
  <!-- bootstrap css -->
  <link href="{{ asset('css/dist/bootstrap.min.css') }}" rel="stylesheet">
  <!-- animation CSS -->
  <link href="{{ asset('css/theme.css') }}" rel="stylesheet">
  <!-- Custom CSS -->
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">
  <!-- color CSS -->
  <link href="css/default_admin.css" id="theme" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

  <link rel="stylesheet" href="{{ asset('css/vendor.css') }}">

  <link href="{{ asset('css/admin-app.css') }}" rel="stylesheet">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
    <style>
      .stickyNav__actions__action.gb-icon-translate.translate {
        display: none;
        height: 55px;
      }
      .stickyNav__actions{
        top: 5px;
      }
    </style>
</head>

<body>
  <section  class="new-login-register hasTransparentMenu" style="position: relative;">
    <div class="wrapper" id="app">
    <header-component></header-component>
    <div class="new-login-box" style="margin-left: calc(50% - 200px);">
      <div class="white-box">
          <div>Xác nhận thành công</div>
          <a href="/">Quay lại trang chủ </a>
      </div>
    </div>
    <footer-component></footer-component>
    </div>
  </section>

  <script src="{{ asset('js/min/jquery.min.js') }}"></script>
  <!-- Bootstrap Core JavaScript -->
  <script src="{{ asset('js/min/bootstrap.min.js') }}"></script>
  <!-- Menu Plugin JavaScript -->
  <script src="{{ asset('js/sidebar-nav.min.js') }}"></script>
  <!--slimscroll JavaScript -->
  <script src="{{ asset('js/min/jquery.slimscroll.min.js') }}"></script>
  <!--Wave Effects -->
  <script src="{{ asset('js/waves.js') }}"></script>
  <!-- Custom Theme JavaScript -->
  {{-- <script src="{{ asset('js/custom.js') }}"></script> --}}
  <!--Style Switcher -->
  <script src="{{ asset('js/jQuery.style.switcher.js') }}"></script>

  <script src="{{ asset('js/jquery.min.js') }}"></script>

  <script src="{{asset('js/app.js')}}"></script>
  {{-- <script>
    $(document).ready(function () {
      $(".stickyNav").addClass("hasOffset");
    });
    window.gb = window.gb || {};
    window.gb.locale = 'en';

    window.gb.urlPrefix = '';
    window.gb.buildVersion = '1543843732689';
    $(document).ready(function () {
      $(".check-language").each(function (index) {
        var a = $(".check-language")[index];
        if ((a.getAttribute('value') == $('#group_lang').attr('value'))) {
          a.classList.add("active")
        }
      });
      $(".mainMenu__parentItem").click(function () {
        if ($(this).parent().hasClass("mainMenu--childActive")) {
          $(this).parent().removeClass("mainMenu--childActive");
        } else {
          $(".mainMenu--childActive").removeClass("mainMenu--childActive");
          $(this).parent().addClass("mainMenu--childActive");
        }
      });
      $(".translate").click(function () {
        if ($("#languageMenu").hasClass("is-active")) {
          $("#languageMenu").removeClass("is-active");
        } else {
          $("#languageMenu").addClass("is-active");
        }
      });
      $(".active-menu").click(function () {
        if ($(".mainNavigation").hasClass("isActive")) {
          $(".mainNavigation").removeClass("isActive");
        } else {
          $(".mainNavigation").addClass("isActive");
        }
      });
      $(".menubox__close").click(function () {
        if ($("#languageMenu").hasClass("is-active")) {
          $("#languageMenu").removeClass("is-active");
        }
      });
      $(".check-language").click(function () {
        $(".check-language").removeClass("active");
        $(this).addClass("active");
        var ajax = new XMLHttpRequest();
        ajax.open("get", "/setlocale/" + $(this).attr('value') + "");
        ajax.send();
        ajax.addEventListener("load", function (e) {
          if (e.target.status == 200) {
            location.reload();
          }
        })
      });
    });
  </script> --}}
</body>

</html>
