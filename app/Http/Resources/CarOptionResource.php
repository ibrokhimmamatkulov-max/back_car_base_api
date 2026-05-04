<?php

namespace App\Http\Resources;

use App\Models\Allowance;
use App\Models\CategoryCar;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

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
            'id' => $this->id,
            'category_car_id' => $this->category_car_id,
            'category_car_name' => $this->category_car->name??null,
            'name' => $this->name,
            // 'allowance_id' => $this->allowance_id,
            // 'allowance_name' => $this->allowance->name??null,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at->format('Y-m-d H:i:s')??null
        ];
    }
}
