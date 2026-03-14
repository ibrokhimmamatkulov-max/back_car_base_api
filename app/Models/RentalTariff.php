<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalTariff extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql';
    protected $table = 'rental_tariffs';
    protected $fillable = [
        'performer_transport_id',
        'duration_days',
        'price',
        'free_weekend_day',
    ];

    public function car()
    {
        return $this->belongsTo(PerformerTransport::class, 'performer_transport_id');
    }
}
