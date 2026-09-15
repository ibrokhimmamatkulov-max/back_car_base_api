<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Арендодатель.
 *
 * Намеренно НЕ App\Models\User: та модель физически живёт в чужой базе
 * (mysql_taxi, см. её конструктор). Публичные пользователи — в нашей базе,
 * на дефолтном подключении.
 */
class Owner extends Authenticatable
{
    use HasApiTokens, HasFactory, SoftDeletes;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_BLOCKED = 'blocked';

    public const TYPE_INDIVIDUAL = 'individual';
    public const TYPE_COMPANY = 'company';

    protected $table = 'owners';

    protected $fillable = [
        'phone',
        'login',
        'password',
        'first_name',
        'last_name',
        'middle_name',
        'owner_type',
        'company_name',
        'tin',
        'email',
        'status',
        'blocked_reason',
        'phone_verified_at',
        'password_changed_at',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'phone_verified_at'   => 'datetime',
        'password_changed_at' => 'datetime',
        'last_login_at'       => 'datetime',
    ];

    public function listings(): HasMany
    {
        return $this->hasMany(PerformerTransport::class, 'owner_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(RentalApplication::class, 'owner_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(OwnerDocument::class, 'owner_id');
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isBlocked(): bool
    {
        return $this->status === self::STATUS_BLOCKED;
    }

    /**
     * Пароль всё ещё тот, что был сгенерирован при регистрации.
     * Используется, чтобы предложить в кабинете сменить его на свой.
     */
    public function hasGeneratedPassword(): bool
    {
        return $this->password_changed_at === null;
    }

    public function getDisplayNameAttribute(): string
    {
        if ($this->owner_type === self::TYPE_COMPANY && $this->company_name) {
            return $this->company_name;
        }

        return trim($this->first_name . ' ' . (string) $this->last_name);
    }
}
