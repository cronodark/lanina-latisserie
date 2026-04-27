{{-- ===== MODAL PILIH ALAMAT ===== --}}
<div id="address-modal"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 opacity-0 pointer-events-none transition-opacity duration-300">

    <div id="address-modal-box"
        class="bg-white w-full max-w-[420px] mx-4 rounded-[20px] p-6 shadow-[0_20px_60px_rgba(0,0,0,0.2)] transform scale-95 transition-all duration-300">

        <h2 class="font-['Playfair_Display'] text-xl font-bold text-[#3D2B1F] mb-4">
            Pilih Alamat
        </h2>

        <div class="flex flex-col gap-3 max-h-[300px] overflow-y-auto">
            @forelse ($addresses as $addr)
                @php
                    $addressText = collect([
                        $addr->street,
                        'RT '.$addr->rt.'/RW '.$addr->rw,
                        $addr->district,
                        $addr->city,
                        $addr->state,
                        $addr->zip_code,
                    ])->filter()->implode(', ');
                @endphp

                <div class="address-item border border-[#E8E0D4] rounded-[12px] p-4 cursor-pointer hover:border-[#7A8C5C] transition"
                    data-address-id="{{ $addr->id }}"
                    data-name="{{ auth()->user()->name }}"
                    data-phone="{{ auth()->user()->telp }}"
                    data-address="{{ $addressText }}">

                    <p class="font-glacial font-bold text-[#3D2B1F] text-sm">
                        {{ auth()->user()->name }}
                        <span class="font-normal text-[#6B4C3B] ml-2">
                            ({{ auth()->user()->telp }})
                        </span>
                    </p>

                    <p class="font-glacial text-[#6B4C3B] text-sm mt-1">
                        {{ $addressText }}
                    </p>
                </div>
            @empty
                <div class="border border-dashed border-[#E8E0D4] rounded-[12px] p-4 text-center">
                    <p class="font-glacial text-[#6B4C3B] text-sm">
                        Belum ada alamat tersimpan.
                    </p>
                </div>
            @endforelse
        </div>

        <div class="flex justify-end mt-5">
            <button id="close-address-modal"
                class="text-sm font-glacial text-[#7A8C5C] font-bold hover:underline">
                Tutup
            </button>
        </div>

    </div>
</div>
