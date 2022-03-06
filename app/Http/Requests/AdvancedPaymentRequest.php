<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\AdvancedPaymentConflict;
use App\Rules\AfterHiring;

class AdvancedPaymentRequest extends FormRequest
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
            'employee_id' => 'required',
            'date' => [
                'required',
                'date',
                new AfterHiring($this->employee_id),
                new AdvancedPaymentConflict($this->employee_id, $this->advanced_payment),
            ],
            'amount' => 'required|numeric|min:1',
        ];
    }
}
