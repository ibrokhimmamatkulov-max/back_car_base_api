<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ModelCarResource extends JsonResource
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
            "category_car_id" => $this->category_car_id,
            "car_brand_id" => $this->car_brand_id,
            "id" => $this->id,
            "car_model" => $this->car_model,
            "car_seat_from" => $this->car_seat_from ?? 4,
            "car_seat_before" => $this->car_seat_before ?? 4
        ];
    }
}
