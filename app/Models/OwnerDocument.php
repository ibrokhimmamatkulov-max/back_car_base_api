<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OwnerDocument extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    protected $table = 'owner_documents';

    protected $fillable = [
        'owner_id',
        'performer_transport_id',
        'type',
        'path',
        'original_name',
        'status',
        'comment',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Owner::class, 'owner_id');
    }

    public function listing(): BelongsTo
    {
        return $this->belongsTo(PerformerTransport::class, 'performer_transport_id');
    }
}
