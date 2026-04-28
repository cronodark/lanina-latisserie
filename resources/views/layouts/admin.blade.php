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
</head>

<body class="bg-[#F5F5F0] min-h-screen">

    {{-- Sidebar --}}
    @include('components.sidebar')

    {{-- Main wrapper --}}
    <div class="ml-[240px] min-h-screen flex flex-col">

        {{-- Topbar --}}
        <header class="sticky top-0 z-30 bg-white border-b border-gray-100 px-8 py-3 flex items-center justify-between">

            {{-- Hamburger + Search --}}
            <div class="flex items-center gap-4">
                <button class="text-gray-500 hover:text-gray-700">
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
                <img src="https://i.pravatar.cc/40?img=47" alt="Admin"
                    class="w-9 h-9 rounded-full object-cover border-2 border-[#8A9E5B]">
                <div class="text-right">
                    <p class="text-sm font-semibold text-gray-800 leading-none">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Admin</p>
                </div>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </header>

        {{-- Page content --}}
        <main class="flex-1 px-8 py-8">
            @yield('content')
        </main>

    </div>

</body>
</html>