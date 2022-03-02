<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use DateInterval;
use App\Models\Vacation;
use App\Models\Employee;
use App\Models\Setting;

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
    public function store(Request $request)
    {
        if ( !($request->has('employee_id'))) {
            return redirect()->back()->withInput()->with('error','Employee not selected');
        }
        
        $employee = Employee::find($request->input('employee_id'));

        $this->validate($request,[
            'date_from' => 'required|date|after_or_equal:' . $employee->vacation_start_count_at,
            'days' => 'required|integer|between:1,' . floor($employee->getVacationDays()),
            'employee_id' => 'required',
        ]);
        
        $weekendDay = $this->setting->weekend;

        $date_from = $request->input('date_from');
        $check_date_from = new DateTime($date_from);
        if ($check_date_from->format('l') == $weekendDay) {
            $date = new DateTime($date_from);
            $date_from = $date->add(new DateInterval('P' . ( 1 ). 'D'))->format('Y-m-d');
        }

        $days_collection = collect([]);
        $vacationDays = $request->input('days');

        for ($x = 0; $x < $vacationDays ; $x++) {
            $date = new DateTime($date_from);
            $day = $date->add(new DateInterval('P' . ( $x ). 'D'))->format('l');
            $days_collection[] = $day;
            if ( $x == ( $vacationDays - 1 ) && $day == $weekendDay ) {
                $date = new DateTime($date_from);
                $days_collection[] = $date->add(new DateInterval('P' . ( $x + 1 ). 'D'))->format('l');
            }
        }

        $weekends = $days_collection->filter(function($item) use ($weekendDay) {
            return $item == $weekendDay;
        });

        $date = new DateTime($date_from);
        $date_to = $date->add(new DateInterval('P' . ( $weekends->count() + $vacationDays - 1 ). 'D'))->format('Y-m-d');
        $check_date_to = new DateTime($date_to);
        if ($check_date_to->format('l') == $weekendDay) {
            $date = new DateTime($date_from);
            $date_to = $date->add(new DateInterval('P' . ( $weekends->count() + $vacationDays ). 'D'))->format('Y-m-d');
        }

        $hasVacation = Vacation::where('employee_id', $employee->id)
        ->where(function($query) use ($date_from,$date_to) {
            $query->Where(function($query) use ($date_from) {
                $query->where('date_from', '<=', $date_from)
                ->where('date_to', '>=', $date_from);
            })->orWhere(function($query) use ($date_to) {
                $query->where('date_from', '<=', $date_to)
                ->where('date_to', '>=', $date_to);
            })->orWhere(function($query) use ($date_from, $date_to) {
                $query->where('date_from', '>=', $date_from)
                ->where('date_to', '<=', $date_to);
            });
        })->get();

        if ($hasVacation->count()) {
            return redirect()->back()->withInput()->with('error','This vacation conflict with another one');            
        }
        
        $vacation = new Vacation();
        $vacation->date_from = $date_from;
        $vacation->date_to = $date_to;
        $vacation->days = $vacationDays;
        $vacation->employee_id = $employee->id;
        $vacation->note = $request->input('note') ? $request->input('note') : 'N/A';

        $employee->vacations()->save($vacation);
        $employee->taken_vacations_days += $vacationDays;
        $employee->save();

        return redirect('/vacation')->with('success','Vacation Added Successfully');
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
        $vacation = Vacation::find($id);
        if (is_null($vacation)){
            return redirect('/vacation')->with('error','this id does not exist');
        }
        
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
    public function update(Request $request, $id)
    {
        $vacation = Vacation::find($id);
        $oldVacationDays = $vacation->days;

        $this->validate($request,[
            'date_from' => 'required|date|after_or_equal:' . $vacation->employee->vacation_start_count_at,
            'days' => 'required|integer|between:1,' . (floor($vacation->employee->getVacationDays()) + $oldVacationDays),
            'employee_id' => 'required',
        ]);
        
        $weekendDay = $this->setting->weekend;

        $date_from = $request->input('date_from');
        $check_date_from = new DateTime($date_from);
        if ($check_date_from->format('l') == $weekendDay) {
            $date = new DateTime($date_from);
            $date_from = $date->add(new DateInterval('P' . ( 1 ). 'D'))->format('Y-m-d');
        }

        $days_collection = collect([]);
        $vacationDays = $request->input('days');

        for ($x = 0; $x < $vacationDays ; $x++) {
            $date = new DateTime($date_from);
            $day = $date->add(new DateInterval('P' . ( $x ). 'D'))->format('l');
            $days_collection[] = $day;
            if ( $x == ( $vacationDays - 1 ) && $day == $weekendDay ) {
                $date = new DateTime($date_from);
                $days_collection[] = $date->add(new DateInterval('P' . ( $x + 1 ). 'D'))->format('l');
            }
        }

        $weekends = $days_collection->filter(function($item) use ($weekendDay) {
            return $item == $weekendDay;
        });

        $date = new DateTime($date_from);
        $date_to = $date->add(new DateInterval('P' . ( $weekends->count() + $vacationDays - 1 ). 'D'))->format('Y-m-d');
        $check_date_to = new DateTime($date_to);
        if ($check_date_to->format('l') == $weekendDay) {
            $date = new DateTime($date_from);
            $date_to = $date->add(new DateInterval('P' . ( $weekends->count() + $vacationDays ). 'D'))->format('Y-m-d');
        }

        $employee_id = $vacation->employee->id;
        
        $hasVacation = Vacation::where('employee_id', $employee_id)
        ->where('date_from', '!=', $vacation->date_from)
        ->where('date_to', '!=', $vacation->date_to)
        ->where(function($query) use ($date_from,$date_to) {
            $query->Where(function($query) use ($date_from) {
                $query->where('date_from', '<=', $date_from)
                ->where('date_to', '>=', $date_from);
            })->orWhere(function($query) use ($date_to) {
                $query->where('date_from', '<=', $date_to)
                ->where('date_to', '>=', $date_to);
            })->orWhere(function($query) use ($date_from, $date_to) {
                $query->where('date_from', '>=', $date_from)
                ->where('date_to', '<=', $date_to);
            });
        })->get();
        
        if ( $hasVacation->count() ) {
            return redirect()->back()->withInput()->with('error','This vacation conflict with another one');            
        }

        $vacation->date_from = $date_from;
        $vacation->date_to = $date_to;
        $vacation->days = $vacationDays;
        $vacation->note = $request->input('note') ? $request->input('note') : 'N/A';

        $employee = Employee::find($request->input('employee_id'));
        $employee->vacations()->save($vacation);
        $employee->taken_vacations_days += $vacationDays - $oldVacationDays;
        $employee->save();


        return redirect('/vacation')->with('success','Vacation Added Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vacation = Vacation::find($id);
        $vacation->employee->taken_vacations_days -= $vacation->days;
        $vacation->employee->save();
        $vacation->delete();
        return redirect('/vacation')->with('success','Vacation Deleted Successfully');
    }

    public function getData(Request $request) {
        $employee = Employee::find($request->employee_id);
        return response()->json([
            'vacationStartCountAt' => $employee->getTakingVacationStartAt(),
            'vacationDays' => $employee->getVacationDays(),
        ]);
    }
}