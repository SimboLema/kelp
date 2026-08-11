<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IpfAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'insurance_order_id',
        'user_id',
        'ipf_plan_id',

        'total_premium',
        'down_payment_percent',
        'down_payment_amount',
        'financed_amount',

        'total_paid',
        'remaining_amount',

        'start_date',
        'expected_end_date',

        'status',
    ];

    protected $casts = [
        'total_premium' => 'decimal:2',
        'down_payment_percent' => 'decimal:2',
        'down_payment_amount' => 'decimal:2',
        'financed_amount' => 'decimal:2',
        'total_paid' => 'decimal:2',
        'remaining_amount' => 'decimal:2',

        'start_date' => 'date',
        'expected_end_date' => 'date',
    ];

    // Exposed on every JSON response so the frontend never has to compute
    // this itself — used for progress bars on the customer + admin views.
    protected $appends = ['progress_percent'];

    public function order()
    {
        return $this->belongsTo(
            InsuranceOrder::class,
            'insurance_order_id'
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(IpfPlan::class, 'ipf_plan_id');
    }

    public function installments()
    {
        return $this->hasMany(IpfInstallment::class);
    }

    public function payments()
    {
        return $this->hasMany(IpfPayment::class);
    }

    public function getProgressPercentAttribute(): float
    {
        if ((float) $this->financed_amount <= 0) {
            return 100.0;
        }

        return round((((float) $this->total_paid) / ((float) $this->financed_amount)) * 100, 2);
    }

    public function hasOverdueInstallments(): bool
    {
        return $this->installments()->where('status', 'overdue')->exists();
    }
}