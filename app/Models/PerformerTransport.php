<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class PerformerTransport extends BasicModel
{
    use SoftDeletes;

    public $table = 'performer_transports';
    public const ACTIVE_CONNECTION = 1;
    public const WAITING_CONNECTION = 2;
    public const DELETED = 3;
    public const ACTIVE = 1;
    public const DISABLED = 0;

    public const CARGO = 3;

    protected $fillable = [
        'performer_id',
        'car_model_id',
        'body_type_id',
        'condition_id',
        'color_id',
        'dop_info',
        'year_of_issue',
        'count_seat',
        'car_number',
        'connected_id',
        'active',
        'fuel_type_id',
        'city_id',
        'gearbox_id',
        'min_rent_days',
        'address',
    ];

    public function model_car() {
        return $this->belongsTo(Marka::class, 'car_model_id', 'id');
    }
    public function body_type() {
        return $this->belongsTo(BodyType::class, 'body_type_id', 'id');
    }
    public function color() {
        return $this->belongsTo(ColorCar::class, 'color_id', 'id');
    }
    public function condition() {
        return $this->belongsTo(CarCondition::class, 'condition_id', 'id');
    }
    public function car_connection() {
        return $this->belongsTo(CarConnected::class, 'connected_id', 'id');
    }
    public function car_drivers() {
        return $this->belongsToMany(Performer::class, DriverCar::class, 'car_id', 'driver_id')->where('is_attached', 1);
    }

    public function performer_info()
    {
        return $this->belongsTo(Performer::class, 'performer_id', 'id');
    }

    public function  created_user() {
        return $this->belongsTo(User::class,'created_user_id', 'id');
    }
    public function  updated_user() {
        return $this->belongsTo(User::class,'updated_user_id', 'id');
    }

    public function car_options() {
        return $this->hasMany(PerformerTransportOption::class, 'performer_transport_id', 'id')->with('car_option');
    }

    public function dopOptions()
    {
        return $this->hasMany(PerformerTransportOption::class, 'performer_transport_id', 'id')
                    ->whereNotNull('option_id')
                    ->whereHas('car_option', function ($query) {
                        $query->whereNull('model');
                    })
                    ->with('car_option'); 
    }

    public function fuel_type()
    {
        return $this->belongsTo(CarOption::class, 'fuel_type_id','id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function gearbox()
    {
        return $this->belongsTo(Gearbox::class);
    }

    public function photos() {
        return $this->hasMany(PerformerTransportPhoto::class);
    }
    
    public function tariffs() {
        return $this->hasMany(RentalTariff::class);
    }

    public function rental_aplication() {
        return $this->belongsTo(RentalApplication::class);
    }
    

}
