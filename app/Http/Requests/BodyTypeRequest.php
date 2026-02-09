<?php

namespace App\Http\Requests;

use App\Models\BodyType;
use App\Models\CategoryCar;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BodyTypeRequest extends FormRequest
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
    public function rules(Request $request)
    {
        if(request()->isMethod("PATCH")){
            $rules = [
                'category_car_id' => ['nullable', Rule::exists(CategoryCar::class,'id')],
                'name' => ['required','string','max:50',
                    Rule::unique(BodyType::class)->where(function ($query)use($request) {
                        return $query->where('name', $request->name)
                            ->where('category_car_id', $request->category_car_id);
                    })
                ],
                'is_active' => ['boolean']
            ];
        }else{
            $rules = [
                'category_car_id' => ['required', Rule::exists(CategoryCar::class,'id')],
                'name' => ['required','string','max:50',
                    Rule::unique(BodyType::class)->where(function ($query)use($request) {
                        return $query->where('name', $request->name)
                            ->where('category_car_id', $request->category_car_id);
                    })
                ],
                'is_active' => ['boolean']
            ];

        }

        return $rules;
    }
}
