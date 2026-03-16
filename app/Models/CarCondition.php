<?php

namespace App\Models;

use App\Traits\FiltersTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarCondition extends Model
{
    
    use HasFactory;
    protected $connection = 'auto_baza';
    public $table = 'car_conditions';
    protected $fillable = [
        'id',
        'name',
        'level',
    ];
}
