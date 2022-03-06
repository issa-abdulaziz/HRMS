<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\OvertimeConflict;
use App\Models\Employee;

class OvertimeRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;
    
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
     * @return array
     */
    public function rules()
    {
        return [
            'employee_id' => 'required',
            'date' => [
                'required',
                'date',
                'after_or_equal:' . Employee::find($this->employee_id)->hired_at,
                new OvertimeConflict($this->employee_id, $this->overtime)
            ],
            'time' => 'required|integer|min:1',
            'rate' => 'required|numeric|between:1,99.999',
            'salary' => 'required|integer',
            'working_hour' => 'required|numeric|between:1,99.999',
            'amount' => 'required|numeric|min:1',
        ];
    }
}
