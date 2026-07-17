<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class BusinessImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'image_path'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
