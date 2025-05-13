@extends('layouts.app')

@section('title','科室成員管理 | ')

@section('content')
    <div class="container">
        <div class="py-5">
            <h1>
                {{ $sections[$section_id] }} 科室成員管理
            </h1>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header text-center">
                            <h3 class="py-2">
                                具審核權(科長)
                                <?php
                                $user_power = \App\Models\UserPower::where('power_type','A')->where('section_id',$section_id)->first();
                                ?>

                                <a href="javascript:open_user('{{ route('my_section.power') }}')" class="btn btn-primary btn-sm">指定審核者</a>

                            </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover">
                                @foreach($a_admins as $a_admin)
                                    <tr>
                                        <td><strong>
                                                審核者：
                                                {{ $a_admin->user->name }}({{ $a_admin->user->username }})
                                            </strong></td>
                                        <td>
                                            @if($a_admin->user->id != auth()->user()->id)
                                                <a href="{{ route('my_section.power_remove',$a_admin->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('確定移除？')">移除</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header text-center">
                            <h3 class="py-2">
                                成員列表
                                <!--20231221 remove
                                <a href="javascript:open_user2('{{ route('my_section.member') }}')" class="btn btn-primary btn-sm">新增成員</a>
                                -->
                            </h3>
                        </div>
                        <div class="card-body">

                            <table class="table table-hover">
                                @foreach($users1 as $user)
                                    <tr>
                                        <td>
                                            {{ $user->name }}({{ $user->username }})
                                        </td>
                                        <td>
                                            @if($user->group_id != 8 and $user->id != auth()->user()->id)
                                                <a href="{{ route('my_section.remove',$user->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('確定移除？')">移除</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header text-center">
                            <h3 class="py-2">
                                選填本科室者
                            </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover">
                                @foreach($users2 as $user)
                                    <tr>
                                        <td>
                                            {{ $user->name }}({{ $user->username }})
                                        </td>
                                        <td>
                                            <a href="{{ route('my_section.agree',$user->id) }}" class="btn btn-success btn-sm" onclick="return confirm('確定同意？')">同意</a>
                                        </td>
                                        <td>
                                            <a href="{{ route('my_section.disagree',$user->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('確定不同意？')">不同意</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
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
    function open_user2(url,name)
    {
        window.open(url,name,'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=850,height=650');
    }
    // -->
</script>
@endsection
