<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'insurance_id',
        'name',
        'code',
        'description',
    ];

    public function insurance()
    {
        return $this->belongsTo(Insurance::class);
    }

    public function coverages()
    {
        return $this->hasMany(Coverage::class);
    }
}
