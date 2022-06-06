<?php

namespace App\Http\Requests;

use App\models\Shift;
use App\Rules\CrossMidnightTimeValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $shift = Shift::find($this->shift);
        $rules = [
            'date' => 'required|date|date_format:Y-m-d',
            'shift' => 'required|exists:shifts,id,user_id,' . auth()->id(),
            'attendance' => 'required|array',
            'attendance.*.employee_id' => 'required|exists:employees,id,user_id,' . auth()->id(),
        ];
        if ($shift->across_midnight) {
            foreach ($this->attendance as $key => $value) {
                $rules['attendance.' . $key . '.time_in'] = [Rule::requiredIf($this->has('attendance.' . $key . '.present')), 'date_format:H:i', 'nullable', new CrossMidnightTimeValidation($shift?->starting_time, $shift?->leaving_time)];
                $rules['attendance.' . $key . '.time_out'] = [Rule::requiredIf($this->has('attendance.' . $key . '.present')), 'date_format:H:i', 'nullable', new CrossMidnightTimeValidation($shift?->starting_time, $shift?->leaving_time)];
            }
        }
        else {
            foreach ($this->attendance as $key => $value) {
                $rules['attendance.' . $key . '.time_in'] = [Rule::requiredIf($this->has('attendance.' . $key . '.present')), 'date_format:H:i', 'nullable', 'before_or_equal:attendance.' . $key . '.time_out', 'after_or_equal:' . $shift?->starting_time];
                $rules['attendance.' . $key . '.time_out'] = [Rule::requiredIf($this->has('attendance.' . $key . '.present')), 'date_format:H:i', 'nullable', 'before_or_equal:' . $shift?->leaving_time, 'after_or_equal:' . $shift?->starting_time];
            }
        }
        return $rules;
    }

    public function attributes()
    {

        foreach ($this->attendance as $key => $Value) {
            $attributes['attendance.' . $key . '.employee_id'] = 'Employee';
            $attributes['attendance.' . $key . '.time_in'] = 'Time In';
            $attributes['attendance.' . $key . '.time_out'] = 'Time Out';
        }
        return $attributes;
    }
}
