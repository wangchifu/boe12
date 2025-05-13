@extends('layouts.app')

@section('title','選單連結 | ')

@section('content')
<div class="container">
    <div class="py-5">
        <div class="card">
            <div class="card-header text-center">
                <h3 class="py-2">
                    新增選單連結
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('menu_add') }}" method="POST">
                    @csrf
                <div class="form-group">
                    <label for="belong"><strong class="text-danger">所屬目錄</strong></label>
                    <select name="belong" class="form-control">
                        <!-- <option value="0">最上層根目錄</option> -->

                        @foreach($folder_menus as $folder_menu)
                            <?php
                            $n = explode('>',$folder_menu->path);
                            $name = "";
                            foreach($n as $k=>$v){
                                if(isset($folder_name[$v])){
                                    $name .= $folder_name[$v]."/";
                                }
                            }
                            $name .= $folder_name[$folder_menu->id]."/";
                            ?>
                            <option value="{{ $folder_menu->id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="type"><strong class="text-danger">類型</strong></label>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="type" value="1">可下拉目錄
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="type" value="2" checked>連結
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="name"><strong class="text-danger">名稱</strong></label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="必填" required>
                </div>
                <div class="form-group">
                    <label for="link"><strong class="text-dark">連結</strong></label>
                    <input type="text" name="link" id="link" class="form-control" placeholder="非必填">
                </div>
                <div class="form-group">
                    <label for="order_by"><strong class="text-dark">排序</strong></label>
                    <input type="number" name="order_by" id="order_by" class="form-control" placeholder="非必填">
                </div>
                <div class="form-group">
                    <label for="target"><strong class="text-dark">開啟方式</strong></label>
                    <select name="target" id="target" class="form-control" placeholder="目錄不用選">
                        <option value="" disabled selected>目錄不用選</option>
                        @foreach($target_array as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <a href="#" class="btn btn-secondary btn-sm" onclick="history.back()">返回</a>
                <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存嗎？')">
                    <i class="fas fa-save"></i> 儲存選單
                </button>
                </form>
            </div>
        </div>
    </div>
</div>
<br>
@endsection
