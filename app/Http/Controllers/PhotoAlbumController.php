<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PhotoAlbum;
use App\Models\Photo;
use Intervention\Image\Facades\Image;
use Purifier;
class PhotoAlbumController extends Controller
{
    public function index()
    {
        $photo_albums = PhotoAlbum::orderBy('id','DESC')->get();
        $data = [
            'photo_albums'=>$photo_albums,
        ];
        return view('photo_albums.index',$data);
    }

    public function guest()
    {
        $photo_albums = PhotoAlbum::orderBy('id','DESC')->get();
        $data = [
            'photo_albums'=>$photo_albums,
        ];
        return view('photo_albums.guest',$data);
    }

    public function guest_show(PhotoAlbum $photo_album)
    {
        $photos = Photo::where('photo_album_id',$photo_album->id)
            ->orderBy('id','ASC')
            ->get();
        $data = [
            'photo_album'=>$photo_album,
            'photos'=>$photos,
        ];
        return view('photo_albums.guest_show',$data);
    }

    public function create()
    {
        return view('photo_albums.create');
    }

    public function store(Request $request)
    {
        $att = $request->all();
        $att['album_name'] = Purifier::clean($att['album_name'], array('AutoFormat.AutoParagraph'=>false));

        $photo_album = PhotoAlbum::create($att);

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 新增了相簿 id：".$photo_album->id." 名稱：".$photo_album->album_name;
        logging('4',$event,get_ip());

        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function edit(PhotoAlbum $photo_album)
    {
        $data = [
            'photo_album'=>$photo_album,
        ];
        return view('photo_albums.edit',$data);
    }

    public function update(Request $request,PhotoAlbum $photo_album)
    {
        $att = $request->all();
        $att['album_name'] = Purifier::clean($att['album_name'], array('AutoFormat.AutoParagraph'=>false));
        $photo_album->update($att);

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 修改了相簿 id：".$photo_album->id." 名稱：".$photo_album->album_name;
        logging('4',$event,get_ip());

        echo "<body onload='opener.location.reload();window.close();'>";
    }

    public function show(PhotoAlbum $photo_album)
    {
        $photos = Photo::where('photo_album_id',$photo_album->id)
            ->orderBy('id','ASC')
            ->get();
        $data = [
            'photo_album'=>$photo_album,
            'photos'=>$photos,
        ];
        return view('photo_albums.show',$data);
    }


    public function store_photo(Request $request,PhotoAlbum $photo_album)
    {
        $result = [];
        $arrExt = array('jpg','jpeg','JPG','JPEG','gif','png','PNG','svg','bmp','webp','heic');
        try{
            //處理檔案上傳
            if ($request->hasFile('files')) {
                $files = $request->file('files');
                $i=0;
                foreach($files as $file){
                    $info = [
                        'mime-type' => $file->getMimeType(),
                        'original_filename' => $file->getClientOriginalName(),
                        'extension' => $file->getClientOriginalExtension(),
                        'size' => $file->getSize(),
                    ];
                    $filename = substr(hash('sha256',$info['original_filename']),0,10).".".$info['extension'];
                    $filename_small = substr(hash('sha256',$info['original_filename']),0,10)."-small".".".$info['extension'];

                    //$check = Photo::where('photo_name',$info['original_filename'])
                    //    ->where('photo_album_id',$photo_album->id)
                    //    ->first();

                    if(!in_array($info['extension'],$arrExt)){
                        $result['files'][$i] = [
                            'name'=> $info['original_filename'],
                            'error'=>'非照片檔！',
                            'type'=>$info['extension'],
                        ];
                    }else{
                        //if(!empty($check)){
                        //    $result['files'][$i] = [
                        //        'name'=> $filename,
                        //        'error'=>'已有相同檔名的照片！',
                        //        'type'=>$info['extension'],
                        //    ];
                        //}else{
                            $att['photo_album_id'] = $photo_album->id;
                            $att['photo_name'] = $filename;
                            $att['user_id'] = auth()->user()->id;
                            $photo = Photo::create($att);
                            //$file->storeAs('public/photo_albums/'.$photo_album->id, $filename);
                            $img = Image::make($file);
                            if(!file_exists(storage_path('app/public/photo_albums'))){
                                mkdir(storage_path('app/public/photo_albums'));
                            }

                            if(!file_exists(storage_path('app/public/photo_albums/'.$photo_album->id))){
                                mkdir(storage_path('app/public/photo_albums/'.$photo_album->id));
                            }
                            $img->resize(1440, null, function ($constraint) {
                                $constraint->aspectRatio();
                            })->save(storage_path('app/public/photo_albums/'.$photo_album->id.'/'.$filename));

                            $img->resize(400, null, function ($constraint) {
                                $constraint->aspectRatio();
                            })->save(storage_path('app/public/photo_albums/'.$photo_album->id.'/'.$filename_small));

                            //log
                            $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 新增了相片 id：".$photo->id." 名稱：".$photo->photo_name;
                            logging('4',$event,get_ip());


                            $result['files'][$i] = [
                                'url'=> '../../storage/photo_albums/'.$photo_album->id.'/'.$filename,
                                'name'=> $info['original_filename'],
                                'new_name'=>$filename,
                                'size'=>$info['size'],
                                'type'=>$info['extension'],
                                'photo_id'=>$photo->id,
                                //'thumbnailUrl'=>asset('storage/photo_albums/'.$info['original_filename']),
                                //'deleteUrl'=>route('photo_albums.delete_photo'),
                                //'deleteType'=>'GET',

                            ];

                            $i++;

                        //}
                    }
                }
            }
        }catch(\Exception $e){
            $result['files'][$i] = [
                'name'=> $info['original_filename'],
                'error'=>'上傳失敗！',
            ];
        }
        echo json_encode($result);
        return ;
    }

    public function delete_photo(Photo $photo)
    {
        if(file_exists(storage_path('app/public/photo_albums/'.$photo->photo_album_id.'/'.$photo->photo_name))){
            unlink(storage_path('app/public/photo_albums/'.$photo->photo_album_id.'/'.$photo->photo_name));
        }
        $photo->delete();

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 刪除了相片 id：".$photo->id." 名稱：".$photo->photo_name;
        logging('4',$event,get_ip());

        return redirect()->back();
    }

    public function delete(PhotoAlbum $photo_album)
    {
        del_folder(storage_path('app/public/photo_albums/'.$photo_album->id));
        Photo::where('photo_album_id',$photo_album->id)->delete();
        $photo_album->delete();

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 刪除了相簿 id：".$photo_album->id." 名稱：".$photo_album->album_name;
        logging('4',$event,get_ip());

        return redirect()->back();
    }
}
