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
        $advancedPayments = auth()->user()->advancedPayments()->filter($params)->with('employee:id,full_name')->orderBy('date', 'desc')->get();
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
        $employees = auth()->user()->employees()->whereActive(1)->orderBy('full_name', 'asc')->get(['id', 'full_name']);
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
        AdvancedPayment::create([
            'date' => $request->date,
            'amount' => $request->amount,
            'employee_id' => $request->employee_id,
            'note' => $request->note ? $request->note : 'N/A',
        ]);
        return redirect()->route('advanced-payment.index')->with('success', 'Advanced Payment Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(AdvancedPayment $advancedPayment)
    {
        abort_if($advancedPayment->employee->user_id !== auth()->id(), 403);
        return view('advancedPayment.show', compact('advancedPayment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(AdvancedPayment $advancedPayment)
    {
        abort_if($advancedPayment->employee->user_id !== auth()->id(), 403);
        $employees = auth()->user()->employees()->whereActive(1)->orderBy('full_name', 'asc')->get(['id', 'full_name']);
        return view('advancedPayment.edit', compact('advancedPayment', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdvancedPaymentRequest $request, AdvancedPayment $advancedPayment)
    {
        abort_if($advancedPayment->employee->user_id !== auth()->id(), 403);
        $advancedPayment->update([
            'date' => $request->date,
            'amount' => $request->amount,
            'employee_id' => $request->employee_id,
            'note' => $request->note ? $request->note : 'N/A',
        ]);
        return redirect()->route('advanced-payment.index')->with('success', 'Advanced Payment Edited Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdvancedPayment $advancedPayment)
    {
        abort_if($advancedPayment->employee->user_id !== auth()->id(), 403);
        $advancedPayment->delete();
        return redirect()->route('advanced-payment.index')->with('success', 'Advanced Pyament Deleted Successfully');
    }

    public function getData(Employee $employee)
    {
        if ($employee->user_id !== auth()->id())
            return response()->json(['message' => 'forbiden'], 403);

        return response()->json([
            'hired_at' => $employee->hired_at,
        ]);
    }
}
