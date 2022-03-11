<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShiftRequest extends FormRequest
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
        $custom_rule = $this->has('across_midnight') ? 'before:starting_time' : 'after:starting_time';
        return [
            'title' => 'required|min:3|max:50',
            'starting_time' => 'required|date_format:H:i',
            'leaving_time' => 'required|date_format:H:i|' . $custom_rule,
        ];
    }
}
