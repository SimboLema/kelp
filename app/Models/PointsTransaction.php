<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointsTransaction extends Model
{
    protected $fillable = ['user_id', 'type', 'points', 'balance_after', 'reference_type', 'reference_id', 'note'];

    public function user() { return $this->belongsTo(User::class); }
    public function reference() { return $this->morphTo(); }
}
