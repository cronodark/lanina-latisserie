# Refactor Dashboard - Grafik Berdasarkan Actual Periode

## Overview

Dashboard grafik penjualan direfactor untuk menggunakan `actual_periode` (tanggal slot pengantaran) sebagai basis perhitungan, bukan `created_at` (tanggal pemesanan). Ini memberikan gambaran yang lebih akurat tentang kapan slot digunakan dan produk benar-benar dikirim/diambil.

## Alasan Refactor

### Mengapa `actual_periode` bukan `created_at`?

1. **Slot Management**: `actual_periode` adalah tanggal yang dipilih customer untuk pengantaran/pengambilan
2. **Kapasitas Produksi**: Menunjukkan beban kerja per hari berdasarkan tanggal pengantaran
3. **Konsistensi**: Laporan penjualan sudah menggunakan `actual_periode`
4. **Business Logic**: Yang penting adalah kapan produk dikirim, bukan kapan dipesan

### Contoh Kasus:
```
Customer pesan tanggal 1 Mei (created_at)
Pilih pengantaran tanggal 10 Mei (actual_periode)

SEBELUM: Grafik menghitung di tanggal 1 Mei
SESUDAH: Grafik menghitung di tanggal 10 Mei ✓

Ini lebih akurat karena:
- Slot terpakai di tanggal 10 Mei
- Produksi dilakukan untuk tanggal 10 Mei
- Kapasitas dihitung untuk tanggal 10 Mei
```

---

## Perubahan yang Dilakukan

### 1. getGrafikPerHari() - Grafik Per Hari

**SEBELUM:**
```php
private function getGrafikPerHari(int $year, int $month): array
{
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    $rows = PreOrder::selectRaw('DAY(created_at) as hari, SUM(total) as total')
        ->whereYear('created_at', $year)
        ->whereMonth('created_at', $month)
        ->whereNotNull('created_at')
        ->groupBy('hari')
        ->orderBy('hari')
        ->pluck('total', 'hari');
    
    // ... rest of code
}
```

**SESUDAH:**
```php
private function getGrafikPerHari(int $year, int $month): array
{
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    // Query berdasarkan actual_periode (tanggal slot)
    $rows = PreOrder::selectRaw('DAY(actual_periode) as hari, SUM(total) as total')
        ->whereYear('actual_periode', $year)
        ->whereMonth('actual_periode', $month)
        ->whereNotNull('actual_periode')
        ->whereIn('status', ['unpaid', 'processing', 'shipping', 'completed']) // Exclude canceled
        ->groupBy('hari')
        ->orderBy('hari')
        ->pluck('total', 'hari');
    
    // ... rest of code
}
```

**Perubahan:**
- ✅ `DAY(created_at)` → `DAY(actual_periode)`
- ✅ `whereYear/whereMonth('created_at')` → `whereYear/whereMonth('actual_periode')`
- ✅ `whereNotNull('created_at')` → `whereNotNull('actual_periode')`
- ✅ Tambah filter status untuk exclude canceled orders

---

### 2. getGrafikPerBulan() - Grafik Per Bulan

**SEBELUM:**
```php
private function getGrafikPerBulan(int $year): array
{
    $rows = PreOrder::selectRaw('MONTH(created_at) as bulan, SUM(total) as total')
        ->whereYear('created_at', $year)
        ->whereNotNull('created_at')
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->pluck('total', 'bulan');
    
    // ... rest of code
}
```

**SESUDAH:**
```php
private function getGrafikPerBulan(int $year): array
{
    // Query berdasarkan actual_periode (tanggal slot)
    $rows = PreOrder::selectRaw('MONTH(actual_periode) as bulan, SUM(total) as total')
        ->whereYear('actual_periode', $year)
        ->whereNotNull('actual_periode')
        ->whereIn('status', ['unpaid', 'processing', 'shipping', 'completed']) // Exclude canceled
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->pluck('total', 'bulan');
    
    // ... rest of code
}
```

**Perubahan:**
- ✅ `MONTH(created_at)` → `MONTH(actual_periode)`
- ✅ `whereYear('created_at')` → `whereYear('actual_periode')`
- ✅ `whereNotNull('created_at')` → `whereNotNull('actual_periode')`
- ✅ Tambah filter status untuk exclude canceled orders

---

## Status Filter

Kedua fungsi sekarang memfilter status untuk hanya menghitung pesanan yang valid:

```php
->whereIn('status', ['unpaid', 'processing', 'shipping', 'completed'])
```

**Status yang Dihitung:**
- ✅ `unpaid` - Belum bayar (tapi sudah booking slot)
- ✅ `processing` - Sedang dikerjakan
- ✅ `shipping` - Sedang dikirim
- ✅ `completed` - Selesai

**Status yang Dikecualikan:**
- ❌ `canceled` - Dibatalkan (slot dikembalikan)

**Alasan**: Pesanan yang dibatalkan tidak menggunakan slot, jadi tidak dihitung dalam grafik penjualan.

---

## Dampak pada Dashboard

### Grafik Per Hari (Default View)
```
Sebelum: Menampilkan pesanan berdasarkan tanggal pemesanan
Sesudah: Menampilkan pesanan berdasarkan tanggal pengantaran

Contoh Mei 2026:
- Tanggal 3: Rp 520.000 (1 pesanan dengan actual_periode 3 Mei)
- Tanggal 4: Rp 380.000 (1 pesanan dengan actual_periode 4 Mei)
- Tanggal 5: Rp 640.000 (1 pesanan dengan actual_periode 5 Mei)
- dst...
```

### Grafik Per Bulan (Yearly View)
```
Sebelum: Menampilkan pesanan berdasarkan bulan pemesanan
Sesudah: Menampilkan pesanan berdasarkan bulan pengantaran

Contoh 2026:
- April: Rp 6.230.000 (15 pesanan dengan actual_periode di April)
- Mei: Rp 9.340.000 (20 pesanan dengan actual_periode di Mei)
- dst...
```

---

## Konsistensi dengan Laporan

Sekarang Dashboard dan Laporan menggunakan basis yang sama:

| Fitur | Basis Perhitungan | Status |
|-------|-------------------|--------|
| Dashboard - Grafik Per Hari | `actual_periode` | ✅ |
| Dashboard - Grafik Per Bulan | `actual_periode` | ✅ |
| Laporan - Summary | `actual_periode` | ✅ |
| Laporan - Produk Terlaris | `actual_periode` | ✅ |
| Laporan - Chart Perbandingan | `actual_periode` | ✅ |
| Laporan - Tabel Transaksi | `actual_periode` | ✅ |

---

## Testing

### 1. Test Grafik Per Hari

```bash
# 1. Buka dashboard
http://localhost:8000/dashboard

# 2. Periksa grafik default (bulan ini)
- Harus menampilkan data Mei 2026
- Total harus sesuai dengan data seeder: ~Rp 9.340.000

# 3. Ganti filter ke April 2026
- Pilih bulan: April
- Pilih tahun: 2026
- Total harus sesuai dengan data seeder: ~Rp 6.230.000

# 4. Verifikasi data per hari
- Hover pada bar chart
- Tanggal harus sesuai dengan actual_periode
- Nilai harus sesuai dengan total pesanan di tanggal tersebut
```

### 2. Test Grafik Per Bulan

```bash
# 1. Buka dashboard
http://localhost:8000/dashboard

# 2. Ganti view ke "Per Bulan"
- Klik toggle/button untuk view bulanan
- Harus menampilkan 12 bulan (Jan-Des)

# 3. Verifikasi data
- April: ~Rp 6.230.000
- Mei: ~Rp 9.340.000
- Bulan lain: Rp 0 (jika tidak ada data)

# 4. Ganti tahun
- Pilih tahun berbeda
- Data harus update sesuai tahun
```

### 3. Test Konsistensi dengan Laporan

```bash
# 1. Buka dashboard
http://localhost:8000/dashboard
- Catat total Mei 2026

# 2. Buka laporan
http://localhost:8000/laporan
- Pilih Mei 2026
- Total harus sama dengan dashboard

# 3. Verifikasi
- Dashboard Mei: Rp 9.340.000
- Laporan Mei: Rp 9.340.000
- Harus sama! ✓
```

---

## Data Seeder

Data yang sudah dibuat untuk testing:

### April 2026
```
✓ 15 pesanan
✓ Total: Rp 6.230.000
✓ Produk: 101 pcs
✓ Status: Semua completed
✓ Periode: 5-30 April 2026
```

### Mei 2026
```
✓ 20 pesanan
✓ Total: Rp 9.340.000
✓ Produk: 157 pcs
✓ Status: 16 completed, 4 processing
✓ Periode: 3-31 Mei 2026
```

**Distribusi Per Hari (Mei 2026):**
- 3 Mei: Rp 520.000
- 4 Mei: Rp 380.000
- 5 Mei: Rp 640.000
- 6 Mei: Rp 295.000
- 10 Mei: Rp 475.000
- 11 Mei: Rp 560.000
- 12 Mei: Rp 420.000
- 13 Mei: Rp 385.000
- 17 Mei: Rp 590.000
- 18 Mei: Rp 410.000
- 19 Mei: Rp 505.000
- 20 Mei: Rp 340.000
- 24 Mei: Rp 625.000
- 25 Mei: Rp 455.000
- 26 Mei: Rp 530.000
- 27 Mei: Rp 395.000
- 30 Mei: Rp 480.000
- 31 Mei: Rp 1.335.000 (3 pesanan)

---

## Troubleshooting

### Grafik Tidak Menampilkan Data

**Problem**: Grafik kosong atau semua nilai 0

**Solution**:
```bash
# 1. Cek apakah data seeder sudah dijalankan
SELECT COUNT(*) FROM pre_orders 
WHERE YEAR(actual_periode) = 2026 
AND MONTH(actual_periode) = 5;

# 2. Cek filter bulan/tahun
- Pastikan memilih Mei 2026

# 3. Cek status pesanan
SELECT status, COUNT(*) FROM pre_orders 
WHERE YEAR(actual_periode) = 2026 
AND MONTH(actual_periode) = 5
GROUP BY status;

# 4. Clear cache
php artisan cache:clear
php artisan view:clear
```

### Data Tidak Konsisten dengan Laporan

**Problem**: Total di dashboard berbeda dengan laporan

**Solution**:
```bash
# 1. Periksa query di DashboardController
- Pastikan menggunakan actual_periode
- Pastikan filter status sama

# 2. Periksa query di LaporanController
- Pastikan menggunakan actual_periode
- Pastikan filter status sama

# 3. Debug query
// Tambahkan di controller
dd(PreOrder::whereYear('actual_periode', 2026)
    ->whereMonth('actual_periode', 5)
    ->whereIn('status', ['unpaid', 'processing', 'shipping', 'completed'])
    ->sum('total'));
```

### Grafik Tidak Update Saat Ganti Filter

**Problem**: Grafik tidak berubah saat ganti bulan/tahun

**Solution**:
```bash
# 1. Periksa JavaScript
- Buka console browser (F12)
- Cek apakah ada error

# 2. Periksa API endpoint
- Test manual: /dashboard/grafik-data?year=2026&month=5
- Harus return JSON dengan data

# 3. Periksa event listener
- Pastikan onChange event terpasang
- Pastikan AJAX request terkirim
```

---

## File yang Diubah

### Modified
- `app/Http/Controllers/DashboardController.php`
  - `getGrafikPerHari()`: whereYear/whereMonth actual_periode + status filter
  - `getGrafikPerBulan()`: whereYear actual_periode + status filter

### Documentation
- `.kilo/docs/DASHBOARD_REFACTOR.md` (file ini)

---

## Benefits

### 1. Akurasi Data
- Grafik menunjukkan kapan slot benar-benar digunakan
- Sesuai dengan kapasitas produksi per hari
- Membantu planning produksi

### 2. Konsistensi
- Dashboard dan Laporan menggunakan basis yang sama
- Tidak ada perbedaan angka antara dashboard dan laporan
- Mudah untuk cross-check data

### 3. Business Insight
- Melihat beban kerja per hari berdasarkan pengantaran
- Identifikasi hari-hari sibuk
- Planning kapasitas lebih akurat

### 4. Slot Management
- Grafik mencerminkan penggunaan slot
- Membantu analisis kapasitas slot
- Identifikasi kebutuhan tambahan slot

---

## Next Steps

### 1. Dashboard Enhancement
- Tambah card summary (total penjualan, pesanan, produk)
- Tambah filter status pada grafik
- Tambah comparison dengan bulan sebelumnya

### 2. Real-time Update
- Auto-refresh grafik setiap X menit
- Notifikasi jika ada pesanan baru
- Live counter untuk pesanan hari ini

### 3. Additional Charts
- Pie chart produk terlaris (seperti di laporan)
- Line chart trend penjualan
- Bar chart perbandingan bulan-bulan

### 4. Export
- Export grafik ke image
- Export data ke Excel
- Scheduled report via email

---

## Referensi

- [Laravel Query Builder - whereYear/whereMonth](https://laravel.com/docs/11.x/queries#where-clauses)
- [MySQL DATE Functions](https://dev.mysql.com/doc/refman/8.0/en/date-and-time-functions.html)
- [Chart.js Documentation](https://www.chartjs.org/docs/latest/)
