<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessRoute extends Model
{
    use HasFactory;
    protected $table = 'access_routes';
    protected $fillable = [
        'name',
        'route',
        'is_active'
    ];
    public $casts = [
        'is_active' => 'boolean',
    ];

    public function accesses() {
        return $this->hasMany(AccessRouteHasAccess::class, 'access_id', 'id');
    }
}
