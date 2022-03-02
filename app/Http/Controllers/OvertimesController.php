<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use App\Models\Overtime;
use App\Models\Setting;
use App\Models\Employee;

class OvertimesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $setting;
    public function __construct()
    {
        $this->setting = Setting::first();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->except('_token');
        $overtimes = Overtime::filter($params)->orderBy('date','desc')->get();
        $date = $request->has('date') ? $params['date'] : date('Y') . '-' . date('m');
        return view('overtime.index')->with(['overtimes' => $overtimes, 'currency' => $this->setting->currency, 'date' => $date]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::select('id', 'full_name')->where('active',1)->orderBy('full_name','asc')->get();
        return view('overtime.create')->with(['employees' => $employees, 'setting' => $this->setting]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ( !($request->has('employee_id'))) {
            return redirect()->back()->withInput()->with('error','Employee not selected');
        }
        $employee = Employee::find($request->input('employee_id'));

        $this->validate($request,[
            'date' => 'required|date|after_or_equal:' . $employee->hired_at,
            'time' => 'required|integer',
            'rate' => 'required|numeric|between:1,99.999',
            'salary' => 'required|integer',
            'working_hour' => 'required|numeric|between:1,99.999',
            'amount' => 'required|numeric',
            'employee_id' => 'required',
        ]);
        
        $employee_id = $request->input('employee_id');
        $date = $request->input('date');
        $hasOvertime = Overtime::where('employee_id', $employee_id)->where('date',$date);
        if ($hasOvertime->count()) {
            return redirect()->back()->withInput()->with('error','Employee already has an overtime in this date');            
        }

        $overtime = new Overtime();
        $overtime->date = $date;
        $overtime->time = $request->input('time');
        $overtime->rate = $request->input('rate');
        $overtime->salary = $request->input('salary');
        $overtime->working_hour = $request->input('working_hour');
        $overtime->amount = $request->input('amount');
        $overtime->employee_id = $employee_id;
        $overtime->note = $request->input('note') ? $request->input('note') : 'N/A';

        $employee->overtimes()->save($overtime);

        return redirect('/overtime')->with('success','Overtime Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $overtime = Overtime::find($id);
        return view('overtime.show')->with(['overtime' => $overtime, 'setting' => $this->setting]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $overtime = Overtime::find($id);
        if (is_null($overtime)){
            return redirect('/overtime')->with('error','this id does not exist');
        }
        $employees = Employee::select('id', 'full_name')->where('active',1)->orderBy('full_name','asc')->get();
        return view('overtime.edit')->with(['overtime' => $overtime, 'employees' => $employees, 'setting' => $this->setting]);
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
        $employee = Employee::find($request->input('employee_id'));

        $this->validate($request,[
            'date' => 'required|date|after_or_equal:' . $employee->hired_at,
            'time' => 'required|integer',
            'rate' => 'required|numeric|between:1,99.999',
            'salary' => 'required|integer',
            'working_hour' => 'required|numeric|between:1,99.999',
            'amount' => 'required|numeric',
            'employee_id' => 'required',
        ]);

        $overtime = Overtime::find($id);
        
        $employee_id = $request->input('employee_id');
        $date = $request->input('date');
        $hasOvertime = Overtime::where('employee_id', $employee_id)->where('date',$date);
        if ( ($overtime->employee_id == $employee_id && $overtime->date != $date && $hasOvertime->count() ) || ( $overtime->employee_id != $employee_id && $hasOvertime->count() ) ) {
            return redirect()->back()->withInput()->with('error','Employee already has an overtime in this date');            
        }

        $overtime->date = $date;
        $overtime->time = $request->input('time');
        $overtime->rate = $request->input('rate');
        $overtime->salary = $request->input('salary');
        $overtime->working_hour = $request->input('working_hour');
        $overtime->amount = $request->input('amount');
        $overtime->employee_id = $employee_id;
        $overtime->note = $request->input('note') ? $request->input('note') : 'N/A';

        $employee->overtimes()->save($overtime);

        return redirect('/overtime')->with('success','Overtime Edited Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $overtime = Overtime::find($id);
        $overtime->delete();
        return redirect('/overtime')->with('success','Overtime Deleted Successfully');
    }

    public function getHourlyPrice(Request $request){
        $employee = Employee::find($request->employee_id);

        return response()->json([
            'hourly_price' => $employee->getHourlyPrice(),
            'salary' => $employee->salary,
            'hired_at' => $employee->hired_at,
            'working_hour' => $employee->shift->getWorkingHour(),
        ]);
    }

    public function getRate(Request $request){
        return response()->json([
            'rate' => $this->setting->getRate($request->date),
        ]);
    }
}
