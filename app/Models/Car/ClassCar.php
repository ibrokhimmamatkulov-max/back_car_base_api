<?php

namespace App\Models\Car;

use App\Models\CategoryCar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassCar extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_car_id',
        'is_active'
    ];

    public function category_car() {
        return $this->belongsTo(CategoryCar::class, 'category_car_id', 'id');
    }
}
