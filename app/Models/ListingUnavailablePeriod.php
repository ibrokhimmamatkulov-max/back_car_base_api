<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListingUnavailablePeriod extends Model
{
    use HasFactory;

    protected $table = 'listing_unavailable_periods';

    protected $fillable = [
        'performer_transport_id',
        'date_from',
        'date_to',
        'comment',
    ];

    protected $casts = [
        'date_from' => 'date',
        'date_to'   => 'date',
    ];

    public function listing(): BelongsTo
    {
        return $this->belongsTo(PerformerTransport::class, 'performer_transport_id');
    }
}
