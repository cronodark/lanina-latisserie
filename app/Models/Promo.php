<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'price',
        'date_until',
        'status',
    ];

    protected $casts = [
        'price' => 'integer',
        'date_until' => 'date',
    ];

    public function preOrdersDetail()
    {
        return $this->hasMany(PreOrderDetail::class);
    }
}
