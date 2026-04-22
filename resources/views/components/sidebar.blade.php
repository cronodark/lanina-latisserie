@props(['active' => 'dashboard'])

{{-- TOGGLE BUTTON --}}
<button id="sidebarToggle"
    class="lg:hidden fixed top-4 left-4 z-50 bg-[#ADC178] text-white p-2 rounded-md shadow">
    ☰
</button>

{{-- OVERLAY --}}
<div id="sidebarOverlay"
    class="fixed inset-0 bg-black/30 z-30 hidden lg:hidden"></div>

<aside id="sidebar"
    class="fixed top-0 left-0 z-40 w-[230px] min-h-screen bg-[#A9BC7A]
           flex flex-col px-6 py-8
           transform -translate-x-full lg:translate-x-0
           transition-transform duration-300">

    {{-- Logo --}}
    <div class="mb-10">
        <img src="/images/logo.png" class="w-[140px]">
    </div>

    <nav class="flex flex-col gap-7 text-sm">

        {{-- Dashboard --}}
        <a href="#"
            class="font-semibold transition
            {{ $active === 'dashboard'
                ? 'text-white'
                : 'text-white/80 hover:text-white' }}">
            Dashboard
        </a>

        {{-- Alamat --}}
        <div>
            <p class="text-white/90 font-semibold mb-2">Alamat Saya</p>

            <div class="flex flex-col gap-2 ml-2">
                <a href="#"
                    class="transition
                    {{ $active === 'daftar-alamat'
                        ? 'text-white font-medium'
                        : 'text-white/70 hover:text-white' }}">
                    Daftar Alamat
                </a>

                <a href="#"
                    class="transition
                    {{ $active === 'tambah-alamat'
                        ? 'text-white font-medium'
                        : 'text-white/70 hover:text-white' }}">
                    Tambah Alamat
                </a>
            </div>
        </div>

        {{-- Pesanan --}}
        <div>
            <p class="text-white/90 font-semibold mb-2">Pesanan Saya</p>

            <div class="flex flex-col gap-2 ml-2">
                @foreach ([
                    'belum-bayar' => 'Belum Bayar',
                    'dikemas' => 'Dikemas',
                    'diantar' => 'Diantar',
                    'selesai' => 'Selesai'
                ] as $key => $label)

                <a href="#"
                    class="transition
                    {{ $active === $key
                        ? 'text-white font-medium'
                        : 'text-white/70 hover:text-white' }}">
                    {{ $label }}
                </a>

                @endforeach
            </div>
        </div>

    </nav>
</aside>

{{-- SCRIPT --}}
<script>
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
</script>
