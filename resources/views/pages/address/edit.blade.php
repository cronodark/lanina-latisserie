@extends('layouts.app')

@section('title', 'Edit Alamat')

@section('content')

    <div class="min-h-screen bg-[#FBFEF3] font-nunito">

        <x-sidebar active="daftar-alamat" />

        {{-- MAIN CONTENT --}}
        <div class="lg:ml-[270px] flex flex-col gap-6 px-4 sm:px-6 lg:px-10 py-8 lg:py-10">

            {{-- Topbar --}}
            <div class="flex items-center gap-4 mb-2">
                <button id="sidebarToggle" class="lg:hidden text-[#3D2B1F]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="flex-1 max-w-[360px]">
                    <div class="flex items-center gap-2 bg-[#F5F6FA] border border-[#D5D5D5] rounded-full px-5 py-3 shadow-[0_2px_8px_rgba(0,0,0,0.06)]">
                        <svg class="w-5 h-5 text-[#9A8878]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z" />
                        </svg>
                        <input type="text" placeholder="Search"
                            class="font-nunito text-sm text-[#3D2B1F] placeholder-[#C4B8AE] outline-none bg-transparent w-full">
                    </div>
                </div>
            </div>

            {{-- Header Banner --}}
            <div class="bg-[#BB9457] rounded-[24px] px-6 sm:px-8 lg:px-10 py-6 sm:py-8 flex items-center gap-5">
                <svg class="w-14 h-14 sm:w-16 sm:h-16 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                </svg>
                <h1 class="font-glacial font-bold text-white text-3xl sm:text-4xl">
                    Update Alamat
                </h1>
            </div>

            {{-- Form Card --}}
            <div class="bg-white rounded-[24px] px-6 sm:px-8 lg:px-10 py-8 lg:py-10 card-shadow">
                <form action="{{ route('profile.address.update', $address->id) }}" method="POST"
                    x-data="addressWilayah({
                        initStateName:    @js(old('state', $address->state)),
                        initCityName:     @js(old('city', $address->city)),
                        initDistrictName: @js(old('district', $address->district)),
                        initZipCode:      @js(old('zip_code', $address->zip_code))
                    })" x-init="init()">
                    @csrf
                    @method('PUT')

                    <h2 class=" font-bold text-[#1A1A1A] text-2xl mb-2">Update Alamat</h2>
                    <hr class="border-[#E0E0E0] mb-8">

                    <h3 class=" font-bold text-[#1A1A1A] text-lg mb-5">Informasi Utama</h3>

                    {{-- Row 1: Alamat Jalan | Wilayah Administratif | Kode Pos --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">

                        {{-- Alamat Jalan --}}
                        <div>
                            <label class=" text-base font-semibold text-[#1A1A1A] mb-2 block">Alamat Jalan:</label>
                            <input type="text" name="street" value="{{ old('street', $address->street) }}"
                                placeholder="Masukan alamat jalan" minlength="5" maxlength="255" required
                                class="w-full text-base text-[#3D2B1F] bg-[#F0F0F0] border-0 rounded-[12px] px-5 py-3.5 outline-none focus:ring-2 focus:ring-[#7A8C5C] transition-all placeholder-[#ABABAB] @error('street') ring-2 ring-red-400 @enderror">
                            @error('street')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Wilayah Administratif (RT/RW) --}}
                        <div>
                            <label class=" text-base font-semibold text-[#1A1A1A] mb-2 block">Wilayah Administratif</label>
                            <div class="flex gap-3">
                                <div class="flex items-center gap-2 flex-1 bg-[#F0F0F0] rounded-[12px] px-4 py-3.5">
                                    <span class=" font-bold text-base text-[#6B6B6B] shrink-0">RT</span>
                                    <input type="text" name="rt" value="{{ old('rt', $address->rt) }}"
                                        placeholder="001" inputmode="numeric" maxlength="3" pattern="\d{1,3}" required
                                        oninput="this.value = this.value.replace(/\D/g, '').slice(0, 3)"
                                        class="w-full text-base text-[#3D2B1F] bg-transparent outline-none">
                                </div>
                                <div class="flex items-center gap-2 flex-1 bg-[#F0F0F0] rounded-[12px] px-4 py-3.5">
                                    <span class=" font-bold text-base text-[#6B6B6B] shrink-0">RW</span>
                                    <input type="text" name="rw" value="{{ old('rw', $address->rw) }}"
                                        placeholder="001" inputmode="numeric" maxlength="3" pattern="\d{1,3}" required
                                        oninput="this.value = this.value.replace(/\D/g, '').slice(0, 3)"
                                        class="w-full text-base text-[#3D2B1F] bg-transparent outline-none">
                                </div>
                            </div>
                            @error('rt')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('rw')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kode Pos --}}
                        <div>
                            <label class=" text-base font-semibold text-[#1A1A1A] mb-2 block">Kode Pos</label>
                            <select name="zip_code" x-model="zipCode" required
                                :disabled="!districtId || loadingZipCodes"
                                class="w-full text-base text-[#3D2B1F] bg-[#F0F0F0] border-0 rounded-[12px] px-5 py-3.5 outline-none focus:ring-2 focus:ring-[#7A8C5C] transition-all disabled:opacity-60 disabled:cursor-not-allowed @error('zip_code') ring-2 ring-red-400 @enderror">
                                <option value="" x-text="!districtId ? 'Pilih kecamatan dulu' : (loadingZipCodes ? 'Memuat...' : (zipCodes.length === 0 ? 'Tidak ada data' : 'Pilih kode pos'))"></option>
                                <template x-for="z in zipCodes" :key="z.code + z.village">
                                    <option :value="z.code" x-text="`${z.code} - ${z.village}`"></option>
                                </template>
                            </select>
                            @error('zip_code')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- Row 2: Provinsi | Kabupaten | Kecamatan | Patokan --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-12">

                        {{-- Provinsi --}}
                        <div>
                            <label class=" text-base font-semibold text-[#1A1A1A] mb-2 block">Provinsi</label>
                            <select x-model="stateId" @change="onStateChange()" required
                                :disabled="loadingProvinces"
                                class="w-full text-base text-[#3D2B1F] bg-[#F0F0F0] border-0 rounded-[12px] px-5 py-3.5 outline-none focus:ring-2 focus:ring-[#7A8C5C] transition-all @error('state') ring-2 ring-red-400 @enderror">
                                <option value="" x-text="loadingProvinces ? 'Memuat...' : 'Pilih provinsi'"></option>
                                <template x-for="p in provinces" :key="p.id">
                                    <option :value="p.id" x-text="p.name"></option>
                                </template>
                            </select>
                            <input type="hidden" name="state" :value="stateName">
                            @error('state')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kabupaten --}}
                        <div>
                            <label class=" text-base font-semibold text-[#1A1A1A] mb-2 block">Kabupaten</label>
                            <select x-model="cityId" @change="onCityChange()" required
                                :disabled="!stateId || loadingCities"
                                class="w-full text-base text-[#3D2B1F] bg-[#F0F0F0] border-0 rounded-[12px] px-5 py-3.5 outline-none focus:ring-2 focus:ring-[#7A8C5C] transition-all disabled:opacity-60 disabled:cursor-not-allowed @error('city') ring-2 ring-red-400 @enderror">
                                <option value="" x-text="!stateId ? 'Pilih provinsi dulu' : (loadingCities ? 'Memuat...' : 'Pilih kabupaten')"></option>
                                <template x-for="c in cities" :key="c.id">
                                    <option :value="c.id" x-text="c.name"></option>
                                </template>
                            </select>
                            <input type="hidden" name="city" :value="cityName">
                            @error('city')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kecamatan --}}
                        <div>
                            <label class=" text-base font-semibold text-[#1A1A1A] mb-2 block">Kecamatan</label>
                            <select x-model="districtId" @change="onDistrictChange()" required
                                :disabled="!cityId || loadingDistricts"
                                class="w-full text-base text-[#3D2B1F] bg-[#F0F0F0] border-0 rounded-[12px] px-5 py-3.5 outline-none focus:ring-2 focus:ring-[#7A8C5C] transition-all disabled:opacity-60 disabled:cursor-not-allowed @error('district') ring-2 ring-red-400 @enderror">
                                <option value="" x-text="!cityId ? 'Pilih kabupaten dulu' : (loadingDistricts ? 'Memuat...' : 'Pilih kecamatan')"></option>
                                <template x-for="d in districts" :key="d.id">
                                    <option :value="d.id" x-text="d.name"></option>
                                </template>
                            </select>
                            <input type="hidden" name="district" :value="districtName">
                            @error('district')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Patokan --}}
                        <div>
                            <label class=" text-base font-semibold text-[#1A1A1A] mb-2 block">Patokan/Keterangan</label>
                            <input type="text" name="notes" value="{{ old('notes', $address->notes) }}"
                                placeholder="Contoh: dekat masjid, rumah cat hijau"
                                minlength="3" maxlength="500" required
                                class="w-full text-base text-[#3D2B1F] bg-[#F0F0F0] border-0 rounded-[12px] px-5 py-3.5 outline-none focus:ring-2 focus:ring-[#7A8C5C] transition-all placeholder-[#ABABAB] @error('notes') ring-2 ring-red-400 @enderror">
                            @error('notes')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- Pesan error fetch API wilayah --}}
                    <template x-if="apiError">
                        <div class="mb-6 rounded-[12px] bg-red-50 border border-red-200 px-5 py-3 text-sm text-red-600">
                            Gagal memuat data wilayah. Periksa koneksi internet Anda lalu muat ulang halaman.
                        </div>
                    </template>

                    {{-- Submit Button --}}
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('profile.address.index') }}"
                            class="bg-[#FD5454] hover:bg-[#FF0000] text-white font-bold text-base px-10 py-4 rounded-[14px] transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                            class="bg-[#7A8C5C] hover:bg-[#5C6B44] text-white font-bold text-base px-10 py-4 rounded-[14px] transition-colors">
                            Simpan
                        </button>
                    </div>
                </form>

            </div>

        </div>
    </div>

    {{-- Alpine component: cascading dropdown wilayah Indonesia (proxy ke emsifa API) --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('addressWilayah', (opts = {}) => ({
                BASE: '{{ url('/api/wilayah') }}',
                provinces: [],
                cities: [],
                districts: [],
                zipCodes: [],
                stateId: '',
                cityId: '',
                districtId: '',
                zipCode: '',
                stateName: opts.initStateName || '',
                cityName: opts.initCityName || '',
                districtName: opts.initDistrictName || '',
                initZipCode: opts.initZipCode || '',
                loadingProvinces: false,
                loadingCities: false,
                loadingDistricts: false,
                loadingZipCodes: false,
                apiError: false,

                async init() {
                    try {
                        this.loadingProvinces = true;
                        const res = await fetch(`${this.BASE}/provinces`);
                        if (!res.ok) throw new Error('Gagal memuat provinsi');
                        this.provinces = await res.json();

                        // Prefill saat edit: cari id berdasarkan nama yang sudah tersimpan
                        if (this.stateName) {
                            const p = this.provinces.find(x => x.name.toLowerCase() === this.stateName.toLowerCase());
                            if (p) {
                                this.stateId = p.id;
                                await this.loadCities();
                                if (this.cityName) {
                                    const c = this.cities.find(x => x.name.toLowerCase() === this.cityName.toLowerCase());
                                    if (c) {
                                        this.cityId = c.id;
                                        await this.loadDistricts();
                                        if (this.districtName) {
                                            const d = this.districts.find(x => x.name.toLowerCase() === this.districtName.toLowerCase());
                                            if (d) {
                                                this.districtId = d.id;
                                                await this.loadZipCodes();
                                                // Set zipCode lama bila masih ada di list
                                                if (this.initZipCode && this.zipCodes.some(z => z.code === this.initZipCode)) {
                                                    this.zipCode = this.initZipCode;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } catch (e) {
                        this.apiError = true;
                    } finally {
                        this.loadingProvinces = false;
                    }
                },

                async loadCities() {
                    if (!this.stateId) return;
                    try {
                        this.loadingCities = true;
                        const res = await fetch(`${this.BASE}/regencies/${this.stateId}`);
                        if (!res.ok) throw new Error('Gagal memuat kabupaten');
                        this.cities = await res.json();
                    } catch (e) {
                        this.apiError = true;
                    } finally {
                        this.loadingCities = false;
                    }
                },

                async loadDistricts() {
                    if (!this.cityId) return;
                    try {
                        this.loadingDistricts = true;
                        const res = await fetch(`${this.BASE}/districts/${this.cityId}`);
                        if (!res.ok) throw new Error('Gagal memuat kecamatan');
                        this.districts = await res.json();
                    } catch (e) {
                        this.apiError = true;
                    } finally {
                        this.loadingDistricts = false;
                    }
                },

                async loadZipCodes() {
                    if (!this.districtName || !this.cityName) return;
                    try {
                        this.loadingZipCodes = true;
                        const params = new URLSearchParams({
                            district: this.districtName,
                            regency: this.cityName
                        });
                        const res = await fetch(`${this.BASE}/kodepos?${params}`);
                        if (!res.ok) throw new Error('Gagal memuat kode pos');
                        this.zipCodes = await res.json();
                    } catch (e) {
                        this.zipCodes = [];
                    } finally {
                        this.loadingZipCodes = false;
                    }
                },

                async onStateChange() {
                    this.cityId = '';
                    this.districtId = '';
                    this.zipCode = '';
                    this.cities = [];
                    this.districts = [];
                    this.zipCodes = [];
                    this.cityName = '';
                    this.districtName = '';
                    const p = this.provinces.find(x => x.id === this.stateId);
                    this.stateName = p?.name ?? '';
                    await this.loadCities();
                },

                async onCityChange() {
                    this.districtId = '';
                    this.zipCode = '';
                    this.districts = [];
                    this.zipCodes = [];
                    this.districtName = '';
                    const c = this.cities.find(x => x.id === this.cityId);
                    this.cityName = c?.name ?? '';
                    await this.loadDistricts();
                },

                async onDistrictChange() {
                    this.zipCode = '';
                    this.zipCodes = [];
                    const d = this.districts.find(x => x.id === this.districtId);
                    this.districtName = d?.name ?? '';
                    await this.loadZipCodes();
                },
            }));
        });
    </script>

    <script>
        {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('sidebarToggle');
            const overlay = document.getElementById('sidebarOverlay');

            toggle.addEventListener('click', () => {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            });

            overlay.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            });
        }
    </script>

@endsection
