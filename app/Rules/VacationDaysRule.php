<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Employee;
use App\Models\Vacation;

class VacationDaysRule implements Rule
{
    private $employee_id;
    private $vacation_id;
    private $totalVacationDays;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($employee_id, $vacation)
    {
        $this->employee_id = $employee_id;
        $this->vacation_id = $vacation?->id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $vacation = Vacation::find($this->vacation_id);
        $oldVacationDays = $vacation ? $vacation->days : 0;
        $this->totalVacationDays = Employee::find($this->employee_id)->vacation_days + $oldVacationDays;
        return $value <= $this->totalVacationDays;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Vacation Days can not exced ' . floor($this->totalVacationDays);
    }
}
