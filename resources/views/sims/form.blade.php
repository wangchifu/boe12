<table class="table table-striped">
    <thead class="thead-light">
    <tr>
        <th>
            群組
        </th>
        <th>
            姓名(帳號)
        </th>
        <th>
            學校-職稱
        </th>
        <th>
            科室
        </th>
        <th>
            動作
        </th>
        <th>
            登入時間
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
            <td>
                @if($user->disable)
                    <i class="fas fa-times-circle text-danger"></i>
                @endif
                @if($user->admin)
                    <i class="fas fa-crown text-primary"></i>
                @endif
                {{ $groups[$user->group_id] }}
            </td>
            <td>
                {{ $user->name }}
                @if($user->username)
                    ({{ $user->username }})
                @else
                    ({{ $user->openid }})
                @endif
                @if($user->disable)
                    <small class="text-danger">停用<br>({{ $user->disabled_at }})</small>
                @endif
                @if($user->admin)
                    <br>
                    <small class="text-primary">系統管理權</small>
                @endif
            </td>
            <td>
                {{ $user->school }} {{ $user->title }}
                @if(check_a_user($user->code,$user->id))
                    <br><small class="text-primary">該校 管理+審核權</small>
                @endif
                @if(check_b_user($user->code,$user->id))
                    <br><small class="text-success">該校 簽收+填報權</small>
                @endif
                @if($user->other_code)
                    <br>
                    <span class="text-danger">{{ $other_schools[$user->other_code] }}</span>
                @endif
            </td>
            <td>
                @if($user->section_id)
                    {{ $sections[$user->section_id] }}
                @endif
                <?php
                    $user_power = \App\Models\UserPower::where('user_id',$user->id)
                        ->where('power_type','A')
                        ->whereIn('section_id',array('A','B','C','D','E','F','G','H','I','J'))
                        ->first();
                ?>
                @if($user_power)
                    <br><small class="text-primary">{{ $sections[$user_power->section_id] }} 審核權</small>
                @endif
            </td>
            <td>
                <a href="{{ route('sims.impersonate',$user->id) }}" class="btn btn-info btn-sm" onclick="return confirm('確定模擬此帳號登入？')">模擬</a>
                @if($user->login_type=="local")
                    <a href="{{ route('reback_password',$user->id) }}" class="btn btn-dark btn-sm" onclick="return confirm('確定還原密碼為 「Plz90-Change-Pwd!!!」 ?')">密碼</a>
                @else
                    <a href="javascript:open_user('{{ route('admins.user_edit',$user->id) }}')" class="btn btn-primary btn-sm">編輯</a>
                @endif
                @if($user->disable)
                    <a href="{{ route('admins.user_reback',$user->id) }}" class="btn btn-secondary btn-sm" onclick="return confirm('確定要復原該帳號可以登入？')">啟用</a>
                @else
                    @if($user->group_id !="8" and $user->group_id != "9" and $user->id != auth()->user()->id)
                        <button class="btn btn-danger btn-sm" onclick="if(confirm('您確定送出嗎?')) document.getElementById('delete{{ $user->id }}').submit();else return false">停用</button>                        
                        <form action="{{ route('admins.user_destroy', $user->id) }}" method="POST" id="delete{{ $user->id }}">
                            @csrf
                            @method('DELETE')
                        </form>                            
                    @endif
                @endif
            </td>
            <td>
                {{ $user->logined_at }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
