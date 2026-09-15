<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListingTerms extends Model
{
    use HasFactory;

    public const DEPOSIT_ON_RETURN = 'on_return';
    public const DEPOSIT_DAILY = 'daily';
    public const DEPOSIT_NONE = 'none';

    public const FUEL_FULL_TO_FULL = 'full_to_full';
    public const FUEL_TENANT = 'tenant';
    public const FUEL_OWNER = 'owner';

    protected $table = 'listing_terms';

    protected $fillable = [
        'performer_transport_id',
        'deposit_amount',
        'deposit_return_policy',
        'deposit_daily_return',
        'mileage_limit_per_day',
        'overmileage_price',
        'fuel_policy',
        'min_driver_age',
        'min_driver_experience',
        'documents_pledge',
        'require_clean_record',
        'allow_taxi',
        'allow_intercity',
        'allow_abroad',
        'allow_smoking',
        'allow_pets',
        'delivery_available',
        'delivery_price',
        'additional_terms',
    ];

    protected $casts = [
        'deposit_amount'        => 'decimal:2',
        'deposit_daily_return'  => 'decimal:2',
        'overmileage_price'     => 'decimal:2',
        'delivery_price'        => 'decimal:2',
        'mileage_limit_per_day' => 'integer',
        'min_driver_age'        => 'integer',
        'min_driver_experience' => 'integer',
        'require_clean_record'  => 'boolean',
        'allow_taxi'            => 'boolean',
        'allow_intercity'       => 'boolean',
        'allow_abroad'          => 'boolean',
        'allow_smoking'         => 'boolean',
        'allow_pets'            => 'boolean',
        'delivery_available'    => 'boolean',
    ];

    public function listing(): BelongsTo
    {
        return $this->belongsTo(PerformerTransport::class, 'performer_transport_id');
    }
}
