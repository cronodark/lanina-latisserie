# Sistem Rekomendasi Promosi - Association Rules Mining

## Overview

Sistem ini menggunakan **Association Rules Mining** untuk menemukan pola pembelian produk yang sering dibeli bersama, kemudian merekomendasikan kombinasi produk untuk bundle promosi.

**Metode**: 2-Itemset Association Rules

## Metode: 2-Itemset Association Rules

### Konsep Dasar

- Analisis transaksi historis dari PreOrder
- Menemukan pasangan produk yang sering dibeli bersama
- Menghitung metrik statistik untuk ranking kombinasi

### Metrik yang Digunakan

#### 1. Support
Proporsi transaksi yang mengandung pasangan produk.

**Formula**: `support(A,B) = count(A ∩ B) / total_transactions`

**Contoh**: Jika 15 dari 100 transaksi membeli produk A dan B bersama-sama, maka support = 15%

**Interpretasi**:
- Support tinggi (>10%): Kombinasi populer
- Support sedang (5-10%): Kombinasi cukup sering
- Support rendah (<5%): Kombinasi jarang

#### 2. Confidence
Probabilitas kondisional - seberapa sering B dibeli ketika A dibeli.

**Formula**:
- Confidence A→B: `P(B|A) = count(A ∩ B) / count(A)`
- Confidence B→A: `P(A|B) = count(A ∩ B) / count(B)`

**Contoh**: Jika 20 transaksi membeli A, dan 15 dari mereka juga membeli B, maka confidence A→B = 75%

**Interpretasi**:
- Confidence tinggi (>60%): Strong association
- Confidence sedang (40-60%): Moderate association
- Confidence rendah (<40%): Weak association

#### 3. Lift
Rasio co-occurrence dibanding random - apakah produk saling mendukung.

**Formula**: `lift = support(A,B) / (support(A) × support(B))`

**Interpretasi**:
- Lift > 1: Produk saling mendukung (positive correlation)
- Lift = 1: Produk independen (no correlation)
- Lift < 1: Produk saling menghambat (negative correlation)

**Contoh**: Lift = 2.4 berarti kombinasi ini 2.4x lebih sering terjadi dibanding jika pembelian random.

#### 4. Weighted Score
Kombinasi metrik untuk ranking kombinasi.

**Formula**: `score = (support × 0.4) + (max_confidence × 0.3) + (lift/3 × 0.3)`

**Bobot**:
- Support: 40% - prioritas kombinasi populer
- Confidence: 30% - prioritas asosiasi kuat
- Lift: 30% - prioritas korelasi positif

## Implementasi Teknis

### Lokasi Code

- **Controller**: `app/Http/Controllers/PromoController.php`
- **Method**: `buildPromoCombinationRanking()` (lines 67-169)
- **View**: `resources/views/pages/promo-admin/rekomendasi.blade.php`
- **Route**: `/admin/promo/rekomendasi`

### Algoritma

```
1. Ekstrak Data Transaksi
   - Ambil PreOrderDetail dengan status: processing, shipping, completed
   - Group by pre_order_id
   - Filter transaksi dengan >= 2 produk

2. Hitung Frekuensi Individual
   - Loop setiap transaksi
   - Count berapa kali setiap product_id muncul
   - Simpan di array productFrequency

3. Hitung Frekuensi Pasangan
   - Nested loop untuk setiap transaksi:
     for i = 0 to n-1:
       for j = i+1 to n:
         pairKey = min(product[i], product[j]) + ":" + max(product[i], product[j])
         pairFrequency[pairKey]++

4. Hitung Metrik untuk Setiap Pasangan
   - Support = pairCount / totalTransactions
   - Confidence A→B = pairCount / frequencyA
   - Confidence B→A = pairCount / frequencyB
   - Lift = support / (P(A) × P(B))
   - Score = weighted combination

5. Ranking & Output
   - Sort by score descending
   - Return top 10 kombinasi
```

### Kompleksitas

- **Time Complexity**: O(T × N²) 
  - T = jumlah transaksi
  - N = rata-rata produk per transaksi
- **Space Complexity**: O(P + C)
  - P = jumlah produk unik
  - C = jumlah pasangan unik

### Pseudocode

```php
function buildPromoCombinationRanking() {
    // 1. Get valid transactions
    transactions = PreOrderDetail
        .where(status IN ['processing', 'shipping', 'completed'])
        .groupBy('pre_order_id')
        .filter(count >= 2)
    
    // 2. Count individual product frequency
    productFrequency = []
    foreach transaction in transactions:
        foreach product in transaction:
            productFrequency[product]++
    
    // 3. Count pair frequency
    pairFrequency = []
    foreach transaction in transactions:
        for i = 0 to length-1:
            for j = i+1 to length:
                pair = [min(i,j), max(i,j)]
                pairFrequency[pair]++
    
    // 4. Calculate metrics
    results = []
    foreach pair, count in pairFrequency:
        support = count / totalTransactions
        confidenceAB = count / productFrequency[A]
        confidenceBA = count / productFrequency[B]
        lift = support / (P(A) * P(B))
        score = (support * 0.4) + (max(confidenceAB, confidenceBA) * 0.3) + (lift/3 * 0.3)
        
        results.push({
            products: [A, B],
            support, confidenceAB, confidenceBA, lift, score
        })
    
    // 5. Sort and return top 10
    return results.sortByDesc('score').take(10)
}
```

## Cara Menggunakan

### 1. Akses Halaman Rekomendasi

Buka di browser (login sebagai admin):
```
http://localhost:8000/admin/promo/rekomendasi
```

### 2. Fitur Halaman

**Ranking Kombinasi Promo**:
- Top 10 pasangan produk dengan metrik lengkap
- Support, Confidence, Lift ditampilkan
- Penjelasan interpretasi untuk setiap kombinasi
- Tombol "Gunakan Kombinasi" untuk quick create

**Daftar Produk**:
- Semua produk dengan jumlah penjualan
- Label status: "Belum ada penjualan", "Mulai dilirik", "Cukup laris", "Terlaris"
- Sorting: Penjualan terendah/tertinggi
- Checkbox untuk selection manual

**Interactive Selection**:
- Pilih produk dengan klik card atau checkbox
- Counter badge menunjukkan jumlah produk terpilih
- Visual feedback dengan ring hijau

### 3. Membuat Promosi dari Rekomendasi

**Cara 1: Dari Ranking (Recommended)**
1. Lihat ranking kombinasi promo
2. Pilih kombinasi dengan metrik terbaik
3. Klik "Gunakan Kombinasi"
4. Redirect ke form create promosi dengan produk pre-selected
5. Isi detail promosi:
   - Nama bundle
   - Harga normal (actual_price)
   - Harga promo (price)
   - Tanggal mulai & berakhir
   - Stok
   - Deskripsi
   - Upload gambar (optional)
6. Submit untuk create bundle promo

**Cara 2: Manual Selection**
1. Pilih produk dengan checkbox di grid
2. Counter badge menunjukkan jumlah produk terpilih
3. Klik "Tambah Promosi Produk"
4. Form create dengan produk pre-selected
5. Isi detail promosi
6. Submit

### 4. Sorting Produk

**Penjualan Terendah** (default):
- Menampilkan produk dengan penjualan paling sedikit di atas
- Berguna untuk promosikan produk slow-moving
- Strategi: Bundle produk slow-moving dengan best-seller

**Penjualan Tertinggi**:
- Menampilkan produk best-seller di atas
- Berguna untuk identifikasi produk populer
- Strategi: Bundle best-sellers untuk maximize revenue

## Limitasi & Batasan

### Limitasi Teknis

#### 1. Hanya 2-Itemsets
**Masalah**: Tidak bisa detect bundle 3+ produk

**Contoh**:
- ✅ Dapat detect: "Kue A + Kue B"
- ❌ Tidak dapat detect: "Kue A + Kue B + Kue C"

**Impact**: Kehilangan insight tentang kombinasi kompleks yang mungkin lebih profitable.

#### 2. Tidak Ada Threshold Filtering
**Masalah**: Semua pasangan dihitung, termasuk yang jarang muncul

**Impact**: 
- Bisa menghasilkan rekomendasi tidak signifikan
- Noise dari kombinasi random
- Perlu manual filtering berdasarkan metrik

**Workaround**: Fokus pada kombinasi dengan support > 5% dan confidence > 40%

#### 3. Performance untuk Dataset Besar
**Masalah**: Nested loop O(N²) per transaksi

**Impact**:
- Untuk ribuan transaksi bisa lambat (>5 detik)
- Page load time meningkat
- Server resource intensive

**Workaround**:
- Limit transaksi ke 3-6 bulan terakhir
- Implement caching
- Pre-compute di background job

#### 4. Real-time Calculation
**Masalah**: Dihitung on-the-fly setiap page load

**Impact**:
- Tidak ada caching
- Setiap akses halaman re-calculate
- Waste computational resources

**Workaround**: Implement Laravel Cache dengan TTL 1 jam

### Batasan Bisnis

#### 1. Hanya Transaksi Valid
**Filter**: Status processing, shipping, completed only

**Excluded**:
- Unpaid orders
- Cancelled orders
- Pending orders

**Rationale**: Hanya transaksi yang benar-benar terjadi yang relevan untuk analisis.

#### 2. Minimum 2 Produk per Transaksi
**Filter**: Transaksi dengan < 2 produk diabaikan

**Impact**: 
- Single-item orders tidak berkontribusi
- Bisa bias untuk produk yang sering dibeli solo

**Rationale**: Association rules memerlukan minimal 2 item untuk analisis.

#### 3. Top 10 Only
**Limit**: Hanya menampilkan 10 kombinasi terbaik

**Impact**: Kombinasi lain tidak terlihat di UI

**Rationale**: Fokus pada rekomendasi terbaik, hindari information overload.

## Interpretasi Hasil

### Contoh Output

```
#1 Chocolate Cake + Vanilla Cupcake
Support: 12.5% | Confidence: 60% | Lift: 2.4x

Kombinasi ini muncul pada 12.5% dari transaksi promosi.
Pelanggan yang memilih salah satu produk memiliki peluang 60% 
untuk ikut memilih pasangannya.
```

**Artinya**:
- **12.5% support**: 1 dari 8 transaksi membeli kedua produk ini
- **60% confidence**: 6 dari 10 pelanggan yang beli Chocolate Cake juga beli Vanilla Cupcake
- **2.4x lift**: Kombinasi ini 2.4x lebih sering terjadi dibanding random

**Rekomendasi Bisnis**: 
- ✅ Bundle ini sangat recommended
- ✅ Support cukup tinggi (populer)
- ✅ Confidence kuat (strong association)
- ✅ Lift positif (saling mendukung)

### Strategi Berdasarkan Metrik

#### High Support + High Confidence (Ideal)
**Karakteristik**:
- Support > 10%
- Confidence > 60%
- Lift > 2

**Strategi**:
- Bundle populer, pasti laku
- Prioritas utama untuk promosi
- Diskon moderat (10-15%) sudah cukup
- Target: mass market

**Contoh**: Chocolate Cake + Vanilla Cupcake

#### Low Support + High Confidence (Niche)
**Karakteristik**:
- Support < 5%
- Confidence > 60%
- Lift > 2

**Strategi**:
- Niche bundle, target spesifik
- Diskon agresif (20-30%) untuk attract
- Marketing targeted ke segment tertentu
- Target: specific customer segment

**Contoh**: Gluten-Free Bread + Sugar-Free Cookies

#### High Support + Low Confidence (Popular but Weak)
**Karakteristik**:
- Support > 10%
- Confidence < 40%
- Lift < 1.5

**Strategi**:
- Produk populer tapi tidak saling terkait
- Bundle kurang efektif
- Better dijual terpisah
- Consider bundling dengan produk lain

**Contoh**: Chocolate Cake + Coffee (keduanya populer tapi independen)

#### High Lift (Strong Correlation)
**Karakteristik**:
- Lift > 3
- Regardless of support/confidence

**Strategi**:
- Produk saling melengkapi
- Good bundle candidate
- Cross-sell opportunity
- Upsell strategy

**Contoh**: Birthday Cake + Candles

## Troubleshooting

### Q: Tidak ada rekomendasi yang muncul?

**Kemungkinan Penyebab**:
- Belum ada transaksi dengan status valid (processing/shipping/completed)
- Semua transaksi hanya 1 produk
- Database kosong atau baru setup

**Solusi**:
1. Cek database: `SELECT COUNT(*) FROM pre_orders WHERE status IN ('processing', 'shipping', 'completed')`
2. Pastikan ada minimal 5-10 transaksi dengan 2+ produk
3. Jika development, seed dummy data
4. Tunggu lebih banyak transaksi real

**Temporary Workaround**: Buat promosi manual tanpa rekomendasi

---

### Q: Rekomendasi tidak masuk akal?

**Kemungkinan Penyebab**:
- Data transaksi terlalu sedikit (< 10 transaksi)
- Produk terlalu banyak variasi
- Tidak ada pola pembelian yang jelas
- Random co-occurrence

**Solusi**:
1. Tunggu lebih banyak data transaksi (minimal 50-100)
2. Filter produk berdasarkan kategori
3. Fokus pada kombinasi dengan:
   - Support > 5%
   - Confidence > 40%
   - Lift > 1.5
4. Manual review sebelum create bundle

**Best Practice**: Combine data-driven recommendation dengan business intuition

---

### Q: Performance lambat?

**Kemungkinan Penyebab**:
- Terlalu banyak transaksi historis (>1000)
- Nested loop O(N²) per transaksi
- Tidak ada caching
- Database query tidak optimal

**Solusi**:

**Short-term**:
```php
// Limit transaksi ke 3 bulan terakhir
$rawDetails = PreOrderDetail::query()
    ->where('created_at', '>=', now()->subMonths(3))
    ->whereNotNull('product_id')
    ->whereHas('preOrder', function ($query) use ($validStatuses) {
        $query->whereIn('status', $validStatuses);
    })
    ->get();
```

**Long-term**:
```php
// Implement caching
public function rekomendasi(Request $request): View
{
    $recommendedCombinations = Cache::remember(
        'promo_recommendations',
        now()->addHour(),
        fn() => $this->buildPromoCombinationRanking()
    );
    
    // ... rest of code
}
```

**Advanced**: Pre-compute di background job (Laravel Queue)

---

### Q: Bagaimana cara clear cache rekomendasi?

**Manual Clear**:
```bash
php artisan cache:forget promo_recommendations
```

**Auto Clear**: Trigger clear cache setiap ada transaksi baru completed
```php
// Di MidtransWebhookController atau PesananController
Cache::forget('promo_recommendations');
```

## Upgrade Path

### Opsi 1: Implement True Apriori Algorithm

**Benefit**:
- Support untuk n-itemsets (3+ products)
- Iterative candidate generation
- Minimum support/confidence thresholds
- Lebih scalable untuk dataset besar
- Academic rigor

**Effort**: High (2-3 hari development)

**Implementation**:
1. Buat `app/Services/AprioriService.php`
2. Implement iterative k-itemset generation
3. Add pruning dengan minimum support
4. Generate association rules dari frequent itemsets
5. Buat command `php artisan promo:generate`

**Reference**: Lihat `APRIORI_README.md` untuk spec lengkap (jika ada)

---

### Opsi 2: Machine Learning Approach

**Benefit**:
- Collaborative filtering
- Matrix factorization
- Neural networks untuk recommendation
- Personalized recommendations
- Better accuracy

**Effort**: Very High (1-2 minggu development)

**Implementation**:
1. Collect user behavior data
2. Build user-item interaction matrix
3. Train recommendation model (Python/TensorFlow)
4. Expose via API
5. Integrate dengan Laravel

**Tools**: Python, scikit-learn, TensorFlow, FastAPI

---

### Opsi 3: Hybrid System

**Benefit**:
- Keep current untuk quick recommendations
- Add advanced algorithm untuk deep analysis
- A/B testing untuk compare effectiveness
- Gradual migration

**Effort**: Medium (3-5 hari development)

**Implementation**:
1. Keep `buildPromoCombinationRanking()` as-is
2. Add `buildAdvancedRecommendations()` dengan Apriori
3. Toggle via config atau feature flag
4. Compare results
5. Choose best performer

**Recommended**: Start dengan ini untuk minimize risk

## Best Practices

### 1. Data Quality
- Pastikan transaksi data akurat
- Clean data dari anomali
- Handle edge cases (refund, return)

### 2. Regular Review
- Review rekomendasi setiap minggu
- Compare dengan actual bundle performance
- Adjust weighted score jika perlu

### 3. Business Context
- Jangan 100% rely on algorithm
- Combine dengan domain knowledge
- Consider seasonal trends
- Factor in inventory levels

### 4. Testing
- A/B test bundle recommendations
- Track conversion rate
- Measure revenue impact
- Iterate based on results

### 5. Performance
- Implement caching
- Limit historical data window
- Monitor query performance
- Consider background jobs

## Referensi

### Academic Papers
- Agrawal, R., & Srikant, R. (1994). "Fast algorithms for mining association rules." *Proceedings of the 20th VLDB Conference*.
- Tan, P. N., Steinbach, M., & Kumar, V. (2005). *Introduction to Data Mining*. Pearson Education.
- Han, J., Pei, J., & Yin, Y. (2000). "Mining frequent patterns without candidate generation." *ACM SIGMOD Record*.

### Implementation Resources
- Laravel Collections: https://laravel.com/docs/collections
- Market Basket Analysis: https://en.wikipedia.org/wiki/Affinity_analysis
- Association Rule Learning: https://en.wikipedia.org/wiki/Association_rule_learning
- Lift Metric: https://en.wikipedia.org/wiki/Lift_(data_mining)

### Tools & Libraries
- **PHP**: Native implementation (current)
- **Python**: mlxtend library untuk Apriori
- **R**: arules package
- **Apache Spark**: MLlib untuk big data

## FAQ

**Q: Apakah ini Apriori algorithm?**  
A: Tidak. Ini adalah simplified 2-itemset association rules mining. Apriori algorithm memiliki iterative candidate generation dan support untuk n-itemsets.

**Q: Kenapa hanya 2 produk per bundle?**  
A: Limitasi implementasi saat ini. Untuk 3+ produk, perlu implement true Apriori atau upgrade algorithm.

**Q: Berapa minimum transaksi yang diperlukan?**  
A: Minimal 50-100 transaksi untuk hasil yang meaningful. Dengan < 10 transaksi, rekomendasi bisa tidak akurat.

**Q: Apakah bisa untuk produk kategori tertentu saja?**  
A: Saat ini tidak ada filter kategori. Perlu modifikasi query untuk filter by category.

**Q: Bagaimana cara adjust bobot metrik?**  
A: Edit weighted score formula di `PromoController.php` line 161. Adjust nilai 0.4, 0.3, 0.3 sesuai prioritas bisnis.

---

**Last Updated**: May 8, 2026  
**Version**: 1.0  
**Author**: Lanina Patisserie Development Team  
**License**: MIT
