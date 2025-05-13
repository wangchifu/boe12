<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPower;
use http\Env\Response;
use Illuminate\Http\Request;

class AdminsController extends Controller
{
    public function user()
    {
        $users = User::where('group_id','2')
            ->orderBy('disable')
            ->orderBy('section_id')
            ->orderBy('title','DESC')
            ->paginate(50);

        $sections = config('boe.sections');
        $section_admins = config('boe.section_admins');

        $data = [
            'users'=>$users,
            'sections'=>$sections,
            'section_admins'=>$section_admins,
        ];
        return view('admins.user',$data);
    }

    public function user_edit(User $user)
    {
        $sections = config('boe.sections');
        $section_admins = config('boe.section_admins');

        $user_power = UserPower::where('user_id',$user->id)
            ->where('power_type','A')
            ->whereIn('section_id',array('A','B','C','D','E','F','G','H','I','J'))
            ->first();
        $section_admin = ($user_power)?$user_power->section_id:null;

        $data = [
            'user'=>$user,
            'sections'=>$sections,
            'section_admins'=>$section_admins,
            'section_admin'=>$section_admin,
        ];
        return view('admins.user_edit',$data);
    }

    public function user_update(Request $request,User $user)
    {
        $att['section_id'] = ($request->input('section_id'))?$request->input('section_id'):null;
        $att['admin'] = $request->input('admin');
        $att['other_code'] = $request->input('other_code');
        $user->update($att);
        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 更改了使用者 id：".$user->id." 名稱：".$user->name."的資料，科室為：".$att['section_id'].",系統管理者為：".$att['admin'].",其他單位為：".$att['other_code'];
        logging('2',$event,get_ip());

        if($request->input('a_user')=="on"){
            $user_power = UserPower::where('user_id',$user->id)
                ->where('section_id',$request->input('code'))
                ->where('power_type','A')
                ->first();
            if(!$user_power){
                $att2['section_id'] = $request->input('code');
                $att2['user_id'] = $user->id;
                $att2['power_type'] = "A";
                UserPower::create($att2);

                //log
                $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 新增了單位代碼 ".$request->input('code')." 的A權限 使用者 id：".$user->id." 名稱：".$user->name;
                logging('2',$event,get_ip());
            }
        }else{
            $check = UserPower::where('user_id',$user->id)
                ->where('section_id',$request->input('code'))
                ->where('power_type','A')
                ->get();
            UserPower::where('user_id',$user->id)
                ->where('section_id',$request->input('code'))
                ->where('power_type','A')
                ->delete();

            //log
            if(count($check)>0){
                $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 移除了單位代碼 ".$request->input('code')." 的A權限 使用者 id：".$user->id." 名稱：".$user->name;
                logging('2',$event,get_ip());
            }
        }

        if($request->input('b_user')=="on"){
            $user_power = UserPower::where('user_id',$user->id)
                ->where('section_id',$request->input('code'))
                ->where('power_type','B')
                ->first();
            if(!$user_power){
                $att3['section_id'] = $request->input('code');
                $att3['user_id'] = $user->id;
                $att3['power_type'] = "B";
                UserPower::create($att3);

                //log
                $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 新增了單位代碼 ".$request->input('code')." 的B權限 使用者 id：".$user->id." 名稱：".$user->name;
                logging('2',$event,get_ip());
            }
        }else{
            $check = UserPower::where('user_id',$user->id)
                ->where('section_id',$request->input('code'))
                ->where('power_type','B')
                ->get();
            UserPower::where('user_id',$user->id)
                ->where('section_id',$request->input('code'))
                ->where('power_type','B')
                ->delete();

            //log
            if(count($check)>0){
                $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 移除了單位代碼 ".$request->input('code')." 的B權限 使用者 id：".$user->id." 名稱：".$user->name;
                logging('2',$event,get_ip());
            }
        }


        if($request->input('section_admin')){
            UserPower::where('user_id',$user->id)
                ->whereIn('section_id',array('A','B','C','D','E','F','G','H','I','J'))
                ->delete();

            $att4['user_id'] = $user->id;
            $att4['section_id'] = $request->input('section_admin');
            $att4['power_type'] = "A";
            UserPower::create($att4);

            //log
            $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 新增了單位代碼 ".$request->input('section_admin')." 的A權限 使用者 id：".$user->id." 名稱：".$user->name;
            logging('2',$event,get_ip());

        }else{
            $check = UserPower::where('user_id',$user->id)
                ->whereIn('section_id',array('A','B','C','D','E','F','G','H','I','J'))
                ->get();
            UserPower::where('user_id',$user->id)
                ->whereIn('section_id',array('A','B','C','D','E','F','G','H','I','J'))
                ->delete();

            //log
            if(count($check)>0){
                $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 移除了所有科室的權限 使用者 id：".$user->id." 名稱：".$user->name;
                logging('2',$event,get_ip());
            }

        }

        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function user_destroy(User $user)
    {
        $att['disable'] = 1;
        $att['my_section_id'] = null;
        $att['section_id'] = null;
        $att['disabled_at'] = now();
        $user->update($att);
        UserPower::where('user_id',$user->id)->delete();

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 停用了使用者 id：".$user->id." 名稱：".$user->name;
        logging('2',$event,get_ip());

        return redirect()->route('sims.index');
    }

    public function user_reback(User $user)
    {
        $att['disable'] = null;
        $att['disabled_at'] = null;
        $user->update($att);

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 啟用了使用者 id：".$user->id." 名稱：".$user->name;
        logging('2',$event,get_ip());

        return redirect()->route('sims.index');
    }

}
