<?php

namespace App\Models\Employee;

use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class EmployeeGroupPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'role_id',
        'is_active'
    ];

    public function role(){
        return $this->belongsTo(Position::class,'role_id','id');
    }
    public function group(){
        return $this->belongsTo(EmployeeGroup::class,'group_id','id');
    }
}
