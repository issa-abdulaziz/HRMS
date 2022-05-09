<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Vacation;

class VacationConflict implements Rule
{
    private $employee_id;
    private $vacation_id;
    private $dateFrom;
    private $dateTo;
    private $vacationDays;
    private $conflictedDateFrom;
    private $conflictedDateTo;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($employee_id, $vacationDays, $vacation)
    {
        $this->employee_id = $employee_id;
        $this->vacationDays = $vacationDays;
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
        $this->dateFrom = $value;
        $this->dateTo = Vacation::getDateTo($value, $this->vacationDays);
        $vacation = Vacation::where('employee_id', $this->employee_id)
        ->where('id', '!=', $this->vacation_id)
        ->where(function($query) {
            $query->Where(function($query) {
                $query->where('date_from', '<=', $this->dateFrom)
                ->where('date_to', '>=', $this->dateFrom);
            })->orWhere(function($query) {
                $query->where('date_from', '<=', $this->dateTo)
                ->where('date_to', '>=', $this->dateTo);
            })->orWhere(function($query)  {
                $query->where('date_from', '>=', $this->dateFrom)
                ->where('date_to', '<=', $this->dateTo);
            });
        })->first();

        if ($vacation) {
            $this->conflictedDateFrom = $vacation->date_from;
            $this->conflictedDateTo = $vacation->date_to;
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This vacation conflict with another one From ' . $this->conflictedDateFrom . ' To ' . $this->conflictedDateTo;
    }
}
