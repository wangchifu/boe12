@extends('layouts.app')

@section('title','新增跑馬燈 | ')

@section('content')
<div class="container">
    <div class="py-5">
        <div class="">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('marquees.index') }}">跑馬燈列表</a></li>
                    <li class="breadcrumb-item active" aria-current="page">新增跑馬燈</li>
                </ol>
            </nav>
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="py-2">
                        新增跑馬燈
                    </h3>
                </div>
                <div class="card-body">
                    @include('layouts.errors')
                    <form action="{{ route('marquees.store') }}" method="POST">
                        @csrf
                    <?php
                        $title = "";
                        $start_date = "";
                        $stop_date = "";
                    ?>
                    @include('marquees.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
