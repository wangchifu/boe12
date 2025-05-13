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
                    <a class="nav-link" href="{{ route('introductions.site') }}">資源網站</a>
                </li>
                @foreach($section_pages as $sp)
                    <?php  $active=($section_page->id == $sp->id)?"active":"";  ?>
                    <li class="nav-item">
                        <a class="nav-link {{ $active }}" href="{{ route('introductions.section_page',$sp->id) }}">{{ $sp->title }}</a>
                    </li>
                @endforeach
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('introductions.section_page_add') }}"><i class="fas fa-plus-circle"></i> 頁面</a>
                </li>
            </ul>
            <br>
            <form action="{{ route('introductions.section_page_update',$section_page->id) }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="title"><strong class="text-danger">標題*</strong></label>
                    <input type="text" name="title" class="form-control" required value="{{ $section_page->title }}">
                </div>
                <div class="form-group">
                    <label for="title"><strong class="text-dark">排序</strong></label>
                    <input type="number" name="order_by" class="form-control" value="{{ $section_page->order_by }}">
                </div>
                <div class="form-group">                    
                    <textarea name="content" id="my-editor" class="form-control" required>{{ old('content', $section_page->content) }}</textarea>
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
                    <button class="btn btn-success btn-sm" onclick="return confirm('確定？')">儲存設定</button>
                    <div class="text-right">
                        <a href="{{ route('introductions.section_page_del',$section_page->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('確定刪除？')">刪除</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
