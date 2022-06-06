<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CrossMidnightTimeValidation implements Rule
{
    private $starting_time,$end_time;

    public function __construct($starting_time, $end_time)
    {
        $this->starting_time = $starting_time;
        $this->end_time = $end_time;

    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute,  $value)
    {
        return ($value >= $this->starting_time && $value <= now()->endOfDay()->format('H:i'))
            || (($value >= now()->startOfDay()->format('H:i') && $value <= $this->end_time));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid Time (out of the shift time).';
    }
}
