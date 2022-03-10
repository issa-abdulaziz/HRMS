<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Employee;

class AfterVacationIsAllowed implements Rule
{
    private $employee_id;
    private $takingVacationAllowedAt;
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
        $this->takingVacationAllowedAt = Employee::find($this->employee_id)->getTakingVacationStartAt();
        return $value >= $this->takingVacationAllowedAt;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute should be after or equal to ' . $this->takingVacationAllowedAt;
    }
}
