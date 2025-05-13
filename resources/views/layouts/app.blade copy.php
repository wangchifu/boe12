<?php
if (chk_ie_browser()) {
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
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="expires" content="0">

    @yield('custom_meta')

    <link href="{{ asset('images/sun.png') }}" rel="icon">
    <link href="{{ asset('mamba/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <link href="{{ asset('css/my_css.css') }}" rel="stylesheet">


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
    <link href="{{ asset('mamba/css/style.css') }}?v=2" rel="stylesheet">

    <script src="{{ asset('mamba/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>

    @yield('custom_css')
</head>

<body>


    <section id="topbar" class="d-none d-lg-block">
        <div class="container clearfix">
            <div class="contact-info float-left">

            </div>
            <div class="social-links float-right">
                <a href="https://education.chcg.gov.tw/00home/index1.asp" class="website" target="_blank"><i class="icofont-web"></i></a>
                <a href="https://www.facebook.com/boe.chc.edu/" class="facebook" target="_blank"><i class="icofont-facebook"></i></a>
                <a href="https://www.youtube.com/channel/UCRMgRmPHuLDrdYSlACT0iVQ" class="youtube" target="_blank"><i class="icofont-youtube-play"></i></a>
                <a href="{{ route('rss') }}" class="rss" target="_blank"><i class="icofont-rss"></i></a>
            </div>
        </div>
    </section>


    <div id="header">
        <div class="container">
            <div class="">
                <a href="/"><img src="{{ asset('images/logo.png') }}" alt="" class="img-fluid d-none d-md-block" style="max-width: 100%;"></a>
                <a href="/"><img src="{{ asset('images/logo.png') }}" alt="" class="img-fluid d-block d-md-none" style="max-width: 100%;"></a>
            </div>
            <nav class="nav-menu  d-none d-lg-block">
                <ul class="font-weight-bold ">
                    @yield('menu')

                    <?php
                    $root_menus = \App\Models\Menu::where('belong', '0')->orderBy('type')->orderBy('order_by')->get();
                    ?>
                    {{ get_tree2($root_menus,0) }}

                    @guest
                    <li><a href="{{ route('glogin') }}">登入</a></li>
                    @else
                    <li class="drop-down">
                        <a href="">
                            <i class="fas fa-user-alt"></i>
                        </a>
                        <ul>
                            <li><a href="#" onclick="alert('你是 {{ Auth::user()->name }}')">
                                    @if(auth()->user()->school)
                                    {{ auth()->user()->school }}<br>{{ auth()->user()->title }}
                                    @endif
                                    @if(auth()->user()->section_id)
                                    <?php $sections = config('boe.sections'); ?>
                                    {{ $sections[auth()->user()->section_id] }}
                                    @endif
                                    {{ Auth::user()->name }}
                                </a>
                            </li>
                            <li class="dropdown-divider"></li>
                            @if(auth()->user()->group_id=="8" or auth()->user()->group_id=="9")
                            <li><a href="{{ route('edit_password') }}"><i class="fas fa-key"></i> 更改密碼</a></li>
                            @endif
                            @if(auth()->user()->group_id==1)
                            @if(check_a_user(auth()->user()->code,auth()->user()->id))
                            <li><a href="{{ route('school_acc.index') }}"><i class="fas fa-school"></i> 學校帳號管理</a></li>
                            <li><a href="{{ route('school_introduction.index') }}"><i class="fas fa-edit"></i> 學校簡介</a></li>
                            @endif
                            @if(check_b_user(auth()->user()->code,auth()->user()->id))
                            <li><a href="{{ route('posts.showSigned') }}"><i class="fas fa-pen-fancy"></i> 公告簽收</a></li>
                            <li><a href="{{ route('school_report.index') }}"><i class="far fa-list-alt"></i> 資料填報</a></li>
                            <li class="dropdown-divider"></li>
                            @endif
                            @endif
                            @if(auth()->user()->other_code)
                            <li><a href="{{ route('posts.people_other') }}"><i class="fas fa-user"></i> 其他單位人員管理</a></li>
                            <li><a href="{{ route('posts.showSigned_other') }}"><i class="fas fa-pen-fancy"></i> 其他單位公告簽收</a></li>
                            @endif
                            @if((auth()->user()->group_id == 2 or !empty(auth()->user()->section_id)) and auth()->user()->group_id !=8)
                            <li><a href="{{ route('posts.reviewing') }}"><i class="fas fa-rss-square"></i> 公告系統</a></li>
                            <li><a href="{{ route('edu_report.index') }}"><i class="fas fa-pen-square"></i> 填報系統</a></li>
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
                            ];
                            ?>
                            <li><a href="{{ route('introductions.upload','&'.$num[auth()->user()->section_id]) }}"><i class="fas fa-upload"></i> 檔案上傳</a></li>
                            <li><a href="{{ route('marquees.index') }}"><i class="fas fa-chevron-up"></i> 跑馬燈系統</a></li>
                            @endif
                            @endif
                            <?php
                            $user_power = \App\Models\UserPower::where('user_id', auth()->user()->id)
                                ->where('power_type', 'A')
                                ->first();
                            ?>
                            @if(auth()->user()->group_id==8 or (!empty(auth()->user()->section_id) and !empty($user_power)))
                            <li class="dropdown-divider"></li>
                            <li><a href="{{ route('introductions.organization') }}"><i class="fas fa-file-alt"></i> 科室頁面管理</a></li>
                            <li><a href="{{ route('my_section.admin') }}"><i class="fas fa-user-circle"></i> 科室成員管理</a></li>
                            @endif
                            @if(auth()->user()->group_id==9 or auth()->user()->admin==1 or auth()->user()->group_id==8 or (!empty(auth()->user()->section_id) and !empty($user_power)))
                            <li class="dropdown-divider"></li>
                            <li class='drop-down'><a href=''>站台管理</a>
                                <ul>
                                    <li><a href="{{ route('title_image') }}"><i class="fas fa-image"></i> 橫幅廣告</a></li>
                                    <li><a href="{{ route('menu') }}"><i class="fas fa-list-alt"></i> 選單連結</a></li>
                                    <li><a href="{{ route('contents.index') }}"><i class="fas fa-hand-paper"></i> 內容管理</a></li>
                                    <li><a href="{{ route('photo_albums.index') }}"><i class="fas fa-images"></i> 相簿管理</a></li>
                                </ul>
                            </li>

                            <li class="dropdown-divider"></li>
                            @endif
                            @if(auth()->user()->group_id==9 or auth()->user()->admin==1)
                            <li><a href="{{ route('sims.index') }}"><i class="fas fa-users"></i> 帳號管理</a></li>
                            <li><a href="{{ route('introductions.index') }}"><i class="fab fa-mixcloud"></i> 教育處介紹管理</a></li>
                            <!--
                                <li><a href="{{ route('links.index') }}"><i class="fas fa-image"></i> 宣導網站</a></li>
                                -->
                            <li><a href="{{ route('others.index') }}"><i class="fab fa-medrt"></i> 其他連結</a></li>
                            <li><a href="{{ route('special') }}"><i class="fas fa-search"></i> 特殊處理</a></li>
                            <li><a href="{{ route('logs') }}"><i class="fas fa-comment"></i> log 記錄</a></li>
                            <li class="dropdown-divider"></li>
                            @endif
                            <li><a href="{{ route('wrench.index') }}"><i class="fas fa-wrench"></i> 系統報錯與建議</a></li>
                            <li><a href="{{ route('questions.index') }}"><i class="fas fa-question-circle"></i> 常見問題集</a></li>
                            <li class="dropdown-divider"></li>
                            <li><a href="#" onclick="if(confirm('您確定登出嗎?')) document.getElementById('logout-form').submit();else return false"><i class="fas fa-walking"></i> 登出</a></li>
                            @impersonating
                            <li class="dropdown-divider"></li>
                            <li><a href="{{ route('sims.impersonate_leave') }}" onclick="return confirm('確定返回原本帳琥？')"><i class="fas fa-user-times"></i> 結束模擬</a></li>
                            @endImpersonating
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </ul>
                    </li>
                    @endguest
                </ul>
            </nav>
        </div>
    </div>
    @yield('hero')

    <main id="main">
        @yield('content')
        <div class="container text-center">
            <a href="/"><img src="{{ asset('images/bg_logo.png') }}" alt="" class="img-fluid"></a>
        </div>
    </main>

    <footer id="footer">
        <div class="footer-top">
            <div class="container">
                <div class="row">

                    <div class="col-lg-3 col-md-6 footer-info">
                        <h3>{{ env('APP_NAME') }}</h3>
                        <p>
                            網頁如有問題請洽教育網路中心
                        </p>
                        <div class="social-links mt-3">
                            <a href="https://education.chcg.gov.tw/00home/index1.asp" class="website" target="_blank"><i class="icofont-web"></i></a>
                            <a href="https://www.facebook.com/boe.chc.edu/" class="facebook" target="_blank"><i class="icofont-facebook"></i></a>
                            <a href="https://www.youtube.com/channel/UCRMgRmPHuLDrdYSlACT0iVQ" class="youtube" target="_blank"><i class="icofont-youtube-play"></i></a>
                            <a href="{{ route('rss') }}" class="website" target="_blank"><i class="icofont-rss"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 footer-links">
                        <h4>相關連結</h4>
                        <ul>
                            <li><i class="bx bx-chevron-right"></i> <a href="https://www.chcg.gov.tw" target="_blank">彰化縣政府</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="https://education.chcg.gov.tw" target="_blank">彰化縣政府教育處官網</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="https://www.chc.edu.tw" target="_blank">彰化縣教育網路中心</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="https://school.chc.edu.tw" target="_blank">彰化學校資料平台</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-2 col-md-6 footer-links">
                        <h4>其他連結</h4>
                        <ul>
                            <?php
                            $others = \App\Models\Other::orderBy('order_by')->get();
                            ?>
                            @foreach($others as $other)
                            <li><i class="bx bx-chevron-right"></i> <a href="{{ $other->url }}" target="_blank">{{ $other->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="col-lg-3 col-md-6 footer-links">
                        <h4>聯絡資訊</h4>
                        <ul>
                            <li><i class="fas fa-map-marker-alt px-2"></i> 彰化市中山路二段416號</li>
                            <li><i class="fas fa-phone-volume px-2"></i> 教育處：04-7222151
                            <li><i class="fas fa-phone-volume px-2"></i> 教育網路中心：04-7237182
                        </ul>
                    </div>
                </div>
            </div>
        </div>



    </footer>


    <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

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