<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarBrand extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'created_by',
        'is_active',
    ];
    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by','id');
    }
}
