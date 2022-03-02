<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
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

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'weekend' => 'required|min:6|max:9',
            'normalOvertimeRate' => 'required|numeric|between:1,99.999',
            'weekendOvertimeRate' => 'required|numeric|between:1,99.999',
            'leewayDiscountRate' => 'required|numeric|between:1,99.999',
            'vacationRate' => 'required|numeric|between:1,99.999',
            'takingVacationAllowedAfter' => 'required|integer|between:1,100',
            'currency' => 'required|min:2|max:4',
        ]);
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
