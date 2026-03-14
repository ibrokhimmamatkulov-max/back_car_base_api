<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessRouteHasAccess extends Model
{
    use HasFactory;

     protected $fillable = [
        'access_route_id',
        'access_id'
    ];

    public function access() 
    {
        return $this->belongsTo(Permission::class, 'access_id', 'id');
    }
    public function access_route()
    {
        return $this->belongsTo(AccessRoute::class, 'access_route_id', 'id');
    }
}
