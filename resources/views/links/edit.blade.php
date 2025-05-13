@extends('layouts.app')

@section('title','修改宣導網站 | ')

@section('content')
<div class="container">
    <div class="py-5">
        <div class="">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('links.index') }}">宣導網站列表</a></li>
                    <li class="breadcrumb-item active" aria-current="page">修改宣導網站</li>
                </ol>
            </nav>
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="py-2">
                        修改宣導網站
                    </h3>
                </div>
                <div class="card-body">
                    @include('layouts.errors')                    
                    <form action="{{ route('links.update', $link->id) }}" method="POST" id="this_form" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                    <div class="form-group">
                        <label for="name">名稱*</label>
                        <input type="text" name="name" id="name" class="form-control" required placeholder="名稱" value="{{ old('name') }}">
                    </div>
                    <div class="form-group">
                        <label for="type">類別</label>
                        <select name="type" id="type" class="form-control">
                            <option value="" disabled selected>不分</option>
                            @foreach($types as $key => $value)
                                <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div clas="form-group">
                        <?php
                        $image = asset('storage/links/'.$link->image);
                        ?>
                        <img src="{{ $image }}" class="image" height="50">
                    </div>
                    <div class="form-group">
                        <label for="image">再傳圖片(600x200)<small class="text-secondary">(取代上方圖片)</small></label><br>
                        <input type="file" name="image" id="image">
                    </div>
                    <div class="form-group">
                        <label for="url">網址*</label>
                        <input type="text" name="url" id="url" class="form-control" required placeholder="https://" value="{{ old('url') }}">
                    </div>
                    <div class="form-group">
                        <label for="order_by">排序</label>                        
                        <input type="text" name="order_by" id="order_by" class="form-control" placeholder="數字" value="{{ old('order_by') }}">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存嗎？')">
                            <i class="fas fa-save"></i> 儲存設定
                        </button>
                    </div>
                    <script>
                        var validator = $("#this_form").validate();
                    </script>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
