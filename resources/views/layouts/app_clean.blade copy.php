<?php
    if(chk_ie_browser()){
        dd('本站不支援 IE 瀏覽器，請使用 Google Chrome、Firefox、Microsoft Edge 等支援 HTML5 的瀏覽器。');
    }
    //$t = file_get_contents('https://opendata.cwb.gov.tw/fileapi/v1/opendataapi/F-C0032-001?Authorization=rdec-key-123-45678-011121314&format=JSON');
    //$content = array_values(json_decode($t,true));
    //$c = $content[0]['dataset']['location'][10]['locationName'];
    //$cloud = $content[0]['dataset']['location'][10]['weatherElement'][0]['time'][0]['parameter']['parameterName'];
    //$MaxT = $content[0]['dataset']['location'][10]['weatherElement'][1]['time'][0]['parameter']['parameterName'];
    //$MinT = $content[0]['dataset']['location'][10]['weatherElement'][2]['time'][0]['parameter']['parameterName'];
    //$f = $content[0]['dataset']['location'][10]['weatherElement'][3]['time'][0]['parameter']['parameterName'];
?>
    <!DOCTYPE html>
<html lang="zh-Hant-TW">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>{{ env('APP_NAME') }} - @yield('title')</title>
    <meta content="" name="descriptison">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ asset('images/sun.png') }}" rel="icon">
    <link href="{{ asset('mamba/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <link href="{{ asset('css/my_css.css') }}" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,600,600i,700,700i,900" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('mamba/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('mamba/vendor/icofont/icofont.min.css') }}" rel="stylesheet">
    <link href="{{ asset('mamba/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('mamba/vendor/animate.css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('mamba/vendor/venobox/venobox.css') }}" rel="stylesheet">
    <link href="{{ asset('mamba/vendor/aos/aos.css') }}" rel="stylesheet">

    <!-- Fontawesome -->
    <link href="{{ asset('fontawesome-5.1.0/css/all.css')}}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('mamba/css/style.css') }}" rel="stylesheet">


    <script src="{{ asset('mamba/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/my_css.css') }}" rel="stylesheet">


    <!-- =======================================================
    * Template Name: Mamba - v2.0.1
    * Template URL: https://bootstrapmade.com/mamba-one-page-bootstrap-template-free/
    * Author: BootstrapMade.com
    * License: https://bootstrapmade.com/license/
    ======================================================== -->
</head>

<body>
<main id="main" class="py-5">
    @yield('content')
</main>

<!-- Vendor JS Files -->
<script src="{{ asset('mamba/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('mamba/vendor/jquery.easing/jquery.easing.min.js') }}"></script>
<!-- <script src="{{ asset('mamba/vendor/php-email-form/validate.js') }}"></script> -->
<script src="{{ asset('mamba/vendor/jquery-sticky/jquery.sticky.js') }}"></script>
<script src="{{ asset('mamba/vendor/venobox/venobox.min.js') }}"></script>
<script src="{{ asset('mamba/vendor/waypoints/jquery.waypoints.min.js') }}"></script>
<script src="{{ asset('mamba/vendor/counterup/counterup.min.js') }}"></script>
<script src="{{ asset('mamba/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('mamba/vendor/aos/aos.js') }}"></script>

<!-- Template Main JS File -->
<script src="{{ asset('mamba/js/main.js') }}"></script>

@yield('page-scripts')

</body>

</html>
