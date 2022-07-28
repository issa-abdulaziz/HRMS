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

    public function update(SettingRequest $request, Setting $setting)
    {
        $setting->update([
            'weekend' => $request->weekend,
            'normal_overtime_rate' => $request->normalOvertimeRate,
            'weekend_overtime_rate' => $request->weekendOvertimeRate,
            'leeway_discount_rate' => $request->leewayDiscountRate,
            'vacation_rate' => $request->vacationRate,
            'taking_vacation_allowed_after' => $request->takingVacationAllowedAfter,
            'currency' => $request->currency,
        ]);
        session(['setting' => $setting]);
        return redirect()->route('setting.index')->with('success', 'Setting Updated Successfully');
    }
}
