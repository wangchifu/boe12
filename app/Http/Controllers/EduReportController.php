<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Report;
use App\Models\ReportSchool;
use App\Models\School;
use App\Models\UserPower;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use Purifier;

class EduReportController extends Controller
{
    public function index()
    {
        $reports = Report::where('user_id',auth()->user()->id)
            ->where('situation','<>',3)
            ->orderBy('id','DESC')
            ->paginate(10);
        $situation = config('boe.situation');
        $sections = config('boe.sections');
        $data = [
            'reports'=>$reports,
            'situation'=>$situation,
            'sections'=>$sections,
        ];
        return view('reports.edu.index',$data);
    }

    public function passing()
    {
        $reports = Report::where('user_id',auth()->user()->id)
            ->where(function($q){
                $q->where('situation','3')
                    ->orWhere('situation','4');
            })
            ->orderBy('id','DESC')
            ->paginate(10);
        $situation = config('boe.situation');
        $sections = config('boe.sections');
        $data = [
            'reports'=>$reports,
            'situation'=>$situation,
            'sections'=>$sections,
        ];
        return view('reports.edu.passing',$data);
    }

    public function create()
    {
        $sections = config('boe.sections');
        $data = [
            'select_school'=>'',
            'sections'=>$sections,
        ];
        return view('reports.edu.create',$data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'die_date' => 'required',
            'die_date' => 'required|date_format:Y-m-d',
            'files.*'=>'nullable|max:10240',
            'title.*'=>'required',
            'sel_school'=>'required',
        ]);

        $att['user_id'] = auth()->user()->id;
        $att['name'] = $request->input('name');
        $att['die_date'] = $request->input('die_date');
        $att['content'] = Purifier::clean($request->input('content'), array('AutoFormat.AutoParagraph'=>false));
        if($request->input('form_action')=="送出審核不再修改"){
            $att['situation'] = "1";
        }elseif($request->input('form_action')=="暫存"){
            $att['situation'] = "-1";
        }
        $att['section_id'] = auth()->user()->section_id;

        // 勾選的學校使用 5 個 BigInt 欄位儲存
        if(!empty($request->input('sel_school'))){
            $school_set=checkbox_val($request->input('sel_school'));
            foreach ($school_set as $key => $value) {
                $att['school_set_'.$key] = $value;
            }
        }

        //檢查檔案
        $allowed_extensions = ["png", "jpg", "pdf","PDF","JPG","odt","ODT","csv","txt","zip","jpeg","ods","ODS"];
        $report = Report::create($att);

        //公務電話
        $user_att['telephone'] = $request->input('telephone');
        auth()->user()->update($user_att);

        //處理檔案上傳
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            foreach($files as $file){
                $info = [
                    'mime-type' => $file->getMimeType(),
                    'original_filename' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                    //'size' => $file->getClientSize(),
                ];
                if ( $info['extension'] && !in_array($info['extension'],$allowed_extensions)) {
                    continue;
                }
                $file->storeAs('public/report_files/'.$report->id, $info['original_filename']);
            }
        }


        foreach($request->input('title') as $k=>$v){
            $att2['title'] = Purifier::clean($v, array('AutoFormat.AutoParagraph'=>false));
            $type = $request->input('type');
            $att2['type'] = $type[$k];
            if($att2['type']=="radio" or $att2['type'] == "checkbox"){
                $options = serialize(Purifier::clean($request->input('option'.$k), array('AutoFormat.AutoParagraph'=>false)));
            }elseif($att2['type']=="text" or $att2['type']=="num"){
                $options = null;
            }
            $att2['options'] = $options;

            $att2['report_id'] = $report->id;
            $att2['show'] = 1;
            Question::create($att2);
        }


        return redirect()->route('edu_report.index');
    }

    public function date_late(Report $report)
    {

        $data = [
            'report'=>$report,
        ];
        return view('reports.edu.date_late',$data);
    }

    public function save_date_late(Request $request,Report $report)
    {
        if($report->user_id == auth()->user()->id) {
            $report->update($request->all());
        }
        $att1['situation'] = null;
        $att1['review_user_id'] = null;
        ReportSchool::where('report_id',$report->id)->where('situation',5)->update($att1);

        $att2['situation'] = 1;
        $att2['review_user_id'] = null;
        ReportSchool::where('report_id',$report->id)->where('situation',0)->update($att2);

        $att3['situation'] = 1;
        $att3['review_user_id'] = null;
        ReportSchool::where('report_id',$report->id)->where('situation',2)->update($att3);

        $att4['situation'] = 1;
        $att4['review_user_id'] = null;
        ReportSchool::where('report_id',$report->id)->where('situation',4)->update($att4);

        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function resend(Report $report)
    {
        if($report->user_id == auth()->user()->id){
            $att['situation'] = 1;
            $report->update($att);
        }

        return redirect()->route('edu_report.index');
    }

    public function destroy(Request $request,Report $report)
    {
        if($report->situation==3 or $report->situation==4){
            dd('都審核或廢除了，還想偷改？');
        }
        if($report->user_id == auth()->user()->id){
            Question::where('report_id',$report->id)->delete();
            $report->delete();
        }
        return redirect()->route('edu_report.index');
    }

    /**
    public function question_destroy(Question $question)
    {
        if($question->report->user_id == auth()->user()->id or check_a_user(auth()->user()->id)){
            $question->delete();
        }
        return redirect()->route('edu_report.show',$question->report->id);
    }


    public function school_destroy(Report $report,$school_id)
    {
        if($report->user_id == auth()->user()->id or check_a_user(auth()->user()->id)){
            $select_school = checkbox_str_num(array($report->school_set_0, $report->school_set_1, $report->school_set_2, $report->school_set_3, $report->school_set_4));
            $select_school .= ",";
            $select_school = str_replace($school_id.",","",$select_school);

            $att['school_set_0'] = null;
            $att['school_set_1'] = null;
            $att['school_set_2'] = null;
            $att['school_set_3'] = null;
            $att['school_set_4'] = null;

            $report->update($att);

            $school_set=checkbox_val(explode(',',$select_school));
            foreach ($school_set as $key => $value) {
                $att2['school_set_'.$key] = $value;
            }

            $report->update($att2);

        }
        return redirect()->route('edu_report.show',$report->id);
    }
     *
     * */

    public function show(Report $report)
    {
        //利用checkbox_str_num將編碼過的所選學校轉成字串
        $old_schools = checkbox_str_num(array($report->school_set_0, $report->school_set_1, $report->school_set_2, $report->school_set_3, $report->school_set_4));


        $select_school = explode(", ", $old_schools);

        $schools = School::whereIn('id', $select_school)->get();

        $school_select = School::orderBy('code_no')->get();

        $files = get_files(storage_path('app/public/report_files/' . $report->id));

        $data = [
            'report'=>$report,
            'schools'=>$schools,
            'school_select'=>$school_select,
            'old_schools'=>$old_schools,
            'files'=>$files,
        ];

        return view('reports.edu.show',$data);
    }

    public function download($id,$filename)
    {
        $file = storage_path('app/public/report_files/' . $id . '/' . $filename);
        return response()->download($file);
    }

    public function delete_file($id,$filename)
    {
        $file = storage_path('app/public/report_files/' . $id . '/' . $filename);
        if(file_exists($file)){
            unlink($file);
        }
        return redirect()->route('edu_report.edit',$id);
    }

    public function edit(Report $report)
    {
        if($report->situation==3 or $report->situation==4){
            dd('都審核或廢除了，還想偷改？');
        }
        $select_school = checkbox_str_num(array($report->school_set_0, $report->school_set_1, $report->school_set_2, $report->school_set_3, $report->school_set_4));
        $files = get_files(storage_path('app/public/report_files/' . $report->id));
        $data = [
            'report'=>$report,
            'select_school'=>$select_school,
            'files'=>$files,
        ];

        return view('reports.edu.edit',$data);
    }

    public function copy(Report $report)
    {
        $select_school = checkbox_str_num(array($report->school_set_0, $report->school_set_1, $report->school_set_2, $report->school_set_3, $report->school_set_4));
        $files = get_files(storage_path('app/public/report_files/' . $report->id));
        $data = [
            'report'=>$report,
            'select_school'=>$select_school,
            'files'=>$files,
        ];

        return view('reports.edu.copy',$data);
    }
/**
    public function add_one(Request $request)
    {
        $att['report_id'] = $request->input('report_id');
        $att['title'] = $request->input('title');

        $report = Report::where('id',$att['report_id'])->first();
        if($report->user_id == auth()->user()->id or check_a_user(auth()->user()->id)) {
            Question::create($att);
        }

        return redirect()->route('edu_report.show',$att['report_id']);
    }

    public function add_one_school(Request $request)
    {
        $report = Report::where('id', $request->input('report_id'))->first();

        if($report->user_id == auth()->user()->id or check_a_user(auth()->user()->id)) {
            $old_schools = $request->input('old_schools');
            $select_school = $old_schools . ',' . $request->input('new_school');

            $sel_school = explode(',', $select_school);

            $att['school_set_0'] = null;
            $att['school_set_1'] = null;
            $att['school_set_2'] = null;
            $att['school_set_3'] = null;
            $att['school_set_4'] = null;

            $report->update($att);

            $school_set = checkbox_val($sel_school);
            foreach ($school_set as $key => $value) {
                $att2['school_set_' . $key] = $value;
            }

            $report->update($att2);
        }

        return redirect()->route('edu_report.show',$report->id);
    }
 * **/

    public function update(Request $request,Report $report)
    {
        //dd($request->all());

        if($report->user_id != auth()->user()->id){
            return back();
        }
        if($report->user_id == auth()->user()->id or check_a_user(auth()->user()->section_id,auth()->user()->id)){
            $att['name'] = $request->input('name');
            $att['content'] = $request->input('content');
            $att['die_date'] = $request->input('die_date');

            if($request->input('form_action')=="送出審核不再修改"){
                $att['situation'] = "1";
            }elseif($request->input('form_action')=="暫存"){
                $att['situation'] = "-1";
            }
            // 勾選的學校使用 5 個 BigInt 欄位儲存
            if(!empty($request->input('sel_school'))){
                $school_set=checkbox_val($request->input('sel_school'));
                foreach ($school_set as $key => $value) {
                    $att['school_set_'.$key] = $value;
                }
            }

   
      
            $att['content'] = Purifier::clean($request->input('content'), array('AutoFormat.AutoParagraph'=>false));
            $att['name'] = Purifier::clean($request->input('name'), array('AutoFormat.AutoParagraph'=>false));

            $report->update($att);

            //處理檔案上傳
            $allowed_extensions = ["png", "jpg", "pdf","PDF","JPG","odt","ODT","csv","txt","zip","jpeg","ods","ODS"];
            if ($request->hasFile('files')) {
                $files = $request->file('files');
                foreach($files as $file){
                    $info = [
                        'mime-type' => $file->getMimeType(),
                        'original_filename' => $file->getClientOriginalName(),
                        'extension' => $file->getClientOriginalExtension(),
                        //'size' => $file->getClientSize(),
                    ];
                    if ( $info['extension'] && !in_array($info['extension'],$allowed_extensions)) {
                      continue;
                    }
        
                    $file->storeAs('public/report_files/'.$report->id, $info['original_filename']);
                }
            }

            $a['show'] = 0;
            $qs = Question::where('report_id',$report->id)->get();
            foreach($qs as $q){
                $q->update($a);
            }

            foreach($request->input('title') as $k=>$v){
                $att2['title'] = $v;

                $type = $request->input('type');
                $att2['type'] = $type[$k];
                if($att2['type']=="radio" or $att2['type'] == "checkbox"){
                    $options = serialize($request->input('option'.$k));
                }elseif($att2['type']=="text" or $att2['type']=="num"){
                    $options = null;
                }
                $att2['options'] = $options;

                $att2['report_id'] = $report->id;
                $att2['show'] = 1;
                Question::create($att2);
            }


        }
        return redirect()->route('edu_report.index');
    }

    /**
    public function add_file(Request $request)
    {
        //處理檔案上傳
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            foreach($files as $file){
                $info = [
                    'mime-type' => $file->getMimeType(),
                    'original_filename' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                    'size' => $file->getClientSize(),
                ];
                $file->storeAs('public/report_files/'.$request->input('report_id'), $info['original_filename']);
            }
        }

        return redirect()->route('edu_report.show',$request->input('report_id'));
    }
    */

    public function del_file($id,$file)
    {
        $report = Report::find($id);
        if($report->user_id != auth()->user()->id){
            return back();
        }
        $file_path = storage_path('app/public/report_files/'.$id.'/'.$file);
        if(file_exists($file_path)){
            unlink($file_path);
        }
        return redirect()->route('edu_report.show',$id);
    }

    public function review()
    {
        //取得他管理的科室
        $user_power = UserPower::where('user_id',auth()->user()->id)
            ->where('power_type','A')
            ->first();

        $reports = Report::where('section_id',$user_power->section_id)
            ->where('situation','1')
            ->orwhere('situation', '=', '2')
            ->orderBy('id','DESC')
            ->paginate(15);

        $situation = config('boe.situation');
        $sections = config('boe.sections');
        $data = [
            'reports'=>$reports,
            'situation'=>$situation,
            'sections'=>$sections,
            'power_section_id'=>$user_power->section_id,
        ];
        return view('reports.edu.review',$data);
    }

    public function return(Request $request,Report $report)
    {
        $att['situation'] = 0;
        $report->update($att);
        return redirect()->route('posts.review');
    }

    public function approve(Report $report)
    {
        $att['situation'] = 3;
        $att['passed_at'] = substr(now(),0,19);
        $att['pass_user_id'] = auth()->user()->id;
        $report->update($att);

        //利用checkbox_str_num將編碼過的所選學校轉成字串
        $select_schools = checkbox_str_num(array($report->school_set_0, $report->school_set_1, $report->school_set_2, $report->school_set_3, $report->school_set_4));

        $select_schools = explode(", ", $select_schools);

        $schools = School::whereIn('id', $select_schools)->get();

        $postSchools = array();  //要先指定$postSchools是陣列，否則會出錯
        //利用multiple insert的方式寫入資料庫，節省寫入時間
        foreach ($schools as $school) {
            $postSchools[] = [
                'report_id' => $report->id,
                'code' => $school->code_no,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        ReportSchool::insert($postSchools);

        return redirect()->route('posts.review');
    }

    public function result(Report $report) 
    {
        //利用checkbox_str_num將編碼過的所選學校轉成字串
        $old_schools = checkbox_str_num(array($report->school_set_0, $report->school_set_1, $report->school_set_2, $report->school_set_3, $report->school_set_4));


        $select_school = explode(", ", $old_schools);

        $schools = School::whereIn('id', $select_school)->get();

        $answers = Answer::where('report_id',$report->id)
            ->get();
        $answer_data = [];
        foreach($answers as $answer){
            //$report_school = ReportSchool::find($answer->report_school_id);

            $answer_data[$answer->school_code][$answer->question_id] = $answer->answer;
        }

        $data = [
            'report'=>$report,
            'schools'=>$schools,
            'answer_data'=> $answer_data,
        ];
        return view('reports.edu.result',$data);
    }

    public function result2(Report $report)
    {
        //利用checkbox_str_num將編碼過的所選學校轉成字串
        $old_schools = checkbox_str_num(array($report->school_set_0, $report->school_set_1, $report->school_set_2, $report->school_set_3, $report->school_set_4));


        $select_school = explode(", ", $old_schools);

        $schools = School::whereIn('id', $select_school)->get();

        $answers = Answer::where('report_id',$report->id)
            ->get();
        $answer_data = [];
        foreach($answers as $answer){
            $report_school = ReportSchool::find($answer->report_school_id);

            $answer_data[$report_school->code][$answer->question_id] = $answer->answer;
        }

        $data = [
            'report'=>$report,
            'schools'=>$schools,
            'answer_data'=> $answer_data,
        ];
        return view('reports.edu.result2',$data);
    }

    public function export(Report $report)
    {
        //利用checkbox_str_num將編碼過的所選學校轉成字串
        $old_schools = checkbox_str_num(array($report->school_set_0, $report->school_set_1, $report->school_set_2, $report->school_set_3, $report->school_set_4));


        $select_school = explode(", ", $old_schools);

        $schools = School::whereIn('id', $select_school)->get();

        $answers = Answer::where('report_id',$report->id)
            ->get();
        $answer_data = [];
        foreach($answers as $answer){           
            $a = str_replace(',','，',$answer->answer);
            $answer_data[$answer->school_code][$answer->question_id] = $a;
        }


        $i=0;
        foreach($schools as $school){
            $rs = ReportSchool::where('code',$school->code_no)
            ->where('report_id',$report->id)
            ->first();
            if($rs->situation==4){
                $no = "-不填報";
            }else{
                $no = null;
            }

            if($rs->signed_user_id){
                $n = $rs->signed_user->name;
            }else{
                $n = "";
            }

            $data[$i] =[
                '學校名稱'=>$school->school_name.$no,
                '填報人'=>$n,
            ];


            $n=1;
            foreach($report->questions as $question){
                            //$report_school = ReportSchool::find($answer->report_school_id);
            
                $school_code = $school->code_no;
            //信義
                if($school_code=="074541074774"){
                    $school_code="074541";
                    if(!isset($answer_data[$school_code][$question->id])){
                        $school_code="074774";
                    }
                }
            //原斗
                if($school_code=="074537074745"){
                    $school_code="074537";
                    if(!isset($answer_data[$school_code][$question->id])){
                        $school_code="074745";
                    }
                }
            //鹿江
                if($school_code=="074542074778"){
                    $school_code="074542";
                    if(!isset($answer_data[$school_code][$question->id])){
                        $school_code="074778";
                    }
                }
            //民權
                if($school_code=="074543074760"){
                    $school_code="074760";
                    if(!isset($answer_data[$school_code][$question->id])){
                        $school_code="074543";
                    }
                }
                if(isset($answer_data[$school_code][$question->id])){

                    //$get_report_school = ReportSchool::where('code',$school->code_no)
                        //->where('report_id',$report->id)
                        //->first();

                    if($rs->situation===3) {
                        $data[$i]['送出時間'] = substr($rs->updated_at, 0, 16);
                        $data[$i]["(".$n.")".$question->title] = $answer_data[$school_code][$question->id];
                    }else{
                        $data[$i]['送出時間'] = "";
                        $data[$i]["(".$n.")".$question->title] = "";
                    }
                }else{
                    if($rs->situation===4) {
                        $data[$i]['送出時間'] = substr($rs->updated_at, 0, 16);
                        $data[$i]["(".$n.")".$question->title] = "不填報";
                    }else{
                        $data[$i]['送出時間'] = "";
                        $data[$i]["(".$n.")".$question->title] = "";
                    }
                }
                $n++;
            }
            $i++;
        }

        $list = collect($data);

        //return (new FastExcel($list))->download('Report'.$report->id.'.xlsx');
        return (new FastExcel($list))->download('Report'.$report->id.'.csv');
    }

    public function post(Request $request)
    {
        $report = Report::where('id',$request->input('report_id'))
            ->first();
        $schools = explode(',',$request->input('schools'));
        $sel_schools = School::whereIn('school_name',$schools)->get();
        foreach($sel_schools as $sel_school){
            $school_array[] = $sel_school->id;
        }
        $data = [
            'schools'=>$request->input('schools'),
            'report'=>$report,
            'school_array'=>$school_array,
        ];
        return view('reports.edu.post',$data);
    }

    public function set_back(ReportSchool $report_school)
    {
        $att['situation'] = 0;
        $att['review_user_id'] = auth()->user()->id;
        $report_school->update($att);
        return redirect()->route('edu_report.result',$report_school->report_id);
    }

    public function set_null(ReportSchool $report_school)
    {
        $att['situation'] = null;
        $att['review_user_id'] = auth()->user()->id;
        $report_school->update($att);
        return redirect()->route('edu_report.result',$report_school->report_id);
    }

    public function obsolete(Report $report)
    {
        if($report->user_id != auth()->user()->id){
            return back();
        }
        $att['situation'] = 4;
        $report->update($att);
        return redirect()->route('edu_report.passing');
    }

    //審查者列出所有的填報
    public function section_all()
    {
        $reports = Report::where('section_id',auth()->user()->section_id)
            ->where(function($q){
                $q->where('situation','3')
                    ->orWhere('situation','4');
            })
            ->orderBy('id','DESC')
            ->paginate(10);
        $situation = config('boe.situation');
        $sections = config('boe.sections');
        $data = [
            'reports'=>$reports,
            'situation'=>$situation,
            'sections'=>$sections,
        ];
        return view('reports.edu.section_all',$data);
    }
}
