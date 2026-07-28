<?php

namespace App\Models\Models\KMJ;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Insurer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'status',
    ];

    public function orders()
    {
        return $this->hasMany(InsuranceOrder::class);
    }
}
