<?php

namespace App\Http\Resources\Cars;

use Illuminate\Http\Resources\Json\JsonResource;

class CarOptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "performer_transport_id" => $this->performer_transport_id,
            "car_option_id" => $this->option_id,
            "name" => $this?->car_option?->name,
        ];
    }
}
