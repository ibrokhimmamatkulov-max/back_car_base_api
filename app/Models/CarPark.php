<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarPark extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'is_active',
        'created_by',
        'organization_id',
        'update_by'
    ];

    // public function organization()
    // {
    //     return $this->belongsTo(Organization::class);
    // }
}

