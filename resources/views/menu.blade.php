@extends('layouts.app')

@section('title','選單連結 | ')

@section('content')
<div class="container">
    <div class="py-5">
        <div class="card">
            <div class="card-header text-center">
                <h3 class="py-2">
                    選單連結
                </h3>
            </div>
            <div class="card-body">
                <a href="{{ route('menu_create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> 新增連結</a>&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;新增連結請點這個按鈕<br>
                目錄結構：<br>
                {{ get_tree($root_menus,0) }}
            </div>
        </div>
    </div>
</div>
<br>
@endsection
