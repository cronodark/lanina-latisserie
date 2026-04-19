<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreOrderDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'quantity',
        'type',
        'product_id',
        'promo_id',
        'pre_order_id',
    ];

    public function preOrder()
    {
        return $this->belongsTo(PreOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }
}
