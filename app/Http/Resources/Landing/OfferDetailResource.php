<?php

namespace App\Http\Resources\Landing;

use App\Http\Resources\Listing\ListingTermsResource;
use App\Models\PerformerTransport;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class OfferDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isGeneral = $this->listing_type === PerformerTransport::TYPE_GENERAL;

        return [
            'id'           => $this->id,
            'listing_type' => $this->listing_type,
            'title'        => $this->title,
            'description'  => $this->description,
            'brand'        => $this->model_car?->brand?->name,
            'model'        => $this->model_car?->car_model,
            'year'         => $this->year_of_issue,
            'count_seat'   => $this->count_seat,
            'dop_info'     => $this->dop_info,
            'address'      => $this->address,
            'min_rent_days'=> $this->min_rent_days,
            'max_rent_days'=> $this->max_rent_days,
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
            'color'        => [
                'id'   => $this->color?->id,
                'name' => $this->color?->name,
            ],
            'fuel_type'    => [
                'id'   => $this->fuel_type?->id,
                'name' => $this->fuel_type?->name,
            ],
            'dop_options'  => $this->dopOptions->map(fn ($opt) => [
                'id'   => $opt->car_option?->id,
                'name' => $opt->car_option?->name,
            ])->values(),
            'photos'       => $this->photos->map(fn ($p) => Storage::url($p->path))->values(),

            'min_price'    => $isGeneral
                ? $this->priceTiers->min('price_per_day')
                : $this->tariffs->min('price'),

            'price_tiers'  => $isGeneral
                ? $this->priceTiers->map(fn ($t) => [
                    'id'            => $t->id,
                    'min_days'      => (int) $t->min_days,
                    'max_days'      => $t->max_days !== null ? (int) $t->max_days : null,
                    'price_per_day' => (float) $t->price_per_day,
                ])->values()
                : [],

            'tariffs'      => $this->tariffs->map(fn ($t) => [
                'id'              => $t->id,
                'duration_days'   => $t->duration_days,
                'price'           => $t->price,
                'free_weekend_day'=> (int) $t->free_weekend_day,
            ])->values(),

            'terms' => $this->terms ? new ListingTermsResource($this->terms) : null,

            // Занятые даты — чтобы карточка могла подсветить их в календаре.
            // Бронирования это не создаёт: заявка на занятые даты принимается (ТЗ §2).
            'unavailable_periods' => $this->unavailablePeriods->map(fn ($p) => [
                'date_from' => $p->date_from?->toDateString(),
                'date_to'   => $p->date_to?->toDateString(),
            ])->values(),

            'owner' => $this->owner ? [
                'display_name' => $this->owner->display_name,
                'owner_type'   => $this->owner->owner_type,
            ] : null,

            'performer_id' => $this->performer_id,
        ];
    }
}
