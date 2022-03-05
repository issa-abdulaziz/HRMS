<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
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
            'full_name' => 'required|min:3|max:50',
            'date_of_birth' => 'required|date',
            'city' => 'required|min:3|max:50',
            'phone_number' => 'required|min:8|max:50',
            'hired_at' => 'required|date',
            'position' => 'required|min:3|max:50',
            'salary' => 'required|integer',
            'shift_id' => 'required',
        ];
    }
}
