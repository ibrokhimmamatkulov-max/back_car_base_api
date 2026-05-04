<?php

namespace App\Http\Resources;

use App\Models\Car\ClassCar;
use App\Models\CarBrand;
use App\Models\CategoryCar;
use App\Models\Marka;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CarModelResource extends JsonResource
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
            'car_model' => $this->car_model,
            'category_car_id' => $this->category_car_id,
            'category' => $this->whenPivotLoadedAs('category_car', CategoryCar::class,function () {
                return $this->category_car->name;
            }),
            'car_brand_id' => $this->car_brand_id,
            'car_brand' => $this->whenPivotLoadedAs('brand', CarBrand::class,function () {
                return $this->brand->name;
            }),
            'class_car_id' => $this->class_car_id,
            'class_car' => $this->whenPivotLoadedAs('class_car', ClassCar::class,function () {
                return $this->class_car->name;
            }),
            'car_seat_from' => $this->when($this->car_seat_from != null, function () {
                return $this->car_seat_from;
            }),
            'car_seat_before' => $this->when($this->car_seat_before != null, function () {
                return $this->car_seat_before;
            }),
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'is_active' => $this->is_active
        ];
    }
}
