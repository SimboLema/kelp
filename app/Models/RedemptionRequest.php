<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RedemptionRequest extends Model
{
    protected $fillable = [
        'user_id', 'points_redeemed', 'amount_tzs', 'method', 'payout_details',
        'status', 'processed_by', 'processed_at', 'admin_note',
    ];

    protected $casts = ['processed_at' => 'datetime'];

    public function user() { return $this->belongsTo(User::class); }
    public function processedBy() { return $this->belongsTo(User::class, 'processed_by'); }
}
