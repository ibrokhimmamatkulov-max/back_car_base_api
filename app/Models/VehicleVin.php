<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Реестр VIN-кодов — переживает архивацию и удаление объявления, в
 * отличие от PerformerTransport::VIN. См. миграцию за пояснением зачем.
 */
class VehicleVin extends Model
{
    use HasFactory;

    protected $table = 'vehicle_vins';

    protected $fillable = [
        'vin',
        'is_damaged',
        'note',
        'performer_transport_id',
        'owner_id',
    ];

    protected $casts = [
        'is_damaged' => 'boolean',
    ];

    /** VIN всегда в верхнем регистре и без пробелов по краям — иначе поиск не найдёт совпадение */
    public static function normalize(string $vin): string
    {
        return mb_strtoupper(trim($vin));
    }

    /**
     * Отмечает, что этот VIN снова встретился в объявлении. Статус
     * битый/небитый при этом не трогаем — его устанавливают отдельно,
     * поданное объявление не должно случайно сбрасывать уже известную
     * отметку.
     */
    public static function record(string $vin, ?int $listingId, ?int $ownerId): self
    {
        return static::updateOrCreate(
            ['vin' => self::normalize($vin)],
            [
                'performer_transport_id' => $listingId,
                'owner_id'                => $ownerId,
            ]
        );
    }

    public function listing(): BelongsTo
    {
        return $this->belongsTo(PerformerTransport::class, 'performer_transport_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Owner::class, 'owner_id');
    }
}
