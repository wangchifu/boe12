<html>
<head>
    <title>
        公告列印
    </title>
    <link rel="stylesheet" href="{{ asset('bootstrap-4.1.3/css/bootstrap.min.css') }}">
</head>
<body onload="window.print()">
    <style type="text/css">
        @import url(https://fonts.googleapis.com/earlyaccess/cwtexkai.css);
        table {
            border: 1px solid #00; border-collapse: collapse;
        }
        tr, td {
            border: 1px solid #00;
        }
        body {
            font-family: 'cwTeXKai', serif;
        }
    </style>
    <br>
    <br>
    <div class="container">
        <div class="row">
            <div class="col-12 text-center font-weight-bolder">
                <h1>彰化縣政府教育處 {{ $categories[$post->category_id] }}</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-2"></div>
            <div class="col-8 text-right" style="font-size: 25px;">
                <span>承辦人：{{ array_get($sections,$post->section_id) }} / {{ $post->user->name }}@if(!empty($post->user->telephone)) <small>TEL</i> {{ $post->user->telephone }}</small> @endif</span>
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row">
            <div class="col-1"></div>
            <div class="col-10 text-left h2">
                受文者：
                @if($post->category_id == "5" and $post->another <> 1)
                    @auth
                        @if(!empty(auth()->user()->school))
                            彰化{{ auth()->user()->school }}
                        @endif
                    @endauth
                @else
                    全體國民
                @endif
            </div>
            <div class="col-1"></div>
        </div>
        <div class="row">
            <div class="col-1"></div>
            <div class="col-10 text-left" style="font-size: 25px;">
                <?php
                    $y = substr($post->passed_at,0,4) - 1911;
                    $m = substr($post->passed_at,5,2);
                    $d = substr($post->passed_at,8,2);
                ?>
                <span>公告時間：中華民國{{ $y }}年{{ $m }}月{{ $d }}日 {{ substr($post->passed_at,11,5) }}</span><br>
                <span>公告編號：{{ $post->post_no }}</span><br>
                <span>速別：
                    @if($post->type===1)
                        最速件
                    @else
                        普通件
                    @endif
                </span><br>
                @if(!empty($files))
                <span>附件：
                    @foreach($files as $file)
                        {{ $file }},
                    @endforeach
                </span><br>
                @endif
                @if(!empty($post->url))
                <span>相關連結：
                    {{ $post->url }}
                </span><br>
                @endif
                @if(!empty($images))
                <span>
                    相關照片：
                    @foreach($images as $image)
                        {{ $image }},
                    @endforeach
                </span>
                @endif
            </div>
            <div class="col-1"></div>
        </div>
        <div style="margin: 10px;"></div>
        <div class="row">
            <div class="col-1"></div>
            <div class="col-10 text-left">
                <table>
                    <tr class="h2">
                        <td nowrap style="vertical-align:text-top;">
                            主旨：
                        </td>
                        <td>
                            @if( $post->situation ===4 )
                                <span style="color:red">[作廢]</span> <strike>[{{ array_get($categories,$post->category_id) }}] {{ $post->title }}</strike>
                            @else
                                {{ $post->title }}
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-1"></div>
        </div>
        <div style="margin: 10px;"></div>
        <div class="row">
            <div class="col-1"></div>
            <div class="col-10 text-left h2">
                說明：<br>
                <?php
                    $content = str_replace("說明：","",$post->content);
                    $content = str_replace("說明:","",$content);
                ?>
                <div class="h2" style="word-break: break-all;margin-left:10px;">
                    @if( $post->situation ===4 )
                        <strike>{!! nl2br(strip_tags($content,"<ol><li><br>")) !!}</strike>
                    @else
                        {!! strip_tags(nl2br($content),'<ol><li><br>') !!}
                    @endif
                </div>
            </div>
            <div class="col-1"></div>
        </div>
    </div>
</body>
</html>
