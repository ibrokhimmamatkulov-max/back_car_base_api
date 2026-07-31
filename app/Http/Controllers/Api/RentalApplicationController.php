<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RentalApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RentalApplicationController extends Controller
{
    public function index()
    {
        $rental_application = RentalApplication::with(['car', 'tariff', 'user', 'city', 'status'])
            ->orderByDesc('id')->get();
        return $this->success($rental_application);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'performer_transport_id' => 'required|exists:performer_transports,id',
            'rental_tariff_id'       => 'required|exists:rental_tariffs,id',
            'user_id'                => 'nullable|exists:mysql_taxi.users,id',
            'phone'                  => 'required|string|max:30',
            'city_id'                => 'required|exists:cities,id',
            'promo_code'             => 'nullable|string|max:50',
            'status_id'              => 'required|exists:application_statuses,id',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', 422, $validator->errors());
        }

        $rental_application = RentalApplication::create($validator->validated());
        return $this->success($rental_application, 'Заявка создана', 201);
    }

    public function show(int $id)
    {
        $rental_application = RentalApplication::with(['car.model_car.brand', 'tariff', 'user', 'city', 'status'])->find($id);
        if (!$rental_application) {
            return $this->error('Заявка не найдена', 404);
        }
        return $this->success($rental_application);
    }

    public function update(Request $request, int $id)
    {
        $rental_application = RentalApplication::find($id);
        if (!$rental_application) {
            return $this->error('Заявка не найдена', 404);
        }

        $validator = Validator::make($request->all(), [
            'status_id'  => 'required|exists:application_statuses,id',
            'promo_code' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', 422, $validator->errors());
        }

        $rental_application->update($validator->validated());
        return $this->success($rental_application);
    }

    public function destroy(int $id)
    {
        $rental_application = RentalApplication::find($id);
        if (!$rental_application) {
            return $this->error('Заявка не найдена', 404);
        }
        $rental_application->delete();
        return $this->success(null, 'Заявка удалена');
    }
}
