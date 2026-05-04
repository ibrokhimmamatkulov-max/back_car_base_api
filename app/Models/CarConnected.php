<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarConnected extends Model
{
    public $table = "car_connecteds";

    use HasFactory;

    protected $fillable = [
        'name',
        'name_en'
    ];
}
