<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IpfPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'duration_days',
        'down_payment_percent',
        'daily_rate_percent',
        'calculation_method',
        'is_active',
    ];

    protected $casts = [
        'down_payment_percent' => 'decimal:2',
        'daily_rate_percent' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function accounts()
    {
        return $this->hasMany(IpfAccount::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}