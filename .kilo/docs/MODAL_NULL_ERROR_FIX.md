# Perbaikan Error "Cannot read properties of null (reading 'classList')"

## Error yang Terjadi

```
Terjadi kesalahan saat membuka detail pesanan: Cannot read properties of null (reading 'classList')
```

## Penyebab Masalah

JavaScript mencoba mengakses `classList` dari elemen yang tidak ditemukan (null). Ini terjadi karena:

1. **ID Element Tidak Ada di HTML**
   - JavaScript mencari `viewCurrentExpeditionDisplay` - tidak ada di HTML
   - JavaScript mencari `viewCurrentExpeditionLabel` - tidak ada di HTML

2. **Tidak Ada Null Check**
   - Kode langsung mengakses `classList` tanpa memeriksa apakah elemen ada
   - Jika `getElementById()` return null, akan terjadi error

## Perbaikan yang Dilakukan

### 1. Perbaikan openViewModal - Edit Status Section (Baris 721-758)

**SEBELUM:**
```javascript
if (currentStatus === 'processing') {
    editStatusSection.classList.remove('hidden');
    document.getElementById('viewModalPesananId').value = id;
    if (sendType === 'kurirEkspedisi') {
        document.getElementById('viewCurrentExpeditionDisplay').classList.remove('hidden');
        document.getElementById('viewCurrentExpeditionLabel').textContent = row.dataset.choosenExpedition || '-';
        document.getElementById('viewResiInputContainer').classList.remove('hidden');
        document.getElementById('viewNomorResi').value = row.dataset.nomorResi || '';
    } else {
        document.getElementById('viewCurrentExpeditionDisplay').classList.add('hidden');
        document.getElementById('viewResiInputContainer').classList.add('hidden');
        document.getElementById('viewNomorResi').value = '';
    }
} else {
    editStatusSection.classList.add('hidden');
}
```

**SESUDAH:**
```javascript
if (editStatusSection) {
    if (currentStatus === 'processing') {
        editStatusSection.classList.remove('hidden');
        const viewModalPesananId = document.getElementById('viewModalPesananId');
        if (viewModalPesananId) {
            viewModalPesananId.value = id;
        }
        
        if (sendType === 'kurirEkspedisi') {
            const viewExpeditionDropdown = document.getElementById('viewExpeditionDropdownContainer');
            const viewResiInput = document.getElementById('viewResiInputContainer');
            const viewNomorResi = document.getElementById('viewNomorResi');
            const viewChoosenExpedition = document.getElementById('viewChoosenExpedition');
            
            if (viewExpeditionDropdown) viewExpeditionDropdown.classList.remove('hidden');
            if (viewResiInput) viewResiInput.classList.remove('hidden');
            if (viewNomorResi) viewNomorResi.value = row.dataset.nomorResi || '';
            if (viewChoosenExpedition) viewChoosenExpedition.value = row.dataset.choosenExpedition || '';
        } else {
            const viewExpeditionDropdown = document.getElementById('viewExpeditionDropdownContainer');
            const viewResiInput = document.getElementById('viewResiInputContainer');
            const viewNomorResi = document.getElementById('viewNomorResi');
            
            if (viewExpeditionDropdown) viewExpeditionDropdown.classList.add('hidden');
            if (viewResiInput) viewResiInput.classList.add('hidden');
            if (viewNomorResi) viewNomorResi.value = '';
        }
    } else {
        editStatusSection.classList.add('hidden');
    }
}
```

**Perubahan:**
- Tambahkan null check untuk `editStatusSection`
- Gunakan ID yang benar: `viewExpeditionDropdownContainer` bukan `viewCurrentExpeditionDisplay`
- Gunakan ID yang benar: `viewChoosenExpedition` untuk select ekspedisi
- Tambahkan null check untuk setiap elemen sebelum mengakses properties

### 2. Perbaikan closeViewModal (Baris 843-861)

**SEBELUM:**
```javascript
function closeViewModal() {
    const modal = document.getElementById('viewModal');
    modal.style.display = 'none';
    document.getElementById('viewNomorResi').value = '';
    document.getElementById('viewResiInputContainer').classList.add('hidden');
    document.getElementById('editStatusSection').classList.add('hidden');
    document.getElementById('viewCurrentExpeditionDisplay').classList.add('hidden');
    document.getElementById('viewCurrentSendTypeLabel').textContent = '-';
    document.getElementById('viewCurrentExpeditionLabel').textContent = '-';
}
```

**SESUDAH:**
```javascript
function closeViewModal() {
    const modal = document.getElementById('viewModal');
    if (modal) modal.style.display = 'none';
    
    const viewNomorResi = document.getElementById('viewNomorResi');
    if (viewNomorResi) viewNomorResi.value = '';
    
    const viewResiInputContainer = document.getElementById('viewResiInputContainer');
    if (viewResiInputContainer) viewResiInputContainer.classList.add('hidden');
    
    const editStatusSection = document.getElementById('editStatusSection');
    if (editStatusSection) editStatusSection.classList.add('hidden');
    
    const viewExpeditionDropdown = document.getElementById('viewExpeditionDropdownContainer');
    if (viewExpeditionDropdown) viewExpeditionDropdown.classList.add('hidden');
    
    const viewCurrentSendTypeLabel = document.getElementById('viewCurrentSendTypeLabel');
    if (viewCurrentSendTypeLabel) viewCurrentSendTypeLabel.textContent = '-';
}
```

**Perubahan:**
- Tambahkan null check untuk setiap elemen
- Hapus referensi ke elemen yang tidak ada (`viewCurrentExpeditionDisplay`, `viewCurrentExpeditionLabel`)
- Gunakan ID yang benar: `viewExpeditionDropdownContainer`

### 3. Perbaikan applyStatusFromView (Baris 773-806)

**SEBELUM:**
```javascript
function applyStatusFromView() {
    const status = 'shipping';
    const id = document.getElementById('viewModalPesananId').value;
    const nomorResi = document.getElementById('viewNomorResi').value.trim();
    const row = document.querySelector(`tr[data-id="${id}"]`);
    const sendType = row?.dataset.sendType || '';

    if (sendType === 'kurirEkspedisi' && !nomorResi) {
        alert('Nomor resi wajib diisi untuk kurir ekspedisi!');
        document.getElementById('viewNomorResi').focus();
        return;
    }

    const payload = {
        status: status,
        nomor_resi: (sendType === 'kurirEkspedisi' && nomorResi) ? nomorResi : undefined
    };
    // ...
}
```

**SESUDAH:**
```javascript
function applyStatusFromView() {
    const status = 'shipping';
    const id = document.getElementById('viewModalPesananId')?.value;
    if (!id) {
        alert('ID pesanan tidak ditemukan');
        return;
    }
    
    const nomorResiInput = document.getElementById('viewNomorResi');
    const nomorResi = nomorResiInput ? nomorResiInput.value.trim() : '';
    
    const choosenExpeditionSelect = document.getElementById('viewChoosenExpedition');
    const choosenExpedition = choosenExpeditionSelect ? choosenExpeditionSelect.value : '';
    
    const row = document.querySelector(`tr[data-id="${id}"]`);
    const sendType = row?.dataset.sendType || '';

    if (sendType === 'kurirEkspedisi') {
        if (!nomorResi) {
            alert('Nomor resi wajib diisi untuk kurir ekspedisi!');
            if (nomorResiInput) nomorResiInput.focus();
            return;
        }
        if (!choosenExpedition) {
            alert('Pilih ekspedisi terlebih dahulu!');
            if (choosenExpeditionSelect) choosenExpeditionSelect.focus();
            return;
        }
    }

    const payload = {
        status: status,
        nomor_resi: (sendType === 'kurirEkspedisi' && nomorResi) ? nomorResi : undefined,
        choosen_expedition: (sendType === 'kurirEkspedisi' && choosenExpedition) ? choosenExpedition : undefined
    };
    // ...
}
```

**Perubahan:**
- Tambahkan null check untuk `viewModalPesananId`
- Tambahkan null check untuk `viewNomorResi` dan `viewChoosenExpedition`
- Tambahkan validasi ekspedisi untuk kurir ekspedisi
- Tambahkan `choosen_expedition` ke payload

## Mapping ID Element yang Benar

| Fungsi JavaScript | ID yang Dicari | ID yang Benar di HTML |
|-------------------|----------------|----------------------|
| openViewModal | `viewCurrentExpeditionDisplay` | `viewExpeditionDropdownContainer` |
| openViewModal | `viewCurrentExpeditionLabel` | Tidak ada (tidak diperlukan) |
| openViewModal | - | `viewChoosenExpedition` (select ekspedisi) |
| closeViewModal | `viewCurrentExpeditionDisplay` | `viewExpeditionDropdownContainer` |
| closeViewModal | `viewCurrentExpeditionLabel` | Tidak ada (tidak diperlukan) |

## Best Practice: Null Check Pattern

### Pattern 1: Simple Check
```javascript
const element = document.getElementById('someId');
if (element) {
    element.classList.add('hidden');
}
```

### Pattern 2: Optional Chaining
```javascript
const value = document.getElementById('someId')?.value;
```

### Pattern 3: Store and Check
```javascript
const element = document.getElementById('someId');
if (element) {
    element.value = 'some value';
    element.classList.remove('hidden');
}
```

## Testing

1. Buka halaman `/pesanan`
2. Klik tombol detail pada pesanan manapun (termasuk PO-00006 dan PO-00028)
3. Modal harus terbuka tanpa error
4. Periksa console browser - tidak boleh ada error
5. Test dengan pesanan yang memiliki status "processing":
   - Section edit status harus muncul
   - Jika send_type = kurirEkspedisi, dropdown ekspedisi dan input resi harus muncul
6. Test close modal - harus bisa ditutup tanpa error

## File yang Diubah

- `resources/views/pages/pesanan-admin/index.blade.php`
  - Baris 721-758: Perbaikan openViewModal edit status section
  - Baris 843-861: Perbaikan closeViewModal
  - Baris 773-806: Perbaikan applyStatusFromView

## Catatan Penting

1. **Selalu Gunakan Null Check**: Sebelum mengakses properties dari hasil `getElementById()`, selalu cek apakah hasilnya null
2. **Gunakan Optional Chaining**: Untuk akses sederhana, gunakan `?.` operator
3. **Konsistensi ID**: Pastikan ID di HTML dan JavaScript sama persis (case-sensitive)
4. **Debugging**: Gunakan console.log untuk memeriksa apakah elemen ditemukan
5. **Error Handling**: Wrap operasi yang bisa gagal dalam try-catch

## Referensi

- [MDN: Optional Chaining](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Operators/Optional_chaining)
- [MDN: getElementById](https://developer.mozilla.org/en-US/docs/Web/API/Document/getElementById)
- [JavaScript Null Check Best Practices](https://stackoverflow.com/questions/2647867/how-can-i-determine-if-a-variable-is-undefined-or-null)
