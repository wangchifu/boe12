<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserPower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MLoginController extends Controller
{
    //停用者不得登入
    public function auth(Request $request)
    {
        if($request->input('chaptcha') != session('chaptcha')){
            if (!session('login_error')) {
                session(['login_error' => 1]);
            } else {
                $a = session('login_error');
                $a++;
                session(['login_error' => $a]);
            }

            return back()->withErrors(['gsuite_error'=>['驗證碼錯誤！']]);
        }

        if (session('login_error') > 2 ) {
            return back();
        }

        //鎖定的馬上返回
        if(login_eroor_count($request->input('username')) >= 3){
            return back()->withErrors(['error'=>['該帳號已被鎖定，請15分鐘後再試！']]);
        }

        if (Auth::attempt([
            'username' => $request->input('username'),
            'password'=>$request->input('password'),
            'disable' => null,
            'login_type'=>'local',
        ])) {
            // 如果認證通過...

            //log
            if(auth()->user()->group_id==9 or auth()->user()->admin==1){
                $event = "系統管理者 ".auth()->user()->name."(".$request->input('username').") 登入";
                logging('6',$event,get_ip());
            }
            $user_power = UserPower::where('user_id',auth()->user()->id)
                ->where('power_type','A')
                ->first();
            if(auth()->user()->group_id==8 or (!empty(auth()->user()->section_id) and !empty($user_power))){
                $event = "科室管理者 ".auth()->user()->name."(".$request->input('username').") 登入";
                logging('6',$event,get_ip());
            }

            $att_login['logined_at'] = now();
            auth()->user()->update($att_login);

            if (session('login_error')) {
                session(['login_error' => 0]);
            }

            return redirect()->route('index');
        }else{
            $user = User::where('username',$request->input('username'))
                ->first();

            if(empty($user)){
                if (!session('login_error')) {
                    session(['login_error' => 1]);
                } else {
                    $a = session('login_error');
                    $a++;
                    session(['login_error' => $a]);
                }

                return back()->withErrors(['error'=>['帳號密碼錯誤']]);
            }else{
                if(password_verify($request->input('password'), $user->password)){
                    if($user->disable == "1"){
                        if (!session('login_error')) {
                            session(['login_error' => 1]);
                        } else {
                            $a = session('login_error');
                            $a++;
                            session(['login_error' => $a]);
                        }

                        return back()->withErrors(['error'=>['帳號密碼錯誤']]);
                    }
                    if($user->login_type == "gsuite"){
                        if (!session('login_error')) {
                            session(['login_error' => 1]);
                        } else {
                            $a = session('login_error');
                            $a++;
                            session(['login_error' => $a]);
                        }
                        
                        return back()->withErrors(['error'=>['帳號密碼錯誤']]);
                    }
                }else{
                    //密碼錯了，就記錄
                    login_error_add($request->input('username'));

                    if (!session('login_error')) {
                        session(['login_error' => 1]);
                    } else {
                        $a = session('login_error');
                        $a++;
                        session(['login_error' => $a]);
                    }
                    return back()->withErrors(['error'=>['帳號密碼錯誤！']]);
                }
            }
        }

    }
}
