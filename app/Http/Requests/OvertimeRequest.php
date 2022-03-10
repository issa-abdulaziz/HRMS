<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\OvertimeConflict;
use App\Rules\AfterHiring;

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
            'employee_id' => 'required|exists:employees,id',
            'date' => [
                'required',
                'date',
                new AfterHiring($this->employee_id),
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
