<?php

namespace App\Models;

use App\Models\Employee\AccessRouteHasRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class Position extends Model
{
    use HasFactory;

    const OPERATOR_ID = 1;
    const DIRECTOR = 7;
    
    protected $table = 'roles';

    public function access_routes()
    {
        return $this->hasMany(AccessRouteHasRole::class, 'role_id', 'id')
                    ->with('role', 'access_route'); 
    }
}