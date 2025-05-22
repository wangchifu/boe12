<?php

namespace App\Http\Controllers;

use App\Models\Marquee;
use Illuminate\Http\Request;
use Purifier;
class MarqueeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $marquees = Marquee::orderBy('stop_date','DESC')
            ->paginate('20');
        $data = [
            'marquees'=>$marquees,
        ];
        return view('marquees.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('marquees.create');
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
            'title' => 'required|max:50',
            'start_date' => 'required',
            'stop_date' => 'required',
        ]);

        $att['title'] = Purifier::clean($request->input('title'), array('AutoFormat.AutoParagraph'=>false));
        $att['start_date'] = $request->input('start_date');
        $att['stop_date'] = $request->input('stop_date');
        $att['user_id'] = auth()->user()->id;
        Marquee::create($att);
        return redirect()->route('marquees.index');
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
    public function edit(Marquee $marquee)
    {
        if($marquee->user_id != auth()->user()->id){
            return back();
        }
        $data = [
            'marquee'=>$marquee,
        ];
        return view('marquees.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Marquee $marquee)
    {
        if($marquee->user_id != auth()->user()->id){
            return back();
        }

        $request->validate([
            'title' => 'required|max:50',
            'start_date' => 'required',
            'stop_date' => 'required',
        ]);

        $att['title'] = $request->input('title');
        $att['start_date'] = $request->input('start_date');
        $att['stop_date'] = $request->input('stop_date');
        $att['user_id'] = auth()->user()->id;
        $marquee->update($att);
        return redirect()->route('marquees.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Marquee $marquee)
    {
        if($marquee->user_id != auth()->user()->id){
            return back();
        }
        $marquee->delete();
        return redirect()->route('marquees.index');
    }

    public function delete(Marquee $marquee)
    {
        if($marquee->user_id != auth()->user()->id){
            return back();
        }
        $marquee->delete();
        return redirect()->route('index');
    }
}
