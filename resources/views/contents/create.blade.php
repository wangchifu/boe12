@extends('layouts.app')

@section('title','內容管理 | ')

@section('content')
    <div class="container">
        <div class="py-5">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('contents.index') }}">內容列表</a></li>
                    <li class="breadcrumb-item active" aria-current="page">新增內容</li>
                </ol>
            </nav>
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="py-2">
                        新增內容
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('contents.store') }}" method="POST" id="this_form">
                        @csrf
                    <div class="form-group">
                        <label for="title">標題*</label>
                        <input type="text" name="title" id="title" class="form-control" required placeholder="標題">
                    </div>
                    <div class="form-group">
                        <label for="content">內文*</label>
                        <textarea name="content" id="my-editor" class="form-control" required></textarea>
                    </div>
                    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
                    <script>
                        CKEDITOR.replace('my-editor'
                            ,{
                                filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
                                filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images',
                                filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
                                filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files',
                            });
                    </script>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存嗎？')">
                            <i class="fas fa-save"></i> 儲存設定
                        </button>
                    </div>
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    <input type="hidden" name="section_id" value="{{ auth()->user()->section_id }}">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
