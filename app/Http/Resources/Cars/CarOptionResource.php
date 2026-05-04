<?php

namespace App\Http\Resources\Cars;

use Carbon\Carbon;
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
            "id" => $this->id,
            "name" => $this->name,
            "category_car_id" => $this->category_car_id,
            "is_active" => $this->is_active,
            "created_at" => Carbon::parse($this->created_at)->format('Y-m-d H:i'),
            "updated_at" => Carbon::parse($this->created_at)->format('Y-m-d H:i'),
            "model" => $this->model,
        ];
    }
}
