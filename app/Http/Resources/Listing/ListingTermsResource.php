<?php

namespace App\Http\Resources\Listing;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListingTermsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'deposit_amount'        => (float) $this->deposit_amount,
            'deposit_return_policy' => $this->deposit_return_policy,
            'deposit_daily_return'  => $this->deposit_daily_return !== null
                ? (float) $this->deposit_daily_return
                : null,

            'mileage_limit_per_day' => $this->mileage_limit_per_day,
            'overmileage_price'     => $this->overmileage_price !== null
                ? (float) $this->overmileage_price
                : null,

            'fuel_policy'           => $this->fuel_policy,

            'min_driver_age'        => $this->min_driver_age,
            'min_driver_experience' => $this->min_driver_experience,
            'documents_pledge'      => $this->documents_pledge,
            'require_clean_record'  => (bool) $this->require_clean_record,

            'allow_taxi'            => (bool) $this->allow_taxi,
            'allow_intercity'       => (bool) $this->allow_intercity,
            'allow_abroad'          => (bool) $this->allow_abroad,
            'allow_smoking'         => (bool) $this->allow_smoking,
            'allow_pets'            => (bool) $this->allow_pets,

            'delivery_available'    => (bool) $this->delivery_available,
            'delivery_price'        => $this->delivery_price !== null
                ? (float) $this->delivery_price
                : null,

            'additional_terms'      => $this->additional_terms,
        ];
    }
}
