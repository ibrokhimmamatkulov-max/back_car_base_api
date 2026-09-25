<?php

namespace App\Http\Resources\Landing;

use App\Models\PerformerTransport;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class OfferListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isGeneral = $this->listing_type === PerformerTransport::TYPE_GENERAL;

        return [
            'id'           => $this->id,
            'listing_type' => $this->listing_type,
            'title'        => $this->title,
            'brand'        => $this->model_car?->brand?->name,
            'model'        => $this->model_car?->car_model,
            'year'         => $this->year_of_issue,
            'city'         => [
                'id'   => $this->city?->id,
                'name' => $this->city?->name,
            ],
            'gearbox'      => [
                'id'   => $this->gearbox?->id,
                'name' => $this->gearbox?->name,
            ],
            'body_type'    => [
                'id'   => $this->body_type?->id,
                'name' => $this->body_type?->name,
            ],
            'fuel_type'    => [
                'id'   => $this->fuel_type?->id,
                'name' => $this->fuel_type?->name,
            ],
            'count_seat'   => $this->count_seat,

            'min_price'    => $this->min_price !== null ? (float) $this->min_price : null,

            'min_rent_days' => $this->min_rent_days,
            'max_rent_days' => $this->max_rent_days,

            'deposit'      => $this->terms ? (float) $this->terms->deposit_amount : null,

            'price_tiers'  => $isGeneral
                ? $this->priceTiers->map(fn ($t) => [
                    'id'            => $t->id,
                    'min_days'      => (int) $t->min_days,
                    'max_days'      => $t->max_days !== null ? (int) $t->max_days : null,
                    'price_per_day' => (float) $t->price_per_day,
                ])->values()
                : [],

            // Тариф под такси с 25.09.2026 — один на объявление, не список.
            'taxi_tariff'  => $this->taxiTariff ? [
                'min_months'         => $this->taxiTariff->min_months,
                'off_days_per_month' => $this->taxiTariff->off_days_per_month,
                'price_per_day'      => (float) $this->taxiTariff->price_per_day,
                'monthly_total'      => $this->taxiTariff->monthly_total,
            ] : null,

            // Старая таксопарковая схема — только для записей до 25.09.2026,
            // у которых нет своей строки в taxi_tariffs.
            'tariffs'      => $this->tariffs->map(fn ($t) => [
                'id'              => $t->id,
                'duration_days'   => $t->duration_days,
                'price'           => $t->price,
                'free_weekend_day'=> (int) $t->free_weekend_day,
            ])->values(),

            'photos'       => $this->photos->map(fn ($p) => Storage::url($p->path))->values(),
        ];
    }
}
