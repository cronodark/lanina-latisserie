# 📅 Kalender Pesanan - Feature Documentation

## Overview
Halaman kalender pesanan admin dengan integrasi penuh sistem slot management. Menampilkan pesanan dan slot availability dalam format kalender interaktif.

---

## ✨ Features Implemented

### 1. **Calendar Grid**
- ✅ Grid 7x6 dengan nama hari (Min-Sab)
- ✅ Navigasi bulan (prev/next)
- ✅ Highlight hari ini dengan ring gold
- ✅ Empty cells untuk hari sebelum tanggal 1

### 2. **Slot Indicators**
- ✅ **Dot indicator** di pojok kanan atas setiap tanggal:
  - 🟢 **Hijau** = Slot Aktif (sisa kuota > 0)
  - 🔴 **Merah** = Slot Penuh (sisa kuota = 0)
  - ⚫ **Abu-abu** = Slot Nonaktif (is_aktif = false)
- ✅ Background color sesuai status slot
- ✅ Hover tooltip menampilkan info slot

### 3. **Order Count Badge**
- ✅ Badge gold dengan jumlah pesanan
- ✅ Label "pesanan" di bawah badge
- ✅ Hanya muncul jika ada pesanan di tanggal tersebut

### 4. **Slot Info Display**
- ✅ Menampilkan `terisi/kuota` untuk tanggal dengan slot
- ✅ Label "slot" di bawah angka
- ✅ Warna sesuai status (hijau/merah/abu-abu)

### 5. **Statistics Cards**
- ✅ **Total Pesanan** - Jumlah pesanan bulan ini
- ✅ **Tanggal Paling Ramai** - Tanggal dengan pesanan terbanyak
- ✅ **Slot Aktif** - Jumlah slot aktif bulan ini
- ✅ **Slot Penuh** - Jumlah slot yang sudah penuh

### 6. **Interactive Modal**
Klik tanggal untuk membuka modal dengan:

#### Slot Information Section
- ✅ Status badge (Aktif/Penuh/Nonaktif)
- ✅ 3 cards: Kuota, Terisi, Sisa
- ✅ Keterangan slot (jika ada)
- ✅ Color-coded background sesuai status

#### Orders List Section
- ✅ Accordion untuk setiap pesanan
- ✅ Customer info (nama, email, telp)
- ✅ Status badge dengan warna:
  - 🟡 **unpaid** - Yellow
  - 🔵 **paid** - Blue
  - 🟣 **processing** - Purple
  - 🟠 **shipping** - Orange
  - 🟢 **completed** - Green
- ✅ Total harga pesanan
- ✅ Detail items (produk, quantity, type)
- ✅ Send type (pickUp/kurirEkspedisi/kurirToko)

### 7. **Legend**
- ✅ Visual legend di atas kalender:
  - Slot Aktif (hijau)
  - Slot Penuh (merah)
  - Nonaktif (abu-abu)
  - Hari Ini (ring gold)

### 8. **Responsive Design**
- ✅ Grid responsif untuk mobile/tablet/desktop
- ✅ Modal full-screen di mobile
- ✅ Stats cards stack di mobile

---

## 🎨 Design System

### Colors
- **Primary Gold**: `#BB9457` - Badges, highlights, today ring
- **Green**: Slot aktif, sisa kuota > 0
- **Red**: Slot penuh, sisa kuota = 0
- **Gray**: Slot nonaktif, empty cells
- **Status Colors**: Yellow (unpaid), Blue (paid), Purple (processing), Orange (shipping), Green (completed)

### Typography
- **Headers**: Playfair Display (elegant serif)
- **Body**: Glacial Indifference / System fonts
- **Sizes**: 
  - Stats: 3xl (bold)
  - Day numbers: sm (semibold)
  - Badges: 10px (bold)
  - Modal: xl header, sm body

### Spacing
- Card padding: 5 (20px)
- Grid gap: 2 (8px)
- Modal padding: 6 (24px)

---

## 🔧 Technical Implementation

### Controller (`TanggalController@kalender`)

```php
// Get orders for the month
$semuaPesanan = PreOrder::with(['customer', 'detailPreOrders.product', 'detailPreOrders.promo'])
    ->whereYear('actual_periode', $tahun)
    ->whereMonth('actual_periode', $bulan)
    ->whereIn('status', ['unpaid', 'paid', 'processing', 'shipping', 'completed'])
    ->get();

// Get slot data for the month
$tanggalTersedia = TanggalTersedia::whereYear('tanggal', $tahun)
    ->whereMonth('tanggal', $bulan)
    ->get()
    ->keyBy(function ($item) {
        return $item->tanggal->format('Y-m-d');
    });

// Group orders by date
$pesananPerTanggal = $semuaPesanan->groupBy('tanggal');

// Calculate stats
$totalPesanan = $semuaPesanan->count();
$slotTersedia = $tanggalTersedia->where('is_aktif', true)->count();
$slotPenuh = $tanggalTersedia->where('status', 'Penuh')->count();
```

### View (`kalender.blade.php`)

**Alpine.js State:**
```javascript
{
    modal: false,
    selectedDate: '',
    selectedOrders: [],
    slotInfo: null,
    openDay(date, orders, slot) {
        this.selectedDate = date;
        this.selectedOrders = orders || [];
        this.slotInfo = slot || null;
        this.modal = true;
    }
}
```

**Cell Rendering Logic:**
1. Check if date has slot (`$tanggalTersedia[$dateKey]`)
2. Check if date has orders (`$pesananPerTanggal[$dateKey]`)
3. Determine background color based on slot status
4. Add indicator dot if slot exists
5. Show order count badge if orders exist
6. Show slot info if slot exists but no orders
7. Make cell clickable if slot or orders exist

---

## 📊 Data Flow

```
Controller
    ↓
Query PreOrders + TanggalTersedia
    ↓
Group by date
    ↓
Calculate stats
    ↓
Pass to view
    ↓
Render calendar grid
    ↓
User clicks date
    ↓
Alpine.js opens modal
    ↓
Display slot info + orders
```

---

## 🚀 Usage

### Admin Access
1. Login sebagai admin
2. Navigate ke **Jadwal > Kalender**
3. View current month calendar
4. Use prev/next buttons untuk navigasi bulan
5. Click tanggal untuk detail

### Reading Calendar
- **Green cells** = Slot tersedia, bisa terima pesanan
- **Red cells** = Slot penuh, tidak bisa terima pesanan lagi
- **Gray cells** = Slot nonaktif
- **Badge angka** = Jumlah pesanan di tanggal tersebut
- **Ring gold** = Hari ini

### Modal Details
- **Slot section** = Info kuota, terisi, sisa
- **Orders section** = List semua pesanan dengan detail lengkap
- Click accordion untuk expand detail pesanan

---

## 🔄 Integration Points

### With Slot Management
- Real-time slot status dari `TanggalTersedia` model
- Accessor `terisi`, `sisa_kuota`, `status` digunakan
- Slot locking system terintegrasi

### With Order Management
- Orders dari `PreOrder` model
- Include semua status (unpaid sampai completed)
- Detail items dari `PreOrderDetail` relation

### With Customer Data
- Customer info dari `User` relation
- Email, telp, nama ditampilkan di modal

---

## 📝 Future Enhancements

### Potential Improvements
1. **Export Calendar** - Export ke PDF/Excel
2. **Print View** - Optimized print layout
3. **Quick Actions** - Edit order status dari modal
4. **Filters** - Filter by status, send_type
5. **Search** - Search customer name/email
6. **Drag & Drop** - Reschedule orders (change actual_periode)
7. **Notifications** - Alert jika slot hampir penuh
8. **Analytics** - Revenue per date, popular dates

### Performance Optimization
- Cache slot data untuk bulan yang sama
- Lazy load modal content
- Pagination untuk orders jika > 50

---

## 🐛 Known Limitations

1. **No past month data** - Hanya menampilkan data real, tidak ada dummy data
2. **Single month view** - Tidak ada week/year view
3. **No inline editing** - Harus ke halaman pesanan untuk edit
4. **No bulk actions** - Tidak bisa select multiple orders

---

## ✅ Testing Checklist

- [x] Calendar renders correctly
- [x] Month navigation works
- [x] Slot indicators show correct colors
- [x] Order count badges display
- [x] Modal opens on click
- [x] Slot info displays correctly
- [x] Orders list with accordion
- [x] Status badges color-coded
- [x] Today highlight works
- [x] Empty state shows when no data
- [x] Responsive on mobile
- [x] Hover tooltips work

---

## 📚 Related Files

- `app/Http/Controllers/TanggalController.php` - Controller logic
- `resources/views/pages/jadwal-admin/kalender.blade.php` - View template
- `app/Models/TanggalTersedia.php` - Slot model with accessors
- `app/Models/PreOrder.php` - Order model
- `routes/web.php` - Route definition (line ~108)

---

## 🎯 Summary

Kalender pesanan sekarang fully functional dengan:
- ✅ Visual slot indicators
- ✅ Order count badges
- ✅ Interactive modal dengan detail lengkap
- ✅ Real-time slot status
- ✅ Color-coded status system
- ✅ Responsive design
- ✅ Smooth animations & transitions

Admin dapat dengan mudah melihat availability slot dan pesanan dalam satu tampilan kalender yang intuitif.
