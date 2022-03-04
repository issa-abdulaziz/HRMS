<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\AdvancedPayment;
use Carbon\Carbon;

class AdvancedPaymentConflict implements Rule
{
    private $employee_id;
    private $advanced_payment_id;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($employee_id,$advanced_payment_id)
    {
        $this->employee_id = $employee_id;
        $this->advanced_payment_id = $advanced_payment_id;
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
        $date = new Carbon($value);
        return AdvancedPayment::where('employee_id', $this->employee_id)->where('date','like',$date->format('Y-m') . '%')->where('id', '!=', $this->advanced_payment_id)->count() == 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Employee already take an Advanced Payment in this month';
    }
}
