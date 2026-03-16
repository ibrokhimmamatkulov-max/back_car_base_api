<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalApplication extends Model
{
    use HasFactory;

    protected $connection = 'auto_baza';
    protected $fillable = [
        'performer_transport_id',
        'rental_tariff_id',
        'user_id',
        'phone',
        'city_id',
        'promo_code',
        'status_id',
    ];

    public function car()
    {
        return $this->belongsTo(PerformerTransport::class, 'performer_transport_id');
    }

    public function tariff()
    {
        return $this->belongsTo(RentalTariff::class, 'rental_tariff_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function status()
    {
        return $this->belongsTo(ApplicationStatus::class, 'status_id');
    }
    
}
