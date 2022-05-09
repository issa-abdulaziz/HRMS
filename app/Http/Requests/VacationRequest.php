<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Employee;
use App\Models\Vacation;
use App\Rules\AfterVacationIsAllowed;
use App\Rules\IsWeekend;
use App\Rules\EmployeeNotChanged;
use App\Rules\VacationDaysRule;
use App\Rules\VacationConflict;

class VacationRequest extends FormRequest
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
            'employee_id' => ['required', 'exists:employees,id', $this->isMethod('PUT') ? new EmployeeNotChanged($this->vacation) : ''],
            'days' => ['required', 'integer', 'min:1', new VacationDaysRule($this->employee_id, $this->vacation)],
            'date_from' => [
                'required',
                'date',
                new AfterVacationIsAllowed($this->employee_id),
                new VacationConflict($this->employee_id, $this->days ,$this->vacation),
                new IsWeekend()
            ],
        ];
    }
}
