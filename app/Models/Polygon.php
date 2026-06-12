<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Polygon extends Model
{
    protected $connection = 'mysql_location';
    public $table = 'polygons';

    public function __construct(array $attributes = [])
    {
        $this->table = DB::connection($this->connection)->getDatabaseName() . '.' . $this->table;
        parent::__construct($attributes);
    }

    protected $fillable = ['name', 'lat', 'lng', 'is_active', 'place_type_id'];
}
