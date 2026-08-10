<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpfPlan extends Model
{
    protected $fillable = [
        'insurance_order_id',
        'total_premium',
        'down_payment_percent',
        'down_payment_amount',
        'financed_amount',
        'daily_rate_percent',
        'daily_installment',
        'penalty_percent',
        'outstanding_balance',
        'start_date',
        'last_charged_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'last_charged_date' => 'date',
    ];

    public function order()
    {
        return $this->belongsTo(InsuranceOrder::class, 'insurance_order_id');
    }

    public function transactions()
    {
        return $this->hasMany(IpfTransaction::class);
    }
}
