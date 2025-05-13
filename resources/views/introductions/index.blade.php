@extends('layouts.app')

@section('title','科室列表 | ')

@section('content')
<div class="container">
    <div class="py-5">
        <div class="">
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="py-2">
                        教育處介紹管理-科室列表
                    </h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('introductions.admin_people2',1) }}" class="btn btn-info" style="margin: 5px;">處長</a>
                    <a href="{{ route('introductions.admin_people2',2) }}" class="btn btn-info" style="margin: 5px;">副處長</a>
                    <a href="{{ route('introductions.admin_people2',3) }}" class="btn btn-info" style="margin: 5px;">專員</a>
                    @foreach($sections as $k=>$v)
                        <a href="{{ route('introductions.admin_organization',$k) }}" class="btn btn-primary" style="margin: 5px;">{{ $v }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
