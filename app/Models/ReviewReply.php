<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewReply extends Model
{
    protected $fillable = [
        'review_id',
        'reply',
    ];

    public function review()
    {
        return $this->belongsTo(Review::class);
    }
}
