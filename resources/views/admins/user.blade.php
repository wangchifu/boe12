@extends('layouts.app')

@section('title','教育處帳號管理 | ')

@section('content')
<div class="container">
    <div class="py-5">
        <div class="">
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="py-2">
                        教育處科員、科長管理
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead class="thead-light">
                        <tr>
                            <th>
                                姓名(帳號)
                            </th>
                            <th>
                                職稱
                            </th>
                            <th>
                                科室
                            </th>
                            <th>
                                審核權
                            </th>
                            <th>
                                動作
                            </th>
                        </tr>
                        </thead>
                        @foreach($users as $user)
                            @if($user->disable)
                                <tr style="color:#cccccc">
                                    <td>
                                        {{ $user->name }}({{ $user->username }})
                                    </td>
                                    <td>
                                        {{ $user->title }}
                                    </td>
                                    <td>
                                        @if($user->section_id)
                                            {{ $sections[$user->section_id] }}
                                        @endif
                                    </td>
                                    <td>

                                    </td>
                                    <td>
                                        (已移除)<a href="{{ route('admins.user_reback',$user->id) }}" class="btn btn-secondary btn-sm" onclick="return confirm('確定？')">復原</a>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td>
                                        @if($user->title=="科長")
                                            <strong>
                                                @endif
                                                {{ $user->name }}({{ $user->username }})
                                                @if($user->title=="科長")
                                            </strong>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->title=="科長")
                                            <strong>
                                                @endif
                                                {{ $user->title }}
                                                @if($user->title=="科長")
                                            </strong>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->section_id)
                                            {{ $sections[$user->section_id] }}
                                        @endif
                                    </td>
                                    <td>
                                        <?php $user_power = \App\UserPower::where('user_id',$user->id)
                                            ->where('power_type','A')
                                            ->first();
                                        ?>
                                        @if(!empty($user_power))
                                            {{ $section_admins[$user_power->section_id] }}
                                        @endif
                                    </td>
                                    <td>
                                        <a href="javascript:open_user('{{ route('admins.user_edit',$user->id) }}')" class="btn btn-primary btn-sm">編輯</a>
                                        <button class="btn btn-danger btn-sm" onclick="if(confirm('您確定送出嗎?')) document.getElementById('delete{{ $user->id }}').submit();else return false">刪除</button>
                                        {{ Form::open(['route'=>['admins.user_destroy',$user->id],'method'=>'delete','id'=>'delete'.$user->id]) }}
                                        {{ Form::close() }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </table>
                </div>
                <div class="card-footer d-flex flex-row justify-content-center">
                    <div class="pt-3">{{ $users->links('layouts.simple-pagination') }}</div>
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
