# Perbaikan Modal Edit Cepat dan Modal Detail - Halaman Pesanan Admin

## Masalah yang Ditemukan

Modal edit cepat (status modal) dan modal detail (view modal) tidak terbuka saat:
1. Status pada tabel ditekan
2. Tombol detail ditekan

## Penyebab Masalah

### 1. ID Element Tidak Konsisten
- **Modal Status**: 
  - HTML menggunakan `id="expeditionDropdownContainer"` 
  - JavaScript mencari `id="modalExpeditionContainer"`
  - JavaScript mencari `id="modalEkspedisi"` tetapi ID sebenarnya adalah `id="modalExpeditionSelect"`

### 2. Data Attribute Tidak Sesuai
- **Modal View**:
  - JavaScript mencari `row.dataset.tanggalPengantaran`
  - HTML hanya menyediakan `data-actual-periode`

### 3. Modal Display Tidak Konsisten
- Modal status menggunakan `class="hidden"` dan `style.display`
- Modal view hanya menggunakan `style.display`

## Perbaikan yang Dilakukan

### 1. Perbaikan ID Element (resources/views/pages/pesanan-admin/index.blade.php)

**Baris 179**: Mengubah ID container ekspedisi
```html
<!-- SEBELUM -->
<div id="expeditionDropdownContainer" class="hidden space-y-2">

<!-- SESUDAH -->
<div id="modalExpeditionContainer" class="hidden space-y-2">
```

**Baris 519 & 533**: Mengubah referensi ID select ekspedisi
```javascript
// SEBELUM
const ekspedisi = document.getElementById('modalEkspedisi')?.value || '';
document.getElementById('modalEkspedisi').focus();

// SESUDAH
const ekspedisi = document.getElementById('modalExpeditionSelect')?.value || '';
document.getElementById('modalExpeditionSelect').focus();
```

### 2. Perbaikan Data Attribute (Baris 635)

```javascript
// SEBELUM
document.getElementById('view_tanggal_pengantaran').textContent = row.dataset.tanggalPengantaran || '-';

// SESUDAH
document.getElementById('view_tanggal_pengantaran').textContent = row.dataset.actualPeriode || '-';
```

### 3. Perbaikan Fungsi openStatusModal (Baris 470-512)

Menambahkan:
- Console.log untuk debugging
- Validasi row tidak null
- Konsistensi penggunaan class `hidden` dan `style.display`

```javascript
function openStatusModal(btn, id, statusCode) {
    console.log('openStatusModal called with:', {btn, id, statusCode});
    
    const row = document.querySelector(`tr[data-id="${id}"]`);
    if (!row) {
        console.error('Row not found for id:', id);
        return;
    }
    
    // ... kode lainnya ...
    
    const modal = document.getElementById('statusModal');
    modal.classList.remove('hidden');
    modal.style.display = 'flex';
}
```

### 4. Perbaikan Fungsi closeStatusModal (Baris 580-588)

```javascript
function closeStatusModal() {
    const modal = document.getElementById('statusModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.getElementById('nomorResi').value = '';
    document.getElementById('resiInputContainer').classList.add('hidden');
    document.getElementById('modalExpeditionContainer').classList.add('hidden');
    document.getElementById('modalSendTypeLabel').textContent = '-';
}
```

### 5. Perbaikan Fungsi openViewModal (Baris 629-711)

Menambahkan:
- Console.log untuk debugging
- Validasi row tidak null

```javascript
function openViewModal(id) {
    const row = document.querySelector(`tr[data-id="${id}"]`);
    if (!row) {
        console.error('Row not found for id:', id);
        return;
    }
    
    // ... kode lainnya ...
    
    const modal = document.getElementById('viewModal');
    modal.style.display = 'flex';
    console.log('View modal opened for id:', id);
}
```

## Cara Testing

1. Buka halaman pesanan admin: `/pesanan`
2. Klik status pada tabel (hanya yang berstatus "Dikerjakan" yang bisa dibuka)
3. Klik tombol detail (ikon mata) pada baris pesanan
4. Periksa console browser untuk melihat log debugging
5. Pastikan modal terbuka dengan benar
6. Pastikan data ditampilkan dengan benar
7. Pastikan modal bisa ditutup dengan tombol X atau klik di luar modal

## File yang Diubah

- `resources/views/pages/pesanan-admin/index.blade.php`

## Catatan

- Modal status hanya bisa dibuka untuk pesanan dengan status "Dikerjakan" (processing)
- Modal view bisa dibuka untuk semua pesanan
- Jika modal tidak terbuka, periksa console browser untuk error
- Pastikan data-attributes pada row tabel sudah lengkap
