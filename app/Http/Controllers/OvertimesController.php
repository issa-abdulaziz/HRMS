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
        $overtimes = Overtime::filter($params)->orderBy('date', 'desc')->get();
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
        $employees = Employee::select('id', 'full_name')->where('active', 1)->orderBy('full_name', 'asc')->get();
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

        $overtime = new Overtime();
        $overtime->date = $request->date;
        $overtime->time = $request->time;
        $overtime->rate = $request->rate;
        $overtime->salary = $request->salary;
        $overtime->working_hour = $request->working_hour;
        $overtime->amount = $request->amount;
        $overtime->employee_id = $request->employee_id;
        $overtime->note = $request->note ? $request->note : 'N/A';

        $employee = Employee::find($request->employee_id);
        $employee->overtimes()->save($overtime);

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
        $employees = Employee::select('id', 'full_name')->where('active', 1)->orderBy('full_name', 'asc')->get();
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
        $overtime->date = $request->date;
        $overtime->time = $request->time;
        $overtime->rate = $request->rate;
        $overtime->salary = $request->salary;
        $overtime->working_hour = $request->working_hour;
        $overtime->amount = $request->amount;
        $overtime->employee_id = $request->employee_id;
        $overtime->note = $request->note ? $request->note : 'N/A';

        $employee = Employee::find($request->employee_id);
        $employee->overtimes()->save($overtime);

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
        $overtime->delete();
        return redirect()->route('overtime.index')->with('success', 'Overtime Deleted Successfully');
    }

    public function getHourlyPrice(Request $request)
    {
        $employee = Employee::findOrFail($request->employee_id);

        return response()->json([
            'hourly_price' => $employee->getHourlyPrice(),
            'salary' => $employee->salary,
            'hired_at' => $employee->hired_at,
            'working_hour' => $employee->shift->getWorkingHour(),
        ]);
    }

    public function getRate(Request $request)
    {
        return response()->json([
            'rate' => session('setting')->getRate($request->date),
        ]);
    }
}
