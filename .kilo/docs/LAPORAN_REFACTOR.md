# Refactor Laporan Penjualan - Berdasarkan Actual Periode

## Overview

Refactor laporan penjualan untuk menggunakan `actual_periode` (tanggal pengantaran yang dipilih customer) sebagai basis perhitungan, bukan `created_at`. Ini memberikan gambaran yang lebih akurat tentang kapan produk benar-benar dikirim/diambil.

## Perubahan yang Dilakukan

### 1. Refactor LaporanController

#### A. getSalesDataForMonth()

**SEBELUM:**
```php
$orders = PreOrder::whereYear('created_at', $year)
    ->whereMonth('created_at', $month)
    ->whereIn('status', $validStatuses)
    ->get();
```

**SESUDAH:**
```php
$orders = PreOrder::whereYear('actual_periode', $year)
    ->whereMonth('actual_periode', $month)
    ->whereIn('status', $validStatuses)
    ->get();
```

**Alasan**: Laporan harus menampilkan penjualan berdasarkan tanggal pengantaran, bukan tanggal pemesanan.

#### B. getTopSellingProducts()

**SEBELUM:**
```php
$orderIds = PreOrder::whereYear('created_at', $year)
    ->whereMonth('created_at', $month)
    ->whereIn('status', $validStatuses)
    ->pluck('id');
```

**SESUDAH:**
```php
$orderIds = PreOrder::whereYear('actual_periode', $year)
    ->whereMonth('actual_periode', $month)
    ->whereIn('status', $validStatuses)
    ->pluck('id');
```

**Alasan**: Produk terlaris harus dihitung berdasarkan tanggal pengantaran.

#### C. getProductSalesCount()

**SEBELUM:**
```php
$orderIds = PreOrder::whereYear('created_at', $year)
    ->whereMonth('created_at', $month)
    ->whereIn('status', $validStatuses)
    ->pluck('id');
```

**SESUDAH:**
```php
$orderIds = PreOrder::whereYear('actual_periode', $year)
    ->whereMonth('actual_periode', $month)
    ->whereIn('status', $validStatuses)
    ->pluck('id');
```

**Alasan**: Chart perbandingan harus berdasarkan tanggal pengantaran.

#### D. getTransactionTable()

**SEBELUM:**
```php
$orders = PreOrder::with([...])
    ->whereYear('created_at', $year)
    ->whereMonth('created_at', $month)
    ->whereIn('status', $validStatuses)
    ->orderBy('created_at', 'desc')
    ->get();
```

**SESUDAH:**
```php
$orders = PreOrder::with([...])
    ->whereYear('actual_periode', $year)
    ->whereMonth('actual_periode', $month)
    ->whereIn('status', $validStatuses)
    ->orderBy('actual_periode', 'desc')
    ->get();
```

**Alasan**: Tabel transaksi harus menampilkan pesanan berdasarkan tanggal pengantaran.

**Bonus Fix**: Ubah ID pesanan dari `ORD-001` menjadi `PO-00001` untuk konsistensi.

---

### 2. Data Seeder untuk Perbandingan

#### A. LaporanPreviousMonthSeeder (April 2026)

**File**: `database/seeders/LaporanPreviousMonthSeeder.php`

**Data yang Dibuat**:
- 15 pesanan dengan `actual_periode` di April 2026
- Status: `completed` (semua sudah selesai)
- Total pendapatan: ~Rp 6.230.000
- Total produk terjual: ~85 pcs
- Periode: 5 April - 30 April 2026

**Distribusi Produk**:
- Produk ID 1: ~50 pcs (paling laris)
- Produk ID 2: ~17 pcs
- Produk ID 3: ~13 pcs
- Produk ID 4: ~8 pcs
- Produk ID 5: ~5 pcs
- Produk ID 6: ~4 pcs
- Produk ID 7: ~1 pcs

**Karakteristik**:
- Pesanan tersebar merata sepanjang bulan
- Fokus pada produk populer (ID 1, 2, 3)
- Semua pesanan sudah completed untuk simulasi bulan lalu

#### B. LaporanCurrentMonthSeeder (Mei 2026)

**File**: `database/seeders/LaporanCurrentMonthSeeder.php`

**Data yang Dibuat**:
- 20 pesanan dengan `actual_periode` di Mei 2026
- Status: `completed` (16) dan `processing` (4)
- Total pendapatan: ~Rp 9.340.000 (growth 49.9% dari April)
- Total produk terjual: ~130 pcs (growth 52.9% dari April)
- Periode: 3 Mei - 31 Mei 2026

**Distribusi Produk**:
- Produk ID 1: ~80 pcs (dominan)
- Produk ID 2: ~21 pcs
- Produk ID 3: ~26 pcs
- Produk ID 4: ~13 pcs
- Produk ID 5: ~9 pcs
- Produk ID 6: ~7 pcs

**Karakteristik**:
- Peningkatan signifikan dari bulan sebelumnya
- 4 pesanan masih processing (untuk tanggal 30-31 Mei)
- Produk ID 1 semakin dominan
- Menunjukkan tren pertumbuhan positif

---

### 3. Cara Menjalankan Seeder

#### Option 1: Fresh Migration + Seed
```bash
php artisan migrate:fresh --seed
```

#### Option 2: Seed Saja (Jika Data Sudah Ada)
```bash
php artisan db:seed --class=LaporanPreviousMonthSeeder
php artisan db:seed --class=LaporanCurrentMonthSeeder
```

#### Option 3: Seed Spesifik via DatabaseSeeder
DatabaseSeeder sudah diupdate untuk memanggil kedua seeder:
```php
$this->call([
    // ... seeder lainnya ...
    LaporanPreviousMonthSeeder::class,
    LaporanCurrentMonthSeeder::class,
]);
```

---

## Perbandingan Data

### April 2026 (Bulan Sebelumnya)
| Metrik | Nilai |
|--------|-------|
| Total Pendapatan | Rp 6.230.000 |
| Total Pesanan | 15 |
| Total Produk Terjual | 85 pcs |
| Status | Semua Completed |
| Produk Terlaris | Produk ID 1 (~59%) |

### Mei 2026 (Bulan Terpilih)
| Metrik | Nilai |
|--------|-------|
| Total Pendapatan | Rp 9.340.000 |
| Total Pesanan | 20 |
| Total Produk Terjual | 130 pcs |
| Status | 16 Completed, 4 Processing |
| Produk Terlaris | Produk ID 1 (~62%) |

### Growth
| Metrik | Growth |
|--------|--------|
| Pendapatan | +49.9% |
| Pesanan | +33.3% |
| Produk Terjual | +52.9% |

---

## Fitur Laporan yang Diimplementasikan

### ✅ 1. Summary Cards
- Total Penjualan (Pendapatan)
- Total Pesanan
- Total Produk Terjual

### ✅ 2. Bar Chart Perbandingan
- Perbandingan Pendapatan Bulanan (April vs Mei)
- Perbandingan Jumlah Terjual Bulanan (April vs Mei)
- Filter per produk

### ✅ 3. Pie Chart Produk Terlaris
- Dinamis dari database
- Interaktif dengan hover tooltip
- Animasi smooth
- Legend dengan persentase
- Warna dari controller

### ✅ 4. Tabel Penjualan Detail
- ID Pesanan
- Nama Pelanggan
- Nama Produk
- Tanggal Pembelian
- Tanggal Pengantaran (dari actual_periode)
- Total Harga (satu baris dengan whitespace-nowrap)
- Status
- Aksi (Hapus, View)

### ✅ 5. Filter
- Filter Bulan (dropdown)
- Filter Tahun (dropdown)
- Filter Produk (untuk chart perbandingan)
- Filter Status (untuk tabel)

### ✅ 6. Modal View Detail
- Informasi lengkap pesanan
- Produk yang dipesan
- Status pembayaran

### ✅ 7. Export PDF
- Export laporan ke PDF
- Include summary dan tabel

---

## Testing

### 1. Test Refactor Actual Periode

```bash
# 1. Jalankan seeder
php artisan db:seed --class=LaporanPreviousMonthSeeder
php artisan db:seed --class=LaporanCurrentMonthSeeder

# 2. Buka halaman laporan
http://localhost:8000/laporan

# 3. Test filter bulan
- Pilih April 2026 → Harus tampil 15 pesanan
- Pilih Mei 2026 → Harus tampil 20 pesanan

# 4. Verifikasi data
- Summary cards harus sesuai dengan data seeder
- Bar chart harus menunjukkan growth
- Pie chart harus menampilkan produk terlaris
- Tabel harus menampilkan pesanan berdasarkan actual_periode
```

### 2. Test Pie Chart

```bash
# 1. Buka halaman laporan
http://localhost:8000/laporan

# 2. Periksa pie chart
- Chart harus muncul dengan warna berbeda
- Hover pada segment untuk tooltip
- Legend harus sinkron dengan chart

# 3. Test filter bulan
- Ganti bulan → Chart harus update
- Data harus sesuai dengan bulan yang dipilih
```

### 3. Test Perbandingan Bulan

```bash
# 1. Pilih Mei 2026
# 2. Periksa bar chart perbandingan
- Bar kiri: April 2026
- Bar kanan: Mei 2026
- Mei harus lebih tinggi (growth)

# 3. Periksa angka
- Pendapatan Mei > Pendapatan April
- Jumlah terjual Mei > Jumlah terjual April
```

### 4. Test Tabel

```bash
# 1. Scroll ke tabel penjualan
# 2. Periksa kolom "Tanggal Pengantaran"
- Harus sesuai dengan actual_periode
- Format: dd/mm/yy

# 3. Periksa kolom "Total Harga"
- Harus dalam satu baris
- Format: Rp X.XXX.XXX

# 4. Test filter status
- Pilih "Selesai" → Hanya tampil completed
- Pilih "Dikerjakan" → Hanya tampil processing
```

---

## Troubleshooting

### Data Tidak Muncul

**Problem**: Laporan kosong atau tidak ada data

**Solution**:
```bash
# 1. Cek apakah seeder sudah dijalankan
php artisan db:seed --class=LaporanPreviousMonthSeeder
php artisan db:seed --class=LaporanCurrentMonthSeeder

# 2. Cek data di database
SELECT COUNT(*) FROM pre_orders WHERE YEAR(actual_periode) = 2026 AND MONTH(actual_periode) = 5;

# 3. Cek filter bulan dan tahun
- Pastikan memilih Mei 2026
```

### Pie Chart Tidak Muncul

**Problem**: Canvas kosong atau error

**Solution**:
```bash
# 1. Periksa console browser (F12)
# 2. Pastikan Chart.js loaded
console.log(typeof Chart); // Harus return "function"

# 3. Periksa data
console.log(@json($produkTerlaris));

# 4. Clear cache
php artisan view:clear
php artisan cache:clear
```

### Perbandingan Tidak Akurat

**Problem**: Bar chart tidak menunjukkan perbedaan yang benar

**Solution**:
```bash
# 1. Periksa data bulan sebelumnya
- Pastikan ada data di April 2026

# 2. Periksa query
- Pastikan menggunakan actual_periode, bukan created_at

# 3. Periksa perhitungan
- Lihat LaporanController::getSalesDataForMonth()
```

---

## File yang Diubah/Dibuat

### Modified
- `app/Http/Controllers/LaporanController.php`
  - getSalesDataForMonth(): whereYear/whereMonth actual_periode
  - getTopSellingProducts(): whereYear/whereMonth actual_periode
  - getProductSalesCount(): whereYear/whereMonth actual_periode
  - getTransactionTable(): whereYear/whereMonth actual_periode, orderBy actual_periode
  - Fix ID pesanan: ORD-001 → PO-00001

- `database/seeders/DatabaseSeeder.php`
  - Tambah LaporanPreviousMonthSeeder
  - Tambah LaporanCurrentMonthSeeder

### Created
- `database/seeders/LaporanPreviousMonthSeeder.php`
  - 15 pesanan April 2026
  - Total: Rp 6.230.000
  - 85 produk terjual

- `database/seeders/LaporanCurrentMonthSeeder.php`
  - 20 pesanan Mei 2026
  - Total: Rp 9.340.000
  - 130 produk terjual

---

## Benefits

### 1. Akurasi Laporan
- Laporan berdasarkan tanggal pengantaran, bukan tanggal pemesanan
- Lebih akurat untuk analisis penjualan
- Sesuai dengan flow bisnis (kapan produk benar-benar dikirim)

### 2. Data Perbandingan
- Ada data bulan sebelumnya untuk perbandingan
- Menunjukkan tren pertumbuhan
- Memudahkan analisis performa

### 3. Konsistensi
- Semua query menggunakan actual_periode
- ID pesanan konsisten (PO-00001)
- Status mapping konsisten

### 4. Testing
- Data seeder memudahkan testing
- Reproducible data
- Tidak perlu manual input

---

## Next Steps

### 1. Export PDF
- Pastikan PDF export juga menggunakan actual_periode
- Test PDF generation dengan data baru

### 2. Dashboard Integration
- Update dashboard untuk menggunakan actual_periode
- Sinkronkan dengan laporan

### 3. Additional Features
- Filter tahun (saat ini hanya bulan)
- Export Excel
- Chart tambahan (line chart untuk trend)
- Perbandingan year-over-year

### 4. Performance
- Index pada actual_periode untuk query lebih cepat
- Cache hasil perhitungan
- Pagination untuk tabel besar

---

## Referensi

- [Laravel Query Builder - whereYear/whereMonth](https://laravel.com/docs/11.x/queries#where-clauses)
- [Carbon Date Manipulation](https://carbon.nesbot.com/docs/)
- [Database Seeding](https://laravel.com/docs/11.x/seeding)
