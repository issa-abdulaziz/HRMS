<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shift;
use App\Http\Requests\ShiftRequest;

class ShiftsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shifts = auth()->user()->shifts;
        return view('shift.index', compact('shifts'));
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
        Shift::create([
            'title' => $request->title,
            'starting_time' => $request->starting_time,
            'leaving_time' => $request->leaving_time,
            'across_midnight' => $request->has('across_midnight') ? 1 : 0,
            'user_id' => auth()->id(),
        ]);
        return redirect()->route('shift.index')->with('success','Shift Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Shift $shift)
    {
        return redirect()->route('shift.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Shift $shift)
    {
        abort_if($shift->user_id !== auth()->id(), 403);
        return view('shift.edit', compact('shift'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ShiftRequest $request, Shift $shift)
    {
        abort_if($shift->user_id !== auth()->id(), 403);
        $shift->update([
            'title' => $request->title,
            'starting_time' => $request->starting_time,
            'leaving_time' => $request->leaving_time,
            'across_midnight' => $request->has('across_midnight') ? 1 : 0,
        ]);
        return redirect()->route('shift.index')->with('success','Shift Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shift $shift)
    {
        abort_if($shift->user_id !== auth()->id(), 403);
        $shift->delete();
        return redirect()->route('shift.index')->with('success','Shift deleted Successfully');
    }
}
