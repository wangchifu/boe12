@extends('layouts.app')

@section('title','橫幅廣告 | ')

@section('content')
<div class="container">
    <div class="py-5">
        <div class="card">
            <div class="card-header text-center">
                <h3 class="py-2">
                    橫幅廣告 
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('title_image_add') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                <div class="form-group">
                    <label for="files[]"><strong class="text-danger">圖檔寬高比例約為1:2.8 (1400 x 500)</strong> [<a href="https://www.iloveimg.com/zh-tw/crop-image" target="_blank">線上裁切圖片</a>]</label>
                    <input type="file" name="pic" class="form-control" required>
                </div>
                <!--
                <div class="form-group">
                    <label for="title"><strong class="text-dark">標題</strong></label>
                    <input type="text" name="title" id="title" class="form-control" placeholder="非必填">
                </div>
                <div class="form-group">
                    <label for="content"><strong class="text-dark">說明</strong></label>
                    <input type="text" name="content" id="content" class="form-control" placeholder="非必填">
                </div>
                    -->
                <div class="form-group">
                    <label for="link"><strong class="text-dark">連結</strong></label>
                    <input type="text" name="link" id="link" class="form-control" placeholder="非必填">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('確定儲存嗎？')">
                        <i class="fas fa-save"></i> 新增圖片
                    </button>
                </div>
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                </form>

            </div>
        </div>
        <br>
        <table class="table table-striped">
            <thead class="thead-light">
            <tr>
                <th class="col-4">圖片</th>
                <th>說明</th>
            </tr>
            </thead>
            <tbody>
            @foreach($title_images as $title_image)
                <tr>
                    <td>
                        <img src="{{ asset('storage/title_image/'.$title_image->photo_name) }}" class="img-fluid">
                    </td>
                    <td>
                        <a href="{{ route('title_image_edit',$title_image) }}" class="btn btn-primary btn-sm">編輯</a> <a href="{{ route('title_image_del',$title_image->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('確定刪除？')">刪除</a><br>
                        @if($title_image->title)
                            <strong>{{ $title_image->title }}</strong><br>
                        @endif
                        @if($title_image->content)
                            {{ $title_image->content }}<br>
                        @endif
                        @if($title_image->link)
                            {{ $title_image->link }}<br>
                        @endif
                        <small class="text-secondary">by {{ $title_image->user->name }}</small>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<br>
@endsection
