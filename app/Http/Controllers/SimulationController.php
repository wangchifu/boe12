<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SimulationController extends Controller
{
    public function index()
    {
        $users = User::orderBy('disable')
            ->orderBy('group_id')
            ->orderBy('section_id')
            ->simplePaginate('20'); 
        $sections = config('boe.sections');
        $groups = config('boe.groups');
        $other_schools = config('boe.other_schools');
        $data = [
            'users'=>$users,
            'sections'=>$sections,
            'groups'=>$groups,
            'other_schools'=>$other_schools,
        ];
        return view('sims.index',$data);
    }

    public function group($group_id)
    {

        if($group_id=="1"){
            $users = User::where('group_id',"1")
                ->orderBy('disable')
                ->orderBy('group_id')
                ->orderBy('section_id')
                ->simplePaginate('20');
        }
        if($group_id=="2"){
            $users = User::where('group_id',"2")
                ->orWhere('group_id','8')
                ->orderBy('disable')
                ->orderBy('group_id')
                ->orderBy('section_id')
                ->simplePaginate('20');
        }
        if($group_id=="3"){
            $users = User::where('group_id',"9")
                ->orWhere('admin','1')
                ->orderBy('disable')
                ->orderBy('group_id')
                ->orderBy('section_id')
                ->simplePaginate('20');
        }

        $sections = config('boe.sections');
        $groups = config('boe.groups');
        $other_schools = config('boe.other_schools');
        $data = [
            'users'=>$users,
            'sections'=>$sections,
            'groups'=>$groups,
            'other_schools'=>$other_schools,
            'group_id'=>$group_id,
        ];
        return view('sims.group',$data);
    }

    public function impersonate(User $user)
    {
        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 模擬了使用者 id：".$user->id." 名稱：".$user->name;
        logging('2',$event,get_ip());

        Auth::user()->impersonate($user);
        $user_power = UserPower::where('user_id', auth()->user()->id)
        ->where('power_type', 'A')
        ->first();
        session(['user_power' => $user_power]);
        return redirect()->route('index');

    }

    public function impersonate_leave()
    {
        Auth::user()->leaveImpersonation();
        $user_power = UserPower::where('user_id', auth()->user()->id)
        ->where('power_type', 'A')
        ->first();
        session(['user_power' => $user_power]);
        return redirect()->route('index');
    }

    public function search(Request $request)
    {
        $want = $request->input('want');

        $sections = config('boe.sections');
        $groups = config('boe.groups');

        $show_s = array_flip($sections);
        if(isset($show_s[$want])){
            $s = $show_s[$want];
        }else{
            $s = 0;
        }

        $other_schools = config('boe.other_schools');
        $show_o = array_flip($other_schools);
        if(isset($show_o[$want])){
            $o = $show_o[$want];
        }else{
            $o = "找不到";
        }

        $users = User::where('username','like','%'.$want.'%')
            ->orWhere('name','like','%'.$want.'%')
            ->orWhere('school','like','%'.$want.'%')
            ->orWhere('title','like','%'.$want.'%')
            ->orWhere('section_id','like','%'.$s.'%')
            ->orWhere('other_code','like','%'.$o.'%')
            ->paginate('20');

        $data = [
            'users'=>$users,
            'sections'=>$sections,
            'groups'=>$groups,
            'want'=>$want,
            'other_schools'=>$other_schools,
        ];
        return view('sims.search',$data);
    }
}
