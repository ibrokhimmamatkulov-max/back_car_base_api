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
// extends Authenticatable implements HasMedia
{
    // protected static function boot()
    // {
    //     parent::boot();

    //     // Включаем предотвращение ленивой загрузки только для этой модели
    //     static::preventLazyLoading(! app()->isProduction());
    // }
    // use HasFactory,HasApiTokens, FiltersTrait, InteractsWithMedia, SoftDeletes;
    // protected $connection = 'mysql_performer';
    // const COLLECTIONNAME = "photo_control";
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
//     public function getTable()
//     {
//         return join('.', [
//             $this->getConnection()->getDatabaseName(),
//             Str::snake(Str::pluralStudly(class_basename($this))) // original function
//         ]);
//     }

//     public function getFioAttribute()
//     {
//         $full_name = $this->last_name.' '.$this->first_name;
//         return $full_name;
//     }   


//     public function AccessTokens(){
        
//         return $this->hasMany(PerformerOauthAccessToken::class,'user_id','id');
//     }
//     protected $hidden = [
//         'password',
//         'city_id',
//         'type_movement_id',
//         'phone_without_code',
//         'promo_code',
//         'parent_id',
//     ];
//     public function performer_transports()
//     {
//         return $this->hasMany(PerformerTransport::class,'performer_id','id');
//     }

//     public function active_car() {
//         return $this->hasOne(PerformerTransport::class,'performer_id','id');
//     }

//     public function active_order() {
//         return $this->hasOne(PerformerOrder::class,'performer_id','id');
//     }

//     public function driver_cars() {
//         return $this->belongsToMany(PerformerTransport::class, DriverCar::class, 'driver_id', 'car_id')->where('is_attached', 1);
//     }
//     public function driver_connection_cars()
//     {
//         return $this->belongsToMany(
//             PerformerTransport::class,
//             'driver_cars',
//             'driver_id',
//             'car_id'
//         )->withPivot(['is_attached', 'attached_at', 'detached_at', 'comment']);
//     }
    
//     public function car() {
//         return $this->belongsToMany(PerformerTransport::class, DriverCar::class, 'driver_id', 'car_id')->where('active',1)->where('is_attached', 1);
//     }

//     public function transport() {
//         return $this->belongsToMany(PerformerTransport::class, DriverCar::class, 'driver_id', 'car_id')->where('active', self::ACTIVE);
//     }

//     public function car_info() {
//         return $this->hasOne(PerformerTransport::class, 'performer_id', 'id')
//                         ->where('active', PerformerTransport::ACTIVE)
//                         ->where('connected_id', PerformerTransport::ACTIVE_CONNECTION);
//     }

//     public function balance()
//     {
//         return $this->belongsTo(DriverBalance::class,'id','performer_id');
//     }
//     public function performer_location(){
//         return $this->belongsTo(PerformerLocation::class, 'id' , 'performer_id')->with(['district']);
//     }

//     public function passport_office(){
//         return $this->belongsTo(PassportOffice::class, 'passport_office_id', 'id');
//     }
//     public function district(){
//         return $this->belongsTo(District::class, 'district_id', 'id');
//     }

//     public function performer_login()
//     {
//         return $this->belongsTo(Accounts::class, 'id');
        
//     }

//     public function performer_tariffs()
//     {
//         return $this->hasMany(PerformerTariff::class, 'performer_id', 'id')->with('tariff');
//     }

//     public function createdBy()
//     {
//         return $this->belongsTo(User::class, 'created_by', 'id');
//     }

//     // public function performerOrder() {
//     //     return $this->belongsTo(PerformerOrder::class, 'performer_id', 'id')->with('order')->orderByDesc('id');
//     // }

//     public function orders()
//     {
// //        $this->setConnection('sip_gram_api');
//         return $this->belongsToMany(Order::class, PerformerOrder::class);
//         // return DB::table('sip_gram_api.performer_orders')->where('performer_id', $this->id)->get();
//     }

//     public function options()
//     {
//         return $this->hasMany(PerformerHasPerformerOption::class, 'performer_id', 'id');
//     }

//     public function performerOrder() : HasOne
//     {
//         return $this->hasOne(PerformerOrder::class, 'performer_id','id')->with('orderActive')->latestOfMany();
//     }

//     public function geoLocation() : HasOne
//     {
//         return $this->hasOne(PerformerLocation::class, 'performer_id' , 'id')->latestOfMany();
//     }

//     public function scopeActive($query) {
//         return $query->where('is_active', self::ACTIVE)
//                     ->where('is_online', self::ACTIVE);
//     }

//     public function car_state()
//     {
//         return $this->hasOne(CarState::class,'performer_id','id')->latest();
//     }

//     public function current_state()
//     {
//         return $this->hasOne(CurrentStatePerformer::class,'performer_id','id');
//     }

//     public function track()
//     {
//         return $this->hasOne(DriverTracking::class, 'performer_id', 'id')->whereNotNull('lng')->whereNotNull('lat')->latestOfMany();
//     }

//     public function currentOrder()
//     {
//         return $this->hasOne(PerformerOrder::class, 'performer_id', 'id')->orderByDesc('id');
//     }

//     public function activatedPromocode()
//     {
//         return $this->hasOne(PromocodeUsage::class, 'user_id', 'id')->where('model_type', Promocode::PERFORMER);
//     }
//     public function driver_lisence() {
//         return $this->belongsTo(DriverLicenseType::class, 'driver_license_type_id', 'id');
//     }
//     public function reason_performer() {
//         return $this->belongsTo(ReasonBlocking::class, 'reason_id', 'id');
//     }

    
//     public function tariffs()
//     {
//         return $this->hasMany(PerformerTariff::class,'performer_id', 'id');
//     }
//     public function devices()
//     {
//         return $this->hasMany(\App\Models\PerformerDevice::class);
//     }
}
