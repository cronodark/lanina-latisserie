<div class="min-h-screen bg-[#FBFEF3]">
    @php
        $activeSidebar = match ($tab) {
            'belum-bayar' => 'belum-bayar',
            'diproses' => 'diproses',
            'diantar' => 'diantar',
            'selesai' => 'selesai',
            default => 'belum-bayar',
        };
    @endphp

    <x-sidebar :active="$activeSidebar" />

    <div class="lg:ml-[270px] flex flex-col gap-6 px-4 sm:px-6 lg:px-10 py-8 lg:py-10">
        <div class="flex items-center gap-4 mb-2">
            <button id="sidebarToggle" class="lg:hidden text-[#3D2B1F]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <div class="py-4 flex items-center justify-between gap-3">
            <button type="button" wire:click="setTab('belum-bayar')"
                class="flex-1 flex items-center justify-center gap-2 px-4 py-3 rounded-full border transition-colors {{ $tab === 'belum-bayar' ? 'border-2 border-[#7A8C5C] bg-[#F0F7E6]' : 'bg-white border-[#E0E0E0] hover:bg-[#F5F5F5]' }}">
                <svg class="w-5 h-5" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M46.25 38.3333H46.2817M3.5 9.83333V54.1667C3.5 57.6645 6.33553 60.5 9.83333 60.5H54.1667C57.6645 60.5 60.5 57.6645 60.5 54.1667V22.5C60.5 19.0022 57.6645 16.1667 54.1667 16.1667L9.83333 16.1667C6.33553 16.1667 3.5 13.3311 3.5 9.83333ZM3.5 9.83333C3.5 6.33553 6.33553 3.5 9.83333 3.5H47.8333M47.8333 38.3333C47.8333 39.2078 47.1245 39.9167 46.25 39.9167C45.3755 39.9167 44.6667 39.2078 44.6667 38.3333C44.6667 37.4589 45.3755 36.75 46.25 36.75C47.1245 36.75 47.8333 37.4589 47.8333 38.3333Z"
                        stroke="#6A7941" stroke-width="7" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span class="font-poppins text-sm {{ $tab === 'belum-bayar' ? 'font-bold text-[#6A7941]' : 'font-semibold text-[#3D2B1F]' }}">
                    Belum Bayar ({{ $this->summary['belum-bayar'] }})
                </span>
            </button>

            <button type="button" wire:click="setTab('diproses')"
                class="flex-1 flex items-center justify-center gap-2 px-4 py-3 rounded-full border transition-colors {{ $tab === 'diproses' ? 'border-2 border-[#7A8C5C] bg-[#F0F7E6]' : 'bg-white border-[#E0E0E0] hover:bg-[#F5F5F5]' }}">
                <svg class="w-5 h-5" viewBox="0 0 67 71" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M60.5162 44.8488C61.8142 43.5508 63.9182 43.5508 65.2162 44.8488C66.5135 46.1469 66.514 48.2511 65.2162 49.5489L53.2487 61.5132C51.9506 62.8109 49.8465 62.8111 48.5486 61.5132L44.5594 57.524C43.2631 56.2259 43.2622 54.1215 44.5594 52.824C45.8575 51.5259 47.9647 51.5259 49.2627 52.824L50.8986 54.4599L60.5162 44.8488ZM6.64756 49.2665L27.6322 61.3833V39.0809L6.64756 26.1688V49.2665ZM9.0203 19.8263L30.9073 33.2935L38.441 28.3987L17.0084 15.2107L9.0203 19.8263ZM23.5521 11.4357L44.6114 24.3933L52.2295 19.4433L30.956 7.16091L23.5521 11.4357ZM61.9152 35.2313C61.9152 37.0667 60.4267 38.5546 58.5914 38.5551C56.7557 38.5551 55.2676 37.067 55.2676 35.2313V25.3995L34.2797 39.0322V61.3833L35.2762 60.8088C36.8659 59.8912 38.8994 60.4364 39.8172 62.026C40.7347 63.6157 40.1896 65.6493 38.6 66.567L32.6178 70.0206C31.5897 70.6135 30.322 70.6141 29.2941 70.0206L1.66189 54.0639C0.634224 53.4703 0.000464205 52.3748 0 51.188V19.2778C0.000229939 18.0906 0.633696 16.9923 1.66189 16.3987L14.7817 8.81956C15.1246 8.51324 15.5248 8.28867 15.9503 8.14766L29.2941 0.445184L29.6901 0.250431C30.6353 -0.139387 31.718 -0.0738496 32.6178 0.445184L60.2533 16.3987C61.2814 16.9923 61.9149 18.0906 61.9152 19.2778V35.2313Z"
                        fill="#6A7941" />
                </svg>
                <span class="font-poppins text-sm {{ $tab === 'diproses' ? 'font-bold text-[#6A7941]' : 'font-semibold text-[#3D2B1F]' }}">
                    Diproses ({{ $this->summary['diproses'] }})
                </span>
            </button>

            <button type="button" wire:click="setTab('diantar')"
                class="flex-1 flex items-center justify-center gap-2 px-4 py-3 rounded-full border transition-colors {{ $tab === 'diantar' ? 'border-2 border-[#7A8C5C] bg-[#F0F7E6]' : 'bg-white border-[#E0E0E0] hover:bg-[#F5F5F5]' }}">
                <svg class="w-5 h-5" viewBox="0 0 71 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M23.075 37.0355C20.7155 37.0363 18.8034 38.9507 18.8034 41.3103C18.8036 43.6697 20.7157 45.581 23.075 45.5819C25.435 45.5819 27.3495 43.6703 27.3498 41.3103C27.3498 38.9502 25.4351 37.0355 23.075 37.0355ZM54.9852 37.0355C52.6251 37.0355 50.7104 38.9502 50.7104 41.3103C50.7106 43.6703 52.6252 45.5819 54.9852 45.5819C57.345 45.5817 59.2565 43.6701 59.2568 41.3103C59.2568 38.9503 57.3452 37.0357 54.9852 37.0355ZM46.1532 34.89C48.1388 32.1631 51.3539 30.3879 54.9852 30.3879C58.6166 30.3881 61.8317 32.163 63.8172 34.89V27.2816L53.385 14.2462H46.1532V34.89ZM70.4648 39.7912C70.4644 43.0706 67.9819 45.7656 64.7943 46.111C63.0173 49.7348 59.2935 52.2293 54.9852 52.2295C50.6763 52.2295 46.9498 49.7355 45.1729 46.111C43.894 45.9719 42.7306 45.4555 41.794 44.673C40.3358 45.6032 38.6108 46.1529 36.7531 46.1532H33.7117C33.4361 46.1531 33.1693 46.1158 32.9132 46.0525C31.1476 49.7079 27.4064 52.2295 23.075 52.2295C18.7437 52.2288 15.0019 49.7081 13.2367 46.0525C12.9816 46.1154 12.7159 46.1531 12.4415 46.1532H9.40007C4.20894 46.1524 0.000854112 41.9442 0 36.7531V9.40007C0.000801149 4.20891 4.20891 0.00080113 9.40007 0H36.7531C41.3277 0.000753714 45.1334 3.26983 45.9746 7.59861H53.5246C55.2153 7.59868 56.8253 8.27011 58.0104 9.44876L58.494 9.98433L69.0691 23.208C69.9713 24.336 70.4647 25.7365 70.4648 27.181V39.7912ZM6.64756 36.7531C6.64842 38.2729 7.88029 39.5048 9.40007 39.5056H12.3084C13.1686 34.3335 17.659 30.3887 23.075 30.3879C28.4916 30.3879 32.9845 34.333 33.8448 39.5056H36.7531C38.1786 39.5048 39.3521 38.4209 39.4926 37.0322L39.5056 36.7531V9.40007C39.5048 7.88029 38.2729 6.64842 36.7531 6.64756H9.40007C7.88026 6.64836 6.64836 7.88026 6.64756 9.40007V36.7531Z"
                        fill="#6A7941" />
                </svg>
                <span class="font-poppins text-sm {{ $tab === 'diantar' ? 'font-bold text-[#6A7941]' : 'font-semibold text-[#3D2B1F]' }}">
                    Diantar/Siap Diambil ({{ $this->summary['diantar'] }})
                </span>
            </button>

            <button type="button" wire:click="setTab('selesai')"
                class="flex-1 flex items-center justify-center gap-2 px-4 py-3 rounded-full border transition-colors {{ $tab === 'selesai' ? 'border-2 border-[#7A8C5C] bg-[#F0F7E6]' : 'bg-white border-[#E0E0E0] hover:bg-[#F5F5F5]' }}">
                <svg class="w-5 h-5" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 29.914C0 13.393 13.393 0 29.914 0C34.6024 0 39.0462 1.08043 43.0029 3.00855C44.3531 3.66648 44.9143 5.29448 44.2564 6.64461C43.5985 7.99473 41.9705 8.55599 40.6204 7.89811C37.3896 6.32374 33.759 5.43892 29.914 5.43892C16.3968 5.43892 5.43892 16.3968 5.43892 29.914C5.43892 43.4313 16.3968 54.3892 29.914 54.3892C43.4313 54.3892 54.3892 43.4313 54.3892 29.914C54.3892 28.4121 55.6067 27.1946 57.1086 27.1946C58.6105 27.1946 59.8281 28.4121 59.8281 29.914C59.8281 46.4351 46.4351 59.8281 29.914 59.8281C13.393 59.8281 0 46.4351 0 29.914ZM50.0869 10.9947C51.1489 9.93267 52.8704 9.93267 53.9324 10.9947C54.9944 12.0567 54.9944 13.7782 53.9324 14.8402L30.1371 38.6354C29.0751 39.6974 27.3536 39.6974 26.2916 38.6354L19.493 31.8368C18.431 30.7748 18.431 29.0533 19.493 27.9913C20.555 26.9293 22.2765 26.9293 23.3385 27.9913L28.2144 32.8672L50.0869 10.9947Z"
                        fill="#6A7941" />
                </svg>
                <span class="font-poppins text-sm {{ $tab === 'selesai' ? 'font-bold text-[#6A7941]' : 'font-semibold text-[#3D2B1F]' }}">
                    Selesai ({{ $this->summary['selesai'] }})
                </span>
            </button>
        </div>

        <div class="bg-[#BB9457] rounded-[24px] px-6 sm:px-8 lg:px-10 py-6 sm:py-8 flex items-center gap-5">
            <svg class="w-14 h-14 text-white shrink-0" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M46.25 38.3333H46.2817M3.5 9.83333V54.1667C3.5 57.6645 6.33553 60.5 9.83333 60.5H54.1667C57.6645 60.5 60.5 57.6645 60.5 54.1667V22.5C60.5 19.0022 57.6645 16.1667 54.1667 16.1667L9.83333 16.1667C6.33553 16.1667 3.5 13.3311 3.5 9.83333ZM3.5 9.83333C3.5 6.33553 6.33553 3.5 9.83333 3.5H47.8333M47.8333 38.3333C47.8333 39.2078 47.1245 39.9167 46.25 39.9167C45.3755 39.9167 44.6667 39.2078 44.6667 38.3333C44.6667 37.4589 45.3755 36.75 46.25 36.75C47.1245 36.75 47.8333 37.4589 47.8333 38.3333Z" stroke="white" stroke-width="7" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <h1 class="font-glacial text-white text-3xl sm:text-4xl">
                {{ $this->tabTitle() }}
            </h1>
        </div>

        @if (session()->has('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-[24px] px-6 py-6 card-shadow flex flex-col gap-4">
            @forelse ($this->orders as $order)
                <div class="bg-[#FAF6EF] rounded-[20px] overflow-hidden flex flex-col md:flex-row">
                    <div class="w-full md:w-[250px] shrink-0 aspect-[4/3] p-4">
                        <img src="{{ $this->orderImage($order) }}" alt="{{ $this->orderTitle($order) }}"
                            class="w-full h-full object-cover rounded-[20px]">
                    </div>

                    <div class="flex-1 px-6 py-6 flex flex-col justify-between">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-start justify-between gap-4">
                                    <h3 class="font-['Playfair_Display'] font-bold text-[#3D2B1F] text-2xl mb-2">
                                        {{ $this->orderTitle($order) }}
                                    </h3>
                                    <span class="font-poppins font-bold text-[#3D2B1F] text-xl shrink-0">
                                        {{ $this->orderQty($order) }}x
                                    </span>
                                </div>
                                <p class="font-poppins text-[#6B4C3B] text-base leading-relaxed">
                                    {{ $this->orderDescription($order) }}
                                </p>
                                <p class="mt-2 text-sm font-poppins text-[#7A8C5C] uppercase tracking-wide">
                                    Status: {{ str_replace('_', ' ', $order->status) }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-5 gap-3 flex-wrap">
                            <p class="font-poppins font-bold text-[#3D2B1F] text-lg">
                                Total: <span class="text-[#6A7941] text-xl ml-1">Rp {{ number_format($this->orderTotal($order), 0, ',', '.') }}</span>
                            </p>

                            <div class="flex items-center gap-3">
                                <button type="button" wire:click="openDetail({{ $order->id }})"
                                    class="border border-[#7A8C5C] text-[#7A8C5C] hover:bg-[#F0F7E6] font-poppins font-semibold text-sm px-6 py-2.5 rounded-full transition-colors">
                                    Detail
                                </button>

                                @if ($tab === 'belum-bayar')
                                    <button type="button" wire:click="cancel({{ $order->id }})"
                                        class="bg-red-400 hover:bg-red-500 text-white font-poppins font-semibold text-sm px-6 py-2.5 rounded-full transition-colors">
                                        Batal
                                    </button>
                                    <button type="button" wire:click="pay({{ $order->id }})"
                                        class="bg-[#7A8C5C] hover:bg-[#5C6B44] text-white font-poppins font-semibold text-sm px-6 py-2.5 rounded-full transition-colors">
                                        Bayar
                                    </button>
                                @endif

                                @if ($tab === 'diantar')
                                    <button type="button" wire:click="confirmReceived({{ $order->id }})"
                                        class="bg-[#7A8C5C] hover:bg-[#5C6B44] text-white font-poppins font-semibold text-sm px-6 py-2.5 rounded-full transition-colors">
                                        Konfirmasi
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <p class="font-poppins text-[#6B4C3B] text-base">Belum ada pesanan pada tab ini.</p>
                </div>
            @endforelse
        </div>
    </div>

    @if ($this->showDetailModal && $this->selectedOrder)
        @php
            $selectedOrder = $this->selectedOrder;
            $shippingMethodLabel = match ($selectedOrder->send_type ?? null) {
                'pickUp' => 'Ambil Sendiri',
                'kurirEkspedisi' => 'Kirim Kurir Ekspedisi',
                'kurirToko' => 'Kirim Kurir Toko',
                default => $selectedOrder->send_type ?? '-',
            };

            $expeditionLabel = match (strtolower((string) ($selectedOrder->choosen_expedition ?? ''))) {
                'jne' => 'JNE',
                'pos' => 'POS Indonesia',
                'tiki' => 'TIKI',
                '' => '-',
                default => strtoupper((string) $selectedOrder->choosen_expedition),
            };
        @endphp

        <div class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6">
            <div class="absolute inset-0 bg-black/50" wire:click="closeDetailModal"></div>

            <div class="relative z-10 w-full max-w-3xl max-h-[90vh] overflow-y-auto rounded-[28px] bg-[#FFFDF8] shadow-2xl border border-white/70">
                <div class="flex items-center justify-between gap-4 px-6 sm:px-8 py-5 border-b border-[#E7DDCF]">
                    <div>
                        <p class="text-sm font-poppins text-[#7A8C5C] uppercase tracking-[0.18em]">Detail Preorder</p>
                        <h2 class="font-['Playfair_Display'] font-bold text-[#3D2B1F] text-2xl mt-1">
                            Pre Order #{{ $selectedOrder->id }}
                        </h2>
                    </div>

                    <button type="button" wire:click="closeDetailModal"
                        class="w-10 h-10 rounded-full bg-[#F5F1EA] text-[#3D2B1F] hover:bg-[#E8E0D4] transition-colors flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="px-6 sm:px-8 py-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="rounded-2xl bg-[#F8F4EC] p-4 border border-[#E7DDCF] md:col-span-2">
                            <p class="text-xs uppercase tracking-[0.18em] text-[#7A8C5C] font-semibold">Status Pesanan</p>
                            <p class="mt-2 font-poppins font-bold text-[#3D2B1F] text-lg">
                                {{ str_replace('_', ' ', $selectedOrder->status ?? '-') }}
                            </p>
                        </div>
                        <div class="rounded-2xl bg-[#F8F4EC] p-4 border border-[#E7DDCF]">
                            <p class="text-xs uppercase tracking-[0.18em] text-[#7A8C5C] font-semibold">Metode Pengiriman</p>
                            <p class="mt-2 font-poppins font-bold text-[#3D2B1F] text-lg">
                                {{ $shippingMethodLabel }}
                            </p>
                        </div>
                        <div class="rounded-2xl bg-[#F8F4EC] p-4 border border-[#E7DDCF]">
                            <p class="text-xs uppercase tracking-[0.18em] text-[#7A8C5C] font-semibold">Nomor Resi</p>
                            <p class="mt-2 font-poppins font-bold text-[#3D2B1F] text-lg break-words">
                                {{ $selectedOrder->tracking_number ?: '-' }}
                            </p>
                        </div>
                        <div class="rounded-2xl bg-[#F8F4EC] p-4 border border-[#E7DDCF] md:col-span-2">
                            <p class="text-xs uppercase tracking-[0.18em] text-[#7A8C5C] font-semibold">Ekspedisi Digunakan</p>
                            <p class="mt-2 font-poppins font-bold text-[#3D2B1F] text-lg">
                                {{ $expeditionLabel }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="rounded-2xl bg-white p-5 border border-[#E7DDCF]">
                            <p class="text-xs uppercase tracking-[0.18em] text-[#7A8C5C] font-semibold mb-3">Penerima</p>
                            <p class="font-poppins font-bold text-[#3D2B1F]">{{ auth()->user()->name }}</p>
                            <p class="font-poppins text-[#6B4C3B] mt-1">{{ auth()->user()->telp }}</p>
                        </div>
                        <div class="rounded-2xl bg-white p-5 border border-[#E7DDCF]">
                            <p class="text-xs uppercase tracking-[0.18em] text-[#7A8C5C] font-semibold mb-3">Alamat Pengiriman</p>
                            @if ($selectedOrder->address)
                                <p class="font-poppins text-[#3D2B1F] leading-relaxed">
                                    {{ $selectedOrder->address->street }}, {{ $selectedOrder->address->district }}, {{ $selectedOrder->address->city }}, {{ $selectedOrder->address->state }} {{ $selectedOrder->address->zip_code }}, RT {{ $selectedOrder->address->rt }}/RW {{ $selectedOrder->address->rw }}
                                    @if ($selectedOrder->address->notes)
                                        . {{ $selectedOrder->address->notes }}
                                    @endif
                                </p>
                            @else
                                <p class="font-poppins text-[#6B4C3B]">Alamat tidak tersedia.</p>
                            @endif
                        </div>
                    </div>

                    <div class="rounded-2xl bg-white p-5 border border-[#E7DDCF]">
                        <p class="text-xs uppercase tracking-[0.18em] text-[#7A8C5C] font-semibold mb-4">Rincian Item</p>

                        <div class="space-y-4">
                            @foreach ($selectedOrder->detailPreOrders as $detail)
                                @php
                                    $item = $detail->type === 'promo' ? $detail->promo : $detail->product;
                                @endphp

                                <div class="flex items-center justify-between gap-4 pb-4 border-b border-[#F0E7DA] last:border-b-0 last:pb-0">
                                    <div class="flex items-center gap-4 min-w-0">
                                        <div class="w-14 h-14 rounded-xl overflow-hidden bg-[#F6F1E8] shrink-0">
                                            @if ($item && !empty($item->image))
                                                <img src="{{ $item->image }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-[#B79F87] text-xs">No Image</div>
                                            @endif
                                        </div>

                                        <div class="min-w-0">
                                            <p class="font-poppins font-semibold text-[#3D2B1F] truncate">
                                                {{ $item->name ?? 'Item tidak tersedia' }}
                                            </p>
                                            <p class="font-poppins text-sm text-[#6B4C3B]">
                                                {{ ucfirst($detail->type) }} x {{ $detail->quantity }}
                                            </p>
                                        </div>
                                    </div>

                                    <p class="font-poppins font-bold text-[#7A8C5C] shrink-0">
                                        Rp {{ number_format((int) ($item->price ?? 0) * (int) $detail->quantity, 0, ',', '.') }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center justify-between rounded-2xl bg-[#7A8C5C] px-5 py-4 text-white">
                        <span class="font-poppins font-semibold">Total</span>
                        <span class="font-poppins font-bold text-xl">Rp {{ number_format($this->orderTotal($selectedOrder), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('sidebarToggle');
            const overlay = document.getElementById('sidebarOverlay');

            if (toggle && sidebar && overlay) {
                toggle.addEventListener('click', () => {
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.remove('hidden');
                });

                overlay.addEventListener('click', () => {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                });
            }
        }
    </script>
</div>
