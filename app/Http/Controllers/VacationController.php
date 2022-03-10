<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Vacation;
use App\Models\Employee;
use App\Models\Setting;
use App\Http\Requests\VacationRequest;

class VacationController extends Controller
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
    {   $params = $request->except('_token');
        $vacations = Vacation::filter($params)->orderBy('date_from','desc')->get();
        $date = $request->has('date') ? $params['date'] : date('Y') . '-' . date('m');
        return view('vacation.index')->with(['vacations' => $vacations, 'date' => $date]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::where('active',1)->orderBy('full_name','asc')->get();
        $employees = $employees->filter(function($employee) {
            return $employee->canTakeVacation();
        });

        return view('vacation.create')->with('employees', $employees);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VacationRequest $request)
    {
        $request->validated();

        $employee = Employee::find($request->employee_id);
        $vacationDays = $request->days;
        
        $vacation = new Vacation();
        $vacation->date_from = $request->date_from;
        $vacation->date_to = Vacation::getDateTo($request->date_from, $vacationDays);
        $vacation->days = $vacationDays;
        $vacation->employee_id = $request->employee_id;
        $vacation->note = $request->note ? $request->note : 'N/A';

        $employee->vacations()->save($vacation);
        $employee->taken_vacations_days += $vacationDays;
        $employee->save();

        return redirect()->route('vacation.index')->with('success','Vacation Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vacation = Vacation::findOrFail($id);
        $vacationDays = $vacation->employee->getVacationDays();
        $thisVacationDays = $vacation->days;
        $totalVacationDays = $vacationDays + $thisVacationDays;

        return view('vacation.edit')->with(['vacation' => $vacation, 'totalVacationDays' => $totalVacationDays]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VacationRequest $request, $id)
    {
        $request->validated();

        $vacation = Vacation::findOrFail($id);
        $employee = Employee::find($request->employee_id);
        
        $oldVacationDays = $vacation->days;
        $vacationDays = $request->days;

        $vacation->date_from = $request->date_from;
        $vacation->date_to = Vacation::getDateTo($request->date_from, $vacationDays);
        $vacation->days = $vacationDays;
        $vacation->note = $request->note ? $request->note : 'N/A';

        $employee->vacations()->save($vacation);
        $employee->taken_vacations_days += $vacationDays - $oldVacationDays;
        $employee->save();

        return redirect()->route('vacation.index')->with('success','Vacation Added Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vacation = Vacation::findOrFail($id);
        $vacation->employee->taken_vacations_days -= $vacation->days;
        $vacation->employee->save();
        $vacation->delete();
        return redirect()->route('vacation.index')->with('success','Vacation Deleted Successfully');
    }

    public function getData(Request $request) {
        $employee = Employee::findOrFail($request->employee_id);
        return response()->json([
            'vacationStartCountAt' => $employee->getTakingVacationStartAt(),
            'vacationDays' => $employee->getVacationDays(),
        ]);
    }
}