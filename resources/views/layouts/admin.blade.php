<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin' }} — Lanina Patisserie</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Gloock&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        glacial: ['Glacial'],
                        poppins: ['Poppins', 'sans-serif'],
                        gloock: ['Gloock', 'serif'],
                    }
                }
            }
        }
    </script>
    <style>
        @font-face {
            font-family: 'Glacial';
            src: url('/fonts/GlacialIndifference-Regular.otf') format('opentype');
            font-weight: 400;
        }
        @font-face {
            font-family: 'Glacial';
            src: url('/fonts/GlacialIndifference-Bold.otf') format('opentype');
            font-weight: 700;
        }
        body { font-family: 'Poppins', sans-serif; }
    </style>
    <style>
        /* Sidebar collapse styles */
        #sidebar { transition: width .25s ease, padding .25s ease; }
        /* wider collapsed width to keep icons/spacing readable */
        #sidebar.collapsed { width: 96px !important; }
        #mainWrapper { transition: margin-left .25s ease; }
        #mainWrapper.collapsed { margin-left: 96px !important; }
        /* hide textual labels when collapsed */
        #sidebar.collapsed .hide-text { display: none; }
        /* make links align and comfortable when collapsed */
        #sidebar nav a,
        #sidebar nav a *,
        #sidebar button {
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        #sidebar nav a { padding-left: .75rem; padding-right: .75rem; }
        #sidebar.collapsed nav a,
        #sidebar.collapsed button {
            justify-content: center;
            padding-left: .25rem !important;
            padding-right: .25rem !important;
        }
        #sidebar nav a svg,
        #sidebar button svg {
            width: 18px; height: 18px;
        }
        #sidebar.collapsed svg { width: 20px; height: 20px; }
    </style>
     @stack('styles')
</head>

<body class="bg-[#F5F5F0] min-h-screen">

    {{-- Sidebar --}}
    @include('components.sidebar')

    {{-- Main wrapper --}}
    <div id="mainWrapper" class="ml-[240px] min-h-screen flex flex-col">

        {{-- Topbar --}}
        <header class="sticky top-0 z-30 bg-white border-b border-gray-100 px-8 py-3 flex items-center justify-between">

            {{-- Hamburger + Search --}}
            <div class="flex items-center gap-4">
                <button class="text-gray-500 hover:text-gray-700" id="hamburger">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" placeholder="Search"
                        class="pl-9 pr-4 py-2 bg-gray-100 rounded-full text-sm text-gray-700 outline-none focus:bg-gray-200 transition w-64">
                </div>
            </div>

            {{-- User info --}}
            <div class="flex items-center gap-3">
                <img src="{{ !empty($user->photo) ? $user->photo : asset('images/avatar.svg') }}" alt="Admin"
                    class="w-9 h-9 rounded-full object-cover border-2 border-[#8A9E5B]">
                <div class="text-right">
                    <p class="text-sm font-semibold text-gray-800 leading-none">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Admin</p>
                </div>
            </div>
        </header>

        {{-- Page content --}}
        <main class="flex-1 px-8 py-8">
            @yield('content')
        </main>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const btn = document.getElementById('hamburger');
            const sidebar = document.getElementById('sidebar');
            const main = document.getElementById('mainWrapper');
            const collapsed = localStorage.getItem('sidebar-collapsed') === 'true';
            if(sidebar && main){
                if(collapsed){ sidebar.classList.add('collapsed'); main.classList.add('collapsed'); }
                btn?.addEventListener('click', function(){
                    sidebar.classList.toggle('collapsed');
                    main.classList.toggle('collapsed');
                    localStorage.setItem('sidebar-collapsed', sidebar.classList.contains('collapsed'));
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
