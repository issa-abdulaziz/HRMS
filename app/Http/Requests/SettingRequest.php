<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->setting->user_id === auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'weekend' => 'required|min:6|max:9',
            'normalOvertimeRate' => 'required|numeric|between:1,99.999',
            'weekendOvertimeRate' => 'required|numeric|between:1,99.999',
            'leewayDiscountRate' => 'required|numeric|between:1,99.999',
            'vacationRate' => 'required|numeric|between:1,99.999',
            'takingVacationAllowedAfter' => 'required|integer|between:1,100',
            'currency' => 'required|min:2|max:4',
        ];
    }
}
