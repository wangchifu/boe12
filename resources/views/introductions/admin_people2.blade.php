@extends('layouts.app')

@section('title',$section_name.' | ')

@section('content')
<div class="container">
    <div class="py-5">
        <div class="">
            <h1>{{ $section_name }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('introductions.index') }}">教育處介紹管理列表</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $section_name }}</li>
                </ol>
            </nav>
            <form action="{{ route('introductions.admin_store2') }}" method="post">
                @csrf
                <div class="form-group">
                    <textarea id="my-editor" name="content" class="form-control" required>{{ old('content', $content) }}</textarea>
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
                <input type="hidden" name="section_id" value="{{ $section_id }}">
                <input type="hidden" name="type" value="people">
                <div class="form-group">
                    <button class="btn btn-success btn-sm" onclick="return confirm('確定？')">儲存設定</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
