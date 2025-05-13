@extends('layouts.app')

@section('title','新增其他連結 | ')

@section('content')
<div class="container">
    <div class="py-5">
        <div class="">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('others.index') }}">其他連結列表</a></li>
                    <li class="breadcrumb-item active" aria-current="page">新增其他連結</li>
                </ol>
            </nav>
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="py-2">
                        新增其他連結
                    </h3>
                </div>
                <div class="card-body">
                    @include('layouts.errors')
                    <form action="{{ route('others.store') }}" method="POST" id="this_form">
                        @csrf
                        <div class="form-group">
                            <label for="name">名稱*</label>
                            <input type="text" name="name" id="name" class="form-control" required="required" placeholder="名稱" value="{{ old('name') }}">
                        </div>
                        <div class="form-group">
                            <label for="url">網址*</label>    
                            <input type="text" name="url" id="url" class="form-control" required="required" placeholder="https://" value="{{ old('url') }}">
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
