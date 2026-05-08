# 📋 Alur Pemesanan Pre-Order (PO) - Lanina Patisserie

## 🎯 Overview

Sistem pre-order Lanina Patisserie menggunakan **session-based cart** dengan integrasi **Midtrans Payment Gateway**. Customer dapat memesan produk atau promo bundle untuk pickup/delivery di tanggal yang dipilih.

---

## 🔄 Flow Diagram

```
┌─────────────┐
│   Browse    │ → Customer lihat produk/promo
│  Products   │
└──────┬──────┘
       │
       ▼
┌─────────────┐
│  Add to     │ → Klik "Add to Cart" → Session cart
│   Cart      │
└──────┬──────┘
       │
       ▼
┌─────────────┐
│  View Cart  │ → Livewire component, pilih items
│  & Select   │
└──────┬──────┘
       │
       ▼
┌─────────────┐
│  Checkout   │ → Pilih alamat, tanggal, metode kirim
│   Page      │
└──────┬──────┘
       │
       ▼
┌─────────────┐
│   Create    │ → Insert PreOrder + PreOrderDetail
│  PreOrder   │
└──────┬──────┘
       │
       ▼
┌─────────────┐
│  Midtrans   │ → Generate payment link
│  Payment    │
└──────┬──────┘
       │
       ▼
┌─────────────┐
│   Payment   │ → Customer bayar di Midtrans
│  Gateway    │
└──────┬──────┘
       │
       ▼
┌─────────────┐
│  Webhook    │ → Midtrans notify status
│  Callback   │
└──────┬──────┘
       │
       ▼
┌─────────────┐
│   Update    │ → Status: unpaid → processing
│   Status    │
└──────┬──────┘
       │
       ▼
┌─────────────┐
│   Admin     │ → Lihat pesanan di kalender/pesanan
│  Process    │
└─────────────┘
```

---

## 📝 Step-by-Step Detail

### **Step 1: Browse Products**

**Route**: `/product` atau `/`

**Controller**: `ProductController@index`, `LandingPageController@index`

**Action**:
- Customer browse produk atau promo
- Lihat detail produk (nama, harga, deskripsi, gambar)

---

### **Step 2: Add to Cart**

**Route**: 
- `POST /cart/{product}` → `CartController@storeProduct`
- `POST /cart/promo/{promo}` → `CartController@storePromo`

**Process**:
```php
// CartController.php
1. Validate qty (1-99)
2. Get session cart: 'cart_user_{user_id}'
3. Add/update item:
   - Key: 'product:123' atau 'promo:456'
   - Data: ['type', 'item_id', 'qty', 'checked' => false]
4. Save to session
5. Redirect back dengan success message
```

**Session Structure**:
```php
'cart_user_1' => [
    'product:5' => [
        'type' => 'product',
        'item_id' => 5,
        'qty' => 2,
        'checked' => false
    ],
    'promo:3' => [
        'type' => 'promo',
        'item_id' => 3,
        'qty' => 1,
        'checked' => false
    ]
]
```

---

### **Step 3: View Cart & Select Items**

**Route**: `GET /cart` → View `pages.cart.index`

**Component**: `Livewire\Cart`

**Features**:
- ✅ List semua items di cart
- ✅ Checkbox untuk select items
- ✅ Increment/decrement quantity
- ✅ Remove item
- ✅ Toggle all items
- ✅ Calculate total (hanya yang checked)
- ✅ Button "Checkout" (disabled jika tidak ada yang checked)

**Livewire Methods**:
```php
- mount() → Load cart dari session
- increment($cartKey) → Tambah qty
- decrement($cartKey) → Kurang qty (min 1)
- removeItem($cartKey) → Hapus item
- toggleItem($cartKey) → Check/uncheck
- toggleAll($value) → Check/uncheck semua
- checkoutSelected() → Redirect ke checkout
```

**Computed Properties**:
```php
- $cartItems → Array items dengan detail (name, price, image, dll)
- $total → Total harga items yang checked
- $allChecked → Boolean, semua item checked?
```

---

### **Step 4: Checkout Page**

**Route**: `GET /checkout` → `CheckoutController@index`

**Process**:
```php
1. Get checked cart items dari session
2. Validate items masih tersedia (query DB)
3. Build checkout items dengan detail lengkap
4. Get user addresses
5. Return view dengan data
```

**View**: `pages.checkout.index`

**Form Fields**:
- ✅ **Alamat**: Pilih dari address list (modal)
- ✅ **Tanggal Pengambilan/Pengiriman**: Pilih dari modal kalender (actual_periode) - **REQUIRED**
- ✅ **Metode Pengiriman**: 
  - `pickUp` → Ambil sendiri
  - `kurirEkspedisi` → Kurir ekspedisi
  - `kurirToko` → Kurir toko
- ✅ **Items Summary**: List produk yang dibeli
- ✅ **Total**: Grand total

**Slot Selection**:
- Modal menampilkan tanggal tersedia dari API `/api/tanggal-tersedia`
- Hanya tanggal dengan `is_aktif = true` dan `sisa_kuota > 0`
- Menampilkan status: Aktif / Penuh / Nonaktif
- Menampilkan sisa kuota: `sisa/kuota`

**Validation**:
```php
'address_id' => 'nullable|integer|exists:addresses,id',
'send_type' => 'required|in:pickUp,kurirEkspedisi,kurirToko',
'actual_periode' => 'required|date|after_or_equal:today'
```

---

### **Step 5: Create PreOrder**

**Route**: `POST /checkout` → `CheckoutController@store`

**Process**:
```php
try {
    DB::transaction(function() {
        // 1. Lock slot untuk mencegah race condition
        $tanggalTersedia = TanggalTersedia::where('tanggal', $validated['actual_periode'])
            ->lockForUpdate()
            ->first();
        
        // 2. Validate slot availability
        if (!$tanggalTersedia) {
            throw new Exception('Tanggal tidak tersedia');
        }
        if (!$tanggalTersedia->is_aktif) {
            throw new Exception('Tanggal tidak aktif');
        }
        if ($tanggalTersedia->sisa_kuota <= 0) {
            throw new Exception('Slot penuh');
        }
        
        // 3. Create PreOrder (status: unpaid → lock slot immediately)
        $preOrder = PreOrder::create([
            'actual_periode' => $validated['actual_periode'],
            'status' => 'unpaid',
            'start_periode' => now(),
            'end_periode' => null,
            'total' => $total,
            'send_type' => $validated['send_type'],
            'address_id' => $validated['address_id'],
            'user_id' => Auth::id(),
            'tracking_number' => null,
            'choosen_expedition' => null,
        ]);

        // 4. Create PreOrderDetail (foreach item)
        foreach ($items as $item) {
            PreOrderDetail::create([
                'quantity' => $item['qty'],
                'type' => $item['type'], // 'product' atau 'promo'
                'product_id' => $item['type'] === 'product' ? $item['id'] : null,
                'promo_id' => $item['type'] === 'promo' ? $item['id'] : null,
                'pre_order_id' => $preOrder->id,
            ]);
        }
    });
} catch (Exception $e) {
    return redirect()->back()->with('error', $e->getMessage());
}
```

**🔒 Slot Locking Mechanism**:
- Slot langsung ter-lock saat order dibuat (status `unpaid`)
- Menggunakan `lockForUpdate()` untuk mencegah race condition
- Accessor `terisi` menghitung order dengan status: `unpaid`, `paid`, `processing`, `shipping`, `completed`
- Mencegah overbooking meskipun customer belum bayar

**Database Tables**:

**pre_orders**:
```sql
- id
- user_id (FK to users)
- address_id (FK to addresses, nullable)
- actual_periode (date) → Tanggal pickup/delivery (FK to tanggal_tersedia.tanggal)
- start_periode (date)
- end_periode (date, nullable)
- status (enum: unpaid, paid, processing, shipping, completed)
- send_type (enum: pickUp, kurirEkspedisi, kurirToko)
- tracking_number (nullable)
- choosen_expedition (nullable)
- total (integer)
- payment_method (string, nullable)
- midtrans_order_id (string, nullable)
- midtrans_transaction_id (string, nullable)
- payment_redirect_url (text, nullable)
- paid_at (datetime, nullable)
- created_at, updated_at
```

**pre_order_details**:
```sql
- id
- pre_order_id (FK to pre_orders)
- product_id (FK to products, nullable)
- promo_id (FK to promos, nullable)
- type (enum: product, promo)
- quantity (integer)
- created_at, updated_at
```

---

### **Step 6: Midtrans Payment**

**Process**:
```php
// CheckoutController@createMidtransPayment()
1. Configure Midtrans (server key, client key, env)
2. Build transaction details:
   - order_id: 'ORDER-{preorder_id}-{timestamp}'
   - gross_amount: $preOrder->total
3. Build item details (foreach product/promo)
4. Build customer details (name, email, phone, address)
5. Call Midtrans\Snap::createTransaction()
6. Get redirect_url (payment page)
7. Update PreOrder:
   - payment_method = 'midtrans'
   - midtrans_order_id
   - midtrans_transaction_id
   - payment_redirect_url
8. Redirect customer ke Midtrans payment page
```

**Midtrans Config**:
```php
Config::$serverKey = env('MIDTRANS_SERVER_KEY');
Config::$clientKey = env('MIDTRANS_CLIENT_KEY');
Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
Config::$isSanitized = true;
Config::$is3ds = true;
```

**Transaction Params**:
```php
[
    'transaction_details' => [
        'order_id' => 'ORDER-123-1234567890',
        'gross_amount' => 150000,
    ],
    'item_details' => [
        [
            'id' => 'PROD-5',
            'price' => 75000,
            'quantity' => 2,
            'name' => 'Nastar Premium',
        ]
    ],
    'customer_details' => [
        'first_name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '08123456789',
        'billing_address' => [...]
    ],
    'enabled_payments' => ['gopay', 'bank_transfer', 'credit_card'],
]
```

---

### **Step 7: Payment Gateway**

**Flow**:
1. Customer redirect ke Midtrans payment page
2. Customer pilih metode pembayaran (GoPay, Transfer Bank, CC, dll)
3. Customer selesaikan pembayaran
4. Midtrans process payment
5. Customer redirect kembali ke finish URL

**Finish URL**: `GET /checkout/payment/{preOrder}/finish`

**Process**:
```php
// CheckoutController@paymentFinish()
1. Verify preOrder belongs to current user
2. Sync payment status dari Midtrans API
3. Update PreOrder status berdasarkan Midtrans response
4. Clear checked items dari cart
5. Redirect ke profile preorder dengan message
```

---

### **Step 8: Webhook Callback**

**Route**: `POST /midtrans/notification` → `MidtransWebhookController@handle`

**Process**:
```php
1. Receive notification dari Midtrans
2. Verify signature (security)
3. Get order_id dari notification
4. Find PreOrder by midtrans_order_id
5. Get transaction status dari Midtrans API
6. Update PreOrder status:
   - settlement → 'processing' (paid)
   - pending → 'unpaid'
   - expire → 'unpaid'
   - cancel → 'unpaid'
   - deny → 'unpaid'
7. Set paid_at timestamp jika settlement
8. Return 200 OK
```

**Status Mapping**:
```php
Midtrans Status → PreOrder Status
- settlement → processing (paid, ready to process)
- pending → unpaid (waiting payment)
- expire → unpaid (payment expired)
- cancel → unpaid (payment cancelled)
- deny → unpaid (payment denied)
```

---

### **Step 9: Admin Process Order**

**Admin Views**:

**A. Kalender Pesanan** (`/admin/jadwal/kalender`)
- Lihat pesanan per tanggal (actual_periode)
- Visual calendar dengan marker pesanan
- Click tanggal → Modal detail pesanan
- Untuk planning produksi

**B. Pesanan Admin** (`/pesanan`)
- List semua pesanan dengan filter status
- Edit pesanan (update status, tracking number)
- Update status workflow:
  - unpaid → (customer bayar) → processing
  - processing → (admin kerjakan) → shipping
  - shipping → (customer terima) → completed

**Status Workflow**:
```
unpaid → processing → shipping → completed
   ↓
(expired/cancelled)
```

---

## 🔑 Key Features

### **1. Session-Based Cart**
- ✅ Tidak perlu login untuk browse
- ✅ Cart persist di session
- ✅ Support multiple items (product + promo)
- ✅ Checkbox selection untuk checkout

### **2. Flexible Delivery**
- ✅ Pickup (ambil sendiri)
- ✅ Kurir Ekspedisi (JNE, dll)
- ✅ Kurir Toko (delivery sendiri)

### **3. Date Selection**
- ✅ Customer pilih tanggal pickup/delivery
- ✅ Default: now + 2 days
- ✅ Tersimpan di `actual_periode`

### **4. Payment Integration**
- ✅ Midtrans Snap (multiple payment methods)
- ✅ Webhook untuk auto-update status
- ✅ Payment verification

### **5. Order Tracking**
- ✅ Customer lihat status di profile
- ✅ Admin update status di pesanan admin
- ✅ Tracking number untuk shipping

---

## 🚨 Current Limitations

### **1. Tanggal Tersedia (Slot) Tidak Terintegrasi**
❌ **Problem**: 
- Customer bisa pilih tanggal apapun
- Tidak ada validasi kuota per tanggal
- Tidak ada check slot tersedia

✅ **Solution Needed**:
```php
// Di CheckoutController@store, tambahkan:
$tanggal = TanggalTersedia::where('tanggal', $validated['actual_periode'])
    ->where('is_aktif', true)
    ->first();

if (!$tanggal || $tanggal->sisa_kuota <= 0) {
    return back()->with('error', 'Tanggal tidak tersedia atau penuh');
}
```

### **2. Tidak Ada Date Picker dengan Slot Info**
❌ **Problem**:
- Customer tidak tahu tanggal mana yang tersedia
- Tidak ada visual indicator slot penuh/kosong

✅ **Solution Needed**:
- Buat API endpoint untuk get available dates
- Implement date picker dengan disable dates yang penuh
- Show kuota tersisa per tanggal

### **3. Production Estimate Tidak Digunakan**
❌ **Problem**:
- Field `production_estimate` di products tidak dipakai
- Tidak ada validasi minimum lead time

✅ **Solution Needed**:
```php
// Validate tanggal harus >= now + max(production_estimate)
$maxEstimate = $items->max('production_estimate') ?? 2;
$minDate = now()->addDays($maxEstimate);

if ($validated['actual_periode'] < $minDate) {
    return back()->with('error', "Minimal {$maxEstimate} hari untuk produksi");
}
```

### **4. Expired Day Tidak Divalidasi**
❌ **Problem**:
- Field `expired_day` di products tidak dipakai
- Tidak ada warning untuk produk yang cepat expired

---

## 🔧 Recommended Improvements

### **Priority 1: Integrate Slot System**

**File**: `CheckoutController.php`

```php
public function index(): View|RedirectResponse
{
    // ... existing code ...
    
    // Get available dates (next 30 days)
    $availableDates = TanggalTersedia::aktif()
        ->mendatang()
        ->where('tanggal', '>=', now()->addDays(2))
        ->where('tanggal', '<=', now()->addDays(30))
        ->get()
        ->map(function($slot) {
            return [
                'tanggal' => $slot->tanggal->format('Y-m-d'),
                'kuota' => $slot->kuota,
                'terisi' => $slot->terisi,
                'sisa' => $slot->sisa_kuota,
                'status' => $slot->status,
            ];
        });
    
    return view('pages.checkout.index', [
        'items' => $items,
        'addresses' => $addresses,
        'grandTotal' => $total,
        'availableDates' => $availableDates, // ← Add this
    ]);
}

public function store(Request $request): RedirectResponse
{
    // ... existing validation ...
    
    // Validate slot availability
    $tanggal = TanggalTersedia::where('tanggal', $validated['actual_periode'])
        ->where('is_aktif', true)
        ->first();
    
    if (!$tanggal) {
        return back()->with('error', 'Tanggal tidak tersedia untuk pre-order.');
    }
    
    if ($tanggal->sisa_kuota <= 0) {
        return back()->with('error', 'Slot untuk tanggal ini sudah penuh.');
    }
    
    // ... continue with create PreOrder ...
}
```

### **Priority 2: Add Date Picker with Slot Info**

**File**: `resources/views/pages/checkout/index.blade.php`

```javascript
// Add Alpine.js component
<div x-data="datePickerWithSlots()">
    <input type="date" 
           x-model="selectedDate"
           :min="minDate"
           :max="maxDate"
           @change="checkSlot()">
    
    <div x-show="slotInfo" class="mt-2">
        <p x-text="slotMessage"></p>
        <div class="flex gap-2">
            <span>Kuota: <span x-text="slotInfo?.kuota"></span></span>
            <span>Terisi: <span x-text="slotInfo?.terisi"></span></span>
            <span>Sisa: <span x-text="slotInfo?.sisa"></span></span>
        </div>
    </div>
</div>

<script>
function datePickerWithSlots() {
    return {
        selectedDate: '',
        slotInfo: null,
        availableDates: @json($availableDates),
        
        checkSlot() {
            this.slotInfo = this.availableDates.find(
                d => d.tanggal === this.selectedDate
            );
        }
    }
}
</script>
```

### **Priority 3: Add Production Lead Time Validation**

```php
// In CheckoutController@store
$maxProductionDays = collect($items)->map(function($item) {
    if ($item['type'] === 'product') {
        $product = Product::find($item['id']);
        return $product->production_estimate ?? 2;
    }
    return 2;
})->max();

$minAllowedDate = now()->addDays($maxProductionDays)->startOfDay();
$selectedDate = Carbon::parse($validated['actual_periode'])->startOfDay();

if ($selectedDate->lt($minAllowedDate)) {
    return back()->with('error', 
        "Pesanan memerlukan minimal {$maxProductionDays} hari untuk produksi. " .
        "Pilih tanggal mulai " . $minAllowedDate->format('d M Y')
    );
}
```

---

## 📊 Database Relationships

```
users (1) ──────────── (N) pre_orders
                            │
                            ├── (N) pre_order_details
                            │        ├── (1) products
                            │        └── (1) promos
                            │
                            └── (1) addresses

tanggal_tersedia (1) ──── (N) pre_orders (via actual_periode)
```

---

## 🗓️ Slot Management System

### **Table: tanggal_tersedia**

```sql
CREATE TABLE tanggal_tersedia (
    id BIGINT UNSIGNED PRIMARY KEY,
    tanggal DATE NOT NULL UNIQUE,
    kuota INT UNSIGNED NOT NULL,
    keterangan VARCHAR(255) NULLABLE,
    is_aktif BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### **Model Accessors**

```php
// TanggalTersedia.php

// Jumlah slot terisi (include unpaid untuk lock slot)
public function getTerisiAttribute(): int
{
    return $this->preOrders()
        ->whereIn('status', ['unpaid', 'paid', 'processing', 'shipping', 'completed'])
        ->count();
}

// Sisa kuota yang bisa dipesan
public function getSisaKuotaAttribute(): int
{
    return max(0, $this->kuota - $this->terisi);
}

// Status dinamis
public function getStatusAttribute(): string
{
    if (!$this->is_aktif) return 'Nonaktif';
    if ($this->sisa_kuota <= 0) return 'Penuh';
    return 'Aktif';
}
```

### **API Endpoint**

**GET** `/api/tanggal-tersedia`

**Response**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "tanggal": "2026-05-09",
      "tanggal_display": "09 May 2026",
      "kuota": 10,
      "terisi": 2,
      "sisa": 8,
      "status": "Aktif",
      "is_available": true,
      "keterangan": "Slot normal - Hari kerja"
    }
  ]
}
```

### **Race Condition Prevention**

```php
// CheckoutController@store
DB::transaction(function() {
    // Lock row untuk mencegah concurrent access
    $tanggalTersedia = TanggalTersedia::where('tanggal', $date)
        ->lockForUpdate()
        ->first();
    
    // Validate availability
    if ($tanggalTersedia->sisa_kuota <= 0) {
        throw new Exception('Slot penuh');
    }
    
    // Create order → slot langsung ter-lock
    PreOrder::create([...]);
});
```

**Key Points**:
- ✅ Slot ter-lock sejak order dibuat (status `unpaid`)
- ✅ `lockForUpdate()` mencegah race condition
- ✅ Tidak ada overbooking meskipun customer belum bayar
- ✅ Accessor `terisi` real-time menghitung dari relasi

---

## 🎯 Summary

**Current Flow**: ✅ Fully Working
- Browse → Cart → Checkout (with slot selection) → Payment → Webhook → Admin Process

**Slot System**: ✅ Integrated
- ✅ Tanggal tersedia divalidasi saat checkout
- ✅ Customer tidak bisa pilih tanggal yang penuh/nonaktif
- ✅ Visual feedback slot availability di modal
- ✅ Race condition prevention dengan database locking
- ✅ Slot ter-lock sejak order dibuat (unpaid status)

**Next Steps**:
1. Integrate slot validation di checkout
2. Add date picker dengan slot info
3. Validate production lead time
4. Show slot status di checkout page

---

**Last Updated**: 2026-05-08  
**Version**: 1.0
