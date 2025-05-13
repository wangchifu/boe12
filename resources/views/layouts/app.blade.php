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
  <link href="{{ env('APP_URL') }}/ZenBlog/assets/vendor/aos/aos.css" rel="stylesheet">

  <!-- Template Main CSS Files -->
  <link href="{{ env('APP_URL') }}/ZenBlog/assets/css/variables.css" rel="stylesheet">
  <link href="{{ env('APP_URL') }}/ZenBlog/assets/css/main.css" rel="stylesheet">

  <!-- Fontawesome -->
  <link href="{{ env('APP_URL') }}/fontawesome-5.15.4/css/all.css" rel="stylesheet">

  <!--
  <link href="{{ env('APP_URL') }}/css/my_css.css" rel="stylesheet">
  -->
  @yield('custom_css')

  <script src="{{ env('APP_URL') }}/js/jquery-3.7.1.min.js"></script>
  <script src="{{ env('APP_URL') }}/js/jquery.validate.js"></script>
  
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

  <!-- ======= Header ======= -->
  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

      <a href="{{ route('index') }}" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <img src="{{ asset('images/sun.png') }}" alt="">
        <img src="{{ asset('images/name.png') }}" alt="">
      </a>

      <nav id="navbar" class="navbar me-auto">
        <ul>
            <?php 
            if (!session('root_menus')) {
              $root_menus = \App\Models\Menu::where('belong', '0')->orderBy('order_by')->orderBy('type')->get();            
              session(['root_menus' => $root_menus]);              
            }                      
            ?>
            {{ get_tree3(session('root_menus'),0) }}
            @guest
                    <li><a href="{{ route('glogin') }}">登入</a></li>
                    @else
                    <li class="dropdown">
                        <a href="#">
                            <i class="fas fa-user-alt"></i> <i class='bi bi-chevron-down dropdown-indicator'></i>
                        </a>
                        <ul>
                            <?php 
                            $tt= "";
                                if(auth()->user()->school){
                                    $tt .= auth()->user()->school." ".auth()->user()->title;
                                }
                                if(auth()->user()->section_id){
                                    $sections = config('boe.sections');
                                    $tt .= " ".$sections[auth()->user()->section_id];
                                }
                                $tt .= " ".Auth::user()->name;
                            ?>
                            <li><a href="#" onclick="alert('你是 {{ $tt }}')">
                                    Hi ! {{ Auth::user()->name }}~~
                                </a>
                            </li>
                            <li class="dropdown-divider"></li>
                            @if(auth()->user()->group_id=="8" or auth()->user()->group_id=="9")
                            <li><a href="{{ route('edit_password') }}">更改密碼</a></li>
                            @endif
                            @if(auth()->user()->group_id==1)
                            @if(check_a_user(auth()->user()->code,auth()->user()->id))
                            <li><a href="{{ route('school_acc.index') }}">學校帳號管理</a></li>
                            <li><a href="{{ route('school_introduction.index') }}">學校簡介</a></li>
                            @endif
                            @if(check_b_user(auth()->user()->code,auth()->user()->id))
                            <li><a href="{{ route('posts.showSigned') }}">公告簽收</a></li>
                            <li><a href="{{ route('school_report.index') }}">資料填報</a></li>
                            <li class="dropdown-divider"></li>
                            @endif
                            @endif
                            @if(auth()->user()->other_code)
                            <li><a href="{{ route('posts.people_other') }}">其他單位人員管理</a></li>
                            <li><a href="{{ route('posts.showSigned_other') }}">其他單位公告簽收</a></li>
                            @endif
                            @if((auth()->user()->group_id == 2 or !empty(auth()->user()->section_id)) and auth()->user()->group_id !=8)
                            <li><a href="{{ route('posts.reviewing') }}">公告系統</a></li>
                            <li><a href="{{ route('edu_report.index') }}">填報系統</a></li>
                            <li class="dropdown-divider"></li>
                            @if(!empty(auth()->user()->section_id))
                            <?php
                            $num = [
                                'A' => 1,
                                'B' => 2,
                                'C' => 3,
                                'D' => 4,
                                'E' => 5,
                                'F' => 6,
                                'G' => 7,
                                'H' => 8,
                                'I' => 9,
                                'J' => 7019,
                            ];
                            ?>
                            <li><a href="{{ route('introductions.upload','&'.$num[auth()->user()->section_id]) }}">檔案上傳</a></li>
                            <li><a href="{{ route('marquees.index') }}">跑馬燈系統</a></li>
                            @endif
                            @endif
                            <?php
                            if (!session('user_power')) {
                              $user_power = \App\Models\UserPower::where('user_id', auth()->user()->id)
                                ->where('power_type', 'A')
                                ->first();
                              session(['user_power' => $user_power]);
                            }                            
                            ?>
                            @if(auth()->user()->group_id==8 or (!empty(auth()->user()->section_id) and !empty(session('user_power'))))
                            <li class="dropdown-divider"></li>
                            <li><a href="{{ route('introductions.organization') }}">科室頁面管理</a></li>
                            <li><a href="{{ route('my_section.admin') }}">科室成員管理</a></li>
                            @endif
                            @if(auth()->user()->group_id==9 or auth()->user()->admin==1 or auth()->user()->group_id==8 or (!empty(auth()->user()->section_id) and !empty(session('user_power'))))
                            <li class="dropdown-divider"></li>
                            <li class='dropdown dropdown-menu-left'><a href=''>站台管理 <i class='bi bi-chevron-down dropdown-indicator'></i></a>
                                <ul>
                                    <li><a href="{{ route('title_image') }}">橫幅廣告</a></li>
                                    <li><a href="{{ route('menu') }}">選單連結</a></li>
                                    <li><a href="{{ route('contents.index') }}">內容管理</a></li>
                                    <li><a href="{{ route('photo_albums.index') }}">相簿管理</a></li>
                                </ul>
                            </li>

                            <li class="dropdown-divider"></li>
                            @endif
                            @if(auth()->user()->group_id==9 or auth()->user()->admin==1)
                            <li><a href="{{ route('sims.index') }}">帳號管理</a></li>
                            <li><a href="{{ route('introductions.index') }}">教育處介紹管理</a></li>
                            <!--
                                <li><a href="{{ route('links.index') }}">宣導網站</a></li>
                                -->
                            <li><a href="{{ route('others.index') }}">其他連結</a></li>
                            <li><a href="{{ route('special') }}">特殊處理</a></li>
                            <li><a href="{{ route('logs') }}">log 記錄</a></li>
                            <li><a href="{{ route('system_posts.index') }}">系統公告</a></li>
                            <li><a href="{{ route('close') }}">>關閉系統<</a></li>
                            <li class="dropdown-divider"></li>
                            @endif
                            <li><a href="{{ route('wrench.index') }}">系統報錯與建議</a></li>
                            <li><a href="{{ route('questions.index') }}">常見問題集</a></li>
                            <li><a href="{{ route('questions.about') }}">關於本系統</a></li>
                            <li class="dropdown-divider"></li>
                            <li><a href="#" onclick="if(confirm('您確定登出嗎?')) document.getElementById('logout-form').submit();else return false">登出</a></li>
                            @impersonating
                            <li class="dropdown-divider"></li>
                            <li><a href="{{ route('sims.impersonate_leave') }}" onclick="return confirm('確定返回原本帳琥？')">結束模擬</a></li>
                            @endImpersonating
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </ul>
                    </li>
                    @endguest
        </ul>
      </nav><!-- .navbar -->

      <div class="position-relative">
        <a href="https://education.chcg.gov.tw/00home/index1.asp" class="mx-1" target="_blank"><span class="bi-globe"></span></a>
        <a href="https://www.facebook.com/boe.chc.edu/" class="mx-1" target="_blank"><span class="bi-facebook"></span></a>
        <a href="https://www.youtube.com/channel/UCRMgRmPHuLDrdYSlACT0iVQ" class="mx-1" target="_blank"><span class="bi-youtube"></span></a>
        <br>
        <a href="https://www.newboe.chc.edu.tw/rss" class="mx-1" target="_blank"><span class="bi-rss"></span></a>
        <a href="#" class="mx-1 js-search-open"><span class="bi-search"></span></a>
        <i class="bi bi-list mobile-nav-toggle"></i>

        <!-- ======= Search Form ======= -->
        <div class="search-form-wrap js-search-form-wrap">
          <form action="{{ route('search') }}" class="search-form" method="get" target="_blank" id="search_form">
            @csrf
            <span class="icon bi-search" onclick="$('#search_form').submit();"></span>
            <input type="text" name="want" placeholder="Enter 送出" class="form-control">
            <button style="display:none;">送出</button>
            <button class="btn js-search-close"><span class="bi-x"></span></button>
          </form>
        </div><!-- End Search Form -->

      </div>

    </div>

  </header><!-- End Header -->

  <main id="main">
    @yield('hero')
    @yield('content')

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">

    <div class="footer-content">
      <div class="container">

        <div class="row g-5">
          <div class="col-lg-3">
            <h3 class="footer-heading">彰化縣教育處新雲端</h3>
            <p>網頁如有問題請洽教育網路中心</p>
            <ul class="footer-links list-unstyled">
              <li><a href="https://www.chc.edu.tw/" target="_blank"><i class="bi bi-chevron-right"></i> 彰化縣教育網路中心</a></li>
              <li><a href="https://school.chc.edu.tw/" target="_blank"><i class="bi bi-chevron-right"></i> 彰化學校資料平台</a></li>
              <li><a href="https://gsuite.chc.edu.tw/" target="_blank"><i class="bi bi-chevron-right"></i> 彰化GSuite平台</a></li>
            </ul>
          </div>
          <div class="col-6 col-lg-3">
            <h3 class="footer-heading">教育行政單位連結</h3>
            <ul class="footer-links list-unstyled">
              <li><a href="https://www.edu.tw/" target="_blank"><i class="bi bi-chevron-right"></i> 教育部</a></li>
              <li><a href="https://www.chcg.gov.tw/" target="_blank"><i class="bi bi-chevron-right"></i> 彰化縣政府</a></li>
              <li><a href="https://education.chcg.gov.tw/" target="_blank"><i class="bi bi-chevron-right"></i> 彰化縣政府教育處官網</a></li>
              <li><a href="https://www.ece.moe.edu.tw/ch/" target="_blank"><i class="bi bi-chevron-right"></i> 全國教保資訊網</a></li>
            </ul>
          </div>
          <div class="col-6 col-lg-3">
            <h3 class="footer-heading">其他連結</h3>
            <?php
              if (!session('others')) {
                $others = \App\Models\Other::orderBy('order_by')->get();
                session(['others' => $others]);
              } 
              
            ?>
            <ul class="footer-links list-unstyled">
              @foreach(session('others') as $other)
                  <li><a href="{{ $other->url }}" target="_blank"><i class="bi bi-chevron-right"></i> {{ $other->name }}</a></li>
              @endforeach
            </ul>
          </div>

          <div class="col-lg-3">
            <h3 class="footer-heading">聯絡資訊</h3>

            <ul class="footer-links footer-blog-entry list-unstyled">
                <li><i class="fas fa-map-marker-alt px-2"></i> 彰化市中山路二段416號</li>
                <li><i class="fas fa-phone-volume px-2"></i> 教育處：04-7222151
                <li><i class="fas fa-phone-volume px-2"></i> 教育網路中心：04-7237182
            </ul>

          </div>
        </div>
      </div>
    </div>

    <div class="footer-legal">
      <div class="container">

        <div class="row justify-content-between">
          <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
            <div class="copyright">
              © Copyright <strong><span>ZenBlog 彰化縣網中心</span></strong>. All Rights Reserved
            </div>

            <div class="credits">
              <!-- All the links in the footer should remain intact. -->
              <!-- You can delete the links only if you purchased the pro version. -->
              <!-- Licensing information: https://bootstrapmade.com/license/ -->
              <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/herobiz-bootstrap-business-template/ -->
              Template Designed by <a href="https://bootstrapmade.com/" target="_blank">BootstrapMade</a>
            </div>

          </div>

          <div class="col-md-6">
            <div class="social-links mb-3 mb-lg-0 text-center text-md-end">
              <a href="https://education.chcg.gov.tw/00home/index1.asp" class="twitter" target="_blank"><i class="bi bi-globe"></i></a>
              <a href="https://www.facebook.com/boe.chc.edu/" class="facebook" target="_blank"><i class="bi bi-facebook"></i></a>
              <a href="https://www.youtube.com/channel/UCRMgRmPHuLDrdYSlACT0iVQ" class="instagram" target="_blank"><i class="bi bi-youtube"></i></a>
              <a href="https://www.newboe.chc.edu.tw/rss" class="google-plus" target="_blank"><i class="bi bi-rss"></i></a>
            </div>

          </div>

        </div>

      </div>
    </div>

  </footer>

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="{{ env('APP_URL') }}/ZenBlog/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="{{ env('APP_URL') }}/ZenBlog/assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="{{ env('APP_URL') }}/ZenBlog/assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="{{ env('APP_URL') }}/ZenBlog/assets/vendor/aos/aos.js"></script>
  <!--
  <script src="{{ env('APP_URL') }}/ZenBlog/assets/vendor/php-email-form/validate.js"></script>
  -->

  <!-- Template Main JS File -->
  <script src="{{ env('APP_URL') }}/ZenBlog/assets/js/main.js"></script>

  @yield('page-scripts')
@auth
  <?php 
    if (!session('user_read_ids')) {
      $user_read_ids = \App\Models\UserRead::where('user_id',auth()->user()->id)->pluck('system_post_id')->toArray();    
      session(['user_read_ids' => $user_read_ids]);
    }
    
    $system_posts = [];
    if(session('user_all_read') != 1){      
      $system_posts = \App\Models\SystemPost::whereNotin('id',session('user_read_ids'))
      ->where('start_date','<=',date('Y-m-d'))
      ->where('end_date','>=',date('Y-m-d'))
      ->get();
      if(count($system_posts)==0){
        session(['user_all_read' => 1]);
      }
    }

  ?>
  @if(count($system_posts)>0)
    <script type="text/javascript">
      window.onload = function () {
        $("#simpleModal").modal('show');
      };      
    </script>
    <!-- Modal -->
    <div class="modal fade" id="simpleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">系統公告</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <?php $no_read_sp=''; ?>
          <div class="modal-body">
            <?php $i=1; ?>
            @foreach($system_posts as $system_post)   
            <?php              
              $no_read_sp .= $system_post->id.',';
            ?>         
            公告{{ $system_post->id }}：<br>
            {!! nl2br($system_post->content) !!}
            @if(count($system_posts)>1 and $i != count($system_posts))
            <hr>
            @endif
            <?php $i++; ?>            
            @endforeach            
            <?php $no_read_sp = substr($no_read_sp,0,-1); ?>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="window.location='{{ route('user_reads',$no_read_sp) }}'">知道了</button>            
          </div>
        </div>
      </div>
    </div>  
  @endif
@endauth
</body>

</html>