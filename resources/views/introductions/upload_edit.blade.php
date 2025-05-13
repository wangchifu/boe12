@extends('layouts.app_clean')

@section('title','修改名稱 | ')

@section('content')
<div class="container">
    <form action="{{ route('introductions.upload_store_name') }}" method="POST">
        @csrf
    <div class="form-group">
        <label for="name"><strong>名稱</strong><small class="text-secondary">目錄/檔案/連結</small></label>        
        <input type="text" name="name" id="name" class="form-control" placeholder="名稱" required value="{{ old('name', $upload->name) }}">
    </div>
    <div class="form-group">
        <input type="hidden" name="id" value="{{ $upload->id }}">
        <input type="hidden" name="path" value="{{ $path }}">
        <button class="btn btn-primary btn-sm" onclick="return confirm('確定修改名稱？')"><i class="fas fa-save"></i> 修改名稱</button>
    </div>
    </form>
</div>
@endsection
