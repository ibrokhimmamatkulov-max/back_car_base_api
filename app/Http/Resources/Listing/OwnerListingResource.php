<?php

namespace App\Http\Resources\Listing;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * Объявление глазами его владельца: со статусом, причиной отклонения
 * и счётчиками — то, чего нет в публичной карточке.
 */
class OwnerListingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'title'             => $this->title,
            'description'       => $this->description,
            'listing_type'      => $this->listing_type,
            'moderation_status' => $this->moderation_status,
            'rejection_reason'  => $this->rejection_reason,
            'published_at'      => $this->published_at?->toIso8601String(),
            'submitted_at'      => $this->submitted_at?->toIso8601String(),
            'views_count'       => (int) $this->views_count,
            'applications_count' => $this->whenCounted('applications'),

            'brand'         => $this->model_car?->brand?->name,
            'model'         => $this->model_car?->car_model,
            'car_model_id'  => $this->car_model_id,
            'year'          => $this->year_of_issue,
            'car_number'    => $this->car_number,
            'count_seat'    => $this->count_seat,
            'address'       => $this->address,
            'min_rent_days' => $this->min_rent_days,
            'max_rent_days' => $this->max_rent_days,

            'city'      => $this->relationOrNull($this->city),
            'gearbox'   => $this->relationOrNull($this->gearbox),
            'body_type' => $this->relationOrNull($this->body_type),
            'color'     => $this->relationOrNull($this->color),
            'fuel_type' => $this->relationOrNull($this->fuel_type),

            'min_price'   => $this->min_price !== null ? (float) $this->min_price : null,
            'price_tiers' => PriceTierResource::collection($this->whenLoaded('priceTiers')),
            'terms'       => $this->whenLoaded('terms', fn () => new ListingTermsResource($this->terms)),

            'unavailable_periods' => $this->whenLoaded(
                'unavailablePeriods',
                fn () => $this->unavailablePeriods->map(fn ($p) => [
                    'id'        => $p->id,
                    'date_from' => $p->date_from?->toDateString(),
                    'date_to'   => $p->date_to?->toDateString(),
                    'comment'   => $p->comment,
                ])
            ),

            'photos' => $this->whenLoaded('photos', fn () => $this->photos->map(fn ($p) => [
                'id'  => $p->id,
                'url' => Storage::url($p->path),
            ])),
        ];
    }

    private function relationOrNull($relation): ?array
    {
        return $relation ? ['id' => $relation->id, 'name' => $relation->name] : null;
    }
}
