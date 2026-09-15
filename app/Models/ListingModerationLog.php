<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListingModerationLog extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    protected $table = 'listing_moderation_logs';

    protected $fillable = [
        'performer_transport_id',
        'moderator_id',
        'from_status',
        'to_status',
        'comment',
    ];

    public function listing(): BelongsTo
    {
        return $this->belongsTo(PerformerTransport::class, 'performer_transport_id');
    }

    /**
     * Модератор живёт в другой базе (mysql_taxi), поэтому связь обычная
     * belongsTo без внешнего ключа на уровне СУБД.
     */
    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderator_id');
    }
}
