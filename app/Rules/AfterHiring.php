<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Employee;

class AfterHiring implements Rule
{
    private $employee_id;
    private $hiredAt;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($employee_id)
    {
        $this->employee_id = $employee_id;
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
        $this->hiredAt = Employee::find($this->employee_id)->hired_at;
        return $value >= $this->hiredAt;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Date should be after hiring ' . $this->hiredAt;
    }
}