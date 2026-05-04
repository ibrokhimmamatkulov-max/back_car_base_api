<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ColorCarRequest extends FormRequest
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
            'name' => 'required|string',
            'name_tj' => 'required|string',
            'name_for_sms' => 'required|string|alpha|regex:/^[a-zA-Z]+$/u',
            'is_active' => ['boolean']
        ];
    }
}
