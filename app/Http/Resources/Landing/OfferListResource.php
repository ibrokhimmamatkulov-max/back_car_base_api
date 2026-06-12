<?php

namespace App\Http\Resources\Landing;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
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
            'min_price'    => $this->tariffs->min('price'),
            'min_rent_days'=> $this->min_rent_days,
            'photos'       => $this->photos->pluck('path'),
        ];
    }
}
