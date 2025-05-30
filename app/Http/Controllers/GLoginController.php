<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\User;
use App\Models\UserPower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class GLoginController extends Controller
{
    public function showLoginForm()
    {

        if (auth()->check()) {
            if (auth()->user()->code) {
                return redirect()->route('posts.showSigned');
            }
        }
        return view('auth.glogin');
    }

    /**
    public function testlogin(Request $request)
    {
        //echo $request->input('id');
        //echo $request->input('pwd');


// 找出隸屬於哪一所學校 id 代號

        //是否已有此帳號
        $user = User::where('username',$request->input('id'))
            ->where('login_type','gsuite')
            ->first();

        if(empty($user)){
            //無使用者，即建立使用者資料
            $att['username'] = $request->input('id');
            $att['group_id'] = "1";
            $att['name'] = '測試'.$request->input('id');
            $att['password'] = bcrypt($request->input('pwd'));
            $att['code'] = '074999';
            $att['school'] = '測試國小';
            $att['login_type'] = "gsuite";

            $user = User::create($att);

        }else{

            //停用者，沒有換學校，不得登入
            if($user->disable==1 and $user->code==$obj['code']){
                return back()->withErrors(['errors'=>['此帳號已停用！']]);;
            }

            //有此使用者，即更新使用者資料
            $att['group_id'] = "1";
            $att['name'] = '測試'.$request->input('id');
            $att['password'] = bcrypt($request->input('pwd'));
            $att['code'] = '074999';
            $att['school'] = '測試國小';
            $att['login_type'] = "gsuite";
            $user->update($att);
        }

        if(Auth::attempt(['username' => $request->input('id'),
            'password' => $request->input('pwd')])){
            return redirect()->route('index');
        }

        return back()->withErrors(['errors'=>['密碼錯誤？無此帳號？']]);;

    }
     * */

    public function auth(Request $request)
    {
        if ($request->input('chaptcha') != session('chaptcha')) {
            if (!session('login_error')) {
                session(['login_error' => 1]);
            } else {
                $a = session('login_error');
                $a++;
                session(['login_error' => $a]);
            }
            return back()->withErrors(['gsuite_error' => ['驗證碼錯誤！']])
                ->withInput(Input::all());
        }

        if (session('login_error') > 2 ) {
            return back();
        }

        //鎖定的馬上返回
        if (login_eroor_count($request->input('username')) >= 3) {
            return back()->withErrors(['error' => ['該帳號已被鎖定，請15分鐘後再試！']])
                ->withInput(Input::all());
        }

        $username = explode('@', $request->input('username'));

        $data = array("email" => $username[0], "password" => $request->input('password'));
        $data_string = json_encode($data);
        $ch = curl_init(env('GSUITE_AUTH'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            )
        );
        $result = curl_exec($ch);
        $obj = json_decode($result, true);

        //學生禁止訪問
        if ($obj['success']) {

            if ($obj['kind'] == "學生") {
                return back()->withErrors(['errors' => ['帳號密碼錯誤']]);
            }

            // 找出隸屬於哪一所學校 id 代號
            //$school = School::where('code_no', 'like', $obj['code'] . '%')->first();
            $schools_id = config('boe.schools_id');
            $school_id = !isset($schools_id[$obj['code']]) ? 0 : $schools_id[$obj['code']];

            //是否已有此帳號
            $user = User::where('edu_key', $obj['edu_key'])                
                ->first();    

            if (empty($user)) {
                //查有無曾用openid登入者
                //已取消openid登入 
                //$user2 = User::where('edu_key', $obj['edu_key'])
                //    ->where('login_type', 'gsuite')
                //    ->first();

                $att['username'] = $username[0];
                $att['password'] = bcrypt($request->input('password'));
                $att['group_id'] = ($obj['code'] == "079998" or $obj['code'] == "079999") ? "2" : "1";
                $att['name'] = $obj['name'];
                $att['code'] = $obj['code'];
                $att['school'] = $obj['school'];
                $att['kind'] = $obj['kind'];
                $att['title'] = $obj['title'];
                $att['edu_key'] = $obj['edu_key'];
                $att['uid'] = $obj['uid'];
                $att['login_type'] = "gsuite";
                $att['school_id'] = $school_id;
                //if (empty($user2)) {
                    //無使用者，即建立使用者資料
                    $user = User::create($att);
                //} else {
                //    $user2->update($att);
                //}
            } else {

                //停用者，沒有換學校，不得登入
                if ($user->disable == 1 and $user->code == $obj['code']) {
                    return back()->withErrors(['errors' => ['帳號密碼錯誤']]);
                }

                //如果換了學校，初次登入刪除權限
                if ($user->code != $obj['code']) {
                    $att_change['disable'] = null;
                    $att_change['disabled_at'] = null;
                    $user->update($att_change);

                    //刪除原學校的權限 //為了讓兼兩所學校的人事會計可用，不刪
                    //$user_power_change = UserPower::where('section_id',$user->code)
                    //->where('user_id',$user->id)
                    //->delete();
                }

                //有此使用者，即更新使用者資料
                $att['group_id'] = ($obj['code'] == "079998" or $obj['code'] == "079999") ? "2" : "1";
                $att['name'] = $obj['name'];
                $att['password'] = bcrypt($request->input('password'));
                $att['code'] = $obj['code'];
                $att['school'] = $obj['school'];
                $att['kind'] = $obj['kind'];
                $att['title'] = $obj['title'];
                $att['edu_key'] = $obj['edu_key'];
                $att['uid'] = $obj['uid'];
                $att['disable'] = null;
                $att['school_id'] = $school_id;
                //是主任就是單位管理者
                $att['school_admin'] = ($obj['title'] == '教務主任' or $obj['title'] == '教導主任' or $obj['title'] == '校長') ? "1" : null;
                $user->update($att);
            }

            //是教務主任、教導主任就是學校管理者
            if ($obj['title'] == '教務主任' or $obj['title'] == '教導主任' or $obj['title'] == '校長') {
                $user_power = UserPower::where('section_id', $obj['code'])
                    ->where('user_id', $user->id)
                    ->where('power_type', 'A')
                    ->first();
                if (!$user_power) {
                    $att2['user_id'] = $user->id;
                    $att2['section_id'] = $obj['code'];
                    $att2['power_type'] = "A";
                    UserPower::create($att2);
                }

                $user_power = UserPower::where('section_id', $obj['code'])
                    ->where('power_type', 'B')
                    ->where('user_id', $user->id)
                    ->first();
                if (!$user_power) {
                    $att2['user_id'] = $user->id;
                    $att2['section_id'] = $obj['code'];
                    $att2['power_type'] = "B";
                    UserPower::create($att2);
                }
            }

            //if (Auth::attempt([
            //    'username' => $username[0],
            //    'password' => $request->input('password')
            //])) {
            //}
            Auth::login($user);
            
            $att_login['logined_at'] = now();
            $user->update($att_login);
            //log
            if (auth()->user()->group_id == 9 or auth()->user()->admin == 1) {
                $event = "系統管理者 " . auth()->user()->name . "(" . $request->input('username') . ") 登入";
                logging('6', $event, get_ip());
            }
            $user_power = UserPower::where('user_id', auth()->user()->id)
                ->where('power_type', 'A')
                ->first();
            if (auth()->user()->group_id == 8 or (!empty(auth()->user()->section_id) and !empty($user_power))) {
                $event = "科室管理者 " . auth()->user()->name . "(" . $request->input('username') . ") 登入";
                logging('6', $event, get_ip());
            }

            if (session('login_error')) {
                session(['login_error' => 0]);
            }
            
            //教育處人員
            if (auth()->user()->section_id) {
                return redirect()->route('posts.reviewing');
            }
            //其他學校單位
            if (auth()->user()->other_code) {
                return redirect()->route('posts.showSigned_other');
            }
            //學校單位
            if (auth()->user()->code) {
                return redirect()->route('posts.showSigned');
            }
            //其餘者
            return redirect()->route('index');
        };

        //密碼錯了，就記錄
        login_error_add($request->input('username'));

        if (!session('login_error')) {
            session(['login_error' => 1]);
        } else {
            $a = session('login_error');
            $a++;
            session(['login_error' => $a]);
        }
        return back()->withErrors(['errors' => ['帳號密碼錯誤']]);;
    }
}
