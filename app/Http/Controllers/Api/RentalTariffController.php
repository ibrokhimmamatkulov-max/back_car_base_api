<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RentalTariff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RentalTariffController extends Controller
{
    public function index()
    {
        $rental_tariff = RentalTariff::with('car.model_car.brand')->orderByDesc('id')->get();
        return $this->success($rental_tariff);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'performer_transport_id' => 'required|exists:auto_baza.performer_transports,id',
            'duration_days'          => 'required|integer|min:1',
            'price'                  => 'required|numeric|min:0',
            'free_weekend_day'       => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', 422, $validator->errors());
        }

        $rental_tariff = RentalTariff::create($validator->validated());
        return $this->success($rental_tariff, 'Тариф создан', 201);
    }

    public function show(int $id)
    {
        $rental_tariff = RentalTariff::with('car.model_car.brand')->find($id);
        if (!$rental_tariff) {
            return $this->error('Тариф не найден', 404);
        }
        return $this->success($rental_tariff);
    }

    public function update(Request $request, int $id)
    {
        $rental_tariff = RentalTariff::find($id);
        if (!$rental_tariff) {
            return $this->error('Тариф не найден', 404);
        }

        $validator = Validator::make($request->all(), [
            'duration_days'    => 'sometimes|integer|min:1',
            'price'            => 'sometimes|numeric|min:0',
            'free_weekend_day' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', 422, $validator->errors());
        }

        $rental_tariff->update($validator->validated());
        return $this->success($rental_tariff);
    }

    public function destroy(int $id)
    {
        $rental_tariff = RentalTariff::find($id);
        if (!$rental_tariff) {
            return $this->error('Тариф не найден', 404);
        }
        $rental_tariff->delete();
        return $this->success(null, 'Тариф удалён');
    }
}
