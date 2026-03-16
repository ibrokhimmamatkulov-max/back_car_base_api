<?php

namespace App\Models;

use App\Models\Car\ClassCar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Marka extends Model
{
    use HasFactory;

    protected $connection = 'auto_baza';
    public $table = 'model_cars';
    protected $fillable = [
        'name',
        'car_model',
        'car_seat_from',
        'car_seat_before',
        'car_brand_id',
        'category_car_id',
        'class_car_id',
        'is_active'
    ];

    public function brand() {
        return $this->belongsTo(CarBrand::class,'car_brand_id', 'id');
    }

    public function  category_car()
    {
        return $this->belongsTo(CategoryCar::class, 'category_car_id', 'id');
    }
    public function  class_car()
    {
        return $this->belongsTo(ClassCar::class, 'class_car_id', 'id');
    }

    public function getFullNameAttribute()
    {
        $brand = $this->brand->name ?? null;
        $name = $this->attributes['name'];
        if(!is_null($this->attributes['car_model'])) {
            $car_model = $this->attributes['car_model'];
            if($this->attributes['car_model'] == $this->attributes["name"]) {
                $car_model = $this->attributes['name'];
            }

            if(!is_null($brand)) {
                if($this->attributes["name"] == $brand) {
                    $brand = $this->attributes["name"];
                }
                $name = $brand.' '.$car_model;
            }
        }
        return $name;
    }
}
