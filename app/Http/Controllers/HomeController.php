<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Log;
use App\Models\Marquee;
use App\Models\Menu;
use App\Models\Post;
use App\Models\PostSchool;
use App\Models\Question;
use App\Models\Report;
use App\Models\ReportSchool;
use App\Models\TitleImage;
use App\Models\User;
use App\Models\SystemPost;
use App\Models\UserRead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Redirect;
use Purifier;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /*public function __construct()
    {
        $this->middleware('auth')->except('index');
    }*/

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */ 

    public function user_reads($no_read_sp){
        $no_read_sp_array = explode(',',$no_read_sp);
        foreach($no_read_sp_array as $k=>$v){
            $att['user_id'] = auth()->user()->id;
            $att['system_post_id'] = $v;
            UserRead::create($att);
        }
        
        $user_read_ids = \App\Models\UserRead::where('user_id',auth()->user()->id)->pluck('id')->toArray();    
        session(['user_read_ids' => $user_read_ids]);
        session(['user_all_read' => 1]);
        return redirect()->back();
        
    }

    public function close()
    {
        if(!file_exists(storage_path('app/privacy/close.txt'))){    
            if(!file_exists(storage_path('app/privacy'))){
                mkdir(storage_path('app/privacy'));
            }
            touch(storage_path('app/privacy/close.txt'));
            $fp = fopen(storage_path('app/privacy/close.txt'), 'w');
            fwrite($fp, '0');          
            fclose($fp);
        }
        $fp = fopen(storage_path('app/privacy/close.txt'), 'r');
        $close = fread($fp, filesize(storage_path('app/privacy/close.txt')));                
        fclose($fp);

        $data = [
            'close'=>$close,
        ];
        return view('close_system',$data);
    }

    public function close_system(){
        $fp = fopen(storage_path('app/privacy/close.txt'), 'r');
        $close = fread($fp, filesize(storage_path('app/privacy/close.txt'))); 
        fclose($fp);
        if($close == 0 ) $do =1;
        if($close == 1 ) $do =0;
        $fp = fopen(storage_path('app/privacy/close.txt'), 'w');
        fwrite($fp, $do);  
        fclose($fp);
        return back();
    }


    public function index()
    {
        $itemSize = 6;
        $post1 = Post::where('category_id', '1')
            ->where('situation', '3')
            ->orWhere(function ($q) {
                $q->where('category_id', '5')
                    ->where('another', '1')
                    ->where('situation', '3');
            })
            ->orderBy('passed_at', 'DESC')->limit(6)->get();
        $post2 = Post::where('category_id', '2')
            ->where('situation', '3')
            ->orderBy('passed_at', 'DESC')->limit(6)->get();
        $post3 = Post::where('category_id', '3')
            ->where('situation', '3')
            ->orderBy('passed_at', 'DESC')->limit(12)->get();
        $post4 = Post::where('category_id', '4')
            ->where('situation', '3')
            ->orderBy('passed_at', 'DESC')->limit(10)->get();

        $marquees = Marquee::where('start_date', '<=', date('Ymd'))
            ->where('stop_date', '>', date('Ymd'))
            ->get();
        //$marquees = Marquee::all();
        //dd($marquees);
        $data = [
            'post1' => $post1,
            'post2' => $post2,
            'post3' => $post3,
            'post4' => $post4,
            'marquees' => $marquees,
        ];
        return view('index', $data);
    }

    public function search(Request $request)
    {
        $want = $request->input('want');
        return redirect('https://www.google.com/search?q=' . $want . '+site%3Anewboe.chc.edu.tw');
    }

    public function bulletin($category)
    {
        $category_id = $category;
        if ($category == 5) {
            return back();
        }

        if ($category == 1) {
            $posts = Post::where('category_id', $category)
                ->where('situation', '3')
                ->orWhere(function ($q) {
                    $q->where('category_id', '5')
                        ->where('another', '1')
                        ->where('situation', '3');
                })
                ->orderBy('passed_at', 'DESC')
                ->paginate('30');
        } else {
            $posts = Post::where('category_id', $category)
                ->where('situation', '3')
                ->orderBy('passed_at', 'DESC')
                ->paginate('30');
        }

        $categories = config('boe.categories');
        $category = $categories[$category];


        $data = [
            'posts' => $posts,
            'category' => $category,
            'category_id' => $category_id,
        ];
        return view('bulletin', $data);
    }

    public function bulletin_search(Request $request)
    {
        if ($request->input('check') != session('search')) {
            return back()->withErrors(['error' => ['驗證碼不對！']]);
        }

        $category_id = $request->input('category_id');
        //$want = $request->input('want');
        $want = strip_tags($request->input('want'));
        $want = str_replace("<","",$want);
        $want = str_replace(">","",$want);

        return redirect()->route('bulletin_search_result', ['category_id' => $category_id, 'want' => $want]);
    }

    public function bulletin_search_result($category_id, $want)
    {
        if ($category_id == 5) {
            dd('別想偷看');
        }

        if ($category_id == 1) {
            $posts = Post::where('category_id', $category_id)
                ->where('situation', '3')
                ->where(function ($q) use ($want) {
                    $q->where('title', 'like', '%' . $want . '%')
                        ->orWhere('content', 'like', '%' . $want . '%')
                        ->orWhereHas('user', function ($query) use ($want) {
                            $query->where('name', 'like', '%' . $want . '%');
                        });
                })
                ->orWhere(function ($q) use ($want) {
                    $q->where('category_id', '5')
                        ->where('another', '1')
                        ->where(function ($q) use ($want) {
                            $q->where('title', 'like', '%' . $want . '%')
                                ->orWhere('content', 'like', '%' . $want . '%')
                                ->orWhereHas('user', function ($query) use ($want) {
                                    $query->where('name', 'like', '%' . $want . '%');
                                });
                        })
                        ->where('situation', '3');
                })
                ->orderBy('passed_at', 'DESC')
                ->paginate('30');
        } else {
            $posts = Post::where('category_id', $category_id)
                ->where('situation', '3')
                ->where(function ($q) use ($want) {
                    $q->where('title', 'like', '%' . $want . '%')
                        ->orWhere('content', 'like', '%' . $want . '%')
                        ->orWhereHas('user', function ($query) use ($want) {
                            $query->where('name', 'like', '%' . $want . '%');
                        });
                })
                ->orderBy('passed_at', 'DESC')
                ->paginate('30');
        }

        $categories = config('boe.categories');
        $category = $categories[$category_id];

        $data = [
            'posts' => $posts,
            'category' => $category,
            'category_id' => $category_id,
            'want' => $want,
        ];
        return view('bulletin_search', $data);
    }


    public function edit_password()
    {
        return view('edit_password');
    }

    public function update_password(Request $request)
    {

        if (!password_verify($request->input('password0'), auth()->user()->password)) {

            $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 更改密碼失敗(舊密碼錯誤)";
            logging('2', $event, get_ip());

            return back()->withErrors(['error' => ['舊密碼錯誤！你不是本人！？']]);
        }
        if ($request->input('password1') != $request->input('password2')) {

            $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 更改密碼失敗(兩次新密碼不同)";
            logging('2', $event, get_ip());
            return back()->withErrors(['error' => ['兩次新密碼不相同']]);
        }


        $att['id'] = auth()->user()->id;
        $att['password'] = bcrypt($request->input('password1'));
        $user = User::where('id', $att['id'])->first();
        $user->update($att);
        return redirect()->route('index');
    }

    public function reback_password(User $user)
    {
        $att['password'] = bcrypt('Plz90-Change-Pwd!!!');
        $user->update($att);

        //log
        $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 還原了使用者 id：" . $user->id . " " . $user->name . " 的密碼";
        logging('2', $event, get_ip());

        return redirect()->route('sims.index');
    }

    public function pic()
    {
        $key = rand(10000, 99999);
        $back = rand(0, 9);
        //$r = rand(0, 255);
        $r = 0;
        //$g = rand(0, 255);
        $g = 0;
        //$b = rand(0, 255);
        $b = 0;

        session(['chaptcha' => $key]);

        $cht = array(0 => "零", 1 => "壹", 2 => "貳", 3 => "參", 4 => "肆", 5 => "伍", 6 => "陸", 7 => "柒", 8 => "捌", 9 => "玖");
        //$cht = array(0=>"0",1=>"1",2=>"2",3=>"3",4=>"4",5=>"5",6=>"6",7=>"7",8=>"8",9=>"9");
        $cht_key = "";
        for ($i = 0; $i < 5; $i++) $cht_key .= $cht[substr($key, $i, 1)];

        header("Content-type: image/gif");
        $images = asset('images/captcha_bk' . $back . '.gif');

        $context = stream_context_create([
            "ssl" => [
                "verify_peer"      => false,
                "verify_peer_name" => false
            ]
        ]);

        $fileContent = file_get_contents($images, false, $context);
        $im = imagecreatefromstring($fileContent);
        $text_color = imagecolorallocate($im, $r, $g, $b);

        imagettftext($im, 50, 0, 50, 50, $text_color, public_path('font/wt071.ttf'), $cht_key);
        imagegif($im);
        imagedestroy($im);
    }

    public function title_image()
    {
        $title_images = TitleImage::where('disable', null)->get();
        $path = storage_path('app/public/title_image');
        if (!is_dir($path)) mkdir($path);

        $data = [
            'title_images' => $title_images,
        ];
        return view('title_image', $data);
    }

    public function title_image_add(Request $request)
    {
        $att = $request->all();

        $path = storage_path('app/public/title_image');

        if (!is_dir($path)) mkdir($path);

        //處理檔案上傳
        if ($request->hasFile('pic')) {
            $pic = $request->file('pic');
            if(check_php($pic)) return back()->withErrors(['errors'=>['不合規定的檔案類型']]);

            $info = [
                'original_filename' => $pic->getClientOriginalName(),
                'extension' => $pic->getClientOriginalExtension(),
            ];
            $name = date('YmdHis');
            $pic->storeAs('public/title_image', $name . '.' . $info['extension']);
            
            $att['photo_name'] = $name . '.' . $info['extension'];

            $title_image = TitleImage::create($att);

            //log
            $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 新增了標題圖片 id：" . $title_image->id;
            logging('5', $event, get_ip());
        }
        return redirect()->route('title_image');
    }

    public function title_image_edit(TitleImage $title_image)
    {
        $data = [
            'title_image' => $title_image,
        ];
        return view('title_image_edit', $data);
    }

    public function title_image_update(Request $request, TitleImage $title_image)
    {
        $att = $request->all();
        $title_image->update($att);

        //log
        $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 修改了標題圖片 id：" . $title_image->id;
        logging('5', $event, get_ip());
        return redirect()->route('title_image');
    }

    public function title_image_del(TitleImage $title_image)
    {
        $att['disable'] = 1;
        $title_image->update($att);
                
        //log
        $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 刪除了標題圖片 id：" . $title_image->id;
        logging('5', $event, get_ip());

        return redirect()->route('title_image');
    }

    public function menu($id=null)
    {
        $id=(empty($id))?0:$id;
        $menus = Menu::where('belong', $id)->orderBy('order_by')->get();
        $all_menus = Menu::all();
        foreach($all_menus as $all_menu){
            $menu2name[$all_menu->id] = $all_menu->name;
        }
        $path0 = "<nav aria-label='breadcrumb'><ol class='breadcrumb'><li class='breadcrumb-item'><a href='".route('menu')."'>最上層</a></li>";
        $path1 = "";
        $path9 = "</ol></nav>";
        if($id != 0){                    
            $this_menu = Menu::find($id);
            $this_menu_name = $this_menu->name;
            $this_menu_id = $this_menu->id;
            $this_menu_type = $this_menu->type;
            //取路徑的 id            
            $path_array = array_filter(explode(">",$this_menu->path.$id), fn($value) => $value !== "" && $value !== null);
            foreach($path_array as $k=>$v){
                if($v != 0){
                    $path1 = $path1."<li class='breadcrumb-item' aria-current='page'><a href='".route('menu',['id'=>$v])."'>".$menu2name[$v]."</a></li>";
                }                
            }            
        }else{
            $this_menu_name = "最上層";
            $this_menu_id = 0;
            $this_menu_type = 1;
        }
        
        $path = $path0.$path1.$path9;
        $data = [
            'menus' => $menus,
            'this_menu_name'=>$this_menu_name,
            'this_menu_id'=>$this_menu_id,
            'this_menu_type'=>$this_menu_type,
            'path'=>$path,
        ];
        return view('menu', $data);
    }

    public function menu_create()
    {
        $folder_menus = Menu::where('type', 1)->orderBy('path')->get();
        $root_menus = Menu::where('belong', '0')->orderBy('order_by')->get();
        $folder_name[0] = "最上層根目錄";
        foreach ($folder_menus as $folder_menu) {
            $folder_name[$folder_menu->id] = $folder_menu->name;
        }
        $target_array = ['_self' => '本視窗(本系統內的連結)', '_blank' => '開新視窗(其他系統內的連結)'];
        $data = [
            'folder_menus' => $folder_menus,
            'folder_name' => $folder_name,
            'root_menus' => $root_menus,
            'target_array' => $target_array,
        ];
        return view('menu_create', $data);
    }

    public function menu_add(Request $request)
    {
        $att = $request->all();
        if ($att['belong'] == 0) {
            $att['path'] = ">0>";
        } else {
            $belong_menu = Menu::find($att['belong']);
            $att['path'] = $belong_menu->path . $belong_menu->id . ">";
        }
        if (!isset($att['order_by'])) {
            $att['order_by'] = 999;
        }

        $att['name'] = Purifier::clean($att['name'], array('AutoFormat.AutoParagraph' => false));
        $att['link'] = Purifier::clean($att['link'], array('AutoFormat.AutoParagraph' => false));
        
        $menu = Menu::create($att);

        //log
        $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 新增了選單連結 id：" . $menu->id . " 名稱：" . $menu->name;
        logging('5', $event, get_ip());

        return redirect()->back();
    }

    public function menu_del(Menu $menu)
    {
        $son_menus = Menu::where('path', 'like', '%>' . $menu->id . '>%')->get();
        foreach ($son_menus as $son_menu) {
            $son_menu->delete();
        }
        $menu->delete();

        //log
        $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 刪除了選單連結 id：" . $menu->id . " 名稱：" . $menu->name;
        logging('5', $event, get_ip());

        return redirect()->back();
    }

    public function menu_edit(Menu $menu)
    {
        $all_menus = Menu::all();
        foreach($all_menus as $all_menu){
            $menu2name[$all_menu->id] = $all_menu->name;
        }
        $path0 = "<nav aria-label='breadcrumb'><ol class='breadcrumb'><li class='breadcrumb-item'><a href='".route('menu')."'>最上層</a></li>";
        $path1 = "";
        $path9 = "</ol></nav>";        
        if($menu->belong != 0){                    
            $this_menu = Menu::find($menu->belong);
            $this_menu_name = $this_menu->name;
            $this_menu_id = $this_menu->id;
            //取路徑的 id            
            $path_array = array_filter(explode(">",$this_menu->path.$menu->belong), fn($value) => $value !== "" && $value !== null);
            foreach($path_array as $k=>$v){
                if($v != 0){
                    $path1 = $path1."<li class='breadcrumb-item' aria-current='page'><a href='".route('menu',['id'=>$v])."'>".$menu2name[$v]."</a></li>";
                }                
            }            
        }else{
            $this_menu_name = "最上層";
            $this_menu_id = 0;
        }
        
        $path = $path0.$path1.$path9;
        $data = [
            'menu' => $menu,
            'this_menu_name' => $this_menu_name,
            'this_menu_id' => $this_menu_id,
            'path' => $path,
        ];        
        return view('menu_edit', $data);
    }

    public function menu_update(Request $request, Menu $menu)
    {
        $att = $request->all();
        if ($att['belong'] == 0) {
            $att['path'] = ">0>";
        } else {
            $belong_menu = Menu::find($att['belong']);
            $att['path'] = $belong_menu->path . $belong_menu->id . ">";
        }
        if (!isset($att['order_by'])) {
            $att['order_by'] = 999;
        }

        $att['name'] = Purifier::clean($att['name'], array('AutoFormat.AutoParagraph' => false));
        $att['link'] = Purifier::clean($att['link'], array('AutoFormat.AutoParagraph' => false));
        $menu->update($att);

        //log
        $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 編輯了選單連結 id：" . $menu->id . " 名稱：" . $menu->name;
        logging('5', $event, get_ip());

        return redirect()->route('menu');
    }

    public function questions()
    {
        return view('questions');
    }

    public function about()
    {
        return view('about');
    }

    public function rss()
    {
        $posts = Post::where('category_id', '<>', 5)
            ->where('situation', '3')
            ->orWhere(function ($q) {
                $q->where('category_id', '5')
                    ->where('another', '1')
                    ->where('situation', '3');
            })
            ->orderBy('passed_at', 'DESC')
            ->paginate('50');

        $categories = config('boe.categories');
        $sections = config('boe.sections');

        $items = "";
        foreach ($posts as $post) {
            $items .= '
            <item>
                <link>
                ' . env('APP_URL') . '/posts/' . $post->id . '
                </link>
                <title>
                    <![CDATA[ ' . $post->title . ' ]]>
                </title>
                <author>' . array_get($sections, $post->section_id) . ' / ' . $post->user->name . '</author>
                <category>
                    <![CDATA[ ' . $categories[$post->category_id] . ' ]]>
                </category>
                <pubDate>' . substr($post->passed_at, 0, 16) . '</pubDate>
                <guid>
                    ' . env('APP_URL') . '/posts/' . $post->id . '
                </guid>
                <description>
                    <![CDATA[
                        ' . $post->content . '
                    ]]>
                </description>
            </item>
            ';
        }

        $content = '<?xml version="1.0" encoding="UTF-8"?>
            <rss version="2.0">
                <channel>
                <title>
                    <![CDATA[ 彰化縣教育處新雲端 ]]>
                </title>
                <link>https://newboe.chc.edu.tw</link>
                <description>
                    <![CDATA[
                        歡迎光臨教育處新雲端！分享彰化縣教育的大小事！
                    ]]>
                </description>
                <language>utf-8</language>
                <copyright>
                    <![CDATA[
                        版權來自：newboe.chc.edu.tw
                    ]]>
                </copyright>
                ' . $items . '
                </channel>
            </rss>

        ';
        $invalid_characters = '/[^\x9\xa\x20-\xD7FF\xE000-\xFFFD]/';
        $content = preg_replace($invalid_characters, '', $content);
        return Response::make($content, '200')->header('Content-Type', 'text/xml');
    }

    public function special()
    {
        return view('specials.index');
    }

    public function special_post(Request $request)
    {
        $id = $request->input('post_id');
        $post = Post::where('id', $id)->first();
        $post_schools = PostSchool::where('post_id', $id)->get();
        $categories = config('boe.categories');
        $situation = config('boe.situation');
        $sections = config('boe.sections');
        $data = [
            'post' => $post,
            'post_schools' => $post_schools,
            'categories' => $categories,
            'situation' => $situation,
            'sections' => $sections,
        ];
        return view('specials.show_post', $data);
    }

    public function special_post_delete(Request $request)
    {
        $id = $request->input('post_id');
        $post = Post::where('id', $id)->first();
        $post_schools = PostSchool::where('post_id', $id)->get();
        foreach ($post_schools as $post_school) {
            $post_school->delete();
        }

        $folder = storage_path('app/public/post_files/' . $post->id);
        if (is_dir($folder)) {
            del_folder($folder);
        }
        $folder = storage_path('app/public/post_photos/' . $post->id);
        if (is_dir($folder)) {
            del_folder($folder);
        }

        //log
        $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 刪除了公告 id：" . $post->id . " 名稱：" . $post->title;
        logging('4', $event, get_ip());

        $post->delete();

        return redirect()->route('special');
    }

    public function special_post_update(Request $request, Post $post)
    {
        $att = $request->all();
        $post->update($att);

        //log
        $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 編輯了公告 id：" . $post->id . " 名稱：" . $post->title;
        logging('4', $event, get_ip());

        return redirect()->route('special');
    }

    public function special_report(Request $request)
    {
        $id = $request->input('report_id');
        $report = Report::where('id', $id)->first();
        $questions = Question::where('report_id', $id)->get();
        $answers = Answer::where('report_id', $id)->get();
        $report_schools = ReportSchool::where('report_id', $id)->get();
        $categories = config('boe.categories');
        $situation = config('boe.situation');
        $sections = config('boe.sections');
        $types = [
            'radio' => '1.單選題',
            'checkbox' => '2.多選題',
            'text' => '3.文字題',
            'num' => '4.數字題',
        ];
        $data = [
            'report' => $report,
            'questions' => $questions,
            'answers' => $answers,
            'report_schools' => $report_schools,
            'categories' => $categories,
            'situation' => $situation,
            'sections' => $sections,
            'types' => $types,
        ];
        return view('specials.show_report', $data);
    }

    public function special_report_delete(Request $request)
    {
        $id = $request->input('report_id');
        $report = Report::where('id', $id)->first();
        $questions = Question::where('report_id', $id)->get();
        $answers = Answer::where('report_id', $id)->get();
        $report_schools = ReportSchool::where('report_id', $id)->get();
        foreach ($questions as $question) {
            $question->delete();
        }
        foreach ($answers as $answer) {
            $answer->delete();
        }
        foreach ($report_schools as $report_school) {
            $report_school->delete();
        }


        $folder = storage_path('app/public/report_files/' . $report->id);
        if (is_dir($folder)) {
            del_folder($folder);
        }

        $report->delete();

        //log
        $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 刪除了資料填報 id：" . $report->id . " 名稱：" . $report->name;
        logging('4', $event, get_ip());

        return redirect()->route('special');
    }

    public function special_report_update(Request $request, Report $report)
    {
        $att = $request->all();
        $report->update($att);

        //log
        $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 編輯了資料填報 id：" . $report->id . " 名稱：" . $report->name;
        logging('4', $event, get_ip());

        return redirect()->route('special');
    }

    public function special_question_update(Request $request)
    {
        $title = $request->input('title');
        $type = $request->input('type');
        $options = $request->input('options');
        $show = $request->input('show');
        foreach ($title as $k => $v) {
            $question = Question::find($k);
            $att['title'] = $v;
            $att['type'] = $type[$k];
            $att['options'] = $options[$k];
            $att['show'] = $show[$k];
            $question->update($att);

            //log
            $event = "管理者 " . auth()->user()->name . "(" . auth()->user()->username . ") 新增了問題 id：" . $question->id . " 名稱：" . $question->title;
            logging('4', $event, get_ip());
        }

        return redirect()->route('special');
    }

    public function log()
    {
        $level_array = [
            0 => 'EMERG',
            1 => 'ALERT',
            2 => 'CRIT',
            3 => 'ERR',
            4 => 'WARN',
            5 => 'NOTICE',
            6 => 'INFO',
        ];

        $select_level = (isset($_GET['select_level'])) ? $_GET['select_level'] : "all";

        if ($select_level == "all") {
            $logs = Log::orderBy('created_at', 'DESC')->paginate(20);
        } else {
            $logs = Log::where('level', $select_level)
                ->orderBy('created_at', 'DESC')->paginate(20);
        }



        $data = [
            'select_level' => $select_level,
            'level_array' => $level_array,
            'logs' => $logs,
        ];
        return view('log', $data);
    }

    function system_posts_index(){
        $system_posts = SystemPost::orderBy('id','DESC')->simplePaginate('15');
        $data = [
            'system_posts'=>$system_posts,
        ];
        return view('system_posts.index',$data);
    }

    function system_posts_create(){
        
    }
    function system_posts_store(Request $request){
        $att = $request->all();
        SystemPost::create($att);
        return redirect()->back();
    }
    function system_posts_edit(SystemPost $system_post){
        
    }
    function system_posts_update(Request $request,SystemPost $system_post){
        
    }
    function system_posts_destroy(SystemPost $system_post){
        $system_post->delete();
        return redirect()->back();
    }
}
