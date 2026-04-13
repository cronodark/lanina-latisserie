@extends('layouts.app')

@section('content')
    <main class="mx-auto max-w-3xl p-6">
        <h1 class="mb-2 text-3xl font-bold">Test CRUD Sederhana - Product Show</h1>
        <p class="mb-6 text-sm text-gray-600">Detail data produk.</p>

        @if (session('success'))
            <div class="mb-4 rounded border border-green-300 bg-green-50 px-4 py-3 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded border border-gray-200 bg-white p-5">
            <dl class="space-y-3">
                <div>
                    <dt class="text-xs uppercase tracking-wide text-gray-500">Gambar</dt>
                    <dd class="pt-1 text-sm text-gray-900">
                        @if ($product->getFirstMediaUrl(\App\Models\Product::MEDIA_COLLECTION))
                            <img
                                src="{{ $product->getFirstMediaUrl(\App\Models\Product::MEDIA_COLLECTION) }}"
                                alt="{{ $product->name }}"
                                class="h-40 w-40 rounded border border-gray-200 object-cover"
                            >
                        @else
                            <span class="text-gray-500">Belum ada gambar.</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-gray-500">ID</dt>
                    <dd class="text-sm text-gray-900">{{ $product->id }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-gray-500">Nama</dt>
                    <dd class="text-sm text-gray-900">{{ $product->name }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-gray-500">Deskripsi</dt>
                    <dd class="text-sm text-gray-900">{{ $product->description }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-gray-500">Harga</dt>
                    <dd class="text-sm text-gray-900">Rp {{ number_format($product->price) }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-gray-500">Dibuat</dt>
                    <dd class="text-sm text-gray-900">{{ $product->created_at?->format('d M Y H:i') }}</dd>
                </div>
            </dl>
        </div>

        <div class="mt-6 flex gap-3">
            <a href="{{ route('product.test.index') }}" class="rounded bg-gray-100 px-4 py-2 text-sm hover:bg-gray-200">Kembali ke Index</a>
            <a href="{{ route('product.test.update-page', $product) }}" class="rounded bg-amber-500 px-4 py-2 text-sm text-white hover:bg-amber-600">Update Data</a>
        </div>
    </main>
@endsection
