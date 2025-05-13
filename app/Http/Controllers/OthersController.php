<?php

namespace App\Http\Controllers;

use App\Models\Other;
use Illuminate\Http\Request;

class OthersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $others = Other::orderBy('order_by')
            ->get();
        return view('others.index',compact('others'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('others.create');
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
            'url' => 'required',
        ]);
        $other = Other::create($request->all());

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 新增了其他連結 id：".$other->id." 名稱：".$other->name;
        logging('5',$event,get_ip());

        return redirect()->route('others.index');
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
    public function edit(Other $other)
    {
        return view('others.edit',compact('other'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Other $other)
    {
        $other->update($request->all());

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 修改了其他連結 id：".$other->id." 名稱：".$other->name;
        logging('5',$event,get_ip());

        return redirect()->route('others.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Other $other)
    {
        $other->delete();

        //log
        $event = "管理者 ".auth()->user()->name."(".auth()->user()->username.") 刪除了其他連結 id：".$other->id." 名稱：".$other->name;
        logging('5',$event,get_ip());

        return redirect()->route('others.index');
    }
}
