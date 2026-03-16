<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    protected $connection = 'auto_baza';
    protected $fillable = [
        'performer_transport_id',
        'performer_id',
        'manager_id',
        'status_id',
        'start_datetime',
        'end_datetime',
    ];

    public function car()
    {
        return $this->belongsTo(PerformerTransport::class, 'performer_transport_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function status()
    {
        return $this->belongsTo(RentalStatus::class, 'status_id');
    }

    public function getRemainingTimeAttribute()
{
    $now = Carbon::now();
    $end = Carbon::parse($this->end_datetime);

    if ($now->greaterThan($end)) {
        return '0';
    }

    $diff = $now->diff($end);

    return [
        'days' => $diff->d,
        'hours' => $diff->h,
        'minutes' => $diff->i,
    ];
}

}
