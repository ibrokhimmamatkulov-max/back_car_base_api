<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class LandingApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:255',
            'phone'     => ['required', 'string', 'regex:/^(\+?992|0)?[0-9]{9}$/'],
            'city_id'   => ['required', 'integer', Rule::exists('cities', 'id')],
            'offer_id'  => ['required', 'integer', Rule::exists('performer_transports', 'id')->where('active', 1)],
            'tariff_id' => ['nullable', 'integer', Rule::exists('rental_tariffs', 'id')],
            'comment'   => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'Укажите ваше имя.',
            'phone.required'   => 'Укажите номер телефона.',
            'phone.regex'      => 'Некорректный номер телефона. Введите номер в формате 992XXXXXXXXX.',
            'city_id.required' => 'Выберите город.',
            'city_id.exists'   => 'Выбранный город не найден.',
            'offer_id.required'=> 'Не указано объявление.',
            'offer_id.exists'  => 'Объявление не найдено или недоступно.',
            'tariff_id.exists' => 'Выбранный тариф не найден.',
        ];
    }

    protected function failedValidation(Validator $validator): never
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'code'    => 422,
                'message' => 'Ошибка валидации.',
                'errors'  => $validator->errors(),
            ], 422)
        );
    }
}
