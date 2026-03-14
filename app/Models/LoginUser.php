<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginUser extends Model
{
    use HasFactory;
    public const LOGIN = 1;
    public const NOT_LOGIN = 0;
    protected $fillable = [
        'user_id',
        'access_token',
        'refresh_token',
        'token_type',
        'access_token_expires',
        'ip_address',
        'user_agent',
        'expires_at',
        'is_login'
    ];
}
