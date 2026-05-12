@extends('layouts.app')

@section('title', 'Checkout')

@section('content')

    <x-navbar />

    <main class="min-h-screen bg-[#F5F0E8] mt-10 py-12 px-6 md:px-16">
        <div class="max-w-[1100px] mx-auto flex justify-center">

            <div class="w-full max-w-[520px] flex flex-col gap-3">
                @php
                    $selectedAddress = collect($addresses)->first();
                    $selectedAddressId = $selectedAddress?->id;
                    $selectedAddressText = $selectedAddress
                        ? collect([
                            $selectedAddress->street,
                            'RT '.$selectedAddress->rt.'/RW '.$selectedAddress->rw,
                            $selectedAddress->district,
                            $selectedAddress->city,
                            $selectedAddress->state,
                            $selectedAddress->zip_code,
                        ])->filter()->implode(', ')
                        : 'Belum ada alamat tersimpan';
                @endphp

                {{-- ===== SECTION 1: Order Items ===== --}}
                <div class="bg-white rounded-[20px] px-8 py-8 shadow-[0_3px_16px_rgba(0,0,0,0.06)]">

                    <h1 class="font-['Playfair_Display'] text-4xl font-bold text-[#3D2B1F] text-center mb-8">
                        Checkout
                    </h1>

                    {{-- ===== ALAMAT ===== --}}
                    <div class="mb-6 pb-6 border-b border-[#E8E0D4]">
                        <div id="open-address-modal" class="bg-[#F2EFEA] rounded-[16px] px-5 py-4 flex flex-col gap-2 cursor-pointer">

                            <div class="flex items-center justify-between">

                                <div class="flex items-center gap-3">
                                    {{-- Icon lokasi --}}
                                    <div class="w-6 h-6 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.05 8.05a5 5 0 119.9 0c0 3.535-4.95 8.95-4.95 8.95S5.05 11.585 5.05 8.05zM10 9a1 1 0 100-2 1 1 0 000 2z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>

                                    <div>
                                        <p id="address-name" class="font-glacial font-bold text-[#3D2B1F] text-sm">
                                            {{ auth()->user()->name }}
                                            <span class="font-normal text-[#6B4C3B] ml-2">
                                                ({{ auth()->user()->telp }})
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                {{-- panah kanan --}}
                                <svg class="w-4 h-4 text-[#3D2B1F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>

                            </div>

                            <p id="address-detail" class="font-glacial text-[#6B4C3B] text-sm leading-relaxed">
                                {{ $selectedAddressText }}
                            </p>

                        </div>
                    </div>

                    <div class="flex flex-col divide-y divide-[#E8E0D4]">
                        @foreach ($items as $item)
                            <div class="py-3 sm:py-4 first:pt-0 last:pb-0">
                                <div class="flex items-start justify-between gap-3 mb-1">
                                    <p class="font-['Playfair_Display'] font-bold text-[#3D2B1F] text-sm sm:text-base break-words min-w-0 flex-1 line-clamp-2">
                                        {{ $item['name'] }}
                                    </p>
                                    <span class="font-glacial text-[#3D2B1F] text-sm font-bold shrink-0">
                                        {{ $item['qty'] }}x
                                    </span>
                                </div>
                                <div class="flex items-end justify-between gap-3">
                                    <p class="font-glacial text-[#6B4C3B] text-xs sm:text-sm break-words min-w-0 flex-1 line-clamp-2">{{ $item['desc'] }}</p>
                                    <span class="font-glacial font-bold text-[#7A8C5C] text-base sm:text-lg shrink-0 whitespace-nowrap">
                                        Rp {{ number_format($item['total'], 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <form action="{{ route('checkout.store') }}" method="POST" class="flex flex-col gap-3">
                    @csrf
                    <input type="hidden" name="address_id" id="address-id-input" value="{{ $selectedAddressId }}">
                    <input type="hidden" name="send_type" id="send-type-input" value="pickUp">
                    <input type="hidden" name="payment_bank" id="payment-bank-input" value="bca">
                    <input type="hidden" name="actual_periode" id="actual-periode-input" value="">

                    {{-- ===== SECTION 2: Metode Kirim ===== --}}
                    <div class="bg-white rounded-[20px] px-8 py-7 shadow-[0_3px_16px_rgba(0,0,0,0.06)]">
                        <h2 class="font-['Playfair_Display'] font-bold text-[#3D2B1F] text-xl mb-4">
                            Metode Pengiriman
                        </h2>
                        <div id="open-shipping-modal"
                            class="flex items-center justify-between pb-4 border-b border-[#E8E0D4] cursor-pointer hover:opacity-75 transition-opacity">
                            <span id="selected-shipping-label" class="font-glacial text-[#3D2B1F] text-sm">Ambil Sendiri</span>
                            <svg class="w-4 h-4 text-[#7A8C5C]" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>

                    {{-- ===== SECTION 2.5: Tanggal Pengambilan ===== --}}
                    <div class="bg-white rounded-[20px] px-8 py-7 shadow-[0_3px_16px_rgba(0,0,0,0.06)]">
                        <h2 class="font-['Playfair_Display'] font-bold text-[#3D2B1F] text-xl mb-4">
                            Tanggal Pengambilan/Pengiriman
                        </h2>
                        <div id="open-date-modal"
                            class="flex items-center justify-between pb-4 border-b border-[#E8E0D4] cursor-pointer hover:opacity-75 transition-opacity">
                            <span id="selected-date-label" class="font-glacial text-[#6B4C3B] text-sm">Pilih tanggal...</span>
                            <svg class="w-5 h-5 text-[#7A8C5C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-xs text-[#9A8878] mt-2">
                            Pilih tanggal yang tersedia untuk pengambilan atau pengiriman pesanan Anda
                        </p>
                    </div>

                    {{-- ===== SECTION 3: Metode Pembayaran ===== --}}
                    <div class="bg-white rounded-[20px] px-8 py-7 shadow-[0_3px_16px_rgba(0,0,0,0.06)]">

                        <h2 class="font-['Playfair_Display'] font-bold text-[#3D2B1F] text-xl mb-4">
                            Metode Pembayaran Midtrans
                        </h2>

                        <div class="flex items-center justify-between pb-4 border-b border-[#E8E0D4]">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#7A8C5C] rounded-[10px] flex items-center justify-center shrink-0">
                                    <span class="text-white font-bold text-[10px] tracking-[0.3em]">M</span>
                                </div>
                                <div>
                                    <p class="font-glacial text-[#3D2B1F] text-sm font-bold">Snap payment page</p>
                                    <p class="font-glacial text-[#9A8878] text-xs">Anda akan diarahkan ke halaman pembayaran Midtrans.</p>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- ===== SECTION 4: Total + Bayar ===== --}}
                    <div class="bg-white rounded-[20px] px-8 py-7 shadow-[0_3px_16px_rgba(0,0,0,0.06)]">

                        {{-- <div class="flex items-center justify-between mb-5">
                            <p class="font-['Playfair_Display'] font-bold text-[#3D2B1F] text-lg">Tanggal Pengambilan</p>
                            <span class="font-glacial font-bold text-[#3D2B1F] text-base">{{ \Illuminate\Support\Carbon::parse($pickupDate)->format('d m y') }}</span>
                        </div> --}}

                        <div class="flex items-center justify-between mb-7">
                            <p class="font-glacial font-bold text-[#3D2B1F] text-base tracking-widest uppercase">TOTAL</p>
                            <span class="font-glacial font-bold text-[#7A8C5C] text-2xl">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-[#7A8C5C] hover:bg-[#5C6B44] text-white font-glacial font-bold text-sm tracking-widest uppercase px-10 py-3 rounded-full transition-colors duration-200">
                                BAYAR
                            </button>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </main>
    @include('components.modalAlamat')
    @include('components.modalpembayaran')
    @include('components.modalpengiriman')
    @include('components.modalTanggal')

    {{-- Modal logic checkout: inline agar tidak tergantung Vite bundle yang di production bisa telat/gagal load. --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (!document.getElementById('payment-modal')) return;

            // ===== MODAL ALAMAT =====
            const addressModal = document.getElementById('address-modal');
            const addressModalBox = document.getElementById('address-modal-box');
            const closeAddressBtn = document.getElementById('close-address-modal');
            const nameEl = document.getElementById('address-name');
            const detailEl = document.getElementById('address-detail');
            const openAddressBtn = document.getElementById('open-address-modal');

            if (openAddressBtn && addressModal && addressModalBox) {
                openAddressBtn.addEventListener('click', () => {
                    addressModal.classList.remove('opacity-0', 'pointer-events-none');
                    addressModalBox.classList.remove('scale-95');
                    addressModalBox.classList.add('scale-100');
                });
            }
            if (closeAddressBtn && addressModal && addressModalBox) {
                closeAddressBtn.addEventListener('click', () => {
                    addressModal.classList.add('opacity-0', 'pointer-events-none');
                    addressModalBox.classList.remove('scale-100');
                    addressModalBox.classList.add('scale-95');
                });
            }

            document.querySelectorAll('.address-item').forEach((item) => {
                item.addEventListener('click', () => {
                    const name = item.dataset.name;
                    const phone = item.dataset.phone;
                    const address = item.dataset.address;
                    const addressId = item.dataset.addressId;
                    const addressIdInput = document.getElementById('address-id-input');

                    if (nameEl) nameEl.innerHTML = `${name} <span class="font-normal text-[#6B4C3B] ml-2">(${phone})</span>`;
                    if (detailEl) detailEl.textContent = address;
                    if (addressIdInput) addressIdInput.value = addressId || '';

                    if (addressModal && addressModalBox) {
                        addressModal.classList.add('opacity-0', 'pointer-events-none');
                        addressModalBox.classList.remove('scale-100');
                        addressModalBox.classList.add('scale-95');
                    }
                });
            });

            // ===== MODAL PEMBAYARAN =====
            const paymentModal = document.getElementById('payment-modal');
            const paymentModalBox = document.getElementById('payment-modal-box');

            function openPaymentModal() {
                if (!paymentModal || !paymentModalBox) return;
                paymentModal.classList.remove('opacity-0', 'pointer-events-none');
                paymentModal.classList.add('opacity-100');
                paymentModalBox.classList.remove('translate-y-4');
            }
            function closePaymentModal() {
                if (!paymentModal || !paymentModalBox) return;
                paymentModal.classList.add('opacity-0', 'pointer-events-none');
                paymentModal.classList.remove('opacity-100');
                paymentModalBox.classList.add('translate-y-4');
            }

            const openPaymentBtn = document.getElementById('open-payment-modal');
            const closePaymentBtn = document.getElementById('close-payment-modal');
            if (openPaymentBtn) openPaymentBtn.addEventListener('click', openPaymentModal);
            if (closePaymentBtn) closePaymentBtn.addEventListener('click', closePaymentModal);
            if (paymentModal) {
                paymentModal.addEventListener('click', (e) => {
                    if (e.target === paymentModal) closePaymentModal();
                });
            }

            document.querySelectorAll('.bank-option').forEach((btn) => {
                btn.addEventListener('click', () => {
                    const label = btn.querySelector('span:last-child')?.textContent ?? '';
                    const bankLabel = document.getElementById('selected-bank-label');
                    const paymentBankInput = document.getElementById('payment-bank-input');
                    if (bankLabel) bankLabel.textContent = label;
                    if (paymentBankInput) paymentBankInput.value = btn.dataset.bank || 'bca';
                    closePaymentModal();
                });
            });

            // ===== MODAL PENGIRIMAN =====
            const shippingModal = document.getElementById('shipping-modal');
            const shippingModalBox = document.getElementById('shipping-modal-box');

            function openShippingModal() {
                if (!shippingModal || !shippingModalBox) return;
                shippingModal.classList.remove('opacity-0', 'pointer-events-none');
                shippingModal.classList.add('opacity-100');
                shippingModalBox.classList.remove('translate-y-4');
            }
            function closeShippingModal() {
                if (!shippingModal || !shippingModalBox) return;
                shippingModal.classList.add('opacity-0', 'pointer-events-none');
                shippingModal.classList.remove('opacity-100');
                shippingModalBox.classList.add('translate-y-4');
            }

            const openShippingBtn = document.getElementById('open-shipping-modal');
            const closeShippingBtn = document.getElementById('close-shipping-modal');
            if (openShippingBtn) openShippingBtn.addEventListener('click', openShippingModal);
            if (closeShippingBtn) closeShippingBtn.addEventListener('click', closeShippingModal);
            if (shippingModal) {
                shippingModal.addEventListener('click', (e) => {
                    if (e.target === shippingModal) closeShippingModal();
                });
            }

            document.querySelectorAll('.shipping-option').forEach((btn) => {
                btn.addEventListener('click', () => {
                    document.querySelectorAll('.shipping-option').forEach((b) => {
                        const radio = b.querySelector('.shipping-radio');
                        if (!radio) return;
                        radio.classList.remove('border-[#7A8C5C]', 'bg-[#7A8C5C]');
                        radio.classList.add('border-[#D8CFC4]', 'bg-white');
                        radio.innerHTML = '';
                    });

                    const activeRadio = btn.querySelector('.shipping-radio');
                    if (activeRadio) {
                        activeRadio.classList.remove('border-[#D8CFC4]', 'bg-white');
                        activeRadio.classList.add('border-[#7A8C5C]', 'bg-[#7A8C5C]');
                        activeRadio.innerHTML = `<svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>`;
                    }

                    const label = btn.querySelector('span')?.textContent ?? '';
                    const shippingLabel = document.getElementById('selected-shipping-label');
                    const sendTypeInput = document.getElementById('send-type-input');
                    if (shippingLabel) shippingLabel.textContent = label;
                    if (sendTypeInput) sendTypeInput.value = btn.dataset.shipping || 'pickUp';

                    setTimeout(closeShippingModal, 200);
                });
            });
        });
    </script>
@endsection
