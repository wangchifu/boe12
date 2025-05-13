@extends('layouts.app')

@section('title', '退回公告列表 | ')

@section('content')
<div class="container">
    <div class="py-5">
        <div class="col-md-8">
            @include('posts.nav')
            <div class="card my-4">
                <div class="card-header text-center">
                    <h3 class="py-2">
                        退回公告列表
                    </h3>
                </div>
                <div class="card-body">
                    @include('posts.list_review')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
