{{-- ===== MODAL PILIH ALAMAT ===== --}}
<div id="address-modal"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 opacity-0 pointer-events-none transition-opacity duration-300">

    <div id="address-modal-box"
        class="bg-white w-full max-w-[420px] mx-4 rounded-[20px] p-6 shadow-[0_20px_60px_rgba(0,0,0,0.2)] transform scale-95 transition-all duration-300">

        <h2 class="font-['Playfair_Display'] text-xl font-bold text-[#3D2B1F] mb-4">
            Pilih Alamat
        </h2>

        @php
            $addresses = [
                [
                    'name' => 'Maimunah Pasutri Gaje',
                    'phone' => '+62 083 7439 2934',
                    'address' => 'Jl. Raya Persawahan No. 12, Tasikmalaya'
                ],
                [
                    'name' => 'Ario Elnino',
                    'phone' => '+62 812 9999 1111',
                    'address' => 'Jl. Melati No. 10, Karawang'
                ],
            ];
        @endphp

        <div class="flex flex-col gap-3 max-h-[300px] overflow-y-auto">
            @foreach ($addresses as $i => $addr)
                <div class="address-item border border-[#E8E0D4] rounded-[12px] p-4 cursor-pointer hover:border-[#7A8C5C] transition"
                    data-name="{{ $addr['name'] }}"
                    data-phone="{{ $addr['phone'] }}"
                    data-address="{{ $addr['address'] }}">

                    <p class="font-glacial font-bold text-[#3D2B1F] text-sm">
                        {{ $addr['name'] }}
                        <span class="font-normal text-[#6B4C3B] ml-2">
                            ({{ $addr['phone'] }})
                        </span>
                    </p>

                    <p class="font-glacial text-[#6B4C3B] text-sm mt-1">
                        {{ $addr['address'] }}
                    </p>
                </div>
            @endforeach
        </div>

        <div class="flex justify-end mt-5">
            <button id="close-address-modal"
                class="text-sm font-glacial text-[#7A8C5C] font-bold hover:underline">
                Tutup
            </button>
        </div>

    </div>
</div>
