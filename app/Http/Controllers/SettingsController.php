<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Http\Requests\SettingRequest;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('setting.edit');
    }

    public function update(SettingRequest $request, $id)
    {
        $request->validated();

        $setting = Setting::findOrFail($id);
        $setting->weekend = $request->weekend;
        $setting->normal_overtime_rate = $request->normalOvertimeRate;
        $setting->weekend_overtime_rate = $request->weekendOvertimeRate;
        $setting->leeway_discount_rate = $request->leewayDiscountRate;
        $setting->vacation_rate = $request->vacationRate;
        $setting->taking_vacation_allowed_after = $request->takingVacationAllowedAfter;
        $setting->currency = $request->currency;
        $setting->save();
        return redirect('/setting')->with('success', 'Setting Updated Successfully');
    }
}
