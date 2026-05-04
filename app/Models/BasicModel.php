<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class BasicModel extends Model
{
    use HasFactory;

    protected static $logFillable = false;

}
