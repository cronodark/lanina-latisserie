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
        'start_periode',
        'end_periode',
        'send_type',
        'tracking_number',
        'choosen_expedition',
        'address_id',
        'user_id',
    ];

    protected $casts = [
        'actual_periode' => 'date',
        'start_periode' => 'date',
        'end_periode' => 'date',
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
