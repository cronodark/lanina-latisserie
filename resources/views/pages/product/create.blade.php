@extends('layouts.app')

@section('content')
    <main class="mx-auto max-w-3xl p-6">
        <h1 class="mb-2 text-3xl font-bold">Test CRUD Sederhana - Product Create</h1>
        <p class="mb-6 text-sm text-gray-600">Tambahkan data produk baru.</p>

        <form action="{{ route('product.test.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 rounded border border-gray-200 bg-white p-5">
            @csrf

            <div>
                <label for="name" class="mb-1 block text-sm font-medium text-gray-700">Nama</label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name') }}"
                    class="w-full rounded border border-gray-300 px-3 py-2 text-sm"
                >
                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="mb-1 block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    class="w-full rounded border border-gray-300 px-3 py-2 text-sm"
                >{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="price" class="mb-1 block text-sm font-medium text-gray-700">Harga</label>
                <input
                    id="price"
                    name="price"
                    type="number"
                    min="0"
                    value="{{ old('price') }}"
                    class="w-full rounded border border-gray-300 px-3 py-2 text-sm"
                >
                @error('price')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="image" class="mb-1 block text-sm font-medium text-gray-700">Gambar Product</label>
                <input
                    id="image"
                    name="image"
                    type="file"
                    accept="image/*"
                    class="w-full rounded border border-gray-300 px-3 py-2 text-sm"
                >
                <p class="mt-1 text-xs text-gray-500">Format image, maksimum 2MB.</p>
                @error('image')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <a href="{{ route('product.test.index') }}" class="rounded bg-gray-100 px-4 py-2 text-sm hover:bg-gray-200">Batal</a>
                <button type="submit" class="rounded bg-green-600 px-4 py-2 text-sm text-white hover:bg-green-700">Simpan Product</button>
            </div>
        </form>
    </main>
@endsection
