<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PromoCode extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'discount_type',
        'discount',
        'valid_until',
        'is_used'
    ];

    public function transactions()
    {
        return $this->hasOne(Transaction::class);
    }
}
