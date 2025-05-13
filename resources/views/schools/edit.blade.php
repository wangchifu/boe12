@extends('layouts.app_clean')

@section('title','學校帳號管理 | ')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="">
            <div class="card">
                <div class="card-head">
                    <img class="card-img-top img-responsive" src="{{ asset('images/small/school_power.png') }}">
                </div>
                <div class="card-body">
                    <form action="{{ route('school_acc.update', $user->id) }}" method="POST">
                        @csrf
                        @method('POST')
                    <table class="table table-hover">
                        <tr>
                            <td>
                                {{ $user->name }}({{ $user->username }})
                            </td>
                            <td>
                                {{ $user->title }}
                            </td>
                            <td>
                                <?php
                                $user_power = check_a_user($user->code,$user->id);

                                $a_checked = ($user_power)?"checked":null;
                                ?>
                                <input type="checkbox" name="a_user" id="a_user" {{ $a_checked }} onclick="check_another()"> <label for="a_user">管理+審核權</label>
                            </td>
                            <td>
                                <?php
                                $user_power = check_b_user($user->code,$user->id);

                                $b_checked = ($user_power)?"checked":null;
                                ?>
                                <input type="checkbox" name="b_user" id="b_user" {{ $b_checked }}> <label for="b_user">簽收+填報權</label>
                            </td>
                            <td>
                                <button class="btn btn-success btn-sm" onclick="return confirm('確定？')">儲存</button>
                            </td>
                        </tr>
                    </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function check_another(){
        if($('#a_user').prop('checked')){
            $('#b_user').prop("checked", true);
        }
    }
</script>
@endsection
