<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpfTransaction extends Model
{
    protected $fillable = [
        'ipf_plan_id',
        'type',
        'amount',
        'balance_after',
        'transaction_date',
        'note',
    ];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    public function plan()
    {
        return $this->belongsTo(IpfPlan::class);
    }
}
