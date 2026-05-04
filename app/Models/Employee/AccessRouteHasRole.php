<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class AccessRouteHasRole extends Model
{
    use HasFactory;
    protected $fillable = [
        'role_id',
        'access_route_id'
    ];

    public function role() 
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
    public function access_route()
    {
        return $this->belongsTo(AccessRoute::class, 'access_route_id', 'id');
    }
    
}
