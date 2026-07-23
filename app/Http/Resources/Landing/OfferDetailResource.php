<?php

namespace App\Http\Resources\Landing;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class OfferDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'brand'        => $this->model_car?->brand?->name,
            'model'        => $this->model_car?->car_model,
            'year'         => $this->year_of_issue,
            'count_seat'   => $this->count_seat,
            'dop_info'     => $this->dop_info,
            'address'      => $this->address,
            'min_rent_days'=> $this->min_rent_days,
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
            ]),
            'photos'       => $this->photos->map(fn($p) => Storage::url($p->path)),
            'tariffs'      => $this->tariffs->map(fn ($t) => [
                'id'              => $t->id,
                'duration_days'   => $t->duration_days,
                'price'           => $t->price,
                'free_weekend_day'=> (int) $t->free_weekend_day,
            ]),
            'performer_id' => $this->performer_id,
        ];
    }
}
