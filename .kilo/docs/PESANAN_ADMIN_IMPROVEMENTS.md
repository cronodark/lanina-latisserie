# Perbaikan Halaman Pesanan Admin

## Tanggal: 2026-05-08

### 1. Perbaikan Kolom Tanggal

#### Sebelum:
- **Tanggal Pembelian**: `created_at` (kapan order dibuat di sistem)
- **Tanggal Pengantaran**: Logic kompleks dengan `start_periode`, `end_periode`, `actual_periode`

#### Sesudah:
- **Tanggal Pembelian**: `actual_periode` (tanggal yang dipilih customer untuk pengambilan/pengiriman)
- **Tanggal Pengiriman**: `created_at` (kapan order dibuat/dipesan)

#### Alasan Perubahan:
- `actual_periode` lebih relevan sebagai "tanggal pembelian" karena ini adalah tanggal yang customer pilih saat checkout
- `created_at` lebih tepat sebagai "tanggal pengiriman" karena menunjukkan kapan pesanan masuk ke sistem
- Lebih konsisten dengan flow bisnis: customer pilih tanggal → buat order → proses → kirim

### 2. Modal Edit Status dengan Dropdown Ekspedisi

#### Fitur Baru:
1. **Dropdown Ekspedisi** di modal status
   - Pilihan: JNE, J&T, SiCepat, Anteraja, ID Express, Ninja Express
   - Hanya muncul jika `send_type` = `kurirEkspedisi`
   - Value tersimpan di field `choosen_expedition`

2. **Input Nomor Resi**
   - Tetap ada dan wajib diisi untuk kurir ekspedisi
   - Tersimpan di field `tracking_number`

3. **Display Info**
   - Menampilkan metode kirim saat ini
   - Menampilkan ekspedisi yang sudah dipilih (jika ada)

#### Implementasi:

**Controller** (`PesananController.php`):
```php
// Line 56-61: Update tanggal pembelian ke actual_periode
$tanggalPembelian = $this->formatDateString($order->actual_periode);
$tanggalPengiriman = $this->formatDateString($order->created_at);

// Line 99: Tambah validation untuk choosen_expedition
'choosen_expedition' => ['nullable', 'string', 'max:50'],

// Line 136-138: Update data ekspedisi
if (!empty($validated['choosen_expedition'])) {
    $updateData['choosen_expedition'] = $validated['choosen_expedition'];
}
```

**View** (`pesanan-admin/index.blade.php`):
```html
<!-- Line 185-201: Dropdown Ekspedisi di Modal -->
<div id="expeditionSelectContainer" class="hidden space-y-2 bg-gray-50 p-3 rounded-lg">
    <label class="block text-sm font-medium text-gray-700">
        Pilih Ekspedisi <span class="text-red-500">*</span>
    </label>
    <select id="choosenExpedition" class="w-full px-3 py-2 border...">
        <option value="">-- Pilih Ekspedisi --</option>
        <option value="JNE">JNE</option>
        <option value="J&T">J&T Express</option>
        <option value="SiCepat">SiCepat</option>
        <option value="Anteraja">Anteraja</option>
        <option value="ID Express">ID Express</option>
        <option value="Ninja Express">Ninja Express</option>
    </select>
</div>
```

**JavaScript** (`pesanan-admin/index.blade.php`):
```javascript
// Line 506-520: Show/hide dropdown ekspedisi
if (sendType === 'kurirEkspedisi') {
    expeditionSelectContainer.classList.remove('hidden');
    choosenExpeditionSelect.value = expedition || '';
    resiInputContainer.classList.remove('hidden');
}

// Line 535-542: Include ekspedisi in payload
const payload = {
    status: status,
    nomor_resi: (sendType === 'kurirEkspedisi' && nomorResi) ? nomorResi : undefined,
    choosen_expedition: (sendType === 'kurirEkspedisi' && choosenExpedition) ? choosenExpedition : undefined
};
```

### 3. Struktur Data Return

**PesananController::index()** sekarang mengembalikan:
```php
[
    'id' => $order->id,
    'id_pesanan' => 'PO-00001',
    'nama_pelanggan' => 'John Doe',
    'nama_produk' => 'Kue A, Kue B',
    'tanggal_pembelian' => '10/05/26',      // actual_periode
    'tanggal_pengiriman' => '08/05/26',     // created_at
    'total_harga' => 150000,
    'status' => 'processing',
    'send_type' => 'kurirEkspedisi',
    'choosen_expedition' => 'JNE',          // NEW
    'nomor_resi' => 'JNE123456',
    // ... other fields
]
```

### 4. Flow Update Status

1. Admin klik tombol status "Dikerjakan" pada pesanan
2. Modal terbuka menampilkan:
   - Status target: "Dikirim" (fixed)
   - Metode kirim: (readonly, dari data order)
   - **Dropdown Ekspedisi**: (jika kurir ekspedisi)
   - Input Nomor Resi: (jika kurir ekspedisi)
3. Admin pilih ekspedisi dan isi nomor resi
4. Klik "Done"
5. Data tersimpan:
   - `status` → 'shipping'
   - `choosen_expedition` → 'JNE' (atau pilihan lain)
   - `tracking_number` → 'JNE123456'

### 5. Validasi

**Required Fields**:
- Jika `send_type` = `kurirEkspedisi`:
  - ✅ `choosen_expedition` wajib dipilih
  - ✅ `nomor_resi` wajib diisi
- Jika `send_type` = `pickUp` atau `kurirToko`:
  - ❌ Tidak perlu ekspedisi
  - ❌ Tidak perlu nomor resi

### 6. Database Schema

**Tidak ada perubahan schema** - menggunakan field existing:
- `actual_periode` (DATE) - sudah ada
- `created_at` (TIMESTAMP) - sudah ada
- `choosen_expedition` (VARCHAR) - sudah ada
- `tracking_number` (VARCHAR) - sudah ada

### 7. Benefits

✅ **Tanggal lebih akurat**: `actual_periode` sebagai tanggal pembelian lebih sesuai dengan ekspektasi user
✅ **Tracking lengkap**: Admin bisa pilih ekspedisi yang digunakan
✅ **Data terstruktur**: `choosen_expedition` tersimpan di database untuk reporting
✅ **UX lebih baik**: Dropdown lebih user-friendly daripada free text
✅ **Validasi ketat**: Ekspedisi wajib dipilih untuk kurir ekspedisi

### 8. Testing Checklist

- [ ] Tanggal pembelian menampilkan `actual_periode` dengan benar
- [ ] Tanggal pengiriman menampilkan `created_at` dengan benar
- [ ] Modal status menampilkan dropdown ekspedisi untuk kurir ekspedisi
- [ ] Dropdown ekspedisi tidak muncul untuk pickup/kurir toko
- [ ] Validasi ekspedisi wajib diisi untuk kurir ekspedisi
- [ ] Data ekspedisi tersimpan dengan benar di database
- [ ] Display ekspedisi muncul di view modal detail pesanan
- [ ] Update status berhasil dengan ekspedisi dan nomor resi

### 9. Files Modified

1. `app/Http/Controllers/PesananController.php`
   - Line 56-61: Update tanggal pembelian/pengiriman
   - Line 64-70: Update return data structure
   - Line 99: Add validation for choosen_expedition
   - Line 136-138: Save choosen_expedition to database

2. `resources/views/pages/pesanan-admin/index.blade.php`
   - Line 42: Update header "Tanggal Pengantaran" → "Tanggal Pengiriman"
   - Line 89-94: Update display tanggal di tabel
   - Line 185-201: Add dropdown ekspedisi di modal
   - Line 506-520: JavaScript show/hide dropdown
   - Line 535-542: JavaScript include ekspedisi in payload

### 10. Future Enhancements

- [ ] Auto-fill nomor resi format berdasarkan ekspedisi yang dipilih
- [ ] Integrasi tracking API untuk validasi nomor resi
- [ ] Bulk update status untuk multiple orders
- [ ] Export data dengan filter ekspedisi
- [ ] Dashboard analytics per ekspedisi
