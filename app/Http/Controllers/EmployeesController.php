<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Shift;
use App\Http\Requests\EmployeeRequest;

class EmployeesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = auth()->user()->employees()->orderBy('full_name', 'asc')->get();
        return view('employee.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shifts = auth()->user()->shifts()->get(['id', 'title']);
        return view('employee.create', compact('shifts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeRequest $request)
    {
        Employee::create([
            'full_name' => $request->full_name,
            'date_of_birth' => $request->date_of_birth,
            'city' => $request->city,
            'phone_number' => $request->phone_number,
            'hired_at' => $request->hired_at,
            'position' => $request->position,
            'salary' => $request->salary,
            'active' => $request->has('active') ? 1 : 0,
            'taken_vacations_days' => 0,
            'vacation_start_count_at' => $request->vacation_start_count_at ? $request->vacation_start_count_at . '-1' : null,
            'shift_id' => $request->shift_id,
            'user_id' => auth()->id(),
        ]);
        return redirect()->route('employee.index')->with('success', 'employee Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        abort_if($employee->user_id !== auth()->id(), 403);
        $date = date('Y-m');

        $overtimeAmount = $employee->getOvertimeAmount($date);
        $advancedPaymentAmount = $employee->getAdvancedPaymentAmount($date);
        $absentDay = $employee->getAbsentDay($date);
        $absentDayDiscountAmount = $employee->getAbsentDayDiscountAmount($date);
        $leewayDiscount = $employee->getLeewayDiscount($date);

        $totalLeeway = $employee->getTotalLeeway($date);
        $leewayTime = floor($totalLeeway / 60)  . ':' . $totalLeeway % 60;

        $total = $overtimeAmount - $advancedPaymentAmount - $absentDayDiscountAmount - $leewayDiscount;

        $vacationDays = $employee->getVacationDays();
        $inTimePercentage = $employee->getInTimePercentage();

        return view('employee.show', compact('employee', 'overtimeAmount', 'advancedPaymentAmount', 'absentDay', 'absentDayDiscountAmount', 'leewayTime', 'leewayDiscount', 'total', 'vacationDays', 'inTimePercentage'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        abort_if($employee->user_id !== auth()->id(), 403);
        $shifts = Shift::whereBelongsTo(auth()->user())->get(['id', 'title']);
        return view('employee.edit', compact('employee', 'shifts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EmployeeRequest $request, Employee $employee)
    {
        abort_if($employee->user_id !== auth()->id(), 403);
        $employee->update([
            'full_name' => $request->full_name,
            'date_of_birth' => $request->date_of_birth,
            'city' => $request->city,
            'phone_number' => $request->phone_number,
            'hired_at' => $request->hired_at,
            'position' => $request->position,
            'salary' => $request->salary,
            'active' => $request->has('active') ? 1 : 0,
            'vacation_start_count_at' => $request->vacation_start_count_at ? $request->vacation_start_count_at . '-1' : null,
            'shift_id' => $request->shift_id,
        ]);
        return redirect()->route('employee.index')->with('success', 'employee Edited Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        abort_if($employee->user_id !== auth()->id(), 403);
        $employee->delete();
        return redirect()->route('employee.index')->with('success', 'Employee Deleted Successfully');
    }
    public function getData(Employee $employee)
    {
        if ($employee->user_id !== auth()->id())
            return response()->json(['message' => 'forbiden'], 403);

        $months_arr = []; // can't be array, should be collection inorder to use the map function
        for ($t = 0; $t < 12; $t++) {
            $months_arr[] = date("Y-m", strtotime(date('Y-m-01') . " -$t months"));
        }
        $months = collect(array_reverse($months_arr));

        $monthsLabel = $months->map(function ($month, $key) {
            return date('M', strtotime($month));
        });

        $data = $months->map(function ($month, $key) use ($employee) {

            $overtimeTotal = $employee->getOvertimeAmount($month);
            $absenceTotal = $employee->getAbsentDayDiscountAmount($month);
            $leewayTotal = $employee->getLeewayDiscount($month);
            $advancedPaymentTotal = $employee->getAdvancedPaymentAmount($month);
            $overall = $overtimeTotal - $leewayTotal - $absenceTotal - $advancedPaymentTotal;

            return [
                'overtimeTotal' => $overtimeTotal,
                'absenceTotal' => $absenceTotal,
                'leewayTotal' => $leewayTotal,
                'advancedPaymentTotal' => $advancedPaymentTotal,
                'overall' => $overall,
            ];
        });

        return response()->json([
            'monthsLabel' => $monthsLabel,
            'data' => $data,
        ]);
    }
}
