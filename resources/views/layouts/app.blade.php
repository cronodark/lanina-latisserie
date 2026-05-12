<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Lanina Patisserie')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Fallback SweetAlert via CDN: jamin window.Swal tersedia sebelum inline script di body dieksekusi (di production Vite bundle bisa telat karena type="module" selalu defer). Pin ke versi exact + SRI hash untuk keamanan. --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.24/dist/sweetalert2.all.min.js"
        integrity="sha384-QjoPbdj/93O7LUz0wqTxepA3tIabUD3jzfZX+x5QLvqFtHBzSw4eYFLSVthB+EDT"
        crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Gloock&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
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

        /* Sidebar collapse styles — sama dengan admin */
        #sidebar { transition: width .25s ease, padding .25s ease; }
        #sidebar.collapsed { width: 96px !important; }
        #mainWrapper { transition: margin-left .25s ease; }
        #mainWrapper.collapsed { margin-left: 96px !important; }
        #sidebar.collapsed .hide-text { display: none; }
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

<body class="h-screen bg-[#F5F5F0]">
    <x-swal />
    @yield('content')
    @livewireScripts

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