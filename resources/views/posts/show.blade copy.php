@extends('layouts.app_clean')

@section('title',$post->title.' | ')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
            <div class="card-head">
                <img class="card-img-top img-responsive" src="{{ asset('images/posts'.$post->category_id.'.png') }}"
                            alt="{{ array_get($categories,$post->category_id) }}"
                            title="{{ array_get($categories,$post->category_id) }}">
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <th scope="row" class="text-center post-th" width="20%">[類別] 標題</th>
                            <td style="color: #000000">
                                @if($post->type===1)
                                    <span class="text-danger">
                                        [最速件]
                                    </span>
                                @endif
                                @if($post->post_no)
                                    [{{ $post->post_no }}]
                                @endif
                                @if( $post->situation ===4 )
                                    <span style="color:red">[公告作廢]</span> <strike>[{{ array_get($categories,$post->category_id) }}] {{ $post->title }}</strike>
                                @else
                                    {{ $post->title }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th scope="row" class="text-center post-th" width="20%">單位 / 發佈人</th>
                            <td style="color: #000000">{{ array_get($sections,$post->section_id) }} / {{ $post->user->name }}</td>
                        </tr>
                        <tr>
                            <th scope="row" class="text-center post-th" width="20%">時間 / 點閱</th>
                            <td style="color: #000000">{{ substr($post->passed_at,0,16)  }} / {{ $post->views }} </td>
                        </tr>
                        <tr>
                            <th scope="row" class="text-center post-th" width="20%">內容</th>
                            <td style="color: #000000;word-break: break-all;">
                                @if( $post->situation ===4 )
                                    <strike>{!! $post->content !!}</strike>
                                @else
                                    {!! $post->content !!}
                                @endif
                            </td>
                        </tr>

                        @if(!empty($files))
                            <tr>
                                <th scope="row" class="text-center post-th" width="20%">
                                    附加檔案
                                </th>
                                <td>

                                    @foreach($files as $file)
                                        <a href="{{ route('posts.download',['id'=>$post->id,'filename'=>$file]) }}"
                                           title="點選下載附加檔案({{ $file }})">
                                            {{ $file }}
                                        </a>
                                        <br>
                                    @endforeach
                                </td>
                            </tr>
                        @endif

                        @if(!empty($images))

                            <tr>
                                <th scope="row" class="text-center post-th" width="20%">
                                    相關照片
                                </th>
                                <td>
                                    @foreach($images as $image)
                                        <?php
                                        $image_path = $images_path . '/' . $image;
                                        $file_path = str_replace('/', '&', $image_path);
                                        ?>
                                        <a href="{{ asset('storage/post_photos/'.$post->id.'/'.$image) }}" target="_blank">
                                            <img src="{{ route('posts.img',$file_path) }}" width="100">
                                        </a>
                                    @endforeach

                                </td>
                            </tr>
                        @endif

                        @if(!empty($post->url))
                            <tr>
                                <th scope="row" class="text-center post-th" width="15%">
                                    相關連結
                                </th>
                                <td colspan="3">
                                    <a target="_blank" href="{{ $post->url }}" title="點選開啟相關連結網頁">
                                        {{ $post->url }}
                                    </a>
                                </td>
                            </tr>
                        @endif

                    </tbody>
                </table>
            </div>
            <div class="card-footer text-center">
                <div>
                    {{ array_get($categories,$post->category_id) }}　{{ array_get($sections,$post->section_id) }}　{{ $post->user->name }}
                        　發佈時間：{{ substr($post->passed_at,0,16)  }}
                </div>
                <div class="py-3 text-right">
                    <a class="btn btn-outline-primary mx-1" href="{{ route('posts.print',$post->id) }}">
                        <i class="fas fa-print"></i> 列印公告
                    </a>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection
