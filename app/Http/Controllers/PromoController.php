<?php

namespace App\Http\Controllers;

use App\Models\PreOrderDetail;
use App\Models\Product;
use App\Models\Promo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class PromoController extends Controller
{
    public function show(Promo $promo): View
    {
        return view('pages.promo.show', [
            'title' => 'Detail Promosi',
            'promo' => $promo,
        ]);
    }

    public function rekomendasi(Request $request): View
    {
        $sort = $request->query('sort', 'penjualan_terendah');
        $salesCounts = $this->buildProductSalesCounts();

        $products = Product::query()
            ->get()
            ->sort(function (Product $left, Product $right) use ($sort, $salesCounts) {
                $leftSales = $salesCounts[$left->id] ?? 0;
                $rightSales = $salesCounts[$right->id] ?? 0;

                if ($leftSales === $rightSales) {
                    return strcasecmp($left->name, $right->name);
                }

                if ($sort === 'penjualan_tertinggi') {
                    return $rightSales <=> $leftSales;
                }

                return $leftSales <=> $rightSales;
            })
            ->values();

        $recommendedCombinations = $this->buildPromoCombinationRanking();

        return view('pages.promo-admin.rekomendasi', [
            'title' => 'Rekomendasi Produk Promosi',
            'products' => $products,
            'salesCounts' => $salesCounts,
            'recommendedCombinations' => $recommendedCombinations,
        ]);
    }

    /**
     * Build ranked product-pair recommendations based on preorder transaction history.
     *
     * Metrics:
     * - support(A,B): P(A and B)
     * - confidence(A->B): P(B|A)
     * - confidence(B->A): P(A|B)
     * - lift: P(A and B) / (P(A) * P(B))
     *
     * @return Collection<int, array<string, mixed>>
     */
    private function buildPromoCombinationRanking(): Collection
    {
        $validStatuses = ['processing', 'shipping', 'completed'];

        $rawDetails = PreOrderDetail::query()
            ->select(['pre_order_id', 'product_id'])
            ->whereNotNull('product_id')
            ->whereHas('preOrder', function ($query) use ($validStatuses) {
                $query->whereIn('status', $validStatuses);
            })
            ->orderBy('pre_order_id')
            ->get();

        if ($rawDetails->isEmpty()) {
            return collect();
        }

        $transactions = $rawDetails
            ->groupBy('pre_order_id')
            ->map(function (Collection $details): array {
                return $details
                    ->pluck('product_id')
                    ->filter()
                    ->map(fn($id) => (int) $id)
                    ->unique()
                    ->values()
                    ->all();
            })
            ->filter(fn(array $items) => count($items) >= 2)
            ->values();

        $transactionCount = $transactions->count();
        if ($transactionCount === 0) {
            return collect();
        }

        $productFrequency = [];
        $pairFrequency = [];

        foreach ($transactions as $items) {
            foreach ($items as $productId) {
                $productFrequency[$productId] = ($productFrequency[$productId] ?? 0) + 1;
            }

            $itemCount = count($items);
            for ($i = 0; $i < $itemCount - 1; $i++) {
                for ($j = $i + 1; $j < $itemCount; $j++) {
                    $left = min($items[$i], $items[$j]);
                    $right = max($items[$i], $items[$j]);
                    $pairKey = $left . ':' . $right;
                    $pairFrequency[$pairKey] = ($pairFrequency[$pairKey] ?? 0) + 1;
                }
            }
        }

        if (empty($pairFrequency)) {
            return collect();
        }

        $products = Product::query()
            ->whereIn('id', array_keys($productFrequency))
            ->get()
            ->keyBy('id');

        $ranked = collect();

        foreach ($pairFrequency as $pairKey => $pairCount) {
            [$leftId, $rightId] = array_map('intval', explode(':', $pairKey));
            $leftFrequency = $productFrequency[$leftId] ?? 0;
            $rightFrequency = $productFrequency[$rightId] ?? 0;

            if ($leftFrequency === 0 || $rightFrequency === 0) {
                continue;
            }

            $leftProduct = $products->get($leftId);
            $rightProduct = $products->get($rightId);
            if (! $leftProduct || ! $rightProduct) {
                continue;
            }

            $support = $pairCount / $transactionCount;
            $confidenceLeftToRight = $pairCount / $leftFrequency;
            $confidenceRightToLeft = $pairCount / $rightFrequency;
            $lift = $support / (($leftFrequency / $transactionCount) * ($rightFrequency / $transactionCount));

            $ranked->push([
                'product_ids' => [$leftId, $rightId],
                'products' => [$leftProduct->name, $rightProduct->name],
                'support' => $support,
                'confidence_a_to_b' => $confidenceLeftToRight,
                'confidence_b_to_a' => $confidenceRightToLeft,
                'lift' => $lift,
                // Weighted score to prioritize strong and frequent combinations.
                'score' => ($support * 0.4) + (max($confidenceLeftToRight, $confidenceRightToLeft) * 0.3) + (min($lift / 3, 1) * 0.3),
            ]);
        }

        return $ranked
            ->sortByDesc(fn(array $item) => $item['score'])
            ->values()
            ->take(10);
    }

    /**
     * Count how often each product appears in completed preorder transactions.
     *
     * @return array<int, int>
     */
    private function buildProductSalesCounts(): array
    {
        $validStatuses = ['processing', 'shipping', 'completed'];

        $rawDetails = PreOrderDetail::query()
            ->select(['pre_order_id', 'product_id'])
            ->whereNotNull('product_id')
            ->whereHas('preOrder', function ($query) use ($validStatuses) {
                $query->whereIn('status', $validStatuses);
            })
            ->orderBy('pre_order_id')
            ->get();

        if ($rawDetails->isEmpty()) {
            return [];
        }

        $transactions = $rawDetails
            ->groupBy('pre_order_id')
            ->map(function (Collection $details): array {
                return $details
                    ->pluck('product_id')
                    ->filter()
                    ->map(fn($id) => (int) $id)
                    ->unique()
                    ->values()
                    ->all();
            })
            ->filter(fn(array $items) => count($items) > 0);

        $salesCounts = [];

        foreach ($transactions as $items) {
            foreach ($items as $productId) {
                $salesCounts[$productId] = ($salesCounts[$productId] ?? 0) + 1;
            }
        }

        return $salesCounts;
    }

    public function produkDalamPromosi(): View
    {
        Promo::synchronizeStatuses();

        $promos = Promo::where('status', 'active')->get();

        return view('pages.promo-admin.produk-dalam-promosi', [
            'title'  => 'Produk Dalam Promosi',
            'promos' => $promos,
        ]);
    }

    public function status(string $tab): View
    {
        Promo::synchronizeStatuses();

        $tab = match ($tab) {
            'active' => 'aktif',
            'scheduled' => 'terjadwal',
            'inactive' => 'berakhir',
            default => $tab,
        };

        $promos = match ($tab) {
            'terjadwal' => Promo::where('status', 'scheduled')->get(),
            'berakhir'  => Promo::where('status', 'inactive')->get(),
            default     => Promo::where('status', 'active')->get(),
        };

        return view('pages.promo-admin.status', [
            'title'  => 'Status Promosi',
            'promos' => $promos,
            'tab'    => $tab,
        ]);
    }

    public function create(): View
    {
        $products = Product::all();

        return view('pages.promo-admin.create', [
            'title'    => 'Tambah Promosi Produk',
            'products' => $products,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $selectedProductIds = collect($request->input('product_ids', []))
            ->filter()
            ->values();

        $request->validate([
            'name'         => ['required', 'string', 'max:50', 'unique:promos,name'],
            'actual_price' => ['nullable', 'integer', 'min:0'],
            'price'        => ['required', 'integer', 'min:0'],
            'date_start'   => ['nullable', 'date'],
            'date_until'   => ['required', 'date'],
            'stok'         => ['nullable', 'integer', 'min:0'],
            'description'  => ['nullable', 'string'],
            'image'        => array_merge(
                $selectedProductIds->count() > 2 ? ['required'] : ['nullable'],
                ['image', 'max:2048']
            ),
            'product_ids'  => ['required', 'array', 'min:1'],
            'product_ids.*'=> ['integer', 'exists:products,id'],
        ]);

        $productIds = collect($request->input('product_ids', []))
            ->map(fn($productId) => (int) $productId)
            ->values();

        $actualPrice = Product::query()
            ->whereIn('id', $productIds)
            ->sum('price');

        $promo = Promo::create([
            'name'         => $request->name,
            'actual_price' => $actualPrice,
            'price'        => $request->price,
            'date_start'   => $request->date_start,
            'date_until'   => $request->date_until,
            'stok'         => $request->stok,
            'description'  => $request->description,
            'status'       => Promo::resolveStatus($request->date_start, $request->date_until),
        ]);



        if ($request->hasFile('image')) {
            $promo->addMediaFromRequest('image')
                ->toMediaCollection(Promo::MEDIA_COLLECTION);
        }

        foreach ($productIds as $productId) {
            $promo->promoDetails()->create([
                'product_id' => $productId,
            ]);
        }

        return redirect()
            ->route('promo-admin.status', 'aktif')
            ->with('success', 'Promosi berhasil ditambahkan.');
    }

    public function edit(Promo $promo): View
    {
        $allProducts = Product::all();

        return view('pages.promo-admin.edit', [
            'title'       => 'Edit Promosi Produk',
            'promo'       => $promo,
            'allProducts' => $allProducts,
        ]);
    }

    public function update(Request $request, Promo $promo): RedirectResponse
    {
        $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'actual_price' => ['required', 'integer', 'min:0'],
            'price'        => ['required', 'integer', 'min:0'],
            'date_start'   => ['required', 'date'],
            'date_until'   => ['required', 'date'],
            'stok'         => ['required', 'integer', 'min:0'],
            'description'  => ['required', 'string'],
            'image'        => ['nullable', 'image', 'max:2048'],
        ]);

        $promo->update([
            'name'         => $request->name ?? $promo->name,
            'actual_price' => $request->actual_price,
            'price'        => $request->price,
            'date_start'   => $request->date_start,
            'date_until'   => $request->date_until,
            'stok'         => $request->stok,
            'description'  => $request->description,
            'status'       => Promo::resolveStatus($request->date_start, $request->date_until),
        ]);

        if ($request->hasFile('image')) {
            $promo->addMediaFromRequest('image')
                ->toMediaCollection(Promo::MEDIA_COLLECTION);
        }

        return redirect()
            ->route('promo-admin.status', 'aktif')
            ->with('success', 'Promosi berhasil diperbarui.');
    }

    public function destroy(Promo $promo): RedirectResponse
    {
        $promo->delete();

        return redirect()
            ->route('promo-admin.status', 'aktif')
            ->with('success', 'Promosi berhasil dihapus.');
    }

    public function destroyProduct(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()
            ->route('promo-admin.rekomendasi')
            ->with('success', 'Produk berhasil dihapus.');
    }

    public function toggleSelect(Product $product): RedirectResponse
    {
        $product->update([
            'selected_for_promo' => !$product->selected_for_promo,
        ]);

        return redirect()->back();
    }
}
