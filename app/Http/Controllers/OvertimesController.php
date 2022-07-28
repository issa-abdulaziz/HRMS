<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\OvertimeRequest;
use App\Models\Overtime;
use App\Models\Employee;

class OvertimesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->except('_token');
        $overtimes = auth()->user()->overtimes()->filter($params)->with('employee:id,full_name')->orderBy('date', 'desc')->get();
        $date = $request->has('date') ? $params['date'] : date('Y') . '-' . date('m');
        return view('overtime.index')->with(['overtimes' => $overtimes, 'date' => $date]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = auth()->user()->employees()->whereActive(1)->orderBy('full_name', 'asc')->get(['id', 'full_name']);
        return view('overtime.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OvertimeRequest $request)
    {
        Overtime::create([
            'date' => $request->date,
            'time' => $request->time,
            'rate' => $request->rate,
            'salary' => $request->salary,
            'working_hour' => $request->working_hour,
            'amount' => $request->amount,
            'employee_id' => $request->employee_id,
            'note' => $request->note ? $request->note : 'N/A',
        ]);
        return redirect()->route('overtime.index')->with('success', 'Overtime Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Overtime $overtime)
    {
        abort_if($overtime->employee->user_id !== auth()->id(), 403);
        return view('overtime.show', compact('overtime'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Overtime $overtime)
    {
        abort_if($overtime->employee->user_id !== auth()->id(), 403);
        $employees = auth()->user()->employees()->whereActive(1)->orderBy('full_name', 'asc')->get(['id', 'full_name']);
        return view('overtime.edit', compact('overtime', 'employees'))->with(['overtime' => $overtime, 'employees' => $employees]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OvertimeRequest $request, Overtime $overtime)
    {
        abort_if($overtime->employee->user_id !== auth()->id(), 403);

        $overtime->update([
            'date' => $request->date,
            'time' => $request->time,
            'rate' => $request->rate,
            'salary' => $request->salary,
            'working_hour' => $request->working_hour,
            'amount' => $request->amount,
            'employee_id' => $request->employee_id,
            'note' => $request->note ? $request->note : 'N/A',
        ]);
        return redirect()->route('overtime.index')->with('success', 'Overtime Edited Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Overtime $overtime)
    {
        abort_if($overtime->employee->user_id !== auth()->id(), 403);
        $overtime->delete();
        return redirect()->route('overtime.index')->with('success', 'Overtime Deleted Successfully');
    }

    public function getHourlyPrice(Employee $employee)
    {
        if ($employee->user_id !== auth()->id())
            return response()->json(['message' => 'forbiden'], 403);

        return response()->json([
            'hourly_price' => $employee->can_take_vacation,
            'salary' => $employee->salary,
            'hired_at' => $employee->hired_at,
            'working_hour' => $employee->shift->working_hour,
        ]);
    }

    public function getRate($date)
    {
        return response()->json([
            'rate' => session('setting')->getRate($date),
        ]);
    }
}
