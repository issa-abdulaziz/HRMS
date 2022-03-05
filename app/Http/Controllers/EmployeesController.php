<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use App\Models\Employee;
use App\Models\Shift;
use App\Models\Setting;
use App\Models\Overtime;
use App\Models\AdvancedPayment;
use App\Models\Attendance;
use App\Http\Requests\EmployeeRequest;

class EmployeesController extends Controller
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
    public function index()
    {
        $employees = Employee::orderBy('full_name','asc')->get();
        return view('employee.index')->with(['employees' => $employees, 'currency' => $this->setting->currency]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shifts = Shift::select('id', 'title')->get();
        return view('employee.create')->with(['shifts' => $shifts, 'currency' => $this->setting->currency]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeRequest $request)
    {
        $request->validated();

        $employee = new Employee();
        $employee->full_name = $request->full_name;
        $employee->date_of_birth = $request->date_of_birth;
        $employee->city = $request->city;
        $employee->phone_number = $request->phone_number;
        $employee->hired_at = $request->hired_at;
        $employee->position = $request->position;
        $employee->salary = $request->salary;
        $employee->active = $request->has('active') ? 1 : 0;
        $employee->taken_vacations_days = 0;
        $employee->vacation_start_count_at = $request->vacation_start_count_at ? $request->vacation_start_count_at . '-1' : null;

        $shift = Shift::find($request->shift_id);
        $shift->employees()->save($employee);

        return redirect('/employee')->with('success','employee Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Employee::find($id);

        $date = date('Y-m');

        $overtimeAmount = $employee->getOvertimeAmount($date);
        $advancedPaymentAmount = $employee->getAdvancedPaymentAmount($date);
        $absentDay = $employee->getAbsentDay($date);
        $absentDayDiscountAmount = $employee->getAbsentDayDiscountAmount($date);
        $leewayDiscount = $employee->getLeewayDiscount($date);

        $totalLeeway = $employee->getTotalLeeway($date);
        $leewayTime = floor($totalLeeway / 60)  . ':' . $totalLeeway % 60;
        
        $vacationDays = $employee->getVacationDays();
        $inTimePercentage = $employee->getInTimePercentage();

        return view('employee.show')->with([
            'employee' => $employee,
            'currency' => $this->setting->currency,
            'overtimeAmount' => $overtimeAmount,
            'advancedPaymentAmount' => $advancedPaymentAmount,
            'absentDay' => $absentDay,
            'absentDayDiscountAmount' => $absentDayDiscountAmount,
            'leewayTime' => $leewayTime,
            'leewayDiscount' => $leewayDiscount,
            'total' => $overtimeAmount - $advancedPaymentAmount - $absentDayDiscountAmount - $leewayDiscount,
            'vacationDays' => $vacationDays,
            'inTimePercentage' => $inTimePercentage,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = Employee::find($id);
        if (is_null($employee)){
            return redirect('/employee')->with('error','this id does not exist');
        }
        $shifts = Shift::select('id', 'title')->get();
        return view('employee.edit')->with(['employee' => $employee, 'shifts' => $shifts, 'currency' => $this->setting->currency]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EmployeeRequest $request, $id)
    {
        $request->validated();

        $employee = Employee::find($id);
        $employee->full_name = $request->full_name;
        $employee->date_of_birth = $request->date_of_birth;
        $employee->city = $request->city;
        $employee->phone_number = $request->phone_number;
        $employee->hired_at = $request->hired_at;
        $employee->position = $request->position;
        $employee->salary = $request->salary;
        $employee->active = $request->has('active') ? 1 : 0;
        $employee->vacation_start_count_at = $request->vacation_start_count_at ? $request->vacation_start_count_at . '-1' : null;

        $shift = Shift::find($request->shift_id);
        $shift->employees()->save($employee);

        return redirect('/employee')->with('success','employee Edited Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee = Employee::find($id);
        $employee->delete();
        return redirect('/employee')->with('success','Employee Deleted Successfully');
    }
    public function getData(Request $request){
        
        $employee = Employee::find($request->employee_id);

        $months_arr = []; // can't be array, should be collection inorder to use the map function
        for ($t = 0; $t < 12; $t++) {
            $months_arr[]= date("Y-m", strtotime( date( 'Y-m-01' )." -$t months"));
        }
        $months = collect(array_reverse($months_arr));

        $monthsLabel = $months->map(function($month, $key) {
            return date('M',strtotime($month));
        });

        $data = $months->map(function($month, $key) use ($employee){

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
