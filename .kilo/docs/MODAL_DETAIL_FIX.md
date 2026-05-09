# Perbaikan Modal Detail untuk PO-00028 dan PO-00006

## Masalah yang Ditemukan

Modal detail tidak muncul untuk pesanan tertentu (PO-00028 dan PO-00006), meskipun modal detail untuk pesanan lain berfungsi dengan baik.

## Analisis Masalah

### 1. Data Pesanan yang Bermasalah

**PO-00006:**
- `actual_periode`: `null`
- `choosen_expedition`: `null`
- `tracking_number`: `null`

**PO-00028:**
- `actual_periode`: `"2026-05-09T00:00:00.000000Z"`
- Data lengkap tersedia

### 2. Penyebab Masalah

#### A. Encoding JSON pada Data Attribute
**Masalah:**
```html
<!-- SEBELUM -->
data-detail-produk='@json($item->detail_produk)'
```

Menggunakan single quote `'` untuk attribute yang berisi JSON bisa menyebabkan masalah jika:
- JSON mengandung single quote
- Ada karakter khusus yang tidak di-escape dengan benar
- HTML entities tidak di-decode dengan benar

**Solusi:**
```html
<!-- SESUDAH -->
data-detail-produk="{{ htmlspecialchars(json_encode($item->detail_produk), ENT_QUOTES, 'UTF-8') }}"
```

Menggunakan:
- `json_encode()` untuk mengkonversi array ke JSON
- `htmlspecialchars()` dengan `ENT_QUOTES` untuk escape semua karakter khusus HTML
- Double quote `"` untuk konsistensi dengan attribute lainnya

#### B. Parsing JSON di JavaScript

**Masalah:**
JavaScript tidak mendecode HTML entities sebelum parsing JSON.

**Solusi:**
```javascript
// Decode HTML entities menggunakan textarea trick
const textarea = document.createElement('textarea');
textarea.innerHTML = detailProdukStr;
const decodedStr = textarea.value;
details = JSON.parse(decodedStr);
```

## Perbaikan yang Dilakukan

### 1. Perbaikan Encoding di Blade Template (Baris 66-82)

```php
<!-- SEBELUM -->
<tr data-id="{{ $item->id }}" data-status="{{ $item->status_filter }}"
    data-detail-produk='@json($item->detail_produk)'
    ...>

<!-- SESUDAH -->
<tr data-id="{{ $item->id }}" data-status="{{ $item->status_filter }}"
    data-detail-produk="{{ htmlspecialchars(json_encode($item->detail_produk), ENT_QUOTES, 'UTF-8') }}"
    ...>
```

### 2. Perbaikan Parsing JSON di JavaScript (Baris 674-687)

```javascript
// SEBELUM
let details = [];
try {
    details = JSON.parse(row.dataset.detailProduk || '[]');
} catch (e) {
    details = [];
}

// SESUDAH
let details = [];
try {
    const detailProdukStr = row.dataset.detailProduk || '[]';
    console.log('Raw detail_produk:', detailProdukStr);
    
    // Decode HTML entities jika ada
    const textarea = document.createElement('textarea');
    textarea.innerHTML = detailProdukStr;
    const decodedStr = textarea.value;
    console.log('Decoded detail_produk:', decodedStr);
    
    details = JSON.parse(decodedStr);
    console.log('Parsed details:', details);
} catch (e) {
    console.error('Error parsing detail_produk:', e);
    console.error('Failed string:', row.dataset.detailProduk);
    details = [];
}
```

### 3. Tambahan Error Handling (Baris 638-745)

```javascript
function openViewModal(id) {
    console.log('openViewModal called with id:', id);
    
    const row = document.querySelector(`tr[data-id="${id}"]`);
    if (!row) {
        console.error('Row not found for id:', id);
        alert('Data pesanan tidak ditemukan. ID: ' + id);
        return;
    }

    console.log('Row found, dataset:', row.dataset);

    try {
        // ... semua operasi modal ...
        
        console.log('View modal opened successfully for id:', id);
    } catch (error) {
        console.error('Error in openViewModal:', error);
        alert('Terjadi kesalahan saat membuka detail pesanan: ' + error.message);
    }
}
```

## Cara Testing

### 1. Test Modal untuk PO-00006 dan PO-00028

1. Buka halaman `/pesanan`
2. Cari pesanan PO-00006 dan PO-00028
3. Klik tombol detail (ikon mata)
4. Modal harus terbuka dengan benar
5. Periksa console browser untuk log:
   ```
   openViewModal called with id: 6
   Row found, dataset: {...}
   Raw detail_produk: [...]
   Decoded detail_produk: [...]
   Parsed details: [...]
   View modal opened successfully for id: 6
   ```

### 2. Test dengan Pesanan Lain

1. Test dengan pesanan yang memiliki:
   - `actual_periode` null
   - `choosen_expedition` null
   - Nama produk dengan karakter khusus
   - Catatan alamat dengan karakter khusus

### 3. Debugging

Jika modal masih tidak muncul:

1. Buka Developer Tools (F12)
2. Pergi ke tab Console
3. Klik tombol detail
4. Periksa log yang muncul:
   - "Row not found" → Masalah dengan selector atau ID
   - "Error parsing detail_produk" → Masalah dengan JSON
   - "Error in openViewModal" → Error lain dalam fungsi

## Penjelasan Teknis

### Mengapa Menggunakan htmlspecialchars?

```php
htmlspecialchars(json_encode($item->detail_produk), ENT_QUOTES, 'UTF-8')
```

- `json_encode()`: Mengkonversi array PHP ke string JSON
- `htmlspecialchars()`: Escape karakter HTML khusus:
  - `&` → `&amp;`
  - `"` → `&quot;`
  - `'` → `&#039;`
  - `<` → `&lt;`
  - `>` → `&gt;`
- `ENT_QUOTES`: Escape both single dan double quotes
- `UTF-8`: Encoding yang digunakan

### Mengapa Menggunakan Textarea untuk Decode?

```javascript
const textarea = document.createElement('textarea');
textarea.innerHTML = detailProdukStr;
const decodedStr = textarea.value;
```

Ini adalah cara paling aman untuk decode HTML entities di JavaScript karena:
- Browser secara otomatis decode HTML entities saat set innerHTML
- Tidak perlu library eksternal
- Aman dari XSS karena textarea tidak render HTML
- Mendukung semua HTML entities standar

## File yang Diubah

- `resources/views/pages/pesanan-admin/index.blade.php`
  - Baris 69: Perbaikan encoding data-detail-produk
  - Baris 638-745: Perbaikan fungsi openViewModal dengan error handling

## Catatan Penting

1. **Konsistensi Encoding**: Semua data attribute yang berisi JSON atau karakter khusus harus menggunakan `htmlspecialchars()`
2. **Debugging**: Console.log sangat membantu untuk debugging masalah parsing JSON
3. **Error Handling**: Selalu wrap operasi yang bisa gagal dalam try-catch
4. **User Feedback**: Berikan alert atau notifikasi jika terjadi error

## Referensi

- [PHP htmlspecialchars](https://www.php.net/manual/en/function.htmlspecialchars.php)
- [HTML Entity Decoding in JavaScript](https://stackoverflow.com/questions/1912501/unescape-html-entities-in-javascript)
- [Blade @json Directive](https://laravel.com/docs/11.x/blade#rendering-json)
