<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RentalPriceTier extends Model
{
    use HasFactory;

    protected $table = 'rental_price_tiers';

    protected $fillable = [
        'performer_transport_id',
        'min_days',
        'max_days',
        'price_per_day',
    ];

    protected $casts = [
        'min_days'      => 'integer',
        'max_days'      => 'integer',
        'price_per_day' => 'decimal:2',
    ];

    public function listing(): BelongsTo
    {
        return $this->belongsTo(PerformerTransport::class, 'performer_transport_id');
    }

    public function covers(int $days): bool
    {
        if ($days < $this->min_days) {
            return false;
        }

        return $this->max_days === null || $days <= $this->max_days;
    }
}
