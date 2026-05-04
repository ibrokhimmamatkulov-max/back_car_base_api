<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DriverCar extends Model
{
    protected $connection = 'mysql_performer';
    
    use HasFactory;
    use SoftDeletes;
    const ACTIVE = 1;
    const CONNECTION = 4;
    protected $fillable = [
        'driver_id',
        'car_id',
        'is_attached',
        'attached_at',
        'detached_at',
        'comment'
    ];

    public function driver(){
        return $this->belongsTo(Performer::class,'driver_id', 'id');
    }
    public function car() {
        return $this->belongsTo(PerformerTransport::class, 'car_id', 'id');
    }
}
