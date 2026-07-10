<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Division extends BasicModel
{
    const ACTIVE = 1;
    const DELETED = 0;
    const GRAM_KHUDJAND = 1;
    const GRAM_DUSHANBE = 6;
    public $table = 'divisions';

    protected $fillable = [
        'name',
        'type',
        'polygon_id',
        'organization_id',
        'is_active',
        'created_by'
    ];
    public function __construct(array $attributes = [])
    {
        $this->table = DB::connection('mysql_taxi')->getDatabaseName().'.'.$this->table;
        parent::__construct($attributes);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function performers()
    {
        return $this->hasMany(Performer::class);
    }
}
