<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CarColorResource extends JsonResource
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
            'name_tj' => $this->name_tj,
            'name_for_sms' => $this->name_for_sms,
            'is_active' => $this->is_active,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'created_by' => $this->createdBy->login??null
        ];
    }
}
