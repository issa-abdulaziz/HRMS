<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Employee;

class AfterHiring implements Rule
{
    private $hiredDate;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($employee_id)
    {
        $this->hiredDate = $employee_id ? $this->getHiredAt($employee_id) : null;
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
        return $this->hiredDate ? $value >= $this->hiredDate : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Date should be after hiring';
    }
    
    private function getHiredAt($employee_id) {
        $employee = Employee::find($employee_id);
        return $employee->hired_at;
    }
}
