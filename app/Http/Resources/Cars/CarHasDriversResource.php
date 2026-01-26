<?php

namespace App\Http\Resources\Cars;

use App\Models\Performer;
use Illuminate\Http\Resources\Json\JsonResource;

class CarHasDriversResource extends JsonResource
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
            'driver_id' => $this->whenLoaded('pivot', function() {
                return $this->pivot->driver_id;
            }),
            'car_id' => $this->whenLoaded('pivot', function() {
                return $this->pivot->car_id;
            }),
            'first_name' => $this->when($this->first_name != null || $this->first_name != "", function () {
                return $this->first_name;
            }),
            'last_name' => $this->when($this->last_name != null || $this->last_name != "", function () {
                return $this->last_name;
            }),
            'patronymic' => $this->when($this->patronymic != null || $this->patronymic != "", function () {
                return $this->patronymic;
            }),
            'phone' => $this->when($this->phone != null || $this->phone != null, function () {
                return $this->phone;
            }),
        ];
    }
}
