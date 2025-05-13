@extends('layouts.app')

@section('title','權限列表 | ')

@section('content')
<div class="container">
    <div class="py-5">
        <div class="">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('school_acc.index') }}">指定帳號</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('school_acc.list') }}">權限列表</a>
                </li>
            </ul>
        </div>
        <br>
        <br>
        <div class="">
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="py-2">
                        {{ auth()->user()->school }}權限列表
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <tr>
                            <td>
                                姓名(帳號)
                            </td>
                            <td>
                                學校
                            </td>
                            <td>
                                職稱
                            </td>
                            <td>
                                權限
                            </td>
                            <td>
                                動作
                            </td>
                        </tr>
                        @foreach($user_powers as $user_power)
                            <tr>
                                <td>
                                    {{ $user_power->user->name }}(
                                    @if($user_power->user->username)
                                        {{ $user_power->user->username }}
                                    @else
                                        {{ $user_power->user->openid }}
                                    @endif
                                    )
                                </td>
                                <td>
                                    {{ $user_power->user->school }}
                                </td>
                                <td>
                                    {{ $user_power->user->title }}
                                </td>
                                <td>
                                    {{ $school_powers[$user_power->power_type] }}
                                </td>
                                <td>
                                    @if($user_power->user_id != auth()->user()->id)
                                        <a href="{{ route('school_acc.power_remove',$user_power->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('確定移除？')">移除權限</a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    <!--
    function open_user(url,name)
    {
        window.open(url,name,'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=850,height=250');
    }
    // -->
</script>
@endsection
