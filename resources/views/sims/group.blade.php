@extends('layouts.app')

@section('title','群組帳號 | ')

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
                    <?php
                        if($group_id =="1"){
                            $active1 = "active";
                            $active2 = "";
                            $active3 = "";
                        }
                        if($group_id =="2"){
                            $active1 = "";
                            $active2 = "active";
                            $active3 = "";
                        }
                        if($group_id =="3"){
                            $active1 = "";
                            $active2 = "";
                            $active3 = "active";
                        }
                    ?>
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('sims.index') }}">全部</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $active1 }}" href="{{ route('sims.group','1') }}">學校</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $active2 }}" href="{{ route('sims.group','2') }}">教育處</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $active3 }}" href="{{ route('sims.group','3') }}">系統管理者</a>
                        </li>
                    </ul>
                    @include('sims.form')
                    {{ $users->links('layouts.simple-pagination') }}
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
