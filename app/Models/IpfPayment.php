<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IpfPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'ipf_account_id',
        'ipf_installment_id',
        'user_id',
        'amount',
        'payment_method',
        'transaction_reference',
        'status',
        'paid_at',
        'payment_response',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'payment_response' => 'array',
    ];

    public function account()
    {
        return $this->belongsTo(IpfAccount::class, 'ipf_account_id');
    }

    public function installment()
    {
        return $this->belongsTo(IpfInstallment::class, 'ipf_installment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}