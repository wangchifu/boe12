<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPower;
use Illuminate\Http\Request;

class MySectionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $sections = config('boe.sections');
        $data = [
            'user' => $user,
            'sections' => $sections,
        ];
        return view('my_section.index', $data);
    }

    public function update(Request $request, User $user)
    {
        $att['my_section_id'] = $request->input('my_section_id');

        $user->update($att);

        return redirect()->route('posts.reviewing');
    }

    public function admin()
    {
        if (auth()->user()->group_id == 8) {
            $section_id = auth()->user()->section_id;
        } else {
            //取得他管理的科室
            $user_power = UserPower::where('user_id', auth()->user()->id)
                ->where('power_type', 'A')
                ->first();
            $section_id = $user_power->section_id;
        }
        //已是本科室成員
        $users1 = User::where('section_id', $section_id)
            ->orderBy('disable')
            ->get();

        //選填本科室者
        $users2 = User::where('my_section_id', $section_id)
            ->get();

        $sections = config('boe.sections');

        $a_admins = UserPower::where('section_id', auth()->user()->section_id)
            ->where('power_type', 'A')
            ->get();

        $data = [
            'users1' => $users1,
            'users2' => $users2,
            'sections' => $sections,
            'a_admins' => $a_admins,
            'section_id' => $section_id,
        ];
        return view('my_section.admin', $data);
    }

    public function agree(User $user)
    {
        $att['my_section_id'] = null;
        $att['section_id'] = $user->my_section_id;
        $user->update($att);

        //log
        $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 同意了使用者 id：" . $user->id . " " . $user->name . " 屬於科室：" . $user->my_section_id;
        logging('2', $event, get_ip());

        return redirect()->route('my_section.admin');
    }

    public function disagree(User $user)
    {
        $att['my_section_id'] = null;

        $user->update($att);
        //log
        $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 不同意了使用者 id：" . $user->id . " " . $user->name . " 屬於科室：" . $user->my_section_id;
        logging('2', $event, get_ip());
        return redirect()->route('my_section.admin');
    }

    public function remove(User $user)
    {
        $old_section_id = $user->section_id;
        $att['section_id'] = null;

        $user->update($att);

        //log
        $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 移除了使用者 id：" . $user->id . " " . $user->name . " 在科室：" . $old_section_id;
        logging('2', $event, get_ip());

        return redirect()->route('my_section.admin');
    }

    public function power()
    {
        $users = User::where('group_id', 2)
            ->where('disable', null)
            ->orderBy('section_id')
            ->get();
        $sections = config('boe.sections');
        foreach ($users as $user) {
            $s = ($user->section_id) ? $sections[$user->section_id] . "--" : "";
            $select_users[$user->id] = $s . $user->name . "(" . $user->username . ")";
        }

        $data = [
            'sections' => $sections,
            'select_users' => $select_users,
            'sections' => $sections,
        ];
        return view('my_section.power', $data);
    }

    public function power_update1(Request $request)
    {
        //UserPower::where('section_id',$request->input('section_id'))
        //    ->where('power_type','A')
        //    ->delete();

        $att['section_id'] = $request->input('section_id');
        $att['user_id'] = $request->input('user_id');
        $att['power_type'] = "A";
        $user_power = UserPower::create($att);

        //log
        $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 增加了使用者 id：" . $user_power->user_id . " " . $user_power->user->name . " 有科室：" . $att['section_id'] . " 的A權限";
        logging('2', $event, get_ip());


        //加入科室
        /**
        $user = User::where('id', $request->input('user_id'))
            ->first();
        $att2['section_id'] = $request->input('section_id');
        $user->update($att2);
         * */

        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function power_update2(Request $request)
    {
        $user = User::where('username', $request->input('username'))
            ->first();

        if ($user) {
            $att['section_id'] = $request->input('section_id');
            $att['user_id'] = $user->id;
            $att['power_type'] = "A";
            $user_power = UserPower::create($att);

            //log
            $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 增加了使用者 id：" . $user_power->user_id . " " . $user_power->user->name . " 有科室：" . $att['section_id'] . " 的A權限";
            logging('2', $event, get_ip());

            //加入科室
            /**
            $att2['section_id'] = $request->input('section_id');
            $user->update($att2);
             * */
        } else {
            return back()->withErrors(['errors' => ['無此帳號！']]);;
        }
        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function power_remove($id)
    {
        $user_power = UserPower::where('id', $id)->first();
        //log
        $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 移除了使用者 id：" . $user_power->user_id . " " . $user_power->user->name . " 在科室的管理權：" . $user_power->section_id;
        logging('2', $event, get_ip());

        $user_power->delete();

        return redirect()->route('my_section.admin');
    }

    public function member()
    {
        $users = User::where('group_id', 2)
            ->where('disable', null)
            ->orderBy('section_id')
            ->get();
        $sections = config('boe.sections');
        foreach ($users as $user) {
            $s = ($user->section_id) ? $sections[$user->section_id] . "--" : "";
            $select_users[$user->id] = $s . $user->name . "(" . $user->username . ")";
        }

        $data = [
            'users' => $users,
            'sections' => $sections,
            'select_users' => $select_users,
            'sections' => $sections,
        ];
        return view('my_section.member', $data);
    }

    public function member_update(Request $request)
    {
        $user = User::where('id', $request->input('user_id'))
            ->first();

        $att['section_id'] = $request->input('section_id');
        //echo $att['section_id'];

        $user->update($att);

        //log
        $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 增加了使用者 id：" . $user->id . " " . $user->name . " 屬於科室：" . $att['section_id'];
        logging('2', $event, get_ip());

        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function member_update2(Request $request)
    {
        $user = User::where('username', $request->input('username'))
            ->first();

        if ($user) {
            $att['section_id'] = $request->input('section_id');
            $user->update($att);

            //log
            $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 增加了使用者 id：" . $user->id . " " . $user->name . " 屬於科室：" . $att['section_id'];
            logging('2', $event, get_ip());
        } else {
            return back()->withErrors(['errors' => ['無此帳號！']]);;
        }


        echo "<body onload='opener.location.reload();window.close();'>";
    }
}
