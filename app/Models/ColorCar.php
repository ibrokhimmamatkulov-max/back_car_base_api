<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColorCar extends BasicModel
{
    use HasFactory;
    public $table = 'colors';
    protected $fillable = [
        'name',
        'name_for_sms',
        'name_tj',
        'is_active'
    ];

    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by','id');
    }
}
