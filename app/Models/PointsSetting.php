<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointsSetting extends Model
{
    protected $fillable = [
        'points_per_amount_unit',
        'amount_unit_tzs',
        'referral_points',
        'redemption_rate_tzs_per_point',
         'min_redeemable_points',
          'updated_by',
    ];

    public static function current(): self
    {
        return static::latest()->firstOrFail();
    }
}
