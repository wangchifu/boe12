<?php
    if(chk_ie_browser()){
        dd('本站不支援 IE 瀏覽器，請使用 Google Chrome、Firefox、Microsoft Edge 等支援 HTML5 的瀏覽器。');
    }
    //header("Content-Security-Policy: default-src 'self';");
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>{{ env('APP_NAME') }} - @yield('title')</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <meta http-equiv="Content-Security-Policy" content="script-src * 'unsafe-inline' 'unsafe-eval';">
  @yield('custom_meta')

  <!-- Favicons -->
  <link href="{{ env('APP_URL') }}/images/sun.png" rel="icon">
  <link href="{{ env('APP_URL') }}/images/sun.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@400;500&family=Inter:wght@400;500&family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ env('APP_URL') }}/ZenBlog/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ env('APP_URL') }}/ZenBlog/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="{{ env('APP_URL') }}/ZenBlog/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="{{ env('APP_URL') }}/ZenBlog/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="{{ env('APP_URL') }}/'ZenBlog/assets/vendor/aos/aos.css" rel="stylesheet">

  <!-- Template Main CSS Files -->
  <link href="{{ env('APP_URL') }}/ZenBlog/assets/css/variables.css" rel="stylesheet">
  <link href="{{ env('APP_URL') }}/ZenBlog/assets/css/main.css" rel="stylesheet">

  <!-- Fontawesome -->
  <link href="{{ env('APP_URL') }}/fontawesome-5.1.0/css/all.css" rel="stylesheet">

  <!--
  <link href="{{ env('APP_URL') }}/css/my_css.css" rel="stylesheet">
  -->
  @yield('custom_css')

  <script src="{{ env('APP_URL') }}/js/jquery-3.7.1.min.js"></script>

  <!-- =======================================================
  * Template Name: ZenBlog - v1.2.1
  * Template URL: https://bootstrapmade.com/zenblog-bootstrap-blog-template/
  * Author: BootstrapMade.com
  * License: https:///bootstrapmade.com/license/
  ======================================================== -->

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-RPLBGVYQZ6"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-RPLBGVYQZ6');
</script>
</head>

<body>
  <main id="main" style="margin: 20px 0">

    @yield('content')

  </main>

  <!-- Vendor JS Files -->
  <script src="{{ env('APP_URL') }}/ZenBlog/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="{{ env('APP_URL') }}/ZenBlog/assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="{{ env('APP_URL') }}/ZenBlog/assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="{{ env('APP_URL') }}/ZenBlog/assets/vendor/aos/aos.js') }}"></script>
  <script src="{{ env('APP_URL') }}/ZenBlog/assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="{{ env('APP_URL') }}/ZenBlog/assets/js/main.js"></script>
  @yield('page-scripts')
  
</body>

</html>