<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = ['id', 'business_id', 'user_name', 'rating', 'comment','status'];

    protected $keyType = 'string';
    public $incrementing = false;


    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function images()
    {
    return $this->hasMany(ReviewImage::class);
    }

    public function reply()
    {
    return $this->hasOne(ReviewReply::class);
    }

    protected static function booted()
    {
        static::creating(function ($review) {
            if (empty($review->id)) {
                $review->id = (string) Str::uuid();
            }
        });
    }
}
