<?php

namespace App\Models;

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
    
        'insurer_name',
        'insurance_name',
        'product_name',
        'coverage_name',
    
        'description',
    
        'sum_insured',
        'premium',
        'premium_breakdown',
    
        'customer_details',
        'motor_details',
    
        'cover_note_start_date',
        'cover_note_end_date',
    
        'payment_mode',
        'registration_number',
    
        'status',
        'transmission_status',
        'external_reference',
        'request_payload',
        'response_payload',
        'sent_at',
        'retry_count',
        'last_error',

        'image_path'
    ];

    protected $casts = [
        'request_payload' => 'array',
        'response_payload' => 'array',
        'premium_breakdown' => 'array',
        'customer_details' => 'array',
        'motor_details' => 'array',
    
        'sum_insured' => 'decimal:2',
        'premium' => 'decimal:2',
    
        'cover_note_start_date' => 'date',
        'cover_note_end_date' => 'date',
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

    public function ipfAccount()
    {
    return $this->hasOne(IpfAccount::class);
    }
    
}
