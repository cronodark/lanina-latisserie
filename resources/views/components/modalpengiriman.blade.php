{{-- ===== MODAL METODE KIRIM ===== --}}
<div id="shipping-modal"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 opacity-0 pointer-events-none transition-opacity duration-300">

    <div id="shipping-modal-box"
        class="bg-white rounded-[24px] w-full max-w-[380px] mx-4 shadow-[0_20px_60px_rgba(0,0,0,0.15)] translate-y-4 transition-transform duration-300">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 pt-6 pb-4 border-b border-[#E8E0D4]">
            <h3 class="font-['Playfair_Display'] font-bold text-[#3D2B1F] text-lg">Metode Kirim</h3>
            <button id="close-shipping-modal"
                class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-[#F0EDE6] transition-colors text-[#9A8878]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Shipping Options --}}
        <div class="px-6 py-2 flex flex-col divide-y divide-[#F0EDE6]">
            @php
                $shippingOptions = [
                    ['id' => 'pickUp',      'label' => 'Ambil Sendiri'],
                    ['id' => 'kurirEkspedisi',    'label' => 'Kirim Kurir Ekspedisi'],
                    ['id' => 'kurirToko',         'label' => 'Kirim Kurir Toko'],
                ];
            @endphp

            @foreach ($shippingOptions as $i => $option)
                <button
                    class="shipping-option flex items-center justify-between py-4 w-full text-left hover:bg-[#F9F6F2] rounded-[10px] px-2 -mx-2 transition-colors duration-150"
                    data-shipping="{{ $option['id'] }}">
                    <span class="font-glacial text-[#3D2B1F] text-sm">{{ $option['label'] }}</span>

                    {{-- Radio indicator --}}
                    <div class="shipping-radio w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0
                        {{ $i === 0 ? 'border-[#7A8C5C] bg-[#7A8C5C]' : 'border-[#D8CFC4] bg-white' }}">
                        @if ($i === 0)
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        @endif
                    </div>
                </button>
            @endforeach
        </div>

        <div class="pb-4"></div>
    </div>
</div>
