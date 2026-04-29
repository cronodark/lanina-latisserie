@extends('layouts.app')

@section('title', 'Dashboard | lanina')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<main class="p-8 min-h-screen bg-[#F5F7FA]">

  <!-- Judul -->
  <h1 class="text-2xl font-semibold text-gray-800 mb-6">Dashboard</h1>

  <!-- TOP SECTION -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    <!-- Total Pendapatan -->
    <div class="bg-[#C89B5E] text-white rounded-2xl p-6 flex justify-between items-center shadow">
      <div>
        <p class="text-xl opacity-80">Total Pendapatan</p>
        <h2 class="text-3xl font-bold mt-2" data-stat="pendapatan">Rp 0</h2>
      </div>
      <div class="bg-white p-3 rounded-xl">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#C89B5E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
        </svg>
        </div>
    </div>

    <!-- Banner -->
    <div class="relative rounded-2xl overflow-hidden shadow">
  <img src="/images/banner.jpg" class="absolute inset-0 w-full h-full object-cover">
  <div class="absolute inset-0 bg-black/40"></div>

 <div class="relative px-12 py-6 text-white flex flex-col justify-end h-full">
    <h3 class="text-3xl font-semibold">Selamat Datang, Admin!</h3>
    <p class="text-xl opacity-90">
      Pantau semua pesanan dan kelola <br>
      toko LANINA dari sini.
    </p>
  </div>
</div>

  </div>

  <!-- STATISTIK -->
  <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-8">

  <!-- Total Pesanan -->
  <div class="bg-white rounded-2xl p-5 shadow flex items-start justify-between">
    <div>
      <p class="text-sm text-gray-500">Total Pesanan</p>
      <h3 class="text-2xl font-bold text-gray-900 mt-2" data-stat="pesanan">0</h3>
    </div>
    <div class="bg-purple-100 p-3 rounded-xl">
      <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m8-2.13a4 4 0 10-8 0 4 4 0 008 0z"/>
  </svg>
    </div>
  </div>

  <!-- Total Selesai -->
  <div class="bg-white rounded-2xl p-5 shadow flex items-start justify-between">
    <div>
      <p class="text-sm text-gray-500">Total Selesai</p>
      <h3 class="text-2xl font-bold text-gray-900 mt-2" data-stat="selesai">0</h3>
    </div>
    <div class="bg-green-100 p-3 rounded-xl">
      <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          d="M5 13l4 4L19 7"/>
      </svg>
    </div>
  </div>

  <!-- Dikirim -->
  <div class="bg-white rounded-2xl p-5 shadow flex items-start justify-between">
    <div>
      <p class="text-sm text-gray-500">Dikirim</p>
      <h3 class="text-2xl font-bold text-gray-900 mt-2" data-stat="dikirim">0</h3>
    </div>
    <div class="bg-blue-100 p-3 rounded-xl">
      <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          d="M3 7h13v10H3V7zm13 3h3l2 3v4h-5v-7z"/>
      </svg>
    </div>
  </div>

  <!-- Dikerjakan -->
  <div class="bg-white rounded-2xl p-5 shadow flex items-start justify-between">
    <div>
      <p class="text-sm text-gray-500">Dikerjakan</p>
      <h3 class="text-2xl font-bold text-gray-900 mt-2" data-stat="dikerjakan">0</h3>
    </div>
    <div class="bg-yellow-100 p-3 rounded-xl">
      <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
    </div>
  </div>

  <!-- Belum -->
  <div class="bg-white rounded-2xl p-5 shadow flex items-start justify-between">
    <div>
      <p class="text-sm text-gray-500">Belum</p>
      <h3 class="text-2xl font-bold text-gray-900 mt-2" data-stat="belum">0</h3>
    </div>
    <div class="bg-red-100 p-3 rounded-xl">
      <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </div>
  </div>

</div>
  </div>

  <!-- Grafik -->
  <div class="bg-white rounded-2xl p-6 shadow mb-8">
    <h3 class="text-lg font-semibold text-black-700 mb-4">Grafik Pendapatan Per Hari</h3>
    <canvas id="chartPendapatan"></canvas>
  </div>

  <!-- Pesanan Mendekati Deadline -->
  <div class="bg-white rounded-2xl p-6 shadow">
    <div class="flex items-center justify-between mb-6">
      <div class="flex items-center gap-3">
        <div>
          <h3 class="text-xl font-semibold text-black-800"> Pesanan Mendekati Deadline</h3>
        </div>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left" id="tableDeadline">
        <thead>
          <tr class="bg-gray-200 ">
            <th class="px-4 py-3 text-sm font-semibold text-black-700">ID Pesanan</th>
            <th class="px-4 py-3 text-sm font-semibold text-black-700">Nama Pelanggan</th>
            <th class="px-4 py-3 text-sm font-semibold text-black-700">Nama Produk</th>
            <th class="px-4 py-3 text-sm font-semibold text-black-700">Tanggal Pembelian</th>
            <th class="px-4 py-3 text-sm font-semibold text-black-700">Tanggal Pengantaran</th>
            <th class="px-4 py-3 text-sm font-semibold text-black-700">Total Harga</th>
            <th class="px-4 py-3 text-sm font-semibold text-black-700">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
        </tbody>
      </table>
    </div>
  </div>
</main>
@endsection

@section('content')
    {{-- Dashboard belum diimplementasikan --}}
@endsection