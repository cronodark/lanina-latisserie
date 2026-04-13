@extends('layouts.app')

@section('content')
    <main class="mx-auto max-w-5xl p-6">
        <h1 class="mb-2 text-3xl font-bold">Test CRUD Sederhana - Product Index</h1>
        <p class="mb-6 text-sm text-gray-600">Halaman ini untuk melihat daftar produk dan masuk ke detail/update.</p>

        <div class="mb-6">
            <a href="{{ route('product.create') }}" class="inline-block rounded bg-green-600 px-4 py-2 text-sm text-white hover:bg-green-700">Add Product</a>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded border border-green-300 bg-green-50 px-4 py-3 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($products->isEmpty())
            <div class="rounded border border-yellow-300 bg-yellow-50 px-4 py-3 text-yellow-700">
                Belum ada data produk.
            </div>
        @else
            <div class="overflow-x-auto rounded border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Gambar</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Nama</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Harga</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach ($products as $product)
                            <tr>
                                <td class="px-4 py-3 text-sm">{{ $product->id }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @if ($product->getFirstMediaUrl(\App\Models\Product::MEDIA_COLLECTION))
                                        <img
                                            src="{{ $product->getFirstMediaUrl(\App\Models\Product::MEDIA_COLLECTION) }}"
                                            alt="{{ $product->name }}"
                                            class="h-12 w-12 rounded border border-gray-200 object-cover"
                                        >
                                    @else
                                        <span class="text-xs text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm">{{ $product->name }}</td>
                                <td class="px-4 py-3 text-sm">Rp {{ number_format($product->price) }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <a href="{{ route('product.show', $product) }}" class="mr-3 text-blue-600 hover:underline">Show</a>
                                    <a href="{{ route('product.edit', $product) }}" class="text-amber-600 hover:underline">Update</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </main>
@endsection
