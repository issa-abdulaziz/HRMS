<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use DateTime;
use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Shift;

class AttendanceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shifts = Shift::select('id', 'title')->get();
        $employees = Employee::where('active', 1)
        ->where('shift_id', $shifts[0]->id)
        ->where('hired_at','<=',date('Y-m-d'))->get();
        return view('attendance.index')->with(['shifts' => $shifts, 'employees' => $employees]);
    }

    public function check(Request $request) {
        $date = $request->date;
        $shift_id = $request->shift_id;
        $shift = Shift::find($shift_id);

        $employeesInVacation = Employee::select('employees.id', 'employees.full_name')
        ->where('active', 1)
        ->orderBy('employees.full_name','asc')
        ->where('employees.shift_id',$shift_id)
        ->where('employees.hired_at','<=',$date)
        ->join('vacations', function ($join) use ($date) {
            $join->on('employees.id', '=', 'vacations.employee_id')
                 ->where('vacations.date_from', '<=', $date)
                 ->where('vacations.date_to', '>=', $date);
        })
        ->get();

        $employeesInVacation_ids = $employeesInVacation->map(function($employee) { return $employee->id;});
        
        $employees = Employee::select('id', 'full_name')->where('active',1)
        ->whereNotIn('id', $employeesInVacation_ids)
        ->orderBy('full_name','asc')
        ->where('shift_id',$shift_id)
        ->where('hired_at','<=',$date)->get();

        $employees_ids = $employees->map(function($employee) { return $employee->id;});
        
        $attendance = Attendance::where('date',$date)
        ->where(function($query) use ($employees_ids,$employeesInVacation_ids) {
            $query->whereIn('employee_id', $employees_ids)
            ->orWhereIn('employee_id', $employeesInVacation_ids);
        })->get();

        return response()->json([
            'attendance' => $attendance,
            'employees' => $employees,
            'employeesInVacation' => $employeesInVacation,
            'shift' => $shift,
        ]);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date|date_format:Y-m-d',
            'present' => 'required',
        ]);
        if ($validator->passes()) {
            $employee = Employee::find($request->employee_id);
            $shift = $employee->shift;
            $attendance = Attendance::where('employee_id', $request->employee_id)->where('date', $request->date)->first();
            
            if ( $attendance == null ) {
                $attendance = new Attendance();    
            }

            $attendance->date = $request->date;
            $attendance->present = filter_var($request->present, FILTER_VALIDATE_BOOLEAN);
            if ( filter_var($request->present, FILTER_VALIDATE_BOOLEAN) ) {
                $starting_time = new DateTime($shift->starting_time);
                $time_in = new DateTime($request->time_in);
                $comming_diff = $starting_time->diff($time_in);
                $comming_leeway = $comming_diff->h * 60 + $comming_diff->i;

                $leaving_time = new DateTime($shift->leaving_time);
                $time_out = new DateTime($request->time_out);
                $leaving_diff = $leaving_time->diff($time_out);
                $leaving_leeway = $leaving_diff->h * 60 + $leaving_diff->i;

                if ($shift->across_midnight) {
                    $midnight = today();
                    $starting_time_min = $starting_time->diff($midnight)->h * 60 + $starting_time->diff($midnight)->i;
                    $time_in_min = $time_in->diff($midnight)->h * 60 + $time_in->diff($midnight)->i;

                    $leaving_time_min = $leaving_time->diff($midnight)->h * 60 + $leaving_time->diff($midnight)->i;
                    $time_out_min = $time_out->diff($midnight)->h * 60 + $time_out->diff($midnight)->i;

                    if( $starting_time_min + $comming_leeway !== $time_in_min ) {
                        $comming_leeway = 24 * 60 - $comming_leeway;
                    }

                    if( $time_out_min + $leaving_leeway !== $leaving_time_min ) {
                        $leaving_leeway = 24 * 60 - $leaving_leeway;
                    }
                }

                $leeway = $comming_leeway + $leaving_leeway;

                $attendance->time_in = $request->time_in;
                $attendance->time_out = $request->time_out;
                $attendance->total_leeway = $leeway;
            }
            else {
                $attendance->time_in = null;
                $attendance->time_out = null;
                $attendance->total_leeway = 0;
            }
            $attendance->note = $request->note;

            $employee->attendances()->save($attendance);

			return response()->json(['success'=>'Added new records.']);
        }

        return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function destroy($date) {
        $deletedRows = Attendance::where('date', $date)->delete();
        if ($deletedRows)
            return redirect()->back()->withInput()->with('success','records at ' . $date . ' deleted successfully');
        return redirect()->back()->withInput()->with('error','records at ' . $date . ' has been not deleted');
    }

}
