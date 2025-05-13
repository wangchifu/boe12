@extends('layouts.app')

@section('title',$section_name.' | ')

@section('content')
<div class="container">
    <div class="py-5">
        <div class="">
            <h1>{{ $section_name }}</h1>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('introductions.organization') }}">業務簡介</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('introductions.people') }}">科室成員</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('introductions.site') }}">資源網站</a>
                </li>
                @foreach($section_pages as $section_page)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('introductions.section_page',$section_page->id) }}">{{ $section_page->title }}</a>
                    </li>
                @endforeach
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('introductions.section_page_add') }}"><i class="fas fa-plus-circle"></i> 頁面</a>
                </li>
            </ul>
            <br>
            <form action="{{ route('introductions.store') }}" method="post">
                @csrf
                <div class="form-group">                    
                    <textarea name="content" id="my-editor" class="form-control" required>{{ old('content', $content) }}</textarea>
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
                <input type="hidden" name="type" value="site">
                <div class="form-group">
                    <button class="btn btn-success btn-sm" onclick="return confirm('確定？')">儲存設定</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
