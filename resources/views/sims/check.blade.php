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
                            <a class="nav-link" href="{{ route('sims.index') }}">全部</a>
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
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('sims.check') }}">檢查重複</a>
                        </li>
                    </ul>
                    <table class="table table-striped">
                        <tr>
                            <td class="text-danger">身分證的重複帳號(理應為多校任職及曾調校者)</td>                                                        
                        </tr>
                        @foreach($duplicates as $k=>$v)
                            <tr>
                                <td>
                                    @foreach($v as $v2)         
                                        @if($userid2name[$v2]['disable'])
                                            <span class="text-danger">[停用]</span>
                                        @endif
                                        {{ $userid2name[$v2]['school'] }} {{ $userid2name[$v2]['title'] }} {{ $userid2name[$v2]['name'] }}({{ $v2 }}) <small class="text-secondary">最後登入{{ $userid2name[$v2]['date'] }}</small><br>
                                    @endforeach
                                </td>                                
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
