<?php

namespace App\Http\Controllers;

use App\Models\Introduction;
use App\Models\SectionPage;
use App\Models\Upload;
use Illuminate\Http\Request;

class IntroductionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function organization()
    {
        $sections = config('boe.sections');
        $section_name = $sections[auth()->user()->section_id];
        $introduction = Introduction::where('section_id',auth()->user()->section_id)
            ->first();
        if(empty($introduction)){
            $content = "";
        }else{
            $content = $introduction->organization;
        }
        $section_pages = SectionPage::where('section_id',auth()->user()->section_id)->orderBy('order_by')->get();

        $data = [
            'section_name'=>$section_name,
            'section_id'=>auth()->user()->section_id,
            'content'=>$content,
            'section_pages'=>$section_pages,
        ];
        return view('introductions.organization',$data);
    }

    public function people()
    {
        $sections = config('boe.sections');
        $section_name = $sections[auth()->user()->section_id];
        $introduction = Introduction::where('section_id',auth()->user()->section_id)
            ->first();
        if(empty($introduction)){
            $content = "";
        }else{
            $content = $introduction->people;
        }

        $section_pages = SectionPage::where('section_id',auth()->user()->section_id)->orderBy('order_by')->get();

        $data = [
            'section_name'=>$section_name,
            'section_id'=>auth()->user()->section_id,
            'content'=>$content,
            'section_pages'=>$section_pages,
        ];
        return view('introductions.people',$data);
    }

    public function site()
    {
        $sections = config('boe.sections');
        $section_name = $sections[auth()->user()->section_id];
        $introduction = Introduction::where('section_id',auth()->user()->section_id)
            ->first();
        if(empty($introduction)){
            $content = "";
        }else{
            $content = $introduction->site;
        }

        $section_pages = SectionPage::where('section_id',auth()->user()->section_id)->orderBy('order_by')->get();

        $data = [
            'section_name'=>$section_name,
            'section_id'=>auth()->user()->section_id,
            'content'=>$content,
            'section_pages'=>$section_pages,
        ];
        return view('introductions.site',$data);
    }

    public function section_page_add()
    {
        $sections = config('boe.sections');
        $section_name = $sections[auth()->user()->section_id];
        $section_pages = SectionPage::where('section_id',auth()->user()->section_id)->orderBy('order_by')->get();
        $data = [
            'section_name'=>$section_name,
            'section_id'=>auth()->user()->section_id,
            'section_pages'=>$section_pages,
        ];
        return view('introductions.section_page_add',$data);
    }

    public function section_page_store(Request $request)
    {
        $att = $request->all();
        $section_page = SectionPage::create($att);
        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 新增了科室頁面 id： ".$section_page->id." 名稱：".$section_page->title;
        logging('5',$event,get_ip());
        return redirect()->route('introductions.section_page',$section_page->id);
    }

    public function section_page(SectionPage $section_page)
    {
        $sections = config('boe.sections');
        $section_name = $sections[auth()->user()->section_id];
        $section_pages = SectionPage::where('section_id',auth()->user()->section_id)->orderBy('order_by')->get();
        $data = [
            'section_name'=>$section_name,
            'section_pages'=>$section_pages,
            'section_page'=>$section_page,
        ];
        return view('introductions.section_page',$data);
    }

    public function section_page_del(SectionPage $section_page)
    {
        if($section_page->section_id != auth()->user()->section_id){
            return redirect()->back();
        }
        $section_page->delete();

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 刪除了科室頁面 id： ".$section_page->id." 名稱：".$section_page->title;
        logging('5',$event,get_ip());

        return redirect()->route('introductions.organization');
    }

    public function section_page_update(Request $request,SectionPage $section_page)
    {
        if($section_page->section_id != auth()->user()->section_id){
            return redirect()->back();
        }
        $att = $request->all();
        $section_page->update($att);

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 編輯了科室頁面 id： ".$section_page->id." 名稱：".$section_page->title;
        logging('5',$event,get_ip());

        return redirect()->route('introductions.section_page',$section_page->id);
    }

    public function upload($path=null)
    {
        $sections = config('boe.sections');
        $section_name = $sections[auth()->user()->section_id];        

        $folder_path[0] = '根目錄';

        $path_array = explode('&',$path);        
        $folder_id = end($path_array);
        
        if(empty($folder_id)) $folder_id=null;

        $i=1;
        
        foreach($path_array as $v){
            if($v != null){
                $check = Upload::where('id',$v)->first();
                $folder_path[$v] = $check->name;
            }
            if($i==1){
                $now_section = 0;
            }
            if($i==2){
                $now_section = $v;
            }
            $i++;
        }

        //非本科室不得進入
        $num = [
            '督學室'=>1,
            '學管科'=>2,
            '國教科'=>3,
            '社教科'=>4,
            '體健科'=>5,
            '學特科'=>6,
            '體設科'=>7,
            '幼教科'=>8,
            '縣網中心'=>9,
            '體發中心'=>7019,
        ];

        if($now_section != $num[$section_name] or $now_section==0){
            return back();
        }


        //列出目錄
        $folders = Upload::where('type','1')
            ->where('folder_id',$folder_id)
            ->orderBy('name')
            ->get();

        //列出檔案
        $files = Upload::where('type','2')
            ->where('folder_id',$folder_id)
            ->orderBy('name')
            ->get();

        //列出檔案
        $urls = Upload::where('type','like','3%')
            ->where('folder_id',$folder_id)
            ->orderBy('name')
            ->get();


        $data = [
            'section_name'=>$section_name,
            'section_id'=>auth()->user()->section_id,
            'path'=>$path,
            'folder_id'=>$folder_id,
            'folders'=>$folders,
            'folder_path'=>$folder_path,
            'files'=>$files,
            'urls'=>$urls,
        ];

        return view('introductions.upload',$data);
    }

    public function upload_edit(Upload $upload,$path)
    {
        $data = [
            'upload'=>$upload,
            'path'=>$path,
        ];
        return view('introductions.upload_edit',$data);
    }

    public function upload_store_name(Request $request)
    {
        $att['name'] = $request->input('name');
        $upload = Upload::find($request->input('id'));

        if($upload->type==1 or $upload->type==2){
            $old_name = "";
            $new_name = "";
            $path_array = explode('&',$request->input('path'));
            foreach($path_array as $v){
                if($v != 0){
                    $check = Upload::where('id',$v)->first();
                    if($v==$request->input('id')){
                        $old_name .= "/".$check->name;
                        $new_name .= "/".$request->input('name');
                    }else{
                        $old_name .= "/".$check->name;
                        $new_name .= "/".$check->name;
                    }

                }

            }
            $old_name = storage_path('app/public/open_files'.$old_name);
            $new_name = storage_path('app/public/open_files'.$new_name);
            if(file_exists($old_name)){
                rename($old_name,$new_name);
            }
        }

        $upload->update($att);

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 變更了檔案上傳 id：".$upload->id." 名稱：".$upload->name;
        logging('5',$event,get_ip());

        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function create_folder(Request $request)
    {
        $sections = config('boe.sections');
        $section_name = $sections[auth()->user()->section_id];

        $root = storage_path('app/public/open_files');

        //新增目錄
        $new_path = $root;

        foreach(explode('&',$request->input('path')) as $v){
            $check = Upload::where('id',$v)->first();
            if(!empty($v)) $new_path .= '/'.$check->name;
        }

        $new_path .= '/'.$request->input('name');

        $att['name'] = $request->input('name');
        $att['type'] = 1;//目錄
        $att['user_id'] = auth()->user()->id;
        $att['folder_id'] = $request->input('folder_id');


        if(!is_dir($new_path)){
            if(file_exists($new_path)){
                mkdir($new_path);
            }
            $upload = Upload::create($att);

            //log
            $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 新增了檔案上傳 id：".$upload->id." 名稱：".$upload->name;
            logging('5',$event,get_ip());

        }
        return redirect()->route('introductions.upload',$request->input('path'));
    }

    public function delete($path)
    {
        $path_array = explode('&',$path);
        $id = end($path_array);
        $check = Upload::where('id',$id)->first();

        $new_path = "";
        $remove = "open_files";

        foreach($path_array as $v){
            if(!empty($v) and $v != $id){
                $new_path .= '&'.$v;
            }
            if(!empty($v)){
                $f = Upload::where('id',$v)->first();
                $remove .= "/".$f->name;
            }
        }

        if($check->type == "1"){
            if(is_dir(storage_path('app/public/'.$remove))){
                rmdir(storage_path('app/public/'.$remove));
            }
        }elseif($check->type == "2"){
            if(file_exists(storage_path('app/public/'.$remove))){
                unlink(storage_path('app/public/'.$remove));
            }
        }

        $check->delete();

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 刪除了檔案上傳 id：".$check->id." 名稱：".$check->name;
        logging('5',$event,get_ip());

        return redirect()->route('introductions.upload',$new_path);
    }

    public function upload_file(Request $request)
    {        
        $request->validate([
                'files.*' => 'max:10240',
        ]);
        $root = storage_path('app/public/open_files');
        $p = 'public/open_files';
        if(!is_dir($root)){
            mkdir($root);
        }
        //新增目錄
        $new_path = $root;


        foreach(explode('&',$request->input('path')) as $v){
            $check = Upload::where('id',$v)->first();
            if(!empty($v)){
                $new_path .= '/'.$check->name;
                $p .= '/'.$check->name;
            }
        }



        //處理檔案上傳
        $allowed_extensions = ["txt","TXT","zip","ZIP","png","PNG","jpg","JPG","jpeg","JPEG","gif","GIF","pdf","PDF","odt","ODT","odt","ODT","ods","ODS","mp3","Mp3","mp4","MP4"];
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            foreach($files as $file){
                $info = [
                    'original_filename' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                ];
                if ( $info['extension'] && !in_array($info['extension'],$allowed_extensions)) {
                    return back()->withErrors(['errors'=>['不合規定的檔案類型']]);
                }

                if(check_php($file)) return back()->withErrors(['errors'=>['不合規定的檔案類型']]);

                $file->storeAs($p, $info['original_filename']);

                $att['name'] = $info['original_filename'];
                $att['type'] = 2;//檔案
                $att['user_id'] = auth()->user()->id;
                $att['folder_id'] = $request->input('folder_id');
                $upload = Upload::create($att);

                //log
                $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 新增了檔案上傳 id：".$upload->id." 名稱：".$upload->name;
                logging('5',$event,get_ip());
            }
        }



        return redirect()->route('introductions.upload',$request->input('path'));

    }

    public function create_url(Request $request)
    {
        $att['name'] = $request->input('name');
        $att['url'] = $request->input('url');
        $att['type'] = $request->input('type');//連結
        $att['user_id'] = auth()->user()->id;
        $att['folder_id'] = $request->input('folder_id');

        $upload = Upload::create($att);

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 新增了檔案上傳 id：".$upload->id." 名稱：".$upload->name;
        logging('5',$event,get_ip());

        return redirect()->route('introductions.upload',$request->input('path'));
    }

    public function download($path)
    {
        $path_array = explode('&',$path);
        $file_id = end($path_array);

        $file = "open_files";
        foreach($path_array as $v){
            if($v != $file_id and !empty($v)){
                $check = Upload::where('id',$v)->first();
                if(!empty($check)){
                    $file .= "&".$check->name;
                }else{
                    return back();
                }
            }
        }

        $upload = Upload::where('id',$file_id)->first();
        if($upload) {
            $file .= '&'.$upload->name;
            $file = str_replace('&','/',$file);
            $file = storage_path('app/public/'.'/'.$file);
            if(file_exists($file)) {
                return response()->download($file);
            }
        }else{
            return back();
        }
    }

    public function show_download($path=null)
    {
        $folder_path[0] = '根目錄';

        $path_array = explode('&',$path);
        $folder_id = end($path_array);
        if(empty($folder_id)) $folder_id=null;

        $i=1;
        foreach($path_array as $v){
            if($v != null){
                $check = Upload::where('id',$v)->first();
                $folder_path[$v] = $check->name;
            }
            if($i==1){
                $now_section = 0;
            }
            if($i==2){
                $now_section = $v;
            }
            $i++;
        }

        //列出目錄
        $folders = Upload::where('type','1')
            ->where('folder_id',$folder_id)
            ->orderBy('name')
            ->get();

        //列出檔案
        $files = Upload::where('type','2')
            ->where('folder_id',$folder_id)
            ->orderBy('name')
            ->get();

        //列出連結
        $urls = Upload::where('type','like','3%')
            ->where('folder_id',$folder_id)
            ->orderBy('name')
            ->get();



        $data = [
            'path'=>$path,
            'folder_id'=>$folder_id,
            'folders'=>$folders,
            'folder_path'=>$folder_path,
            'files'=>$files,
            'urls'=>$urls,
        ];

        return view('introductions.show_download',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $introduction = Introduction::where('section_id',$request->input('section_id'))
            ->first();
        if(empty($introduction)){
            $att[$request->input('type')] = $request->input('content');
            $att['section_id'] = $request->input('section_id');
            Introduction::create($att);
        }else{
            $att[$request->input('type')] = $request->input('content');
            $introduction->update($att);
        }

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 變更了教育處介紹 ".$request->input('section_id')." ".$request->input('type')." 的內容";
        logging('5',$event,get_ip());

        return redirect()->route('introductions.'.$request->input('type'));
    }

    //最高管理使用
    public function index()
    {
        $sections = config('boe.sections');
        $data = [
            'sections'=>$sections,
        ];
        return view('introductions.index',$data);
    }

    public function admin_organization($type)
    {
        $sections = config('boe.sections');
        $section_name = $sections[$type];
        $introduction = Introduction::where('section_id',$type)
            ->first();
        if(empty($introduction)){
            $content = "";
        }else{
            $content = $introduction->organization;
        }

        $data = [
            'section_name'=>$section_name,
            'section_id'=>$type,
            'content'=>$content,
        ];
        return view('introductions.admin_organization',$data);
    }

    public function admin_people($type)
    {
        $sections = config('boe.sections');
        $section_name = $sections[$type];
        $introduction = Introduction::where('section_id',$type)
            ->first();
        if(empty($introduction)){
            $content = "";
        }else{
            $content = $introduction->people;
        }

        $data = [
            'section_name'=>$section_name,
            'section_id'=>$type,
            'content'=>$content,
        ];
        return view('introductions.admin_people',$data);
    }

    public function admin_people2($type)
    {
        $sections = [
            '1'=>'處長',
            '2'=>'副處長',
            '3'=>'專員',
        ];
        $section_name = $sections[$type];
        $introduction = Introduction::where('section_id',$type)
            ->first();
        if(empty($introduction)){
            $content = "";
        }else{
            $content = $introduction->people;
        }

        $data = [
            'section_name'=>$section_name,
            'section_id'=>$type,
            'content'=>$content,
        ];
        return view('introductions.admin_people2',$data);
    }

    public function admin_site($type)
    {
        $sections = config('boe.sections');
        $section_name = $sections[$type];
        $introduction = Introduction::where('section_id',$type)
            ->first();
        if(empty($introduction)){
            $content = "";
        }else{
            $content = $introduction->site;
        }

        $data = [
            'section_name'=>$section_name,
            'section_id'=>$type,
            'content'=>$content,
        ];
        return view('introductions.admin_site',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function admin_store(Request $request)
    {
        $introduction = Introduction::where('section_id',$request->input('section_id'))
            ->first();
        if(empty($introduction)){
            $att[$request->input('type')] = $request->input('content');
            $att['section_id'] = $request->input('section_id');
            Introduction::create($att);
        }else{
            $att[$request->input('type')] = $request->input('content');
            $introduction->update($att);
        }

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 變更了教育處介紹 ".$request->input('section_id')." ".$request->input('type')." 的內容";
        logging('5',$event,get_ip());

        return redirect()->route('introductions.admin_'.$request->input('type'),$request->input('section_id'));
    }

    public function admin_store2(Request $request)
    {
        $introduction = Introduction::where('section_id',$request->input('section_id'))
            ->first();
        if(empty($introduction)){
            $att[$request->input('type')] = $request->input('content');
            $att['section_id'] = $request->input('section_id');
            Introduction::create($att);
        }else{
            $att[$request->input('type')] = $request->input('content');
            $introduction->update($att);
        }

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 變更了教育處介紹 ".$request->input('section_id')." ".$request->input('type')." 的內容";
        logging('5',$event,get_ip());

        return redirect()->route('introductions.admin_people2',$request->input('section_id'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($type,$section_id)
    {
        $sections = config('boe.sections');
        $section_name = $sections[$section_id];

        $introduction = Introduction::where('section_id',$section_id)
            ->first();
        if(empty($introduction)){
            $content = "";
        }else{
            $content = $introduction->$type;
        }

        $section_pages = SectionPage::where('section_id',$section_id)->orderBy('order_by')->get();

        $data = [
            'type'=>$type,
            'section_id'=>$section_id,
            'content'=>$content,
            'section_name'=>$section_name,
            'section_pages'=>$section_pages,
        ];
        return view('introductions.show',$data);

    }

    public function section_page_show($section_id,SectionPage $section_page)
    {
        $sections = config('boe.sections');
        $section_name = $sections[$section_id];
        $section_pages = SectionPage::where('section_id',$section_id)->orderBy('order_by')->get();
        $data = [
            'section_id'=>$section_id,
            'section_page'=>$section_page,
            'section_name'=>$section_name,
            'section_pages'=>$section_pages,
        ];
        return view('introductions.section_page_show',$data);
    }

    public function show2($type,$section_id)
    {
        $sections = [
            '1'=>'處長',
            '2'=>'副處長',
            '3'=>'專員',
        ];
        $section_name = $sections[$section_id];

        $introduction = Introduction::where('section_id',$section_id)
            ->first();
        if(empty($introduction)){
            $content = "";
        }else{
            $content = $introduction->$type;
        }

        $data = [
            'type'=>$type,
            'section_id'=>$section_id,
            'content'=>$content,
            'section_name'=>$section_name,
        ];
        return view('introductions.show2',$data);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
