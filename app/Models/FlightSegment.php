<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FlightSegment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'sequence',
        'flight_id',
        'airport_id',
        'time'
    ];

    protected $casts = [
        'time' => 'datetime'
    ];

    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

    public function airport()
    {
        return $this->belongsTo(Airport::class);
    }
}
