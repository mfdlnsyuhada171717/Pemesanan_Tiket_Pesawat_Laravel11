<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Airline extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'logo'
    ];

    public function flights()
    {
        return $this->hasMany(Flight::class);
    }
}
