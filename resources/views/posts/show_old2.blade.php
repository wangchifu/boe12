@extends('layouts.master_clean')

@section('content')
    <style>
        pre {
            white-space: pre-wrap;
            word-wrap: break-word;
        }
    </style>
    <div class="col-12">
        <table>
            <tr>
                <td colspan="4" align="left">
                    <img src="{{ asset('images/posts'.$post->category_id.'.png') }}"
                         alt="{{ array_get($categories,$post->category_id) }}"
                         title="{{ array_get($categories,$post->category_id) }}">
                </td>
            </tr>
        </table>

        <table class="table table-striped">
            <tr>
                <th nowrap class="bg-secondary text-light">
                    公告名稱
                </th>
                <td colspan="3">
                    @if($post->category_id==5)
                        編號第 {{ $post->post_no }} 號<br>
                    @endif
                    {{ $post->title }}
                </td>
            </tr>
            <tr>
                <th class="bg-secondary text-light">
                    發佈單位
                </th>
                <td>
                    {{ array_get($sections,$post->section_id) }}
                </td>
                <th nowrap class="bg-secondary text-light">
                    發佈人
                </th>
                <td nowrap>
                    {{ $post->user->name }}
                </td>
            </tr>
            <tr>
                <th class="bg-secondary text-light">
                    發佈時間
                </th>
                <td>
                    {{ date_format($post->updated_at,'Y-m-d')  }}
                </td>
                <th class="bg-secondary text-light">
                    點閱數
                </th>
                <td>
                    {{ $post->views }}
                </td>
            </tr>
            <tr>
                <th colspan="4" class="bg-secondary text-light">
                    公告內容
                </th>
            </tr>
            <tr>
                <td colspan="4" style="word-wrap: break-word;">
                    {!! $post->content !!}
                </td>
            </tr>
            @if(!empty($files))
                <tr>
                    <th class="bg-secondary text-light">
                        附加檔案
                    </th>
                    <td colspan="3">

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
                    <th class="bg-secondary text-light">
                        相關照片
                    </th>
                    <td colspan="3">
                        @foreach($images as $image)
                            <?php
                            $image_path = $images_path . '/' . $image;
                            $file_path = str_replace('/', '&', $image_path);
                            ?>
                            <a href="{{ asset('storage/post_photos/'.$post->id.'/'.$image) }}">
                                <img src="{{ route('posts.img',$file_path) }}" width="100">
                            </a>
                        @endforeach

                    </td>
                </tr>
            @endif
            @if(!empty($post->url))
                <tr>
                    <th class="bg-secondary text-light">
                        相關連結
                    </th>
                    <td colspan="3">
                        <a target="_blank" href="{{ $post->url }}" title="點選開啟相關連結網頁">
                            {{ $post->url }}
                        </a>
                    </td>
                </tr>
            @endif
        </table>
        <div align="center">
            <input onclick="window.close();" value="關閉視窗" type="button">　
            <input type="button" name="print" onClick="window.print(); return false"
                   OnKeypress="window.print(); return false" value="友善列印">
        </div>
    </div>
    <br>
@endsection
