<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalTariff extends Model
{
    use HasFactory;

    protected $table = 'rental_tariffs';
    protected $fillable = [
        'duration_days',
        'price',
        'free_weekend_day',
    ];
}
