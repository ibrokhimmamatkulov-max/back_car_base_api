<?php

namespace App\Http\Resources\Listing;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PriceTierResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'min_days'      => (int) $this->min_days,
            'max_days'      => $this->max_days !== null ? (int) $this->max_days : null,
            'price_per_day' => (float) $this->price_per_day,
        ];
    }
}
