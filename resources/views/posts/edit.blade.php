@extends('layouts.app')

@section('title', '修改公告 | ')

@section('page-scripts')
    <script>
        function show_type(G) {
            if (G.value == '5') {
                $("#show_type").show();
            } else {
                $("#show_type").hide();
            }
        }
    </script>
@endsection

@section('content')
    <div class="container">
        <div class="py-5">
            <div class="">
                @include('posts.nav')
                <form action="{{ route('posts.update', $post->id) }}" method="POST" id="this_form" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                <div class="card my-4">
                    <div class="card-header text-center">
                        <h3 class="py-2">
                            編輯公告
                        </h3>
                    </div>
                    <div class="card-body">
                        @include('posts.form')
                        <div class="form-group">
                            現有附件：<br>
                            @if(!empty($files))
                                @foreach($files as $v)
                                    <a href="{{ route('posts.del_att',['id'=>$post->id,'filename'=>$v]) }}" onclick="return window.confirm('確定刪除？');">X刪除</a>
                                    <a href="{{ route('posts.download',['filename'=>$v,'post_id'=>$post->id]) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-download">{{ $v }}</i>
                                    </a>
                                @endforeach
                            @endif
                        </div>
                        <div class="form-group">
                            現有圖片：<br>
                            @if(!empty($images))
                                @foreach($images as $v)
                                    <?php
                                    $image_path = $images_path.'/'.$v;
                                    $file_path = str_replace('/','&',$image_path);
                                    ?>
                                        <a href="{{ route('posts.del_img',['id'=>$post->id,'filename'=>$v]) }}" onclick="return window.confirm('確定刪除？');">X刪除</a>
                                    <a href="{{ route('posts.downloadimage',['filename'=>$v,'post_id'=>$post->id]) }}">
                                        <img src="{{ route('posts.img',$file_path) }}" height="50">
                                    </a>
                                @endforeach
                            @endif
                        </div>

                        <div id='show_type'
                             @if($post->category_id!=5)
                             style="display:none"
                                @endif
                        >
                            <div class="form-group">
                                <label>緊急程度</label><br>
                                <input name="type" type="checkbox" value="1" id="type"
                                    @if($post->type===1)
                                        checked
                                   @endif
                                > <label for="type">[最速件]</label>
                            </div>
                            <div class="form-group">
                                <label>公開為「一般公告」給訪客？</label><br>
                                <input name="another" type="checkbox" value="1" id="another"
                                       @if($post->another===1)
                                       checked
                                    @endif
                                > <label for="another">公開<small class="text-secondary">(任何人將看到此則公告)</small></label>
                            </div>
                            <div class="form-group">
                                <label for="schools"><strong class="text-danger">發送對象學校*</strong></label>
                                @include('posts.select_school')
                            </div>

                        </div>

                        <div class="form-group">
                            <input type="submit" class="btn btn-outline-primary" name="form_action" value="暫存" onclick="return confirm('確定暫存？')">                        
                            <input type="submit" class="btn btn-primary" name="form_action" value="送出審核不再修改" onclick="return confirm('送出後，無法再修改喔！')">                        
                            <a href="#" class="btn btn-secondary" onclick="history.back();"><i class="fas fa-backward"></i>
                                返回</a>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        var validator = $("#this_form").validate();
    </script>
@endsection
