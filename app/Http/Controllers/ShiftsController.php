<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shift;
use App\Http\Requests\ShiftRequest;

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
    public function store(ShiftRequest $request)
    {
        $request->validated();

        $shift = new Shift();
        $shift->title = $request->title;
        $shift->starting_time = $request->starting_time;
        $shift->leaving_time = $request->leaving_time;
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
        $shift = Shift::findOrFail($id);
        return view('shift.edit')->with('shift',$shift);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ShiftRequest $request, $id)
    {
        $request->validated();
        
        $shift = Shift::findOrFail($id);
        $shift->title = $request->title;
        $shift->starting_time = $request->starting_time;
        $shift->leaving_time = $request->leaving_time;
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
        $shift = Shift::findOrFail($id);
        $shift->delete();
        return redirect('/shift')->with('success','Shift deleted Successfully');
    }
}
