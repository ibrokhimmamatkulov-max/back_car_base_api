<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformerTransportPhoto extends Model
{
    use HasFactory;
    
    protected $connection = 'auto_baza';
    protected $table = 'performer_transport_photos';
    protected $fillable = [
        'performer_transport_id',
        'path',
    ];

    public function transport()
    {
        return $this->belongsTo(PerformerTransport::class, 'performer_transport_id');
    }
}
