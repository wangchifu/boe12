@extends('layouts.app')

@section('title','帳號管理 | ')

@section('content')
<div class="container">
    <div class="py-5">
        <div class="">
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="py-2">
                        帳號管理
                    </h3>
                    @include('sims.search_nav')
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('sims.index') }}">全部</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('sims.group','1') }}">學校</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('sims.group','2') }}">教育處</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('sims.group','3') }}">系統管理者</a>
                        </li>
                    </ul>
                    @include('sims.form')
                    {{ $users->links() }}
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
