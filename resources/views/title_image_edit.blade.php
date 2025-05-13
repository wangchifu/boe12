@extends('layouts.app')

@section('title','橫幅廣告 | ')

@section('content')
<div class="container">
    <div class="py-5">
        <div class="card">
            <div class="card-header text-center">
                <h3 class="py-2">
                    修改橫幅廣告
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('title_image_update', $title_image->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                <div class="container">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <img src="{{ asset('storage/title_image/'.$title_image->photo_name) }}" class="img-fluid">
                            </div>
                        </div>
                        <div class="col-6">
                            <!--
                            <div class="form-group">
                                <label for="title"><strong class="text-dark">標題</strong></label>
                                <input type="text" name="title" id="title" class="form-control" placeholder="非必填" value="{{ old('title', $title_image->title) }}">
                            </div>
                            <div class="form-group">
                                <label for="content"><strong class="text-dark">說明</strong></label>
                                <input type="text" name="content" id="content" class="form-control" placeholder="非必填" value="{{ old('content', $title_image->content) }}">
                            </div>
                            -->
                            <div class="form-group">
                                <label for="link"><strong class="text-dark">連結</strong></label>
                                <input type="text" name="link" id="link" class="form-control" placeholder="非必填" value="{{ old('link', $title_image->link) }}">
                            </div>
                            <div class="form-group">
                                <a href="#" class="btn btn-secondary btn-sm" onclick="history.back()">返回</a>
                                <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存嗎？')">
                                    <i class="fas fa-save"></i> 修改圖片
                                </button>
                            </div>
                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        </div>
                    </div>
                </div>
                </form>

            </div>
        </div>
        <br>
    </div>
</div>
<br>
@endsection
