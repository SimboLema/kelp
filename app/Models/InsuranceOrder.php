<?php

namespace App\Models\Models\KMJ;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InsuranceOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reference_no',
        'user_id',
        'insurer_id',
        'insurance_id',
        'product_id',
        'coverage_id',
        'description',
        'status',
        'transmission_status',
        'external_reference',
        'request_payload',
        'response_payload',
        'sent_at',
        'retry_count',
        'last_error',
    ];

    protected $casts = [
        'request_payload' => 'array',
        'response_payload' => 'array',
        'sent_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function insurer()
    {
        return $this->belongsTo(Insurer::class);
    }

    public function insurance()
    {
        return $this->belongsTo(Insurance::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function coverage()
    {
        return $this->belongsTo(Coverage::class);
    }

    public static function generateReference(): string
    {
        return 'INS-' . now()->format('YmdHis') . rand(100, 999);
    }
}
