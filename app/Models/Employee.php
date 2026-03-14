<?php

namespace App\Models;

use App\Models\Employee\EmployeeGroupPosition;
use App\Traits\FiltersTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Employee extends Model
{
    use HasFactory;
    // use FiltersTrait;
    const OPERATOR = 'operator';
    protected $connection = 'mysql';

    protected $fillable = [
        'division_id',
        'dop_phone',
        'user_id',
        'employee_group_id',
        'car_park_id'
    ];

    public function division() {
        return $this->belongsTo(Division::class, 'division_id', 'id');
    }
    // public function employee_group(){
    //     return $this->belongsTo(EmployeeGroupPosition::class,'employee_group_id','id')->with(['role', 'group']);
    // }
    // public function users() {
    //     return $this->belongsTo(User::class, 'user_id', 'id');
    // }

    // public function employeeSipUser()
    // {
    //     return $this->hasOne(EmployeeSipUser::class,'employee_id', 'id')->with('sipUser');
    // }

    // public function employeeDivision()
    // {
    //     return $this->hasMany(EmployeeDivision::class, 'employee_id', 'id')->with('division');
    // }

    public function taxiPark() {
        return $this->belongsTo(CarPark::class, 'car_park_id', 'id');
    }

    public function divisions()
    {
        return $this->belongsToMany(
            Division::class,
            'employee_divisions',   
            'employee_id',          
            'division_id'           
        );
    }
    public function taxiParks()
    {
        return $this->belongsToMany(
            CarPark::class,
            'employee_tax_park',
            'employee_id',
            'tax_park_id'
        )->whereNull('employee_tax_park.deleted_at'); 
    }

    public function employee_group(){
        return $this->belongsTo(EmployeeGroupPosition::class,'employee_group_id','id')->with(['role', 'group']);
    }




}
