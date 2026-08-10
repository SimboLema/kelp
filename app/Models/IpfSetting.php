<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpfSetting extends Model
{
    protected $fillable = [
        'down_payment_percent',
        'daily_rate_percent',
        'penalty_percent',
        'updated_by',
    ];

    /**
     * Always returns the most recently created settings row.
     * Admin updates should INSERT a new row rather than UPDATE in place,
     * so this also doubles as a free audit trail of rate changes over time.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public static function current(): self
    {
        return static::latest()->firstOrFail();
    }

    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }
}