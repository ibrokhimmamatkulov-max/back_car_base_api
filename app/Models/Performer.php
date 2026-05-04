<?php

namespace App\Models;

use Amp\Loop\Driver;
use App\Models\Balance\DriverBalance;
use App\Traits\FiltersTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Performer 
{
    const CITY_ID = 2;
    const PHOTO_CONTROL_STATUS = [
        'ACCEPTED',
        'IN_PROCESS',
        'NOT_ACCEPTED',
        'ASSIGNE'
    ];
    const ACTIVE = 1;
    const BONUS_COMMISSION_AFTER_REGISTER = ['percent' => 0,'expire_months' => 1];
    const DISABLE = 0;
    const FREE = 1;
    const NOT_FREE = 0;
    const TYPE_EARNING_ID = 2;
    public $table = 'performers';
    protected static $logFillable = true;
    protected $fillable = [
        'first_name',
        'last_name',
        'patronymic',
        'gender',
        'date_of_birth',
        'email',
        'contact_number',
        'promo_code',
        'city_id',
        'rating',
        'expired_zero_commission',
        'commission_percent',
        'type_earning_id',
        'serials_number',
        'expirated_driver_license',
        'serial_number_passport',
        'expirated_passport',
        'district_id',
        'passport_office_id',
        'address',
        'is_active',
        'status',
        'phone',
        'phone_without_code',
        'password',
        'login',
        'is_free',
        'fcm_token',
        'created_by',
        'is_online',
        'socket_id',
        'rating_by_client',
        'is_on_shift',
        'driver_license_type_id',
        'avatar_url',
        'reason_id',
        'comment',
        'service_type',
        'sub_serv_type'
    ];
}
