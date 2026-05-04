<?php

namespace App\Http\Requests;

use App\Models\Car\ClassCar;
use App\Models\CarBrand;
use App\Models\CategoryCar;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CarModelRequest extends FormRequest
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
            'car_model' => 'required|string',
            'car_seat_from' => 'nullable|integer',
            'car_seat_before' => 'nullable|integer',
            'car_brand_id' => ['required', Rule::exists(CarBrand::class,'id')],
            'category_car_id' => ['required', Rule::exists(CategoryCar::class,'id')],
            'class_car_id' => ['required', Rule::exists(ClassCar::class,'id')],
            'is_active' => ['boolean']
        ];
    }
}
