<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceRequest;
use App\Models\Attendance;
use App\Models\Shift;
use Carbon\Carbon;
use Exception;
use Illuminate\Validation\ValidationException;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shifts = auth()->user()->shifts()->get(['id', 'title']);
        $employees = auth()->user()->employees()->whereActive(1)->where('shift_id', $shifts->first()?->id)->where('hired_at', '<=', now())->orderBy('full_name', 'asc')->get();
        return view('attendance.index', compact('shifts', 'employees'));
    }

    public function check($date, Shift $shift)
    {
        abort_if($shift->user_id != auth()->id(), 403);

        $employeesInVacation = auth()->user()->employees()->whereActive(1)->where('shift_id', $shift->id)
        ->where('hired_at', '<=', $date)->inVacation($date)->pluck('id')->toArray();

        $employees = auth()->user()->employees()->whereActive(1)->where('shift_id', $shift->id)->where('hired_at', '<=', $date)->with(['shift', 'attendances' => fn ($query) => $query->where('date', $date)])->orderBy('full_name', 'desc')->get();

        $data = $employees->map(function ($employee) use ($employeesInVacation) {
            return [
                'employee_id' => $employee->id,
                'full_name' => $employee->full_name,
                'present' => $employee->attendances->first() ? $employee->attendances->first()->present : 0,
                'time_in' => $employee->attendances->first() && $employee->attendances->first()->present ? Carbon::parse($employee->attendances->first()->time_in)->format('H:i') : '',
                'time_out' => $employee->attendances->first() && $employee->attendances->first()->present ? Carbon::parse($employee->attendances->first()->time_out)->format('H:i') : '',
                'note' => $employee->attendances->first() && $employee->attendances->first()->present ? $employee->attendances->first()->note : '',
                'has_attendance' => $employee->attendances->first() ? true : false,
                'in_vacation' => in_array($employee->id, $employeesInVacation),
            ];
        });

        return response()->json([
            'data' => $data,
            'shift' => $shift,
        ]);
    }

    public function store(AttendanceRequest $request)
    {
        $shift = Shift::find($request->shift);

        $employeesInVacation = auth()->user()->employees()->whereActive(1)->where('employees.shift_id', $shift->id)
        ->where('employees.hired_at', '<=', $request->date)->inVacation($request->date)->pluck('id')->toArray();

        foreach ($request->attendance as $key => $value) {
            $absent_or_inVacation = !array_key_exists('present', $value) || in_array($value['employee_id'], $employeesInVacation);
            if ($absent_or_inVacation)
            {
                Attendance::updateOrCreate([
                    'employee_id' => $value['employee_id'],
                    'date' => $request->date,
                ], [
                    'present' => 0,
                    'time_in' => '',
                    'time_out' => '',
                    'note' => '',
                    'total_leeway' => 0,
                ]);
            }
            else
            {
                $total_leeway = calculateDiffBtw2TimeString($shift->starting_time, $value['time_in'], $shift->across_midnight) + calculateDiffBtw2TimeString($value['time_out'], $shift->leaving_time, $shift->across_midnight);
                Attendance::updateOrCreate([
                    'employee_id' => $value['employee_id'],
                    'date' => $request->date,
                ], [
                    'present' => 1,
                    'time_in' => $value['time_in'],
                    'time_out' => $value['time_out'],
                    'note' => $value['note'] ?? '',
                    'total_leeway' => $total_leeway,
                ]);
            }

        }
        return redirect()->route('attendance.index')->with('success', 'Attendance Saved Successfully');
    }

    public function destroy($date)
    {
        try {
            $deletedRows = Attendance::where('date', $date)->whereHas('employee', fn ($query) => $query->whereBelongsTo(auth()->user()))->delete();
        } catch(Exception $e) {
            throw ValidationException::withMessages(['Something went wrong']);
        }
        if ($deletedRows)
            return redirect()->back()->withInput()->with('success', 'records at ' . $date . ' deleted successfully');
        return redirect()->back()->withInput()->with('error', 'There are no records at ' . $date . ' to be deleted');
    }
}
