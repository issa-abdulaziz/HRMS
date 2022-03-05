<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Overtime;

class OvertimeConflict implements Rule
{
    private $employee_id;
    private $overtime_id;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($employee_id, $overtime_id)
    {
        $this->employee_id = $employee_id;
        $this->overtime_id = $overtime_id;
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
        return Overtime::where('employee_id', $this->employee_id)->where('date',$value)->where('id', '!=', $this->overtime_id)->count() == 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Employee already has an overtime in this date';
    }
}
