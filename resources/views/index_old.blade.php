@extends('layouts.app')

@section('herosection')
    <?php
        $path = storage_path('app/public/title_image');
        if(!is_dir($path)) mkdir($path);

        $images = get_files($path);
        $n = count($images);

        if($n==0){
            $file = "/images/hero-section.jpg";
        }else{
            $r = rand(0,$n-1);
            $file = "/storage/title_image/".$images[$r];
        }
    ?>
    <style>
        .hero {
            background-image: url({{ $file }});
            background-size: cover;
            height: 350px;
            margin-top: -20px;
            -webkit-box-flex: 0;
            -ms-flex: 0 0 auto;
            flex: 0 0 auto;
        }

        .hero .heading {
            color: #fff;
            text-align: center;
            letter-spacing: 10px;
            padding-top: 120px;
        }

        .hero p {
            text-align: center;
            color: #fff;
            padding: 5px 60px;
        }

        @media screen and (min-width: 576px) {
            #lna {
                font-size: 32px;
            }
            #lnb {
                font-size: 32px;
            }
         }

         @media screen and (min-width: 768px) {
            #lna {
                font-size: 48px;
            }
            #lnb {
                font-size: 48px;
            }
         }

        @media screen and (max-width: 576px) {
            #lna {
                font-size: 24px;
            }
            #lnb {
                font-size: 24px;
            }
        }

    </style>

        <div class="hero">
            @auth
                <h1 class="heading">歡迎光臨教育處新雲端</h1>
                <p>登入後請由右上角開始</p>
            @endauth
            @guest
                <h1 class="heading">輕鬆掌握彰化教育大小事</h1>
                <p>立即登入取得更多功能</p>
            @endguest
                <div style="text-align: center" class="row">
                    <div class="row col-12" >
                        <div class="col-md-4 text-sm-center text-md-right col-12">
                            <a id='lna' style="background-color:#F4FF81;border-radius: 5px;" target="_blank" href="https://sites.google.com/chc.edu.tw/chcgelearning/%E9%A6%96%E9%A0%81" style="color: #fff;"><b> 幼兒親子共學專區</b></a>
                        </div>
                        <div class="col-md-4 text-sm-center text-md-center col-12">
                            <a id='lna' style="background-color:#F4FF81;border-radius: 5px;" target="_blank" href="https://sites.google.com/chc.edu.tw/health/%E9%A6%96%E9%A0%81" style="color: #fff;"><b> 肺炎防疫專區</b></a>
                        </div>
                        <div class="col-md-4  text-sm-center  text-md-left col-12">
                            <a id='lnb' style="background-color:#F4FF81;border-radius: 5px;" target="_blank" href="https://sites.google.com/chc.edu.tw/golearn/%E9%A6%96%E9%A0%81" style="color: #fff;"><b> 自主學習專區</b></a>
                        </div>


                    </div>

                </div>

        </div>

@endsection

@section('content')
    <style>
        .demo3 {
            font-family: Arial, sans-serif;
            border: 1px solid #C20;
            margin: 0px 0;
            font-style: italic;
            position: relative;
            padding: 0 0 0 80px;
            box-shadow: 0 2px 5px -3px #000;
            border-radius: 3px;
        }
        .demo3:before {
            content: "跑 馬 燈";
            display: inline-block;
            font-style: normal;
            background: #C20;
            padding: 10px;
            color: #FFF;
            font-weight: bold;
            position: absolute;
            top: 0;
            left: 0;
        }
        .demo3:after {
            content: '';
            display: block;
            top: 0;
            left: 80px;
            background: linear-gradient(#FFF, rgba(255, 255, 255, 0));
            height: 20px;
        }
        .demo3 ul li {
            list-style: none;
            padding: 10px 0;
        }
    </style>
    <!--<script src="{{ asset('jquery_marquee/jquery.marquee.min.js') }}" type="text/javascript"></script>-->
    <script src="{{ asset('jquery-easy-ticker/jquery.easy-ticker.min.js') }}" type="text/javascript"></script>

<div class="container">
    @if($marquees->first())
    <div class="row">
        <div class="col-12">
            <div class="demo3">
                <ul>
                    @foreach($marquees as $marquee)
                        <li>
                            {{ $marquee->title }}
                            @auth
                                <?php
                                $user_power = \App\UserPower::where('user_id',auth()->user()->id)
                                    ->where('power_type','A')
                                    ->first();
                                ?>
                                @if (auth()->user()->admin=="1" or auth()->user()->group_id==8 or !empty(auth()->user()->section_id) && !empty($user_power))
                                    <a href="{{ route('marquees.delete',$marquee->id) }}" onclick="return confirm('刪除？')"><i class="fas fa-times-circle text-danger"></i></a>
                                @endif
                            @endauth
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif
    <div class="row justify-content-center pt-4">
        <div class="col-lg-6 col-md-12">
            <div class="card shadow-sm">
                <a href="{{ route('bulletin.show','1') }}">
                    <div class="hovereffect">
                        <img class="card-img-top img-responsive" src="/images/billboard.png" alt="Card image cap">
                        <div class="overlay align-items-center">
                            <h2>一般公告</h2>
                        </div>
                    </div>
                </a>

                <div class="card-body">
                    <table class="table table-borderless table-hover">
                        <tbody>
                        @foreach($post1 as $post)
                            <tr>
                                <td>
                                    <a href="javascript:open_post('{{ route('posts.show',$post->id) }}','新視窗')">{{ str_limit($post->title,100) }}</a>
                                </td>
                                <a href="https://www.google.com.tw">
                                <td class="text-right" nowrap>
                                    {{ substr($post->passed_at,0,10) }}
                                </td>
                                </a>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{-- <small><a href="{{ route('bulletin.show','1') }}"><i class="fas fa-forward"></i> 更多一般公告...</a>
                    </small> --}}
                </div>
                <div class="card-footer text-muted text-center">
                    <a href="{{ route('bulletin.show','1') }}"><i class="fas fa-angle-double-right px-2"></i>更多一般公告</a>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 mt-sm-3 mt-lg-0">
            <div class="card shadow-sm">
                <a href="{{ route('bulletin.show','2') }}">
                    <div class="hovereffect">
                        <img class="card-img-top img-responsive" src="/images/information.png" alt="Card image cap">
                        <div class="overlay align-items-center">
                            <h2>競賽訊息</h2>
                        </div>
                    </div>
                </a>

                <div class="card-body">
                    <table class="table table-hover">
                        <tbody>
                        @foreach($post2 as $post)
                            <tr>
                                <td>
                                    <a href="javascript:open_post('{{ route('posts.show',$post->id) }}','新視窗')">{{ str_limit($post->title,100) }}</a>
                                </td>
                                <td class="text-right" nowrap>
                                    {{ substr($post->passed_at,0,10) }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{-- <small><a href="{{ route('bulletin.show','2') }}"><i class="fas fa-forward"></i> 更多競賽訊息...</a>
                    </small> --}}
                </div>

                <div class="card-footer text-muted text-center">
                    <a href="{{ route('bulletin.show','2') }}"><i class="fas fa-angle-double-right px-2"></i>更多競賽訊息</a>
                </div>

            </div>
        </div>
    </div>
    <div class="row justify-content-center my-4">
        <div class="col-lg-6 col-md-12">
            <div class="card shadow-sm">
                <a href="{{ route('bulletin.show','3') }}">
                    <div class="hovereffect">
                        <img class="card-img-top img-responsive" src="/images/gallery.png" alt="Card image cap">
                        <div class="overlay align-items-center">
                            <h2>活動成果</h2>
                        </div>
                    </div>
                </a>

                <div class="card-body">
                    <table class="table table-hover">
                        <tbody>
                        @foreach($post3 as $post)
                            <tr>
                                <td>
                                    <a href="javascript:open_post('{{ route('posts.show',$post->id) }}','新視窗')">{{ str_limit($post->title,100) }}</a>
                                </td>
                                <td class="text-right" nowrap>
                                    {{ substr($post->passed_at,0,10) }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{-- <small><a href="{{ route('bulletin.show','3') }}"><i class="fas fa-forward"></i> 更多活動成果...</a>
                    </small> --}}
                </div>

                <div class="card-footer text-muted text-center">
                    <a href="{{ route('bulletin.show','3') }}"><i class="fas fa-angle-double-right px-2"></i>更多活動成果</a>
                </div>

            </div>
        </div>
        <div class="col-lg-6 col-md-12 mt-sm-3 mt-lg-0">
            <div class="card shadow-sm">

                <a href="{{ route('bulletin.show','4') }}">
                    <div class="hovereffect">
                    <img class="card-img-top image-responsive" src="/images/newsFlash.png" alt="Card image cap">
                    <div class="overlay align-items-center">
                        <h2>新聞快訊</h2>
                    </div>
                    </div>
                </a>

                <div class="card-body">
                    <table class="table table-hover">
                        <tbody>
                        @foreach($post4 as $post)
                            <tr>
                                <td>
                                    <a href="javascript:open_post('{{ route('posts.show',$post->id) }}','新視窗')">{{ str_limit($post->title,100) }}</a>
                                </td>
                                <td class="text-right" nowrap>
                                    {{ substr($post->passed_at,0,10) }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{-- <small><a href="{{ route('bulletin.show','4') }}"><i class="fas fa-forward"></i> 更多新聞快訊...</a>
                    </small> --}}
                </div>

                <div class="card-footer text-muted text-center">
                    <a href="{{ route('bulletin.show','4') }}"><i class="fas fa-angle-double-right px-2"></i>更多新聞快訊</a>
                </div>

            </div>
        </div>
    </div>

    <div class="row justify-content-center pt-4">
        <div class="col-lg-12 col-md-12 mt-sm-3 mt-lg-0">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-light">
                    宣導網站
                </div>
                <div class="card-body">
                    <?php
                    $links = \App\Link::orderBy('order_by')->get();
                    ?>
                    <div class="container-fluid">
                        <div class="row">
                            @foreach($links as $link)
                                <div class="col-lg-2 col-md-4 col-sm-6 col-6">
                                    <a href="{{ $link->url }}" target="_blank">
                                        <img src="{{ asset('storage/links/'.$link->image) }}" class="figure-img img-fluid">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function open_post(url,name)
    {
        window.open(url,name,'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=850,height=650');
    }
    $('.demo3').easyTicker({
        visible: 1,
        interval: 2000,
    });
</script>
<!--
網頁設計：
和東國小 王麒富
縣網中心 黃奕豪
縣網中心 曾楚竣
網頁    善心人士
大成國小 黃俊凱
伸港國中 梁世憲
永靖國小 邱顯錫
二林國小 紀明村
-->
@endsection

@section('page-scripts')
    <script src="/js/toSolid.js"></script>
@endsection

