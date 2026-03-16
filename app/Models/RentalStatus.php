<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalStatus extends Model
{
    use HasFactory;

    protected $connection = 'auto_baza';
    protected $fillable = [
        'code',
        'name',
    ];
}
