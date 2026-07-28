<?php

namespace App\Models\Models\KMJ;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coverage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'risk_name',
        'risk_code',
        'rate',
        'minimum_amount',
        'tpp',
        'additional_amount',
        'per_seat',
        'coverage_type',
        'parameters',
    ];

    protected $casts = [
        'parameters' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
