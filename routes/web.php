<?php
//如果關閉網站
//if($_SERVER['REQUEST_URI'] != "/close" and $_SERVER['REQUEST_URI'] != "/login" and $_SERVER['REQUEST_URI'] != "/pic"){
//    close_system();
//};
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminsController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\EduReportController;
use App\Http\Controllers\GLoginController;
use App\Http\Controllers\Auth\MLoginController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IntroductionController;
use App\Http\Controllers\LinksController;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\MarqueeController;
use App\Http\Controllers\MySectionController;
use App\Http\Controllers\OpenIdLoginController;
use App\Http\Controllers\OthersController;
use App\Http\Controllers\PhotoAlbumController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\SchoolReportController;
use App\Http\Controllers\SimulationController;
use App\Http\Controllers\WrenchController;

//首頁
Route::get('/', [HomeController::class,'index'])->name('index');////
Route::get('index', [HomeController::class,'index'])->name('index');////
Route::get('home', [HomeController::class,'index'])->name('home');////

#登入
Route::get('login', [LoginController::class,'showLoginForm'])->name('login');////
Route::post('login', [MLoginController::class,'auth'])->name('auth');////

//認證圖片
Route::get('pic', [HomeController::class,'pic'])->name('pic');////

//gsuite登入
Route::get('glogin', [GLoginController::class,'showLoginForm'])->name('glogin');////
Route::post('glogin', [GLoginController::class,'auth'])->name('gauth');////

//openid登入
Route::get('sso', [OpenIdLoginController::class,'sso'])->name('sso');
Route::get('auth/callback', [OpenIdLoginController::class,'callback'])->name('callback');

#登出
Route::post('logout', [LoginController::class,'logout'])->name('logout');////

Route::get('close', [HomeController::class,'close'])->name('close');
Route::get('search', [HomeController::class,'search'])->name('search');
//秀出公告類別的公告
Route::get('bulletin/{category}', [HomeController::class,'bulletin'])->name('bulletin.show');
Route::post('bulletin_search', [HomeController::class,'bulletin_search'])->name('bulletin_search');
Route::get('bulletin_search_result/{category_id}/{want}/result', [HomeController::class,'bulletin_search_result'])->name('bulletin_search_result');

//rss
Route::get('rss', [HomeController::class,'rss'])->name('rss');

//內容頁面
Route::get('contents/{content}/show', [ContentController::class,'show'])->where('content', '[0-9]+')->name('contents.show');

//相簿
Route::get('photo_albums/guest', [PhotoAlbumController::class,'guest'])->name('photo_albums.guest');
Route::get('photo_albums/{photo_album}/guest_show', [PhotoAlbumController::class,'guest_show'])->name('photo_albums.guest_show');

//已註冊使用者可進入
Route::group(['middleware' => 'auth'],function(){
    //結束模擬
        Route::get('sims/impersonate_leave', [SimulationController::class,'impersonate_leave'])->name('sims.impersonate_leave');////
    
        //下載資料填報附檔
        Route::get('edu_report/{id}/{filename}/download', [EduReportController::class,'download'])->name('edu_report.download');
    
        //報錯
        Route::get('wrench/index', [WrenchController::class,'index'])->name('wrench.index');
        Route::post('wrench/store', [WrenchController::class,'store'])->name('wrench.store');
        Route::get('wrench/download/{wrench_id}/{filename}', [WrenchController::class,'download'])->name('wrench.download');
    
        //常見問題集
        Route::get('questions/index', [HomeController::class,'questions'])->name('questions.index');
        Route::get('questions/about', [HomeController::class,'about'])->name('questions.about');
    
        Route::get('user_reads/{no_read_sp}', [HomeController::class,'user_reads'])->name('user_reads');
});

//最高權限管理者
//最高管理者可用
Route::group(['middleware' => 'admin'],function(){
    //系統公告
    Route::get('system_posts/index',[HomeController::class,'system_posts_index'])->name('system_posts.index');
    Route::get('system_posts/create',[HomeController::class,'system_posts_create'])->name('system_posts.create');
    Route::post('system_posts/store',[HomeController::class,'system_posts_store'])->name('system_posts.store');
    Route::get('system_posts/edit/{system_post}',[HomeController::class,'system_posts_edit'])->name('system_posts.create');
    Route::patch('system_posts/update/{system_post}',[HomeController::class,'system_posts_update'])->name('system_posts.update');
    Route::get('system_posts/destroy/{system_post}',[HomeController::class,'system_posts_destroy'])->name('system_posts.destroy');

    Route::get('close_system',[HomeController::class,'close_system'])->name('close_system');

    //log
    Route::get('logs',[HomeController::class,'log'])->name('logs');

    //變更local使用者密碼
    Route::get('reback_password/{user}',[HomeController::class,'reback_password'])->name('reback_password');

    //相關連結
    Route::get('links', [LinksController::class,'index'])->name('links.index');
    Route::get('links/create', [LinksController::class,'create'])->name('links.create');
    Route::post('links', [LinksController::class,'store'])->name('links.store');
    Route::delete('links/{link}', [LinksController::class,'destroy'])->name('links.destroy');
    Route::get('links/{link}/edit', [LinksController::class,'edit'])->name('links.edit');
    Route::patch('links/{link}', [LinksController::class,'update'])->name('links.update');

    //其他連結
    Route::get('others', [OthersController::class,'index'])->name('others.index');////
    Route::get('others/create', [OthersController::class,'create'])->name('others.create');////
    Route::post('others', [OthersController::class,'store'])->name('others.store');////
    Route::delete('others/{other}', [OthersController::class,'destroy'])->name('others.destroy');////
    Route::get('others/{other}/edit', [OthersController::class,'edit'])->name('others.edit');////
    Route::patch('others/{other}', [OthersController::class,'update'])->name('others.update');////


    //帳號管理
    Route::get('admin/user', [AdminsController::class,'user'])->name('admins.user');////
    Route::get('admin/user/{user}/edit', [AdminsController::class,'user_edit'])->name('admins.user_edit');////
    Route::patch('admin/user/{user}/update', [AdminsController::class,'user_update'])->name('admins.user_update');////
    Route::delete('admin/user/{user}/destroy',[AdminsController::class,'user_destroy'])->name('admins.user_destroy');////
    Route::get('admin/user/{user}/reback',[AdminsController::class,'user_reback'])->name('admins.user_reback');////

    //模擬登入
    Route::get('sims/index' , [SimulationController::class,'index'])->name('sims.index');////
    Route::get('sims/{group_id}/group' , [SimulationController::class,'group'])->name('sims.group');////
    Route::get('sims/{user}/impersonate', [SimulationController::class,'impersonate'])->name('sims.impersonate');////
    Route::match(['post','get'],'sims',[SimulationController::class,'search'])->name('sims.search');////

    //教育處介紹
    Route::get('introduction/index', [IntroductionController::class,'index'])->name('introductions.index');////
    Route::get('introduction/{type}/admin_organization', [IntroductionController::class,'admin_organization'])->name('introductions.admin_organization');////
    Route::get('introduction/{type}/admin_people', [IntroductionController::class,'admin_people'])->name('introductions.admin_people');////
    Route::get('introduction/{type}/admin_people2', [IntroductionController::class,'admin_people2'])->name('introductions.admin_people2');////
    Route::get('introduction/{type}/admin_site', [IntroductionController::class,'admin_site'])->name('introductions.admin_site');////
    Route::post('introduction/admin/store', [IntroductionController::class,'admin_store'])->name('introductions.admin_store');////
    Route::post('introduction/admin/store2', [IntroductionController::class,'admin_store2'])->name('introductions.admin_store2');////


    //管理員回覆
    Route::post('wrench/reply', [WrenchController::class,'reply'])->name('wrench.reply');////
    Route::get('wrench/set_show/{wrench}', [WrenchController::class,'set_show'])->name('wrench.set_show');////
    Route::get('wrench/destroy/{wrench}', [WrenchController::class,'destroy'])->name('wrench.destroy');////

    //特殊處理
    Route::get('special',[HomeController::class,'special'])->name('special');
    Route::post('special_post',[HomeController::class,'special_post'])->name('special.post');
    Route::post('special_post_delete',[HomeController::class,'special_post_delete'])->name('special.post_delete');
    Route::patch('special_post_update/{post}',[HomeController::class,'special_post_update'])->name('special.post_update');

    Route::post('special_report',[HomeController::class,'special_report'])->name('special.report');
    Route::post('special_report_delete',[HomeController::class,'special_report_delete'])->name('special.report_delete');
    Route::patch('special_report_update/{report}',[HomeController::class,'special_report_update'])->name('special.report_update');
    Route::patch('special_question_update',[HomeController::class,'special_question_update'])->name('special.question_update');

});

//系統管理者、科室管理者
Route::group(['middleware' => 'all_admin'],function(){
    //上傳圖片
    //Route::get('/laravel-filemanager', '\UniSharp\LaravelFilemanager\Controllers\LfmController::class,'show');
    //Route::post('/laravel-filemanager/upload', '\UniSharp\LaravelFilemanager\Controllers\UploadController::class,'upload');
    Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
        \UniSharp\LaravelFilemanager\Lfm::routes();
    });

    //更改密碼
    Route::get('edit_password',[HomeController::class,'edit_password'])->name('edit_password');////
    Route::patch('update_password',[HomeController::class,'update_password'])->name('update_password');////

    //橫幅廣告
    Route::get('title_image',[HomeController::class,'title_image'])->name('title_image');////
    Route::post('title_image_add',[HomeController::class,'title_image_add'])->name('title_image_add');////
    Route::get('title_image_del/{title_image}',[HomeController::class,'title_image_del'])->name('title_image_del');////
    Route::get('title_image_edit/{title_image}',[HomeController::class,'title_image_edit'])->name('title_image_edit');////
    Route::post('title_image_update/{title_image}',[HomeController::class,'title_image_update'])->name('title_image_update');////

    //選單連結
    Route::get('menu',[HomeController::class,'menu'])->name('menu');////
    Route::get('menu/create',[HomeController::class,'menu_create'])->name('menu_create');////
    Route::post('menu_add',[HomeController::class,'menu_add'])->name('menu_add');////
    Route::get('menu_edit/{menu}',[HomeController::class,'menu_edit'])->name('menu_edit');////
    Route::post('menu_update/{menu}',[HomeController::class,'menu_update'])->name('menu_update');////
    Route::get('menu_del/{menu}',[HomeController::class,'menu_del'])->name('menu_del');////

    //內容管理
    Route::get('contents', [ContentController::class,'index'])->name('contents.index');////
    Route::get('contents/create', [ContentController::class,'create'])->name('contents.create');////
    Route::post('contents/store', [ContentController::class,'store'])->name('contents.store');////
    Route::post('contents/{content}', [ContentController::class,'destroy'])->name('contents.destroy');////
    Route::get('contents/{content}/edit', [ContentController::class,'edit'])->name('contents.edit');////
    Route::post('contents/{content}/update', [ContentController::class,'update'])->name('contents.update');////

    //album
    Route::get('photo_albums', [PhotoAlbumController::class,'index'])->name('photo_albums.index');////
    Route::get('photo_albums/create', [PhotoAlbumController::class,'create'])->name('photo_albums.create');////
    Route::post('photo_albums/store', [PhotoAlbumController::class,'store'])->name('photo_albums.store');////
    Route::get('photo_albums/{photo_album}/show', [PhotoAlbumController::class,'show'])->name('photo_albums.show');////
    Route::post('photo_albums/{photo_album}/store_photo', [PhotoAlbumController::class,'store_photo'])->name('photo_albums.store_photo');//有問題，無法上傳
    Route::get('photo_albums/{photo_album}/delete', [PhotoAlbumController::class,'delete'])->name('photo_albums.delete');////
    Route::get('photo_albums/{photo_album}/edit', [PhotoAlbumController::class,'edit'])->name('photo_albums.edit');////
    Route::post('photo_albums/{photo_album}/update', [PhotoAlbumController::class,'update'])->name('photo_albums.update');////
    //Route::get('photo_albums/{photo}/edit_photo', [PhotoAlbumController::class,'edit_photo'])->name('photo_albums.edit_photo');
    //Route::post('photo_albums/{photo}/update_photo', [PhotoAlbumController::class,'update_photo'])->name('photo_albums.update_photo');
    Route::get('photo_albums/{photo}/delete_photo', [PhotoAlbumController::class,'delete_photo'])->name('photo_albums.delete_photo');////
});

//admin1~admin9及有教育處科內一級管理A才可進入
//科室管理者及admin1~admin9
Route::group(['middleware' => 'section_admin'],function(){
    //成員管理
    Route::get('my_section/admin', [MySectionController::class,'admin'])->name('my_section.admin');
    Route::get('my_section/{user}/agree', [MySectionController::class,'agree'])->name('my_section.agree');
    Route::get('my_section/{user}/disagree', [MySectionController::class,'disagree'])->name('my_section.disagree');

    Route::get('my_section/{user}/remove', [MySectionController::class,'remove'])->name('my_section.remove');

    Route::get('my_section/power', [MySectionController::class,'power'])->name('my_section.power');
    Route::post('my_section/power_update1', [MySectionController::class,'power_update1'])->name('my_section.power_update1');
    Route::post('my_section/power_update2', [MySectionController::class,'power_update2'])->name('my_section.power_update2');
    Route::get('my_section/{id}/power_remove', [MySectionController::class,'power_remove'])->name('my_section.power_remove');

    Route::get('my_section/member', [MySectionController::class,'member'])->name('my_section.member');
    Route::post('my_section/update', [MySectionController::class,'member_update'])->name('my_section.member_update');
    Route::post('my_section/update2', [MySectionController::class,'member_update2'])->name('my_section.member_update2');

    //科室頁面介紹
    Route::get('introduction/organization', [IntroductionController::class,'organization'])->name('introductions.organization');
    Route::get('introduction/people', [IntroductionController::class,'people'])->name('introductions.people');
    Route::get('introduction/site', [IntroductionController::class,'site'])->name('introductions.site');
    Route::post('introduction', [IntroductionController::class,'store'])->name('introductions.store');
    Route::get('introduction/section_page_add', [IntroductionController::class,'section_page_add'])->name('introductions.section_page_add');
    Route::post('introduction/section_page_store', [IntroductionController::class,'section_page_store'])->name('introductions.section_page_store');
    Route::get('introduction/section_page/{section_page}', [IntroductionController::class,'section_page'])->name('introductions.section_page');
    Route::get('introduction/section_page_del/{section_page}', [IntroductionController::class,'section_page_del'])->name('introductions.section_page_del');
    Route::post('introduction/section_page_update/{section_page}', [IntroductionController::class,'section_page_update'])->name('introductions.section_page_update');

    //刪除跑馬燈
    Route::get('marquees/{marquee}/delete' , [MarqueeController::class,'delete'])->name('marquees.delete');
});

//教育處身分才可進入
//教育處可用
Route::group(['middleware' => 'edu'],function(){
    //選填我的科室
        Route::get('my_section', [MySectionController::class,'index'])->name('my_section.index');
        Route::patch('my_section/{user}', [MySectionController::class,'update'])->name('my_section.update');
    
    //出現新增公告的表單
        Route::get('posts/create', [PostsController::class,'create'])->name('posts.create');
    //實際post儲存公告資料
        Route::post('posts/store', [PostsController::class,'store'])->name('posts.store');
    //出現要修改的指定公告
        Route::get('posts/{post}/edit', [PostsController::class,'edit'])->name('posts.edit');
    //送出要修改的指定公告內容
        Route::patch('posts/{post}/update', [PostsController::class,'update'])->name('posts.update');
    //刪除指定的公告
        Route::delete('posts/{post}/delete', [PostsController::class,'destroy'])->name('posts.destroy');
    //作廢指定的公告
        Route::patch('posts/{post}/obsolete', [PostsController::class,'obsolete'])->name('posts.obsolete');
    //再次送審
        Route::patch('posts/{post}/resend', [PostsController::class,'resend'])->name('posts.resend');
    //催收公告
        Route::patch('posts/{post}/signedquickly', [PostsController::class,'signedquickly'])->name('posts.signedquickly');
    
    
    //審核中
        Route::get('posts/reviewing', [PostsController::class,'reviewing'])->name('posts.reviewing');
    //已讀未審
        Route::get('posts/reading', [PostsController::class,'reading'])->name('posts.reading');
    //顯示通過的公告
        Route::get('posts/passing', [PostsController::class,'passing'])->name('posts.passing');
    //顯示本科室內的全數公告
        Route::get('posts/section_all', [PostsController::class,'section_all'])->name('posts.section_all');
        Route::post('posts/do_search_in_section', [PostsController::class,'do_search_in_section'])->name('posts.do_search_in_section');
        Route::get('posts/{want}/all_search_in_section', [PostsController::class,'all_search_in_section'])->name('posts.all_search_in_section');
        Route::get('posts/all', [PostsController::class,'all'])->name('posts.all');
        Route::post('posts/do_search', [PostsController::class,'do_search'])->name('posts.do_search');
        Route::get('posts/{want}/all_search', [PostsController::class,'all_search'])->name('posts.all_search');
        Route::post('posts/select_category', [PostsController::class,'select_category'])->name('posts.select_category');
        Route::get('posts/{category}/all_category', [PostsController::class,'all_category'])->name('posts.all_category');
        Route::post('posts/select_situation', [PostsController::class,'select_situation'])->name('posts.select_situation');
        Route::get('posts/{situation}/all_situation', [PostsController::class,'all_situation'])->name('posts.all_situation');
        Route::get('posts/{user_id}/all_user_id', [PostsController::class,'all_user_id'])->name('posts.all_user_id');
    //秀行程中的公告
        Route::get('posts/show_doing_post/{post}', [PostsController::class,'show_doing_post'])->name('posts.show_doing_post');
        Route::get('posts/show_doing_post_print/{post}', [PostsController::class,'show_doing_post_print'])->name('posts.show_doing_post_print');
    
    //顯示退回的公告
        Route::get('posts/backing', [PostsController::class,'backing'])->name('posts.backing');
    
        //刪除指定公告的附件
        Route::get('posts/{id}/{filename}/del_att', [PostsController::class,'del_att'])->name('posts.del_att');
    //刪除指定公告的圖片
        Route::get('posts/{id}/{filename}/del_img', [PostsController::class,'del_img'])->name('posts.del_img');
    
        //複製公告
        Route::get('posts/{post}/copy', [PostsController::class,'copy'])->name('posts.copy');
    
        //資料填報
        Route::get('edu_report', [EduReportController::class,'index'])->name('edu_report.index');
        Route::get('edu_report/create', [EduReportController::class,'create'])->name('edu_report.create');
        //Route::post('edu_report/add_one', [EduReportController::class,'add_one'])->name('edu_report.add_one');
        //Route::post('edu_report/add_one_school', [EduReportController::class,'add_one_school'])->name('edu_report.add_one_school');
        Route::post('edu_report/store', [EduReportController::class,'store'])->name('edu_report.store');
        Route::get('edu_report/{report}/edit', [EduReportController::class,'edit'])->name('edu_report.edit');
        Route::get('edu_report/{id}/{filename}/delete_file', [EduReportController::class,'delete_file'])->name('edu_report.delete_file');
        Route::get('edu_report/{report}/show', [EduReportController::class,'show'])->name('edu_report.show');
        Route::get('edu_report/{report}/date_late', [EduReportController::class,'date_late'])->name('edu_report.date_late');
        Route::patch('edu_report/{report}/save_date_late', [EduReportController::class,'save_date_late'])->name('edu_report.save_date_late');
        Route::get('edu_report/{report}/result', [EduReportController::class,'result'])->name('edu_report.result');
        Route::get('edu_report/{report}/result2', [EduReportController::class,'result2'])->name('edu_report.result2');
        Route::patch('edu_report/{report}/update', [EduReportController::class,'update'])->name('edu_report.update');
        //增加附件
        //Route::post('edu_report/add_file', [EduReportController::class,'add_file'])->name('edu_report.add_file');
        //刪除附件
        Route::get('edu_report/{id}/{file}/del_file', [EduReportController::class,'del_file'])->name('edu_report.del_file');
        Route::delete('edu_report/{report}/destroy', [EduReportController::class,'destroy'])->name('edu_report.destroy');
        //Route::get('edu_report/{question}/question_destroy', [EduReportController::class,'question_destroy'])->name('edu_report.question_destroy');
        //Route::get('edu_report/{report}/{school_id}/school_destroy', [EduReportController::class,'school_destroy'])->name('edu_report.school_destroy');
        Route::get('edu_report/passing', [EduReportController::class,'passing'])->name('edu_report.passing');
        //再次送審
        Route::patch('edu_report/{report}/resend', [EduReportController::class,'resend'])->name('edu_report.resend');
    
        //下載excel
        Route::get('edu_report/{report}/export', [EduReportController::class,'export'])->name('edu_report.export');
    
        //作廢
        Route::get('edu_report/{report}/obsolete', [EduReportController::class,'obsolete'])->name('edu_report.obsolete');
        Route::get('edu_report/{report}/copy', [EduReportController::class,'copy'])->name('edu_report.copy');
    
        //催促公告
        Route::post('edu_report/post', [EduReportController::class,'post'])->name('edu_report.post');
    
        //退回學校的填報
        Route::get('edu_report/{report_school}/set_back', [EduReportController::class,'set_back'])->name('edu_report.set_back');
        Route::get('edu_report/{report_school}/set_null', [EduReportController::class,'set_null'])->name('edu_report.set_null');
    
    
        //掛載檔案
        Route::get('introduction/upload/{path?}', [IntroductionController::class,'upload'])->name('introductions.upload');
        //修改名稱
        Route::get('introduction/{upload}/{path}/upload_edit', [IntroductionController::class,'upload_edit'])->name('introductions.upload_edit');
        Route::post('introduction/upload_store_name', [IntroductionController::class,'upload_store_name'])->name('introductions.upload_store_name');
    
        //公開文件
        Route::get('open_files_delete/{path}' , [IntroductionController::class,'delete'])->name('open_files.delete');
        Route::post('open_files_create_folder' , [IntroductionController::class,'create_folder'])->name('open_files.create_folder');
        Route::post('open_files_create_url' , [IntroductionController::class,'create_url'])->name('open_files.create_url');
        Route::post('open_files_upload_file' , [IntroductionController::class,'upload_file'])->name('open_files.upload_file');
    
        //跑馬燈
        Route::get('marquees' , [MarqueeController::class,'index'])->name('marquees.index');
        Route::get('marquees/create' , [MarqueeController::class,'create'])->name('marquees.create');
        Route::post('marquees' , [MarqueeController::class,'store'])->name('marquees.store');
        Route::get('marquees/{marquee}/edit' , [MarqueeController::class,'edit'])->name('marquees.edit');
        Route::patch('marquees/{marquee}/update' , [MarqueeController::class,'update'])->name('marquees.update');
        Route::delete('marquees/{marquee}/destroy' , [MarqueeController::class,'destroy'])->name('marquees.destroy');
});

//教育處科室內的人，且是一級管理A身分才可進入
//教育處科室長官可用
Route::group(['middleware' => 'edu_admin'],function(){
    //公告審查
    Route::get('posts/review', [PostsController::class,'review'])->name('posts.review');
    //退回指定的公告內容
    Route::patch('posts/{post}/return', [PostsController::class,'return'])->name('posts.return');
    //核准指定的公告內容
    Route::get('posts/{post}/approve', [PostsController::class,'approve'])->name('posts.approve');
    //將核准的公告寫到Post_schools資料表
    Route::post('posts/{post}/addPostSchools', [PostsController::class,'addPostSchools'])->name('posts.addPostSchools');

    //修改的指定公告
    Route::get('posts/{id}/eduadminedit', [PostsController::class,'eduadminedit'])->name('posts.eduadminedit');
    //實際儲存修改好的公告資料
    Route::patch('posts/{id}/eduadminupdate', [PostsController::class,'eduadminupdate'])->name('posts.eduadminupdate');

    //資料填報審查
    Route::get('reports/review', [EduReportController::class,'review'])->name('reports.review');
    //退回指定的公告內容
    Route::patch('reports/{report}/return', [EduReportController::class,'return'])->name('reports.return');
    //核准指定的公告內容
    Route::patch('reports/{report}/approve', [EduReportController::class,'approve'])->name('reports.approve');

    //審核者可看
    Route::get('reports/section_all', [EduReportController::class,'section_all'])->name('reports.section_all');


});

//學校一級管理A才可進入
//學校管理可用
Route::group(['middleware' => 'school_admin'],function(){
    //列出學校帳號
    Route::get('school_acc', [SchoolController::class,'index'])->name('school_acc.index');
    //列出所有管理學校的權限名單
    Route::get('school_acc/list', [SchoolController::class,'list'])->name('school_acc.list');
    Route::get('school_acc/{id}/power_remove', [SchoolController::class,'power_remove'])->name('school_acc.power_remove');
    //修改帳號
    Route::get('school_acc/{user}/edit', [SchoolController::class,'edit'])->name('school_acc.edit');
    Route::post('school_acc/{user}/update', [SchoolController::class,'update'])->name('school_acc.update');
    Route::get('school_acc/{user}/destroy', [SchoolController::class,'destroy'])->name('school_acc.destroy');
    Route::get('school_acc/{user}/reback', [SchoolController::class,'reback'])->name('school_acc.reback');

    Route::patch('school_report/{report_school}/back', [SchoolReportController::class,'back'])->name('school_report.back');
    Route::patch('school_report/{report_school}/delay', [SchoolReportController::class,'delay'])->name('school_report.delay');
    Route::patch('school_report/{report_school}/cancel', [SchoolReportController::class,'cancel'])->name('school_report.cancel');
    Route::patch('school_report/{report_school}/passing', [SchoolReportController::class,'passing'])->name('school_report.passing');

    Route::post('school_acc/other', [SchoolController::class,'other'])->name('school_acc.other');
    Route::post('school_acc/store_other', [SchoolController::class,'store_other'])->name('school_acc.store_other');

    //學校簡介
    Route::get('school_introduction', [SchoolController::class,'school_introduction'])->name('school_introduction.index');
    Route::post('school_introduction_store', [SchoolController::class,'school_introduction_store'])->name('school_introduction.store');
});

//學校一級簽收B才可進入
//學校簽收填報者可用
Route::group(['middleware' => 'school_sign'], function () {
    //顯示簽收公告
    Route::get('posts/showSigned', [PostsController::class,'showSigned'])->name('posts.showSigned');
    Route::get('posts/show_not_Signed', [PostsController::class,'show_not_Signed'])->name('posts.show_not_Signed');
    Route::get('posts/show_quick_Signed', [PostsController::class,'show_quick_Signed'])->name('posts.show_quick_Signed');
    Route::get('posts/show_person_Signed', [PostsController::class,'show_person_Signed'])->name('posts.show_person_Signed');
    //列印簽收公告
    Route::get('posts/{post}/showSigned_print', [PostsController::class,'showSigned_print'])->name('posts.showSigned_print');
    Route::get('posts/{post}/showSigned_print2', [PostsController::class,'showSigned_print2'])->name('posts.showSigned_print2');
    Route::get('posts/{post}/showSigned_print3', [PostsController::class,'showSigned_print3'])->name('posts.showSigned_print3');

    //簽收公告路由
    Route::patch('posts/{ps_id}/signed', [PostsController::class,'signed'])->name('posts.signed');
    Route::patch('posts/{ps_id}/signed_at_show', [PostsController::class,'signed_at_show'])->name('posts.signed_at_show');
    Route::post('posts/signed_more', [PostsController::class,'signed_more'])->name('posts.signed_more');
    Route::patch('posts/{ps_id}/signed2', [PostsController::class,'signed2'])->name('posts.signed2');
    Route::patch('posts/{ps_id}/signed3', [PostsController::class,'signed3'])->name('posts.signed3');

    //搜尋公告編號主旨、內文、公告人
    Route::match(['post', 'get'], 'posts/search', [PostsController::class,'search'])->name('posts.search');
    Route::get('posts/search_by_section/{section_id}', [PostsController::class,'search_by_section'])->name('posts.search_by_section');

    //資料填報
    Route::get('school_report', [SchoolReportController::class,'index'])->name('school_report.index');
    Route::get('school_report_not', [SchoolReportController::class,'not_index'])->name('school_report_not.index');
    Route::get('show_person_Signed', [SchoolReportController::class,'show_person_Signed'])->name('school_report.show_person_Signed');
    //搜尋填報編號主旨、內文、公告人
    Route::match(['post', 'get'], 'school_report/search', [SchoolReportController::class,'search'])->name('school_report.search');
    Route::get('school_report/{report_school}/create', [SchoolReportController::class,'create'])->name('school_report.create');
    //20230815取消這功能
    Route::get('school_report/{report_school}/no_report', [SchoolReportController::class,'no_report'])->name('school_report.no_report');
    Route::post('school_report/store', [SchoolReportController::class,'store'])->name('school_report.store');
    Route::get('school_report/{report_school}/show', [SchoolReportController::class,'show'])->name('school_report.show');
    Route::get('school_report/{report_school}/edit', [SchoolReportController::class,'edit'])->name('school_report.edit');
    Route::patch('school_report/update', [SchoolReportController::class,'update'])->name('school_report.update');
    Route::post('school_report/save_temp', [SchoolReportController::class,'save_temp'])->name('school_report.save_temp');
    Route::post('school_report/pull_temp/{report_id}', [SchoolReportController::class,'pull_temp'])->name('school_report.pull_temp');

    //列印資料填報
    Route::get('school_report/{report_school}/print', [SchoolReportController::class,'print'])->name('school_report.print');
});

//其他類學校的單位
Route::group(['middleware' => 'school_sign_other'], function () {
    //顯示簽收公告
    Route::get('posts/showSigned_other', [PostsController::class,'showSigned_other'])->name('posts.showSigned_other');
    //簽收公告路由
    Route::patch('posts/{ps_id}/signed_other', [PostsController::class,'signed_other'])->name('posts.signed_other');
    //其他單位人員管理
    Route::get('posts/people_other', [PostsController::class,'people_other'])->name('posts.people_other');
    Route::post('posts/people_add', [PostsController::class,'people_add'])->name('posts.people_add');
    Route::get('posts/{user}/people_remove', [PostsController::class,'people_remove'])->name('posts.people_remove');
});
//顯示最新公告列表，暫時沒用到先註解掉
//Route::get('posts', [PostsController::class,'index'])->name('posts.index');

//秀出指定的公告
Route::get('posts/{post}/{ps_id?}', [PostsController::class,'show'])->name('posts.show');
Route::get('posts_print/{post}', [PostsController::class,'print'])->name('posts.print');

//下載檔案
Route::get('download/{id}/{filename}', [PostsController::class,'download'])->name('posts.download');

//顯示上傳的圖片
Route::get('img/{file_path}', [PostsController::class,'getImg'])->name('posts.img');
//下載圖片
Route::get('downloadimage/{id}/{filename}', [PostsController::class,'downloadimage'])->name('posts.downloadimage');

//各科室介紹
Route::get('introduction/{type}/show/{section_id}', [IntroductionController::class,'show'])->name('introductions.show');
Route::get('introduction/{type}/show2/{section_id}', [IntroductionController::class,'show2'])->name('introductions.show2');
Route::get('introduction/{section_id}/section_page_show/{section_page}', [IntroductionController::class,'section_page_show'])->name('introductions.section_page_show');

//學校介紹
Route::get('school/school_map', [SchoolController::class,'school_map'])->name('introductions.school_map');
Route::get('school/school_list', [SchoolController::class,'school_list'])->name('introductions.school_list');
Route::get('school/{code_no}/school_show', [SchoolController::class,'school_show'])->name('introductions.school_show');

//檔案下載
Route::get('open_file/{path?}', [IntroductionController::class,'show_download'])->name('introductions.show_download');
Route::get('open_files_download/{path}', [IntroductionController::class,'download'])->name('open_files.download');