<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'actual_periode',
        'status',
        'payment_status',
        'payment_method',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'payment_redirect_url',
        'paid_at',
        'start_periode',
        'end_periode',
        'send_type',
        'tracking_number',
        'choosen_expedition',
        'address_id',
        'user_id',
        'total'
    ];

    protected $casts = [
        'actual_periode' => 'date',
        'start_periode' => 'date',
        'end_periode' => 'date',
        'paid_at' => 'datetime',
        'total' => 'integer',
    ];

    public function detailPreOrders()
    {
        return $this->hasMany(PreOrderDetail::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }
}
