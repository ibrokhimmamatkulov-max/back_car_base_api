<?php

namespace App\Http\Resources\Cars;

use App\Models\BodyType;
use App\Models\CarCondition;
use App\Models\CarConnected;
use App\Models\ColorCar;
use App\Models\Division;
use App\Models\Marka;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $car_options = CarOptionResource::collection($this->car_options);
        return [
            "id" => $this->id,
            'performer_id' => $this->performer_id??null,
            'category_car_id' => $this->whenPivotLoadedAs('model_car', Marka::class, function () {
                return $this->model_car->category_car_id;
            }),
            'category' => $this->when(isset($this->model_car->category_car), function () {
                return $this->model_car->category_car->name;
            }),
            'class_car' => $this->when(isset($this->model_car->class_car), function () {
                return $this->model_car->class_car->name;
            }),
            "fuel_type_id" => $this->fuel_type_id,
            'fuel_type_name' => $this->fuel_type->name??null,
            "car_brand_id" => $this->model_car->car_brand_id??null,
            "car_brand" => $this->model_car->brand->name??null,
            "model_car_id" => $this->car_model_id,
            "model" => $this->whenPivotLoadedAs('model_car', Marka::class, function () {
                return $this->model_car->car_model;
            }),
            "body_type_id" => $this->when($this->body_type_id != null, function() {
               return $this->body_type_id;
            }),
            "body_type" => $this->whenPivotLoadedAs('body_type', BodyType::class, function () {
                return $this->body_type->name;
            }),
            "color_id" => $this->color_id ?? 0,
            "color" => $this->when(isset($this->color->name), function () {
                return $this->color->name;
            },function() {
                return "Без цвета (!)";
            }),
            "condition_id" => $this->condition_id,
            "condition" => $this->whenPivotLoadedAs('condition', CarCondition::class, function () {
                return $this->condition->name;
            }),
            "car_number" => $this->car_number,
            "year_of_issue" => $this->year_of_issue,
            "count_seat" => $this->count_seat,
            'cargo_properties' => isset($this->cargo_properties) ? json_decode($this->cargo_properties) : null,
            "dop_info" => $this->dop_info,
            "dop_options" => $car_options,
            'status_id' => $this->connected_id,
            "status" => $this->whenPivotLoadedAs('car_connection', CarConnected::class, function () {
                return $this->car_connection->name;
            }),
            "active" => $this->active,
            // "car_drivers" => $this->when(count($this->car_drivers) > 0, function () {
            //     return CarHasDriversResource::collection($this->whenLoaded('car_drivers'));
            // }),
            // "created_user" => $this->when($this->created_user_id!=null,function() {
            //     return [
            //         'id' => $this->created_user?->employee->id??null,
            //         'login' => $this->created_user?->login,
            //     ];
            // },function () {
            //     return [
            //         'id' => 0,
            //         'login' => 'stu_DriverService'
            //     ];
            // }),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            "city_id" => $this->city_id,
            "city" => $this->city?->name,
            "address" => $this->address,
            "gearbox_id" => $this->gearbox_id,
            "gearbox" => $this->gearbox?->name,
            "min_rent_days" => $this->min_rent_days,
            "photos" => $this->photos->map(fn($p) => Storage::url($p->path)),
            "tariffs" => $this->tariffs->map(function ($tariff) {
                return [
                    'id' => $tariff->id,
                    'duration_days' => $tariff->duration_days,
                    'price' => $tariff->price,
                    'free_weekend_day' => $tariff->free_weekend_day,
                ];
            }),
        ];
    }
}
