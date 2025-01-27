<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facility extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'image',
        'name',
        'description'
    ];

    public function classes()
    {
        return $this->belongsToMany(FlightClass::class, 'flight_class_facility', 'flight_class_id', 'facility_id');
    }
}
