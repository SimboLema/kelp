<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IpfInstallment extends Model
{
    use HasFactory;

    protected $fillable = [
        'ipf_account_id',
        'installment_number',
        'due_date',
        'amount_due',
        'amount_paid',
        'remaining_amount',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'amount_due' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function account()
    {
        return $this->belongsTo(IpfAccount::class, 'ipf_account_id');
    }

    public function payments()
    {
        return $this->hasMany(IpfPayment::class, 'ipf_installment_id');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'pending')
            ->where('due_date', '<', now()->toDateString());
    }
}