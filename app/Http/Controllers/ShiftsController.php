<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shift;

class ShiftsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shifts = Shift::all();
        return view('shift.index')->with('shifts',$shifts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('shift.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'title' => 'required|max:50',
            'starting_time' => 'required|date_format:H:i',
            'leaving_time' => 'required|date_format:H:i',
        ]);
        $shift = new Shift();
        $shift->title = $request->input('title');
        $shift->starting_time = $request->input('starting_time');
        $shift->leaving_time = $request->input('leaving_time');
        $shift->across_midnight = $request->has('across_midnight') ? 1 : 0;
        $shift->save();
        return redirect('/shift')->with('success','Shift Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('/shift');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $shift = Shift::find($id);
        if (is_null($shift)){
            return redirect('/shift')->with('error','this id does not exist');
        }
        return view('shift.edit')->with('shift',$shift);
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
        $this->validate($request,[
            'title' => 'required',
            'starting_time' => 'required',
            'leaving_time' => 'required',
        ]);
        $shift = Shift::find($id);
        $shift->title = $request->input('title');
        $shift->starting_time = $request->input('starting_time');
        $shift->leaving_time = $request->input('leaving_time');
        $shift->across_midnight = $request->has('across_midnight') ? 1 : 0;
        $shift->save();
        return redirect('/shift')->with('success','Shift Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $shift = Shift::find($id);
        $shift->delete();
        return redirect('/shift')->with('success','Shift deleted Successfully');
    }
}
