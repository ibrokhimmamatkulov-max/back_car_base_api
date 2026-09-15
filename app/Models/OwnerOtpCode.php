<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OwnerOtpCode extends Model
{
    use HasFactory;

    public const PURPOSE_AUTH = 'auth';
    public const PURPOSE_PHONE_CHANGE = 'phone_change';
    public const PURPOSE_PASSWORD_RESET = 'password_reset';

    public const MAX_ATTEMPTS = 5;

    protected $table = 'owner_otp_codes';

    protected $fillable = [
        'phone',
        'code_hash',
        'purpose',
        'expires_at',
        'attempts',
        'consumed_at',
        'ip',
    ];

    protected $casts = [
        'expires_at'  => 'datetime',
        'consumed_at' => 'datetime',
        'attempts'    => 'integer',
    ];

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isConsumed(): bool
    {
        return $this->consumed_at !== null;
    }

    public function attemptsExhausted(): bool
    {
        return $this->attempts >= self::MAX_ATTEMPTS;
    }

    public function isUsable(): bool
    {
        return !$this->isExpired() && !$this->isConsumed() && !$this->attemptsExhausted();
    }
}
