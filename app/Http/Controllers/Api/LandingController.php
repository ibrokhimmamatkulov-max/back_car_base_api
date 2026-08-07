<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LandingApplicationRequest;
use App\Http\Resources\Landing\OfferDetailResource;
use App\Http\Resources\Landing\OfferListResource;
use App\Models\ApplicationStatus;
use App\Models\PerformerTransport;
use App\Models\RentalApplication;
use App\Services\CarFilterService;
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

    // 2. Данные из списка автомобилей в формате landing с пагинацией
    public function offers(Request $request): JsonResponse
    {
        $query = PerformerTransport::query()->with([
            'model_car', 'car_connection', 'model_car.brand', 'model_car.category_car',
            'model_car.class_car', 'body_type', 'color', 'condition', 'updated_user',
            'dopOptions', 'dopOptions.car_option', 'fuel_type', 'photos', 'tariffs', 'city', 'gearbox',
        ]);

        $query = CarFilterService::applyFilters($query, $request)
            ->when($request->filled('city_id'), fn ($q) => $q->where('city_id', $request->integer('city_id')))
            ->when($request->filled('gearbox_id'), fn ($q) => $q->where('gearbox_id', $request->integer('gearbox_id')))
            ->when($request->filled('duration_days'), fn ($q) => $q->whereHas(
                'tariffs',
                fn ($tq) => $tq->where('duration_days', $request->integer('duration_days'))
            ));

        $minPriceSubquery = fn () => DB::table('car_rental_tariff')
            ->join('rental_tariffs', 'rental_tariffs.id', '=', 'car_rental_tariff.rental_tariff_id')
            ->select('rental_tariffs.price')
            ->whereColumn('car_rental_tariff.performer_transport_id', 'performer_transports.id')
            ->orderBy('rental_tariffs.price')
            ->limit(1);

        match ($request->input('sort')) {
            'price_asc'  => $query->orderBy($minPriceSubquery()),
            'price_desc' => $query->orderByDesc($minPriceSubquery()),
            'year_desc'  => $query->orderByDesc('year_of_issue'),
            'year_asc'   => $query->orderBy('year_of_issue'),
            default      => $query->orderByDesc('id'),
        };

        $offers = $query->paginate($request->integer('per_page', 12));

        return $this->success([
            'data' => OfferListResource::collection($offers),
            'meta' => [
                'total'        => $offers->total(),
                'per_page'     => $offers->perPage(),
                'current_page' => $offers->currentPage(),
                'last_page'    => $offers->lastPage(),
            ],
        ]);
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
