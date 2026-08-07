<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LandingApplicationRequest;
use App\Http\Resources\Landing\OfferDetailResource;
use App\Models\ApplicationStatus;
use App\Models\PerformerTransport;
use App\Models\RentalApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LandingController extends Controller
{
    // 1. Тот же список городов, что и в GET /api/cities
    public function cities(): JsonResponse
    {
        return app(CityController::class)->index();
    }

    // 2. Те же данные и фильтры, что и в GET /api/cars
    public function offers(Request $request): JsonResponse
    {
        return app(CarController::class)->index($request);
    }

    // 3. Детальная страница объявления
    public function offer(int $id): JsonResponse
    {
        $offer = PerformerTransport::where('active', PerformerTransport::ACTIVE)
            ->with([
                'model_car.brand',
                'gearbox',
                'city',
                'body_type',
                'color',
                'fuel_type',
                'photos',
                'tariffs',
                'dopOptions.car_option',
            ])
            ->find($id);

        if (! $offer) {
            return $this->error('Объявление не найдено.', 404);
        }

        return $this->success(new OfferDetailResource($offer));
    }

    // 4-6. Приём заявки
    public function apply(LandingApplicationRequest $request): JsonResponse
    {
        $data = $request->validated();

        // Если указан tariff_id, проверяем принадлежность к объявлению
        if (! empty($data['tariff_id'])) {
            $validTariff = DB::table('car_rental_tariff')
                ->where('rental_tariff_id', $data['tariff_id'])
                ->where('performer_transport_id', $data['offer_id'])
                ->exists();

            if (! $validTariff) {
                return $this->error('Тариф не относится к данному объявлению.', 422);
            }
        }

        $statusId = ApplicationStatus::first()?->id ?? 1;

        $application = RentalApplication::create([
            'performer_transport_id' => $data['offer_id'],
            'rental_tariff_id'       => $data['tariff_id'] ?? null,
            'name'                   => $data['name'],
            'phone'                  => $data['phone'],
            'city_id'                => $data['city_id'],
            'comment'                => $data['comment'] ?? null,
            'status_id'              => $statusId,
        ]);

        return $this->success(
            ['application_id' => $application->id],
            'Заявка принята. Мы свяжемся с вами в ближайшее время.',
            201
        );
    }
}
