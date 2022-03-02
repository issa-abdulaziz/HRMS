<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use App\Models\AdvancedPayment;
use App\Models\Setting;
use App\Models\Employee;

class AdvancedPaymentController extends Controller
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
        $advancedPayments = AdvancedPayment::filter($params)->orderBy('date','desc')->get();
        $date = $request->has('date') ? $params['date'] : date('Y') . '-' . date('m');
        return view('advancedPayment.index')->with(['advancedPayments' => $advancedPayments, 'currency' => $this->setting->currency, 'date' => $date]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::select('id', 'full_name')->where('active',1)->orderBy('full_name','asc')->get();
        return view('advancedPayment.create')->with(['employees' => $employees, 'setting' => $this->setting]);
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
            'amount' => 'required|numeric',
            'employee_id' => 'required',
        ]);

        $employee_id = $request->input('employee_id');
        $date = new DateTime($request->input('date'));
        $hasAdvancedPayment = AdvancedPayment::where('employee_id', $employee_id)->whereMonth('date',$date->format('m'))->whereYear('date',$date->format('Y'));
        if ($hasAdvancedPayment->count()) {
            return redirect()->back()->withInput()->with('error','Employee already take an Advanced Payment in this month');            
        }

        $advancedPayment = new AdvancedPayment();
        $advancedPayment->date = $request->input('date');
        $advancedPayment->amount = $request->input('amount');
        $advancedPayment->employee_id = $request->input('employee_id');
        $advancedPayment->note = $request->input('note') ? $request->input('note') : 'N/A';

        $employee = Employee::find($request->input('employee_id'));
        $employee->advancedPayments()->save($advancedPayment);

        return redirect('/advanced-payment')->with('success','Advanced Payment Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $advancedPayment = AdvancedPayment::find($id);
        return view('advancedPayment.show')->with(['advancedPayment' => $advancedPayment, 'setting' => $this->setting]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $advancedPayment = AdvancedPayment::find($id);
        if (is_null($advancedPayment)){
            return redirect('/advanced-payment')->with('error','this id does not exist');
        }
        $employees = Employee::select('id', 'full_name')->where('active',1)->orderBy('full_name','asc')->get();
        return view('advancedPayment.edit')->with(['advancedPayment' => $advancedPayment ,'employees' => $employees, 'setting' => $this->setting]);
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
        if ( !($request->has('employee_id'))) {
            return redirect()->back()->withInput()->with('error','Employee not selected');
        }
        $employee = Employee::find($request->input('employee_id'));

        $this->validate($request,[
            'date' => 'required|date|after_or_equal:' . $employee->hired_at,
            'amount' => 'required|numeric',
            'employee_id' => 'required',
        ]);

        $advancedPayment = AdvancedPayment::find($id);
        
        $employee_id = $request->input('employee_id');
        $newDate = new DateTime($request->input('date'));
        $oldDate = new DateTime($advancedPayment->date);
        $hasAdvancedPayment = AdvancedPayment::where('employee_id', $employee_id)->whereMonth('date',$newDate->format('m'))->whereYear('date',$newDate->format('Y'));
        if ( ($advancedPayment->employee_id == $employee_id && $oldDate->format('Y-m') != $newDate->format('Y-m') && $hasAdvancedPayment->count() ) || ( $advancedPayment->employee_id != $employee_id && $hasAdvancedPayment->count() ) ) {
            return redirect('/advanced-payment')->with('error','Employee already take an Advanced Payment in this month');
        }

        $advancedPayment->date = $request->input('date');
        $advancedPayment->amount = $request->input('amount');
        $advancedPayment->employee_id = $request->input('employee_id');
        $advancedPayment->note = $request->input('note') ? $request->input('note') : 'N/A';

        $employee = Employee::find($request->input('employee_id'));
        $employee->advancedPayments()->save($advancedPayment);

        return redirect('/advanced-payment')->with('success','Advanced Payment Edited Successfully');
        }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $advancedPayment = AdvancedPayment::find($id);
        $advancedPayment->delete();
        return redirect('/advanced-payment')->with('success','Advanced Pyament Deleted Successfully');
    }
    
    public function getData(Request $request) {
        $employee = Employee::find($request->employee_id);
        return response()->json([
            'hired_at' => $employee->hired_at,
        ]);
    }
}
