<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vacation;
use App\Models\Employee;
use App\Http\Requests\VacationRequest;
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
        $date = $request->has('date') ? $params['date'] : date('Y') . '-' . date('m');
        return view('vacation.index', compact('vacations', 'date'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = auth()->user()->employees()->whereActive(1)->orderBy('full_name', 'asc')->get();
        $employees = $employees->filter(function ($employee) {
            return $employee->canTakeVacation();
        });
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
            Vacation::create([
                'date_from' => $request->date_from,
                'date_to' => getDateTo($request->date_from, $request->days),
                'days' => $request->days,
                'employee_id' => $request->employee_id,
                'note' => $request->note ? $request->note : 'N/A',
            ]);
            Employee::findOrFail($request->employee_id)->increment('taken_vacations_days', $request->days);
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

        $vacationDays = $vacation->employee->getVacationDays();
        $totalVacationDays = $vacationDays + $vacation->days;
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
            $vacation->update([
                'date_from' => $request->date_from,
                'date_to' => getDateTo($request->date_from, $request->days),
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
            $vacation->delete();
        });
        return redirect()->route('vacation.index')->with('success', 'Vacation Deleted Successfully');
    }

    public function getData(Employee $employee)
    {
        if ($employee->user_id !== auth()->id())
            return response()->json(['message' => 'forbiden'], 403);

        return response()->json([
            'vacationStartCountAt' => $employee->getTakingVacationStartAt(),
            'vacationDays' => $employee->getVacationDays(),
        ]);
    }
}
