<?php

namespace App\Http\Resources\Owner;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OwnerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                      => $this->id,
            'phone'                   => $this->phone,
            'login'                   => $this->login,
            'first_name'              => $this->first_name,
            'last_name'               => $this->last_name,
            'middle_name'             => $this->middle_name,
            'display_name'            => $this->display_name,
            'owner_type'              => $this->owner_type,
            'company_name'            => $this->company_name,
            'tin'                     => $this->tin,
            'email'                   => $this->email,
            'status'                  => $this->status,
            // Фронт показывает предложение сменить пароль, пока он тот,
            // что пришёл при регистрации (ТЗ §7.4).
            'has_generated_password'  => $this->hasGeneratedPassword(),
            'created_at'              => $this->created_at?->toIso8601String(),
        ];
    }
}
