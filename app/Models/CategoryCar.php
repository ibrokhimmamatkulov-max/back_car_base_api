<?php

namespace App\Models;

use App\Models\Car\ClassCar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryCar extends BasicModel
{
    protected $connection = 'auto_baza';
    public $table = 'category_cars';
    public const ACTIVE = 1;
    public const MOTORBIKE = 5;
    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];
    public function model_cars(){
        return $this->hasMany(Marka::class, 'category_car_id', 'id');
    }

    public function class_cars() {
        return $this->hasMany(ClassCar::class, 'category_car_id', 'id');
    }
}
