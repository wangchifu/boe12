@extends('layouts.app')

@section('title','相簿管理 | ')

@section('custom_meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('custom_css')
    <link href="{{ asset('css/styles.imageuploader.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <div class="py-5">
        <div class="card">
            <div class="card-header text-center">
                <h3 class="py-2">
                    新增相簿
                </h3>
            </div>
            <div class="card-body">
                <input type="text" name="album_name" class="form-control" placeholder="相簿名稱">
                <section role="main" class="l-main" style="margin-top:50px;margin-bottom:50px;">
                    <header class="site-header">
                        <h1 class="site-title l-site-title" style="font-size:1.2em;">jquery多文件上傳插件效果演示</h1>
                    </header>
                    <div class="uploader__box js-uploader__box l-center-box">
                        <form action="{{ route('photo_albums.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="uploader__contents">
                                <label class="button button--secondary" for="fileinput">請選擇文件</label>
                                <input id="fileinput" class="uploader__file-input" type="file" multiple value="Select Files">
                            </div>
                            <input class="button button--big-bottom" type="submit" value="Upload Selected Files">
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
<br>
<script src="{{ asset('js/jquery.imageuploader.js') }}"></script>
<script>
    (function(){
        var options = {};
        $('.js-uploader__box').uploader({
            'selectButtonCopy':'請按這裡選擇照片',
            'instructionsCopy':'選擇照片',
            'submitButtonCopy':'上傳選擇的照片',
            'furtherInstructionsCopy':'你可以選擇更多的照片',
            'secondarySelectButtonCopy':'選擇更多的照片',
        });
    }());
</script>
@endsection
