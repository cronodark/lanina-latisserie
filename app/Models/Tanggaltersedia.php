<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TanggalTersedia extends Model
{
    use HasFactory;

    protected $table = 'tanggal_tersedia';

    protected $fillable = [
        'tanggal',
        'kuota',
        'keterangan',
        'is_aktif',
    ];

    protected $casts = [
        'tanggal'  => 'date',
        'is_aktif' => 'boolean',
    ];

    // ─── Relasi ───────────────────────────────────────────────────────────────

    /**
     * PreOrder yang memilih tanggal ini sebagai actual_periode.
     */
    public function preOrders()
    {
        return $this->hasMany(PreOrder::class, 'actual_periode', 'tanggal');
    }

    // ─── Accessor ─────────────────────────────────────────────────────────────

    /**
     * Jumlah slot yang sudah terisi (total pesanan termasuk unpaid untuk lock slot).
     * Lock slot sejak order dibuat untuk mencegah race condition dan overbooking.
     */
    public function getTerisiAttribute(): int
    {
        return $this->preOrders()
            ->whereIn('status', ['unpaid', 'paid', 'processing', 'shipping', 'completed'])
            ->count();
    }

    /**
     * Sisa kuota yang masih bisa dipesan.
     */
    public function getSisaKuotaAttribute(): int
    {
        return max(0, $this->kuota - $this->terisi);
    }

    /**
     * Status dinamis: Penuh jika sisa kuota = 0, Aktif selama is_aktif true.
     */
    public function getStatusAttribute(): string
    {
        if (!$this->is_aktif) return 'Nonaktif';
        if ($this->sisa_kuota <= 0) return 'Penuh';
        return 'Aktif';
    }

    // ─── Scope ────────────────────────────────────────────────────────────────

    /** Hanya slot yang aktif (is_aktif = true). */
    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }

    /** Hanya tanggal yang belum lewat. */
    public function scopeMendatang($query)
    {
        return $query->where('tanggal', '>=', now()->startOfDay());
    }
}