<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Vacation;

class EmployeeNotChanged implements Rule
{
    private $vacation_id;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($vacation)
    {
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
        $vacation = Vacation::findOrFail($this->vacation_id);
        return $vacation->employee_id == $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Employee should not be changed';
    }
}
