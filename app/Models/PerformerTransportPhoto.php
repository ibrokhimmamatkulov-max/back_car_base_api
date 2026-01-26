<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformerTransportPhoto extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'performer_transport_id',
        'path',
    ];

    public function transport()
    {
        return $this->belongsTo(PerformerTransport::class, 'performer_transport_id');
    }
}
