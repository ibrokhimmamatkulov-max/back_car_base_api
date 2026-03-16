<?php

namespace App\Models;

use App\Traits\FiltersTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class CarOption extends Model
{
    // use FiltersTrait;
    use HasFactory;
    public const CONDICIONER = 1;
    protected $connection = 'auto_baza';
    protected $fillable = [
        'category_car_id',
        'name',
        'allowance-id',
        'is_active',
        'model'
    ];

    public function category_car() {
        return $this->belongsTo(CategoryCar::class, 'category_car_id', 'id');
    }
}
