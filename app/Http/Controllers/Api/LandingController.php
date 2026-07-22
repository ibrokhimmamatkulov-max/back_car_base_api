<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LandingApplicationRequest;
use App\Http\Resources\Landing\CityResource;
use App\Http\Resources\Landing\OfferDetailResource;
use App\Http\Resources\Landing\OfferListResource;
use App\Models\ApplicationStatus;
use App\Models\PerformerTransport;
use App\Models\Polygon;
use App\Models\RentalApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LandingController extends Controller
{
    // 1. Список городов из polygons (mysql_location)
    public function cities(): JsonResponse
    {
        $typeId = config('services.landing.city_place_type_id');

        $cities = Polygon::where('is_active', 1)
            ->when($typeId, fn ($q) => $q->where('place_type_id', $typeId))
            ->orderBy('name')
            ->get(['id', 'name', 'lat', 'lng']);

        return $this->success(CityResource::collection($cities));
    }

    // 2. Список объявлений с фильтрами
    public function offers(Request $request): JsonResponse
    {
        $query = PerformerTransport::query()
            ->where('active', PerformerTransport::ACTIVE)
            ->with(['model_car.brand', 'gearbox', 'city', 'body_type', 'photos', 'tariffs'])
            ->when($request->filled('city_id'),     fn ($q) => $q->where('city_id',     $request->integer('city_id')))
            ->when($request->filled('gearbox_id'),  fn ($q) => $q->where('gearbox_id',  $request->integer('gearbox_id')))
            ->when($request->filled('duration_days'), fn ($q) => $q->whereHas('tariffs',
                fn ($tq) => $tq->where('duration_days', $request->integer('duration_days'))
            ));

        $minPriceSubquery = fn () => DB::table('car_rental_tariff')
            ->join('rental_tariffs', 'rental_tariffs.id', '=', 'car_rental_tariff.rental_tariff_id')
            ->select('rental_tariffs.price')
            ->whereColumn('car_rental_tariff.performer_transport_id', 'performer_transports.id')
            ->orderBy('rental_tariffs.price')->limit(1);

        match ($request->input('sort')) {
            'price_asc'  => $query->orderBy($minPriceSubquery()),
            'price_desc' => $query->orderByDesc($minPriceSubquery()),
            'year_desc'  => $query->orderByDesc('year_of_issue'),
            'year_asc'   => $query->orderBy('year_of_issue'),
            default      => $query->orderByDesc('created_at'),
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
