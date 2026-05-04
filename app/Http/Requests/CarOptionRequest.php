<?php

namespace App\Http\Requests;

use App\Models\Allowance;
use App\Models\CategoryCar;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CarOptionRequest extends FormRequest
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
            'category_car_id' => ['required', Rule::exists(CategoryCar::class,'id')],
            'name' => 'required|string',
            'is_active' => ['boolean']
        ];
    }
}
