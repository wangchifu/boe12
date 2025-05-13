<?php
    if(chk_ie_browser()){
        dd('本站不支援 IE 瀏覽器，請使用 Google Chrome、Firefox、Microsoft Edge 等支援 HTML5 的瀏覽器。');
    }
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="Shortcut Icon" type="image/x-icon" href="{{ asset('images/sun.png') }}" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title'){{ env('APP_NAME') }}</title>

    <!-- Scripts -->
    <!--會衝突
    <script src="{{ asset('js/app.js') }}"></script>
    -->
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/additional-methods.min.js') }}"></script>
    <script src="{{ asset('js/messages_zh_TW.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('bootstrap-4.1.3/css/bootstrap.min.css') }}">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/my_css.css') }}" rel="stylesheet">

    <!-- Fontawesome -->
    <link rel="stylesheet" href="{{ asset('fontawesome-5.1.0/css/all.css')}}">
    @yield('page-style')
</head>
<body>
    <div id="app">
        <nav class="@if(request()->is('/')) navbar navbar-expand-xl navbar-dark navbar-laravel fixed-top @else navbar navbar-expand-xl navbar-dark navbar-laravel fixed-top solid @endif">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{-- <img src="/images/boe-logo.svg" alt="logo" width="130px" height="30px"> --}}
                    <b>{{ env('APP_NAME') }}</b>

                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-chalkboard-teacher"></i> 數位學習
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="https://cloud.edu.tw/curation/detail/1166" target="_blank">
                                    教育部數位學習平台匯整
                                </a>
                                <a class="dropdown-item" href="https://sites.google.com/chc.edu.tw/elearn/%E9%A6%96%E9%A0%81" target="_blank">
                                    數位學習平台使用說明
                                </a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fab fa-audible"></i> 最新消息
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="{{ url('bulletin/1') }}">
                                    一般公告
                                </a>
                                <a class="dropdown-item" href="{{ url('bulletin/2') }}">
                                    競賽訊息
                                </a>
                                <a class="dropdown-item" href="{{ url('bulletin/3') }}">
                                    活動成果
                                </a>
                                <a class="dropdown-item" href="{{ url('bulletin/4') }}">
                                    新聞快訊
                                </a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fab fa-mixcloud"></i> 教育處介紹
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('introductions.show2',['type'=>'people','section_id'=>1]) }}">處長</a>
                                <a class="dropdown-item" href="{{ route('introductions.show2',['type'=>'people','section_id'=>2]) }}">副處長</a>
                                <!--
                                <a class="dropdown-item" href="{{ route('introductions.show2',['type'=>'people','section_id'=>3]) }}">專員</a>
                                -->
                                <div class="dropdown-divider"></div>
                                <?php $sections = config('boe.sections'); ?>
                                @foreach($sections as $k=>$v)
                                    @if($v != "縣網中心" and $v != "體設科")
                                    <a class="dropdown-item" href="{{ route('introductions.show',['type'=>'organization','section_id'=>$k]) }}">{{ $v }}</a>
                                    @endif
                                @endforeach
                                <a class="dropdown-item" href="https://www.chc.edu.tw" target="_blank">縣網中心</a>
                                <a class="dropdown-item" href="https://chc.familyedu.moe.gov.tw/SubSites/Home.aspx?site=364e7da4-ddb9-4eaf-8308-9355ea9b660a" target="_blank">家庭教育中心</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('introductions.show_download') }}" class="nav-link"><i class="fas fa-arrow-alt-circle-down"></i> 檔案下載</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fab fa-medrt"></i> 其他連結
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <?php
                                $others = \App\Models\Other::orderBy('order_by')->get();
                                ?>
                                @foreach($others as $other)
                                    <a class="dropdown-item" href="{{ $other->url }}" target="_blank">{{ $other->name }}</a>
                                @endforeach
                            </div>
                        </li>
                    </ul>
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('glogin') }}"><i class="fas fa-user-circle px-1"></i>登入</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    @if(auth()->user()->school)
                                        {{ auth()->user()->school }} {{ auth()->user()->title }}
                                    @endif
                                    @if(auth()->user()->section_id)
                                        <?php $sections = config('boe.sections'); ?>
                                        {{ $sections[auth()->user()->section_id] }}
                                    @endif
                                    {{ Auth::user()->name }} <i class="fas fa-bars"></i><span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    @if(auth()->user()->group_id=="8" or auth()->user()->group_id=="9")
                                        <a class="dropdown-item" href="{{ route('edit_password') }}"><i class="fas fa-key"></i> 更改密碼</a>
                                    @endif
                                    @if(auth()->user()->group_id==1)
                                        @if(check_a_user(auth()->user()->code,auth()->user()->id))
                                            <a class="dropdown-item" href="{{ route('school_acc.index') }}"><i class="fas fa-school"></i> 學校帳號管理</a>
                                        @endif
                                        @if(check_b_user(auth()->user()->code,auth()->user()->id))
                                            <a class="dropdown-item" href="{{ route('posts.showSigned') }}"><i class="fas fa-pen-fancy"></i> 公告簽收</a>
                                            <a class="dropdown-item" href="{{ route('school_report.index') }}"><i class="far fa-list-alt"></i> 資料填報</a>
                                        @endif
                                    @endif
                                    @if(auth()->user()->other_code)
                                            <a class="dropdown-item" href="{{ route('posts.people_other') }}"><i class="fas fa-user"></i> 其他單位人員管理</a>
                                            <a class="dropdown-item" href="{{ route('posts.showSigned_other') }}"><i class="fas fa-pen-fancy"></i> 其他單位公告簽收</a>
                                    @endif
                                    @if((auth()->user()->group_id == 2 or !empty(auth()->user()->section_id)) and auth()->user()->group_id !=8)
                                        <a class="dropdown-item" href="{{ route('posts.reviewing') }}"><i class="fas fa-rss-square"></i> 公告系統</a>
                                        <a class="dropdown-item" href="{{ route('edu_report.index') }}"><i class="fas fa-pen-square"></i> 填報系統</a>
                                        @if(!empty(auth()->user()->section_id))
                                            <?php
                                                $num = [
                                                    'A'=>1,
                                                    'B'=>2,
                                                    'C'=>3,
                                                    'D'=>4,
                                                    'E'=>5,
                                                    'F'=>6,
                                                    'G'=>7,
                                                    'H'=>8,
                                                    'I'=>9,
                                                ];
                                            ?>
                                            <a class="dropdown-item" href="{{ route('introductions.upload','&'.$num[auth()->user()->section_id]) }}"><i class="fas fa-upload"></i> 檔案上傳</a>
                                            <a class="dropdown-item" href="{{ route('marquees.index') }}"><i class="fas fa-chevron-up"></i> 跑馬燈系統</a>
                                        @endif
                                    @endif
                                    <?php
                                        $user_power = \App\Models\UserPower::where('user_id',auth()->user()->id)
                                            ->where('power_type','A')
                                            ->first();
                                    ?>
                                    @if(auth()->user()->group_id==8 or (!empty(auth()->user()->section_id) and !empty($user_power)))
                                        <a class="dropdown-item" href="{{ route('introductions.organization') }}"><i class="fas fa-file-alt"></i> 科室頁面管理</a>
                                        <a class="dropdown-item" href="{{ route('my_section.admin') }}"><i class="fas fa-user-circle"></i> 科室成員管理</a>
                                    @endif
                                    @if(auth()->user()->group_id==9 or auth()->user()->admin==1)
                                            <a class="dropdown-item" href="{{ route('sims.index') }}"><i class="fas fa-users"></i> 帳號管理</a>
                                            <a class="dropdown-item" href="{{ route('title_image') }}"><i class="fas fa-image"></i> 橫幅廣告</a>
                                            <!--
                                            <a class="dropdown-item" href="{{ route('admins.user') }}"><i class="fas fa-user-circle"></i> 教育處帳號管理</a>
                                            -->
                                            <a class="dropdown-item" href="{{ route('introductions.index') }}"><i class="fab fa-mixcloud"></i> 教育處介紹管理</a>
                                            <a class="dropdown-item" href="{{ route('links.index') }}"><i class="fas fa-image"></i> 宣導網站</a>
                                            <a class="dropdown-item" href="{{ route('others.index') }}"><i class="fab fa-medrt"></i> 其他連結</a>
                                            <a class="dropdown-item" href="{{ route('special') }}"><i class="fas fa-search"></i> 特殊處理</a>
                                    @endif
                                        <a class="dropdown-item" href="{{ route('wrench.index') }}"><i class="fas fa-wrench"></i> 系統報錯與建議</a>
                                        <a class="dropdown-item" href="{{ route('questions.index') }}"><i class="fas fa-question-circle"></i> 常見問題集</a>
                                    @impersonating
                                    <a class="dropdown-item" href="{{ route('sims.impersonate_leave') }}" onclick="return confirm('確定返回原本帳琥？')"><i class="fas fa-user-times"></i> 結束模擬</a>
                                    @endImpersonating
                                        <a class="dropdown-item" href="#" onclick="
                                        if(confirm('您確定登出嗎?')) document.getElementById('logout-form').submit();
                                            else return false">
                                            <i class="fas fa-walking"></i> 登出
                                        </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        @yield('herosection')

        <main class="mainSection py-4">
            @yield('content')
        </main>

        @include('layouts.footer')
    </div>
    <script>
        // Global Javascript
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>
    @yield('page-scripts')
</body>
</html>
