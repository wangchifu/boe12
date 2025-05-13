<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;
use Purifier;
class LinksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $links = Link::orderBy('order_by')
            ->get();
        return view('links.index',compact('links'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = [1=>'學校用',2=>'民眾用'];
        $data = [
            'types'=>$types,
        ];
        return view('links.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'required|mimes:jpeg,png|max:5120',
            'url' => 'required',
        ]);

        $att['name'] = $request->input('name');
        $att['image'] = 'temp_name';
        $att['url'] = $request->input('url');
        $att['order_by'] = $request->input('order_by');
        $att['type'] = $request->input('type');

        $att['name'] = Purifier::clean($att['name'], array('AutoFormat.AutoParagraph'=>false));
        $att['url'] = Purifier::clean($att['url'], array('AutoFormat.AutoParagraph'=>false));


        $link = Link::create($att);

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 新增了宣導網站 id：".$link->id." 名稱：".$link->name;
        logging('5',$event,get_ip());

        //處理檔案上傳
        $allowed_extensions = ["png", "jpg", "pdf","PDF","JPG","odt","ODT","csv","txt","zip","jpeg"];
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $info = [
                'original_filename' => $image->getClientOriginalName(),
                'extension' => $image->getClientOriginalExtension(),
            ];
            if ( $info['extension'] && in_array($info['extension'],$allowed_extensions)) {
                $image_name = $link->id.'.'.$info['extension'];
                $image->storeAs('public/links',$image_name);
    
                $att2['image'] = $image_name;
                $link->update($att2);
            }
           
        }



        return redirect()->route('links.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Link $link)
    {
        $types = [1=>'學校用',2=>'民眾用'];
        $data = [
            'link'=>$link,
            'types'=>$types,
        ];
        return view('links.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Link $link)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'nullable|mimes:jpeg,png|max:5120',
            'url' => 'required',
        ]);

        $att['name'] = $request->input('name');
        $att['url'] = $request->input('url');
        $att['order_by'] = $request->input('order_by');
        $att['type'] = $request->input('type');

        $att['name'] = Purifier::clean($att['name'], array('AutoFormat.AutoParagraph'=>false));
        $att['url'] = Purifier::clean($att['url'], array('AutoFormat.AutoParagraph'=>false));

        $link->update($att);

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 修改了宣導網站 id：".$link->id." 名稱：".$link->name;
        logging('5',$event,get_ip());

        //處理檔案上傳
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $info = [
                'original_filename' => $image->getClientOriginalName(),
                'extension' => $image->getClientOriginalExtension(),
            ];

            if ( $info['extension'] && in_array($info['extension'],$allowed_extensions)) {
                $image_name = $link->id.'.'.$info['extension'];
                $image->storeAs('public/links',$image_name);
    
                $att2['image'] = $image_name;
                $link->update($att2);
            }
         
        }


        return redirect()->route('links.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Link $link)
    {
        $file = storage_path('app/public/links/'.$link->image);
        if(file_exists($file)){
            unlink($file);
        }
        $link->delete();

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 刪除了宣導網站 id：".$link->id." 名稱：".$link->name;
        logging('5',$event,get_ip());

        return redirect()->route('links.index');
    }
}
