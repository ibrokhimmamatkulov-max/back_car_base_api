<?php

namespace App\Http\Requests;

use App\Models\Car\ClassCar;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CarClassRequest extends FormRequest
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
            'category_car_id' => ['required', Rule::exists(ClassCar::class,'id')],
            'is_active' => ['boolean']
        ];
    }
}
