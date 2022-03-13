<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdvancedPayment;
use App\Models\Employee;
use App\Http\Requests\AdvancedPaymentRequest;

class AdvancedPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->except('_token');
        $advancedPayments = AdvancedPayment::filter($params)->orderBy('date', 'desc')->get();
        $date = $request->has('date') ? $params['date'] : date('Y-m');
        return view('advancedPayment.index')->with(['advancedPayments' => $advancedPayments, 'date' => $date]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::select('id', 'full_name')->where('active', 1)->orderBy('full_name', 'asc')->get();
        return view('advancedPayment.create')->with(['employees' => $employees]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdvancedPaymentRequest $request)
    {
        $request->validated();

        $advancedPayment = new AdvancedPayment();
        $advancedPayment->date = $request->date;
        $advancedPayment->amount = $request->amount;
        $advancedPayment->employee_id = $request->employee_id;
        $advancedPayment->note = $request->note ? $request->note : 'N/A';

        $employee = Employee::find($request->employee_id);
        $employee->advancedPayments()->save($advancedPayment);

        return redirect('/advanced-payment')->with('success', 'Advanced Payment Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $advancedPayment = AdvancedPayment::findOrFail($id);
        return view('advancedPayment.show')->with(['advancedPayment' => $advancedPayment]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $advancedPayment = AdvancedPayment::findOrFail($id);
        $employees = Employee::select('id', 'full_name')->where('active', 1)->orderBy('full_name', 'asc')->get();
        return view('advancedPayment.edit')->with(['advancedPayment' => $advancedPayment, 'employees' => $employees]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdvancedPaymentRequest $request, $id)
    {
        $request->validated();

        $advancedPayment = AdvancedPayment::findOrFail($id);
        $advancedPayment->date = $request->date;
        $advancedPayment->amount = $request->amount;
        $advancedPayment->employee_id = $request->employee_id;
        $advancedPayment->note = $request->note ? $request->note : 'N/A';

        $employee = Employee::find($request->employee_id);
        $employee->advancedPayments()->save($advancedPayment);

        return redirect('/advanced-payment')->with('success', 'Advanced Payment Edited Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $advancedPayment = AdvancedPayment::findorFail($id);
        $advancedPayment->delete();
        return redirect('/advanced-payment')->with('success', 'Advanced Pyament Deleted Successfully');
    }

    public function getData(Request $request)
    {
        $employee = Employee::findOrFail($request->employee_id);
        return response()->json([
            'hired_at' => $employee->hired_at,
        ]);
    }
}
