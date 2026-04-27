{{-- ===== MODAL  ===== --}}
<div id="payment-modal"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 opacity-0 pointer-events-none transition-opacity duration-300">

    <div id="payment-modal-box"
        class="bg-white rounded-[24px] w-full max-w-[380px] mx-4 shadow-[0_20px_60px_rgba(0,0,0,0.15)] translate-y-4 transition-transform duration-300">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 pt-6 pb-4 border-b border-[#E8E0D4]">
            <h3 class="font-['Playfair_Display'] font-bold text-[#3D2B1F] text-lg">Bank Transfer</h3>
            <button id="close-payment-modal"
                class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-[#F0EDE6] transition-colors text-[#9A8878]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Bank List --}}
        <div class="px-6 py-3 flex flex-col divide-y divide-[#F0EDE6]">

            @php
                $banks = [
                    ['id' => 'bca', 'label' => 'Bank BCA', 'bg' => '#005BAA', 'text' => 'BCA', 'logo_type' => 'text'],
                    ['id' => 'bni', 'label' => 'Bank BNI', 'bg' => '#F37021', 'text' => 'BNI', 'logo_type' => 'text'],
                    ['id' => 'bri', 'label' => 'Bank BRI', 'bg' => '#004A97', 'text' => 'BRI', 'logo_type' => 'text'],
                    [
                        'id' => 'jago',
                        'label' => 'Bank Jago',
                        'bg' => '#FF6B00',
                        'text' => 'Jago',
                        'logo_type' => 'text',
                    ],
                    [
                        'id' => 'cimb',
                        'label' => 'Bank CIMB Niaga',
                        'bg' => '#CC0001',
                        'text' => 'CIMB',
                        'logo_type' => 'text',
                    ],
                    [
                        'id' => 'mandiri',
                        'label' => 'Bank Mandiri',
                        'bg' => '#003D7C',
                        'text' => 'mandiri',
                        'logo_type' => 'text',
                    ],
                ];
            @endphp

            @foreach ($banks as $bank)
                <button
                    class="bank-option flex items-center gap-4 py-3.5 w-full text-left hover:bg-[#F9F6F2] rounded-[10px] px-2 -mx-2 transition-colors duration-150"
                    data-bank="{{ $bank['id'] }}">
                    <div class="w-12 h-8 rounded-[8px] flex items-center justify-center shrink-0"
                        style="background-color: {{ $bank['bg'] }}">
                        <span class="text-white font-bold text-[10px] tracking-wide">{{ $bank['text'] }}</span>
                    </div>
                    <span class="font-glacial text-[#3D2B1F] text-sm">{{ $bank['label'] }}</span>
                </button>
            @endforeach

        </div>

        <div class="px-6 pb-6 pt-2"></div>
    </div>
</div>
