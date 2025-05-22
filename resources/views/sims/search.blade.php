@extends('layouts.app')

@section('title','搜尋模擬 | ')

@section('content')
<div class="container">
    <div class="py-5">
        <div class="">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('sims.index') }}">帳號列表</a></li>
                    <li class="breadcrumb-item active" aria-current="page">搜尋「{{ $want }}」結果</li>
                </ol>
            </nav>
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="py-2">
                        搜尋「{{ $want }}」使用者列表
                    </h3>
                    @include('sims.search_nav')
                </div>
                <div class="card-body">
                    @include('sims.form')
                    {{ $users->appends(['want' => $want])->links('layouts.simple-pagination') }}
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    <!--
    function open_user(url,name)
    {
        window.open(url,name,'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=850,height=650');
    }
    // -->
</script>
@endsection
