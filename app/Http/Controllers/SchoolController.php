<?php

namespace App\Http\Controllers;

use App\Models\SchoolIntroduction;
use App\Models\User;
use App\Models\UserPower;
use Illuminate\Http\Request;
use Purifier;
class SchoolController extends Controller
{
    public function index()
    {
        //信義國中小
        if(auth()->user()->code ==="074774" or auth()->user()->code ==="074541"){
            $users = User::where('code','074774')
                ->orWhere('code','074541')
                ->orderBy('disable')
                ->get();
        //原斗國中小
        }elseif(auth()->user()->code ==="074745" or auth()->user()->code ==="074537"){
            $users = User::where('code','074745')
                ->orWhere('code','074537')
                ->orderBy('disable')
                ->get();
        //民權國中小
        }elseif(auth()->user()->code ==="074760" or auth()->user()->code ==="074543"){
            $users = User::where('code','074760')
                ->orWhere('code','074543')
                ->orderBy('disable')
                ->get();
        //鹿江國中小
        }elseif(auth()->user()->code ==="074542" or auth()->user()->code ==="074778"){
            $users = User::where('code','074542')
                ->orWhere('code','074778')
                ->orderBy('disable')
                ->get();
        }else{
            $users = User::where('code',auth()->user()->code)
                ->orderBy('disable')
                ->get();
        }

        $user_not_data = [];

        //信義國中小
        if(auth()->user()->code ==="074774" or auth()->user()->code ==="074541"){
            $user_powers = UserPower::where('section_id','074774')
                ->orWhere('section_id','074541')
                ->get();
            //原斗國中小
        }elseif(auth()->user()->code ==="074745" or auth()->user()->code ==="074537"){
            $user_powers = UserPower::where('section_id','074745')
                ->orWhere('section_id','074537')
                ->get();
            //民權國中小
        }elseif(auth()->user()->code ==="074760" or auth()->user()->code ==="074543"){
            $user_powers = UserPower::where('section_id','074760')
                ->orWhere('section_id','074543')
                ->get();
        //鹿江國中小
        }elseif(auth()->user()->code ==="074542" or auth()->user()->code ==="074778"){
            $user_powers = UserPower::where('section_id','074542')
                ->orWhere('section_id','074778')
                ->get();
        }else{
            $user_powers = UserPower::where('section_id',auth()->user()->code)
                ->get();
        }

        foreach($user_powers as $user_power){
            if($user_power->user->code != auth()->user()->code){
                //信義國中小視為同校
                if(auth()->user()->code ==="074774" and $user_power->user->code=="074541"){
                    continue;
                }
                if(auth()->user()->code ==="074541" and $user_power->user->code=="074774"){
                    continue;
                }

                //原斗國中小視為同校
                if(auth()->user()->code ==="074745" and $user_power->user->code=="074537"){
                    continue;
                }
                if(auth()->user()->code ==="074537" and $user_power->user->code=="074745"){
                    continue;
                }

                //民權國中小視為同校
                if(auth()->user()->code ==="074760" and $user_power->user->code=="074543"){
                    continue;
                }
                if(auth()->user()->code ==="074543" and $user_power->user->code=="074760"){
                    continue;
                }

                //鹿江國中小視為同校
                if(auth()->user()->code ==="074542" and $user_power->user->code=="074778"){
                    continue;
                }
                if(auth()->user()->code ==="074778" and $user_power->user->code=="074542"){
                    continue;
                }
                $user_not_data[$user_power->user->id]['name'] = $user_power->user->name;
                $user_not_data[$user_power->user->id]['username'] = $user_power->user->username;
                $user_not_data[$user_power->user->id]['openid'] = $user_power->user->openid;
                $user_not_data[$user_power->user->id]['school'] = $user_power->user->school;
                $user_not_data[$user_power->user->id]['title'] = $user_power->user->title;
            }
        }

        $data = [
            'users'=>$users,
            'user_not_data'=>$user_not_data,
        ];
        return view('schools.index',$data);
    }

    public function list()
    {
        //信義國中小
        if(auth()->user()->code ==="074774" or auth()->user()->code ==="074541"){
            $user_powers = UserPower::where('section_id','074774')
                ->orWhere('section_id','074541')
                ->get();
            //原斗國中小
        }elseif(auth()->user()->code ==="074745" or auth()->user()->code ==="074537"){
            $user_powers = UserPower::where('section_id','074745')
                ->orWhere('section_id','074537')
                ->get();
            //民權國中小
        }elseif(auth()->user()->code ==="074760" or auth()->user()->code ==="074543") {
            $user_powers = UserPower::where('section_id', '074760')
                ->orWhere('section_id', '074543')
                ->get();
        //鹿江
        }elseif(auth()->user()->code ==="074542" or auth()->user()->code ==="074778"){
            $user_powers = UserPower::where('section_id', '074542')
                ->orWhere('section_id', '074778')
                ->get();
        }else{
            $user_powers = UserPower::where('section_id',auth()->user()->code)
                ->get();
        }


        $school_powers = config('boe.school_powers');
        $data = [
            'user_powers'=>$user_powers,
            'school_powers'=>$school_powers,
        ];
        return view('schools.list',$data);
    }

    public function power_remove($id)
    {
        UserPower::where('id',$id)
            ->delete();
        return redirect()->route('school_acc.list');
    }

    public function edit(User $user)
    {
        $data = [
            'user'=>$user,
        ];
        return view('schools.edit',$data);
    }

    public function other(Request $request)
    {
        $user = User::where('username',$request->input('username'))->first();
        if(empty($user)){
            return back()->withErrors(['errors'=>['無此帳號！']]);;
        }
        $data = [
            'user'=>$user,
        ];
        return view('schools.edit_other',$data);
    }

    public function store_other(Request $request)
    {
        if($request->input('a_user')=="on"){
            $user_power = UserPower::where('user_id',$request->input('user_id'))
                ->where('section_id',auth()->user()->code)
                ->where('power_type','A')
                ->first();
            if(!$user_power){
                $att['section_id'] = auth()->user()->code;
                $att['user_id'] = $request->input('user_id');
                $att['power_type'] = "A";
                UserPower::create($att);
            }
        }else{
            $user_power = UserPower::where('user_id',$request->input('user_id'))
                ->where('section_id',auth()->user()->code)
                ->where('power_type','A')
                ->delete();
        }

        if($request->input('b_user')=="on"){
            $user_power = UserPower::where('user_id',$request->input('user_id'))
                ->where('section_id',auth()->user()->code)
                ->where('power_type','B')
                ->first();
            if(!$user_power){
                $att2['section_id'] = auth()->user()->code;
                $att2['user_id'] = $request->input('user_id');
                $att2['power_type'] = "B";
                UserPower::create($att2);
            }
        }else{
            $user_power = UserPower::where('user_id',$request->input('user_id'))
                ->where('section_id',auth()->user()->code)
                ->where('power_type','B')
                ->delete();
        }

        return redirect()->route('school_acc.index');
    }

    public function update(Request $request,User $user)
    {
        if($request->input('a_user')=="on"){
            $user_power = UserPower::where('user_id',$user->id)
                ->where('section_id',auth()->user()->code)
                ->where('power_type','A')
                ->first();
            if(!$user_power){
                $att['section_id'] = auth()->user()->code;
                $att['user_id'] = $user->id;
                $att['power_type'] = "A";
                UserPower::create($att);
            }
        }else{
            $user_power = UserPower::where('user_id',$user->id)
                ->where('section_id',auth()->user()->code)
                ->where('power_type','A')
                ->delete();
        }

        if($request->input('b_user')=="on"){
            $user_power = UserPower::where('user_id',$user->id)
                ->where('section_id',auth()->user()->code)
                ->where('power_type','B')
                ->first();
            if(!$user_power){
                $att2['section_id'] = auth()->user()->code;
                $att2['user_id'] = $user->id;
                $att2['power_type'] = "B";
                UserPower::create($att2);
            }
        }else{
            $user_power = UserPower::where('user_id',$user->id)
                ->where('section_id',auth()->user()->code)
                ->where('power_type','B')
                ->delete();
        }

        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function destroy(User $user)
    {
        $att['disable'] = 1;
        $att['disabled_at'] = now();
        $user->update($att);

        UserPower::where('user_id',$user->id)->delete();

        return redirect()->route('school_acc.index');
    }

    public function reback(User $user)
    {
        $att['disable'] = null;
        $att['disabled_at'] = null;
        $user->update($att);

        return redirect()->route('school_acc.index');
    }

    public function school_map()
    {
        return view('schools.school_map');
    }
    public function school_list()
    {        
        $town_ships = [
            '500'=>'彰化市',
            '502'=>'芬園鄉',
            '503'=>'花壇鄉',
            '504'=>'秀水鄉',
            '505'=>'鹿港鎮',
            '506'=>'福興鄉',
            '507'=>'線西鄉',
            '508'=>'和美鎮',
            '509'=>'伸港鄉',
            '510'=>'員林市',
            '511'=>'社頭鄉',
            '512'=>'永靖鄉',
            '513'=>'埔心鄉',
            '514'=>'溪湖鎮',
            '515'=>'大村鄉',
            '516'=>'埔鹽鄉',
            '520'=>'田中鎮',
            '521'=>'北斗鎮',
            '522'=>'田尾鄉',
            '523'=>'埤頭鄉',
            '524'=>'溪州鄉',
            '525'=>'竹塘鄉',
            '526'=>'二林鎮',
            '527'=>'大城鄉',
            '528'=>'芳苑鄉',
            '530'=>'二水鄉',
        ];

        foreach($town_ships as $k => $v){
            $ships_town["'".$v."'"] = $k;
        }

        $file = fopen(asset('school.csv'),"r");

        $row = 1 ;
        while(! feof($file))
        {   
            if($row>1){
                $line = fgetcsv($file);
                $all_school[$ships_town["'".$line[1]."'"]][$line[0]]['school'] = $line[2];                
                $all_school[$ships_town["'".$line[1]."'"]][$line[0]]['website'] = $line[3];
                $all_school[$ships_town["'".$line[1]."'"]][$line[0]]['type'] = $line[4];
                $row++;            
            }else{
                $line = fgetcsv($file);
                $row++;
            }    
        }

        $data = [
            'town_ships'=>$town_ships,
            'ships_town'=>$ships_town,
            'all_school'=>$all_school,
        ];
        return view('schools.school_list',$data);
    }

    public function school_show($code_no){
        $school_introduction = SchoolIntroduction::where('code_no',$code_no)->first();
        if(!empty($school_introduction)){
            $introduction1 = $school_introduction->introduction1;
            $introduction2 = $school_introduction->introduction2;
            $website = str_replace("https://","",$school_introduction->website);
            $website = str_replace("http://","",$website);
            $facebook = str_replace("https://","",$school_introduction->facebook);
            $facebook = str_replace("http://","",$facebook);
            $wiki = str_replace("https://","",$school_introduction->wiki);
            $wiki = str_replace("http://","",$wiki);
        }else{
            $introduction1 = null;
            $introduction2 = null;
            $website = null;
            $facebook = null;
            $wiki = null;
        }

        $file = fopen(asset('school.csv'),"r");

        $row = 1 ;
        while(! feof($file))
        {   
            if($row>1){
                $line = fgetcsv($file);                      
                $school_web[$line[0]]['website'] = $line[3];
                $school_web[$line[0]]['school'] = $line[2];
                $row++;            
            }else{
                $line = fgetcsv($file);
                $row++;
            }    
        }

        //dd($school_web);

        $data = [
            'school_introduction'=> $school_introduction,
            'introduction1'=>$introduction1,
            'introduction2'=>$introduction2,
            'website'=>$website,
            'facebook'=>$facebook,
            'wiki'=>$wiki,
            'school_web'=>$school_web,
            'code_no'=>$code_no,
        ];
        return view('schools.school_show',$data);
    }

    public function school_introduction()
    {
        $school_introduction = SchoolIntroduction::where('code_no',auth()->user()->code)->first();

        if(!empty($school_introduction)){
            $introduction1 = $school_introduction->introduction1;
            $introduction2 = $school_introduction->introduction2;
            $website = $school_introduction->website;
            $facebook = $school_introduction->facebook;
            $wiki = $school_introduction->wiki;
        }else{
            $introduction1 = null;
            $introduction2 = null;
            $website = null;
            $facebook = null;
            $wiki = null;
        }

        $data = [
            'school_introduction'=> $school_introduction,
            'introduction1'=>$introduction1,
            'introduction2'=>$introduction2,
            'website'=>$website,
            'facebook'=>$facebook,
            'wiki'=>$wiki,
        ];
        return view('schools.introduction',$data);
    }

    public function school_introduction_store(Request $request)
    {
        $att['code_no'] = auth()->user()->code;
        $att['user_id'] = auth()->user()->id;
        $att['introduction1'] =    Purifier::clean($request->input('introduction1'), array('AutoFormat.AutoParagraph'=>false));
        $att['introduction2'] =   Purifier::clean($request->input('introduction2'), array('AutoFormat.AutoParagraph'=>false));
        $att['website'] =  Purifier::clean($request->input('website'), array('AutoFormat.AutoParagraph'=>false));
        $att['facebook'] = Purifier::clean($request->input('facebook'), array('AutoFormat.AutoParagraph'=>false));
        $att['wiki'] =  Purifier::clean($request->input('wiki'), array('AutoFormat.AutoParagraph'=>false));
     
        if($request->hasFile('pic1')){
            $pic1 = $request->file('pic1');
            //驗證是不是真實圖片檔
            if(!getimagesize($pic1)) return redirect()->route('index');
            if(check_php($pic1)) return redirect()->route('index');

            $att['pic1'] = $pic1->getClientOriginalName();
        }
        if($request->hasFile('pic2')){
            $pic2 = $request->file('pic2');
            //驗證是不是真實圖片檔
            if(!getimagesize($pic2)) return redirect()->route('index');
            if(check_php($pic2)) return redirect()->route('index');

            $att['pic2'] = $pic2->getClientOriginalName();
        }
        $check = SchoolIntroduction::where('code_no',$att['code_no'])->first();
        if(empty($check)){        
            $school_introduction = SchoolIntroduction::create($att);        
        }else{
            if(file_exists(storage_path('app/public/school_introductions/'. auth()->user()->code.'/'.$check->pic1)) and isset($att['pic1']) and !empty($check->pic1)){
                unlink(storage_path('app/public/school_introductions/'. auth()->user()->code.'/'.$check->pic1));                
            }
            if(file_exists(storage_path('app/public/school_introductions/'. auth()->user()->code.'/'.$check->pic2)) and isset($att['pic2']) and !empty($check->pic2)){
                unlink(storage_path('app/public/school_introductions/'. auth()->user()->code.'/'.$check->pic2));
            }
            $check->update($att);
            
        }

        if(isset($att['pic1'])){
            $pic1->storeAs('public/school_introductions/'. auth()->user()->code, $att['pic1']);
        }
        if(isset($att['pic2'])){
            $pic2->storeAs('public/school_introductions/'. auth()->user()->code, $att['pic2']);
        }

        return redirect()->route('school_introduction.index');
    
    }
}
