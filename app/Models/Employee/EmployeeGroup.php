<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active'
    ];

    public function positions(){
        return $this->hasMany(EmployeeGroupPosition::class,'group_id','id');
    }
}
