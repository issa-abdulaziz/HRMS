<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vacation;
use App\Models\Employee;
use App\Http\Requests\VacationRequest;
use App\models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class VacationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->except('_token');
        $vacations = auth()->user()->vacations()->filter($params)->with('employee:id,full_name')->orderBy('date_from', 'desc')->get();
        $date = $request->has('date') ? $params['date'] : date('Y-m');
        return view('vacation.index', compact('vacations', 'date'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = auth()->user()->employees()->whereActive(1)->orderBy('full_name', 'asc')->get()->where('can_take_vacation', true);
        return view('vacation.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VacationRequest $request)
    {
        DB::transaction(function () use ($request) {
            $date_from = Carbon::parse($request->date_from);
            $date_to = Carbon::parse(getDateTo($request->date_from, $request->days));
            $totalDiffDays = $date_from->diffInDays($date_to);
            Vacation::create([
                'date_from' => $date_from,
                'date_to' => $date_to,
                'days' => $request->days,
                'employee_id' => $request->employee_id,
                'note' => $request->note ? $request->note : 'N/A',
            ]);
            Employee::findOrFail($request->employee_id)->increment('taken_vacations_days', $request->days);
            $attendances = [];
            for ($x = 0; $x <= $totalDiffDays; $x++) {
                $date = $date_from->copy()->addDays($x);
                if (!isWeekend($date->format('l')))
                    $attendances[] = [
                        'employee_id' => $request->employee_id,
                        'date' => $date,
                        'present' => 0,
                        'time_in' => '',
                        'time_out' => '',
                        'note' => '',
                        'total_leeway' => 0,
                    ];
            }
            Attendance::where('employee_id', $request->employee_id)->whereBetween('date', [$date_from, $date_to])->delete();
            Attendance::insert($attendances);
        });
        return redirect()->route('vacation.index')->with('success', 'Vacation Added Successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Vacation $vacation)
    {
        abort_if($vacation->employee->user_id !== auth()->id(), 403);

        $totalVacationDays = $vacation->employee->vacation_days + $vacation->days;
        return view('vacation.edit', compact('vacation', 'totalVacationDays'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VacationRequest $request, Vacation $vacation)
    {
        abort_if($vacation->employee->user_id !== auth()->id(), 403);

        DB::transaction(function () use ($vacation, $request) {
            Employee::findOrFail($request->employee_id)->increment('taken_vacations_days', $request->days - $vacation->days);

            $date_from = Carbon::parse($request->date_from);
            $date_to = getDateTo($request->date_from, $request->days);
            $totalDiffDays = $date_from->diffInDays($date_to);

            $attendances = [];
            for ($x = 0; $x <= $totalDiffDays; $x++) {
                $date = $date_from->copy()->addDays($x);
                if (!isWeekend($date->format('l')))
                    $attendances[] = [
                        'employee_id' => $request->employee_id,
                        'date' => $date,
                        'present' => 0,
                        'time_in' => '',
                        'time_out' => '',
                        'note' => '',
                        'total_leeway' => 0,
                    ];
            }
            Attendance::where('employee_id', $request->employee_id)
            ->where(function($query) use ($date_from, $date_to, $vacation) {
                $query->whereBetween('date', [$date_from, $date_to])
                    ->orWhereBetween('date', [$vacation->date_from, $vacation->date_to]);
            })->delete();
            Attendance::insert($attendances);

            $vacation->update([
                'date_from' => $date_from,
                'date_to' => $date_to,
                'days' => $request->days,
                'note' => $request->note ? $request->note : 'N/A',
            ]);
        });
        return redirect()->route('vacation.index')->with('success', 'Vacation edited Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vacation $vacation)
    {
        abort_if($vacation->employee->user_id !== auth()->id(), 403);

        DB::transaction(function() use ($vacation) {
            $vacation->employee->decrement('taken_vacations_days', $vacation->days);
            Attendance::where('employee_id', $vacation->employee_id)->whereBetween('date', [$vacation->date_from, $vacation->date_to])->delete();
            $vacation->delete();
        });
        return redirect()->route('vacation.index')->with('success', 'Vacation Deleted Successfully');
    }

    public function getData(Employee $employee)
    {
        if ($employee->user_id !== auth()->id())
            return response()->json(['message' => 'forbiden'], 403);

        return response()->json([
            'vacationStartCountAt' => $employee->taking_vacation_start_at,
            'vacationDays' => $employee->vacation_days,
        ]);
    }
}
