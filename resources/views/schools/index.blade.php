@extends('layouts.app')

@section('title','學校帳號管理 | ')

@section('content')
<div class="container">
    <div class="row justify-content-cente py-5">
        <div class="">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('school_acc.index') }}">指定帳號</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('school_acc.list') }}">權限列表</a>
                </li>
            </ul>
        </div>
        <br><br>
        <div class="">
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="py-2" >
                        {{ auth()->user()->school }}學校帳號
                    </h3>
                    <h4 class="py-2" style="color:red">
                        因職務調動，確認校務系統帳號有變動後，請該教師登出本系統，再次登入後即可看到變更。
                    </h4>
                    <h4 class="py-2" style="color:red">
                        有兼任其他學校的同仁（人事、幹事、會計...）
                        1.該同仁須在兩所學校內的校務系統各自建立帳號，使用不同密碼
                        2.該同仁可以在本系統使用同一個 GSuite 帳號，不同密碼來切換兩所學校。
                    </h4>

                </div>
                <div class="card-body">
                    <h2>
                        1.本校帳號
                    </h2>
                    <table class="table table-hover">
                        <tr>
                            <td>
                                姓名(帳號)
                            </td>
                            <td>
                                職稱
                            </td>
                            <td colspan="2">
                                權限
                            </td>
                            <td>
                                動作
                            </td>
                        </tr>
                        @foreach($users as $user)
                            @if($user->disable)
                                <tr style="color:#cccccc">
                                    <td>
                                        {{ $user->name }}(
                                        @if($user->username)
                                            {{ $user->username }}，帳號已移除)
                                        @else
                                            {{ $user->openid }}，帳號已移除)
                                        @endif
                                    </td>
                                    <td>
                                        {{ $user->title }}
                                    </td>
                                    <td>

                                    </td>
                                    <td>

                                    </td>
                                    <td>
                                        <a href="{{ route('school_acc.reback',$user->id) }}" class="btn btn-secondary btn-sm" onclick="return confirm('確定？')">復原</a>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td>
                                        {{ $user->name }}(
                                        @if($user->username)
                                            {{ $user->username }})
                                        @else
                                            {{ $user->openid }})
                                        @endif
                                    </td>
                                    <td>
                                        {{ $user->school }} {{ $user->title }}
                                    </td>
                                    <td>
                                        <?php
                                        //信義國中小
                                        if(auth()->user()->code ==="074774" or auth()->user()->code ==="074541"){
                                            $user_power = \App\Models\UserPower::where('user_id',$user->id)
                                                ->where('power_type','A')
                                                ->where(function($q){
                                                    $q->where('section_id','074774')->orWhere('section_id','074541');
                                                })
                                                ->first();
                                        //原斗國中小
                                        }elseif(auth()->user()->code ==="074745" or auth()->user()->code ==="074537"){
                                            $user_power = \App\Models\UserPower::where('user_id',$user->id)
                                                ->where('power_type','A')
                                                ->where(function($q){
                                                    $q->where('section_id','074745')->orWhere('section_id','074537');
                                                })
                                                ->first();
                                        //民權國中小
                                        }elseif(auth()->user()->code ==="074760" or auth()->user()->code ==="074543"){
                                            $user_power = \App\Models\UserPower::where('user_id',$user->id)
                                                ->where('power_type','A')
                                                ->where(function($q){
                                                    $q->where('section_id','074760')->orWhere('section_id','074543');
                                                })
                                                ->first();
                                        //鹿江國中小
                                        }elseif(auth()->user()->code ==="074542" or auth()->user()->code ==="074778"){
                                            $user_power = \App\Models\UserPower::where('user_id',$user->id)
                                                ->where('power_type','A')
                                                ->where(function($q){
                                                    $q->where('section_id','074542')->orWhere('section_id','074778');
                                                })
                                                ->first();
                                        }else{
                                            $user_power = \App\Models\UserPower::where('section_id',$user->code)
                                                ->where('user_id',$user->id)
                                                ->where('power_type','A')
                                                ->first();
                                        }



                                        ?>
                                        @if($user_power)
                                            審核+管理權
                                        @endif
                                    </td>
                                    <td>
                                        <?php
                                        //信義國中小
                                        if(auth()->user()->code ==="074774" or auth()->user()->code ==="074541"){
                                            $user_power = \App\Models\UserPower::where('user_id',$user->id)
                                                ->where('power_type','B')
                                                ->where(function($q){
                                                    $q->where('section_id','074774')->orWhere('section_id','074541');
                                                })
                                                ->first();
                                        //原斗國中小
                                        }elseif(auth()->user()->code ==="074745" or auth()->user()->code ==="074537"){
                                            $user_power = \App\Models\UserPower::where('user_id',$user->id)
                                                ->where('power_type','B')
                                                ->where(function($q){
                                                    $q->where('section_id','074745')->orWhere('section_id','074537');
                                                })
                                                ->first();
                                        //民權國中小
                                        }elseif(auth()->user()->code ==="074760" or auth()->user()->code ==="074543"){
                                            $user_power = \App\Models\UserPower::where('user_id',$user->id)
                                                ->where('power_type','B')
                                                ->where(function($q){
                                                    $q->where('section_id','074760')->orWhere('section_id','074543');
                                                })
                                                ->first();
                                        //鹿江國中小
                                        }elseif(auth()->user()->code ==="074542" or auth()->user()->code ==="074778"){
                                            $user_power = \App\Models\UserPower::where('user_id',$user->id)
                                                ->where('power_type','B')
                                                ->where(function($q){
                                                    $q->where('section_id','074542')->orWhere('section_id','074778');
                                                })
                                                ->first();
                                        }else{
                                            $user_power = \App\Models\UserPower::where('section_id',$user->code)
                                                ->where('user_id',$user->id)
                                                ->where('power_type','B')
                                                ->first();
                                        }

                                        ?>
                                        @if($user_power)
                                            簽收+填報權
                                        @endif
                                    </td>
                                    <td>
                                        <a href="javascript:open_user('{{ route('school_acc.edit',$user->id) }}')" class="btn btn-primary btn-sm">編輯</a>
                                        @if($user->id != auth()->user()->id and $user->admin !=1)
                                            <a href="{{ route('school_acc.destroy',$user->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('確定？')">停用</a>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </table>
                    <h2>
                        2.他校兼任
                    </h2>
                    <form action="{{ route('school_acc.other') }}" method="POST">
                        @csrf
                    <div class="form-group">
                        <input type="text" name="username" class="form-control" required placeholder="請輸入成員GSuite帳號">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-sm">編輯成員
                        </button>
                    </div>
                    <input type="hidden" name="section_id" value="{{ auth()->user()->other_code }}">
                    </form>
                    @include('layouts.errors')
                    <table class="table table-hover">
                        <tr>
                            <td>
                                姓名(帳號)
                            </td>
                            <td>
                                職稱
                            </td>
                            <td colspan="2">
                                權限
                            </td>
                        </tr>
                        @foreach($user_not_data as $k=>$v)
                            <tr>
                                <td>
                                    {{ $v['name'] }}(
                                    @if($v['username'])
                                        {{ $v['username'] }})
                                    @else
                                        {{ $v['openid'] }})
                                    @endif
                                </td>
                                <td>
                                    {{ $v['school'] }} {{ $v['title'] }}
                                </td>
                                <?php
                                $user_powerA = \App\Models\UserPower::where('section_id',auth()->user()->code)
                                    ->where('user_id',$k)
                                    ->where('power_type','A')
                                    ->first();

                                $user_powerB = \App\Models\UserPower::where('section_id',auth()->user()->code)
                                    ->where('user_id',$k)
                                    ->where('power_type','B')
                                    ->first();
                                ?>
                                <td>
                                    @if(!empty($user_powerA))
                                        審核+管理權
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($user_powerA))
                                        簽收+填報權
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
        window.open(url,name,'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=850,height=650');
    }
    // -->
</script>
@endsection
