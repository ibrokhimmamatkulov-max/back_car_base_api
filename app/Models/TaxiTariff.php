<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxiTariff extends Model
{
    use HasFactory;

    protected $table = 'taxi_tariffs';

    /** Условный месяц для расчёта — те же 30 дней, что и у срока жизни объявления */
    private const DAYS_IN_MONTH = 30;

    protected $fillable = [
        'performer_transport_id',
        'min_months',
        'off_days_per_month',
        'price_per_day',
    ];

    protected $casts = [
        'min_months'         => 'integer',
        'off_days_per_month' => 'integer',
        'price_per_day'      => 'decimal:2',
    ];

    public function listing(): BelongsTo
    {
        return $this->belongsTo(PerformerTransport::class, 'performer_transport_id');
    }

    /**
     * «Сколько выходит в месяц» — то, что должен увидеть владелец сразу
     * при подаче (условный месяц 30 дней, из которых часть — выходные,
     * за которые не платят).
     */
    public function getMonthlyTotalAttribute(): float
    {
        return round((float) $this->price_per_day * (self::DAYS_IN_MONTH - $this->off_days_per_month), 2);
    }
}
