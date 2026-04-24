<div>
    {{-- Select All --}}
    <div class="flex items-center gap-2 mb-3 ml-4">
        <input type="checkbox" id="select-all"
            class="w-5 h-5 accent-[#7A8C5C] cursor-pointer rounded-lg"
            @checked($this->allChecked)
            wire:click="toggleAll({{ $this->allChecked ? 'false' : 'true' }})">
        <label for="select-all" class="text-sm font-glacial text-[#3D2B1F] cursor-pointer">
            Pilih Semua
        </label>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[1fr_480px] gap-6 items-start">

        {{-- ===== LEFT: Cart Items ===== --}}
        <div class="flex flex-col gap-4">

            @forelse ($this->cartItems as $item)
                <div class="bg-[#FFF9F2] rounded-[20px] p-4 flex items-center gap-4 shadow-[0_3px_16px_rgba(0,0,0,0.06)]">

                    {{-- Image --}}
                    <div class="w-[140px] h-[100px] shrink-0 rounded-[14px] overflow-hidden bg-[#EDE8DF]">
                        @if ($item['image'])
                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}"
                                class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-[#B0A090] text-xs font-glacial">
                                No Image
                            </div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <p class="font-['Playfair_Display'] font-bold text-[#3D2B1F] text-lg mb-0.5">
                            {{ $item['name'] }}
                        </p>
                        <p class="font-glacial text-[#6B4C3B] text-sm leading-relaxed mb-3">
                            {{ $item['desc'] }}
                        </p>

                        {{-- Qty --}}
                        <div class="flex items-center gap-2">
                            <div class="flex items-center border border-[#D8CFC4] rounded-lg overflow-hidden bg-[#F5F0E8]">
                                <button wire:click="decrement({{ $item['id'] }})"
                                    class="w-8 h-8 flex items-center justify-center text-[#7A8C5C] hover:bg-[#E8E3DB] transition-colors font-bold text-base">
                                    −
                                </button>
                                <span class="w-8 text-center font-glacial font-bold text-[#3D2B1F] text-sm select-none">
                                    {{ $item['qty'] }}
                                </span>
                                <button wire:click="increment({{ $item['id'] }})"
                                    class="w-8 h-8 flex items-center justify-center text-[#7A8C5C] hover:bg-[#E8E3DB] transition-colors font-bold text-base">
                                    +
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Price + Checkbox --}}
                    <div class="flex flex-col items-end gap-3 shrink-0">
                        <div class="flex items-center gap-2">
                            <input type="checkbox"
                                class="w-5 h-5 accent-[#7A8C5C] cursor-pointer rounded-lg"
                                wire:click="toggleItem({{ $item['id'] }})"
                                @checked($item['checked'])>
                        </div>
                        <div class="bg-[#7A8C5C] text-white font-glacial font-bold text-sm px-5 py-2.5 rounded-full">
                            Rp {{ number_format($item['price'], 0, ',', '.') }}
                        </div>
                    </div>
                </div>

            @empty
                <div class="bg-[#FFF9F2] rounded-[20px] p-10 text-center shadow-[0_3px_16px_rgba(0,0,0,0.06)]">
                    <p class="font-glacial text-[#6B4C3B] text-base">Keranjang Anda masih kosong.</p>
                </div>
            @endforelse

        </div>

        {{-- ===== RIGHT: Order Summary ===== --}}
        <div class="bg-white rounded-[20px] p-7 shadow-[0_3px_16px_rgba(0,0,0,0.06)]">

            <div class="flex flex-col divide-y divide-[#E8E0D4] mb-4">
                @forelse ($this->cartItems as $item)
                    @if ($item['checked'])
                        <div class="py-4 first:pt-0">
                            <div class="flex items-start justify-between mb-1">
                                <p class="font-['Playfair_Display'] font-bold text-[#3D2B1F] text-base">
                                    {{ $item['name'] }}
                                </p>
                                <span class="font-glacial text-[#3D2B1F] text-sm font-bold ml-4 shrink-0">
                                    {{ $item['qty'] }}x
                                </span>
                            </div>
                            <div class="flex items-end justify-between">
                                <p class="font-glacial text-[#6B4C3B] text-sm">{{ $item['desc'] }}</p>
                                <span class="font-glacial font-bold text-[#7A8C5C] text-lg ml-4 shrink-0">
                                    Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @endif
                @empty
                @endforelse
            </div>

            {{-- Total + Checkout --}}
            <div class="flex items-stretch bg-[#7A8C5C] rounded-[14px] p-2 gap-2">
                <div class="flex-1 flex items-center bg-white rounded-[10px] px-5 py-4">
                    <span class="font-['Playfair_Display'] font-bold text-[#3D2B1F] text-lg">
                        Rp {{ number_format($this->total, 0, ',', '.') }}
                    </span>
                </div>
                <button
                    type="button"
                    wire:click="checkoutSelected"
                    class="bg-[#ADC178] hover:bg-[#5C6B44] text-white font-glacial font-bold text-base px-7 py-4 rounded-[10px] transition-colors duration-200 disabled:opacity-50 cursor-pointer disabled:cursor-not-allowed"
                    @disabled($this->total === 0)>
                    Checkout!
                </button>
            </div>

        </div>
    </div>
</div>
