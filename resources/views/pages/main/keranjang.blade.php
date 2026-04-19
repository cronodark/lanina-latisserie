@extends('layouts.app')

@section('title', 'Keranjang')

@section('content')

    <x-navbar />

    <template id="summary-template">
        <div class="py-4">
            <div class="flex items-start justify-between mb-1">
                <p class="item-name font-['Playfair_Display'] font-bold text-[#3D2B1F] text-base"></p>
                <span class="item-qty font-glacial text-[#3D2B1F] text-sm font-bold ml-4"></span>
            </div>
            <div class="flex items-end justify-between">
                <p class="item-desc font-glacial text-[#6B4C3B] text-sm"></p>
                <span class="item-total font-glacial font-bold text-[#7A8C5C] text-lg ml-4"></span>
            </div>
        </div>
    </template>

    <main
        class="min-h-screen bg-[#F0EAD2]
    px-4 sm:px-6 md:px-10 lg:px-16 xl:px-24 2xl:px-32
    pt-16 sm:pt-20 md:pt-24 xl:pt-28
    pb-12">
        <div class="max-w-[1100px] mx-auto">

            <h1 class="font-['Playfair_Display'] text-5xl font-bold text-[#3D2B1F] mb-10">
                Keranjang Anda
            </h1>
            <div class="flex items-center gap-2 mb-3 ml-4">
                <input type="checkbox" id="select-all" class="w-5 h-5 accent-[#7A8C5C] cursor-pointer rounded-lg">
                <label for="select-all" class="text-sm font-glacial text-[#3D2B1F] cursor-pointer">
                    Pilih Semua
                </label>
            </div>

            {{-- MAIN CONTENT --}}
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_480px] gap-6 items-start">

                {{-- ===== LEFT: Cart Items ===== --}}

                <div class="flex flex-col gap-4">
                    @php
                        $cartItems = [
                            [
                                'image' => '/images/1.png',
                                'name' => 'Lorem Ipsum',
                                'desc' => 'Lorem Ipsum is simply dummy text of the printing',
                                'price' => '50.000',
                            ],
                            [
                                'image' => '/images/2.png',
                                'name' => 'Lorem Ipsum',
                                'desc' => 'Lorem Ipsum is simply dummy text of the printing',
                                'price' => '50.000',
                            ],
                            [
                                'image' => '/images/3.png',
                                'name' => 'Lorem Ipsum',
                                'desc' => 'Lorem Ipsum is simply dummy text of the printing',
                                'price' => '50.000',
                            ],
                        ];
                    @endphp


                    @foreach ($cartItems as $i => $item)
                        <div class="cart-item bg-[#FFF9F2] rounded-[20px] p-4 flex items-center gap-4 shadow-[0_3px_16px_rgba(0,0,0,0.06)]"
                            data-price="{{ str_replace('.', '', $item['price']) }}">

                            {{-- Image --}}
                            <div class="w-[140px] h-[100px] shrink-0 rounded-[14px] overflow-hidden">
                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}"
                                    class="w-full h-full object-cover">
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <p class="item-name font-['Playfair_Display'] font-bold text-[#3D2B1F] text-lg mb-0.5">
                                    {{ $item['name'] }}
                                </p>
                                <p class="item-desc font-glacial text-[#6B4C3B] text-sm leading-relaxed mb-3">
                                    {{ $item['desc'] }}
                                </p>

                                {{-- Qty --}}
                                <div class="flex items-center gap-2">
                                    <div
                                        class="flex items-center border border-[#D8CFC4] rounded-lg overflow-hidden bg-[#F5F0E8]">
                                        <button
                                            class="qty-minus w-8 h-8 flex items-center justify-center text-[#7A8C5C] hover:bg-[#E8E3DB] transition-colors font-bold text-base">
                                            −
                                        </button>
                                        <span
                                            class="qty-value w-8 text-center font-glacial font-bold text-[#3D2B1F] text-sm select-none"
                                            data-id="{{ $i }}">
                                            2
                                        </span>
                                        <button
                                            class="qty-plus w-8 h-8 flex items-center justify-center text-[#7A8C5C] hover:bg-[#E8E3DB] transition-colors font-bold text-base">
                                            +
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Price + Radio --}}
                            <div class="flex flex-col items-end gap-3 shrink-0">
                                <input type="checkbox" name="selected-item" value="{{ $i }}"
                                    class="item-checkbox w-5 h-5 accent-[#7A8C5C] cursor-pointer rounded-lg">
                                <div
                                    class="bg-[#7A8C5C] text-white font-glacial font-bold text-sm px-5 py-2.5 rounded-full">
                                    Rp {{ $item['price'] }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- ===== RIGHT: Order Summary ===== --}}
                <div class="bg-white rounded-[20px] p-7 shadow-[0_3px_16px_rgba(0,0,0,0.06)]">
                    <div id="summary-list" class="flex flex-col divide-y divide-[#E8E0D4]">
                        {{-- @foreach ($summaryItems as $summary)
                            <div class="py-4 first:pt-0">
                                <div class="flex items-start justify-between mb-1">
                                    <p class="font-['Playfair_Display'] font-bold text-[#3D2B1F] text-base">
                                        {{ $summary['name'] }}
                                    </p>
                                    <span class="font-glacial text-[#3D2B1F] text-sm font-bold ml-4 shrink-0">
                                        {{ $summary['qty'] }}
                                    </span>
                                </div>
                                <div class="flex items-end justify-between">
                                    <p class="font-glacial text-[#6B4C3B] text-sm">{{ $summary['desc'] }}</p>
                                    <span class="font-glacial font-bold text-[#7A8C5C] text-lg ml-4 shrink-0">
                                        Rp {{ $summary['total'] }}
                                    </span>
                                </div>
                            </div>
                        @endforeach --}}
                    </div>

                    {{-- Date Picker --}}
                    <div id="date-wrapper" class="relative inline-flex items-center opacity-50 pointer-events-none mb-3">
                        <input type="date" id="date-input" class="absolute inset-0 opacity-0 cursor-pointer z-10">
                        <div
                            class="inline-flex items-center gap-2 border border-[#D8CFC4] rounded-full px-5 py-2.5 bg-white">
                            <span id="date-text" class="font-glacial text-[#3D2B1F] text-sm">
                                Pilih Tanggal
                            </span>

                            <svg class="w-4 h-4 text-[#7A8C5C]" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>

                    </div>

                    {{-- Price + Checkout --}}
                    <div class="flex items-stretch bg-[#7A8C5C] rounded-[14px] p-2 gap-2">
                        <div class="flex-1 flex items-center bg-white rounded-[10px] px-5 py-4">
                            <span id="total-price" class="font-['Playfair_Display'] font-bold text-[#3D2B1F] text-lg">Rp
                                0</span>
                        </div>
                        <button
                            class="bg-[#ADC178] hover:bg-[#5C6B44] text-white font-glacial font-bold text-base px-7 py-4 rounded-[10px] transition-colors duration-200">
                            Checkout!
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <x-footer />

    <script>
        const selectAll = document.getElementById('select-all');
        const items = document.querySelectorAll('.item-checkbox');

        // UPDATE SUMMARY FUNCTION
        function updateSummary() {
            const summaryList = document.getElementById('summary-list');
            const totalEl = document.getElementById('total-price');
            const dateWrapper = document.getElementById('date-wrapper'); // tambah ini

            summaryList.innerHTML = '';
            let total = 0;
            let hasChecked = false; // tambah ini

            document.querySelectorAll('.item-checkbox:checked').forEach(item => {
                hasChecked = true; // tambah ini
                const card = item.closest('.cart-item');

                const name = card.querySelector('.item-name').textContent;
                const desc = card.querySelector('.item-desc').textContent;
                const qty = parseInt(card.querySelector('.qty-value').textContent);
                const price = parseInt(card.dataset.price);

                const itemTotal = price * qty;
                total += itemTotal;

                const template = document.getElementById('summary-template');
                const clone = template.content.cloneNode(true);

                clone.querySelector('.item-name').textContent = name;
                clone.querySelector('.item-desc').textContent = desc;
                clone.querySelector('.item-qty').textContent = qty + 'x';
                clone.querySelector('.item-total').textContent =
                    'Rp ' + itemTotal.toLocaleString('id-ID');

                summaryList.appendChild(clone);
            });

            totalEl.textContent = 'Rp ' + total.toLocaleString('id-ID');

            // Enable/disable date picker
            if (hasChecked) {
                dateWrapper.classList.remove('opacity-50', 'pointer-events-none');
            } else {
                dateWrapper.classList.add('opacity-50', 'pointer-events-none');
            }
        }

        // QTY BUTTON
        document.querySelectorAll('.qty-minus, .qty-plus').forEach(btn => {
            btn.addEventListener('click', () => {
                const row = btn.closest('.flex.items-center.gap-2');
                const valEl = row.querySelector('.qty-value');

                let qty = parseInt(valEl.textContent);

                if (btn.classList.contains('qty-minus') && qty > 1) qty--;
                if (btn.classList.contains('qty-plus')) qty++;

                valEl.textContent = qty;

                updateSummary();
            });
        });

        // SELECT ALL
        selectAll.addEventListener('change', function() {
            items.forEach(item => {
                item.checked = selectAll.checked;
            });

            updateSummary();
        });

        // SINGLE CHECKBOX
        items.forEach(item => {
            item.addEventListener('change', function() {
                const allChecked = [...items].every(i => i.checked);
                selectAll.checked = allChecked;

                updateSummary();
            });
        });


        const dateInput = document.getElementById('date-input');
        const dateText = document.getElementById('date-text');

        dateInput.addEventListener('change', function() {
            if (this.value) {
                const date = new Date(this.value);
                dateText.textContent = date.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
            }
        });
    </script>

@endsection
