@extends('layouts.master_clean')

@section('content')
    <div class="col-12">
        @if(session()->get('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        <table width="100%" border=0 cellspacing="0" cellpadding="0">
            <tr>
                <td colspan="4" align="left">
                    <img src="{{ asset('images/posts'.$post->category_id.'.png') }}"
                         alt="{{ array_get($categories,$post->category_id) }}"
                         title="{{ array_get($categories,$post->category_id) }}">
                </td>
            </tr>
        </table>
        <form method="post" action="{{ route('posts.signedquickly',$post->id) }}">
            @csrf
            {{ method_field('PATCH') }}
            <table width="100%" style="margin-bottom:10px;font-size:13px;" border=1 bordercolor="#CCCCCC"
                   cellspacing="2" cellpadding="5">
                <tr>
                    <th bgcolor="#C2E3EB" align="center">公告名稱</th>
                    <td colspan="3" align="left">
                        @if( $post->situation ===4 )
                            <span style="color:red">[本公告已作廢]</span><strike>{{ $post->title }}</strike>
                        @else
                            {{ $post->title }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th bgcolor="#9CD8EB" align="center">公告類別</th>
                    <td align="left" colspan="3">{{ array_get($categories,$post->category_id) }}</td>
                </tr>
                <tr>
                    <th bgcolor="#C2E3EB" width="16%" align="center">發佈單位</th>
                    <td width="32%" align="left">{{ array_get($sections,$post->section_id) }}</td>
                    <th bgcolor="#C2E3EB" width="16%" align="center">發佈人</th>
                    <td width="32%" align="left">{{ $post->user->name }}</td>
                </tr>
                <tr>
                    <th bgcolor="#9CD8EB" align="center">發佈時間</th>
                    <td align="left">{{ date_format($post->updated_at,'Y-m-d')  }}</td>
                    <th bgcolor="#9CD8EB" align="center">點閱率</th>
                    <td align="left">{{ $post->views }}</td>
                </tr>
                <tr>
                    <th bgcolor="#C2E3EB" align="center" colspan="4">活動內容簡要說明</th>
                </tr>
                <tr>
                    <td colspan="4" align="left">
                        @if( $post->situation ===4 )
                            <strike>{!! $content !!}</strike>
                        @else
                            {!! $post->content !!}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th bgcolor="#9CD8EB" align="center">附加檔案</th>
                    <td colspan="3" align="left">
                        @if(!empty($files))
                            @foreach($files as $file)
                                <a href="{{ route('posts.download',['id'=>$post->id,'filename'=>$file]) }}"
                                   title="點選下載附加檔案({{ $file }})">
                                    {{ $file }}
                                </a>
                                <br>
                            @endforeach
                        @endif
                    </td>
                </tr>
                <tr>
                    <th bgcolor="#C2E3EB" align="center">相關照片</th>
                    <td colspan="3" align="left">
                        @if(!empty($images))
                            @foreach($images as $image)
                                <?php
                                $image_path = $images_path . '/' . $image;
                                $file_path = str_replace('/', '&', $image_path);
                                ?>
                                <a href="{{ route('posts.downloadimage',['filename'=>$image,'post_id'=>$post->id]) }}">
                                    <img src="{{ route('posts.img',$file_path) }}" width="100"><br>
                                </a>
                            @endforeach
                        @endif
                    </td>
                </tr>
                <tr>
                    <th bgcolor="#9CD8EB" align="center">相關連結</th>
                    <td colspan="3" align="left">
                        <a target="_blank" href="{{ $post->url }}" title="點選開啟相關連結網頁">
                            {{ $post->url }}
                        </a>
                    </td>
                </tr>
                @if($post->category_id ===5)
                    <tr>
                        <th bgcolor="#C2E3EB" align="center">發送對象學校</th>
                        <td colspan="3" align="left">
                            @foreach( $schools as $school)
                                {{ $loop->first ? '' : '、' }}
                                {{ $school->school_name }}
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th bgcolor="#9CD8EB" align="center">已簽收學校</th>
                        <td colspan="3" align="left">
                            @foreach( $signedSchools as $signedSchool)
                                {{ $loop->first ? '' : '、' }}
                                {{ $signedSchool->school_name }}
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th bgcolor="#9CD8EB" align="center">
                            未簽收學校<br/>
                            @if(count($noSignedSchools)>0)
                                @if(auth()->user()->id === $post->user_id)
                                    <input class="btn btn-danger btn-sm" type="submit" value="催簽收">
                                @endif
                            @endif
                        </th>
                        <td colspan="3" align="left">
                            @foreach( $noSignedSchools as $noSignedSchool)
                                {{ $loop->first ? '' : '、' }}
                                {{ $noSignedSchool->school_name }}
                            @endforeach
                        </td>
                    </tr>
                @endif
            </table>
        </form>
        <table border="0" width="100%" bgcolor="#FFFFFF">
            <tr>
                <td align="right" colspan="3">
                    {{ array_get($categories,$post->category_id) }}　{{ $post->user->name }}
                    　發佈時間：{{ $post->updated_at }}
                </td>
            </tr>
        </table>
        <div align="center">
            <input onclick="window.close();" value="關閉視窗" type="button">　
            <input type="button" name="print" onClick="window.print(); return false"
                   OnKeypress="window.print(); return false" value="友善列印">
        </div>
    </div>
@endsection
