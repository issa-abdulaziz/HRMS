<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $setting = Setting::first();
        return view('setting.edit')->with('setting',$setting);   
    }

    public function update(SettingRequest $request, $id)
    {
        $request->validated();

        $setting = Setting::find($id);
        $setting->weekend = $request->input('weekend');
        $setting->normal_overtime_rate = $request->input('normalOvertimeRate');
        $setting->weekend_overtime_rate = $request->input('weekendOvertimeRate');
        $setting->leeway_discount_rate = $request->input('leewayDiscountRate');
        $setting->vacation_rate = $request->input('vacationRate');
        $setting->taking_vacation_allowed_after = $request->input('takingVacationAllowedAfter');
        $setting->currency = $request->input('currency');
        $setting->save();
        return redirect('/setting')->with('success','Setting Updated Successfully');
    }
}
