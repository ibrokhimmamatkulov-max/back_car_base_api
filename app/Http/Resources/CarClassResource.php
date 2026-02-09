<?php

namespace App\Http\Resources;

use App\Models\Car\ClassCar;
use App\Models\CategoryCar;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CarClassResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'category_car_id' => $this->category_car_id,
            'category' => $this->whenPivotLoadedAs('category_car', CategoryCar::class, function () {
                return $this->category_car->name;
            }),
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'is_active' => $this->is_active
        ];
    }
}
