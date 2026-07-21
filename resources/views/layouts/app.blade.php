<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'FASTON360') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|space-grotesk:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            --sidebar-bg: #d6e8f5;
            --header-blue: #3b6ea5;
            --banner-blue: #3b6ea5;
        }
        body { font-family: 'Inter', sans-serif; }
        .sidebar-link {
            color: #1a1a1a;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.03em;
        }
        .sidebar-link:hover {
            color: #1a1a1a;
            text-decoration: none;
        }
        .pln-header-font {
            font-family: 'Times New Roman', Times, serif;
        }
        /* Rail accent for footer */
        .rail-line {
            width: 100%;
            height: 5px;
            background: repeating-linear-gradient(90deg,
                #3b6ea5 0, #3b6ea5 16px,
                #fff500 16px, #fff500 32px,
                #ed1c24 32px, #ed1c24 48px
            );
        }
    </style>
</head>
<body class="flex h-screen overflow-hidden bg-[#e9f2fb]">
    
    {{-- ========================================= --}}
    {{-- SIDEBAR --}}
    {{-- ========================================= --}}
    <aside class="w-60 flex flex-col flex-shrink-0 border-r border-[#c0d8ec]" style="background-color: var(--sidebar-bg);">
        
        {{-- Logo / Brand --}}
        <div class="pt-3 px-4 pb-0 flex flex-col items-center">
            <div class="mb-4 text-center">
                <img src="{{ asset('images/faston.png') }}" alt="FASTON360 Logo" class="max-w-[170px] mx-auto mix-blend-multiply drop-shadow -mb-2">
                <h2 class="text-[0.65rem] font-bold text-black tracking-wider leading-tight w-full" style="font-family: 'Space Grotesk', sans-serif;">Full Acceleration & Service<br>Tracking ON 360&deg;</h2>
            </div>
        </div>
        
        {{-- Navigation Menus --}}
        @php
            $role = Auth::user()->role ?? 'managerULP';
            $prefixMap = [
                'managerULP'    => 'ulp',
                'managerUP3'    => 'up3',
                'administrator' => 'admin',
                'pelayanan'     => 'pelayanan',
                'konstruksi'    => 'konstruksi',
                'jaringan'      => 'jaringan',
                'perencanaan'   => 'perencanaan',
                'transaksi'     => 'transaksi',
            ];
            $prefix = $prefixMap[$role] ?? 'dashboard';

            // Daftar menu yang tersedia untuk setiap role
            $roleMenus = [
                'managerULP' => ['data_pbpd', 'tanpa_perluasan', 'perluasan_jtm', 'perluasan_jtr', 'proses_perluasan', 'restitusi', 'pengoperasian', 'pencarian', 'laporan', 'notifikasi'],
                'managerUP3' => ['data_pbpd', 'tanpa_perluasan', 'perluasan_jtm', 'perluasan_jtr', 'proses_perluasan', 'restitusi', 'pengoperasian', 'pencarian', 'laporan', 'notifikasi'],
                'administrator' => ['data_pbpd', 'proses_perluasan', 'restitusi', 'laporan', 'notifikasi'],
                'jaringan' => ['data_pbpd', 'proses_perluasan', 'restitusi', 'laporan', 'notifikasi'],
                'konstruksi' => ['data_pbpd', 'proses_perluasan', 'restitusi', 'laporan', 'notifikasi'],
                'pelayanan' => ['data_pbpd', 'perluasan_jtm', 'perluasan_jtr', 'proses_perluasan', 'restitusi', 'laporan', 'notifikasi'],
                'perencanaan' => ['data_pbpd', 'perluasan_jtm', 'perluasan_jtr', 'proses_perluasan', 'survey', 'laporan', 'notifikasi'],
                'transaksi' => ['data_pbpd', 'perluasan_jtm', 'perluasan_jtr', 'ba_operasi', 'pencarian', 'laporan', 'notifikasi'],
            ];
            
            $activeRoleMenus = $roleMenus[$role] ?? [];

            // Konfigurasi label dan icon untuk setiap menu
            $menuConfig = [
                'data_pbpd' => ['label' => 'DATA PB/PD', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v16.5c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9zm3.75 11.625a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />'],
                'tanpa_perluasan' => ['label' => 'TANPA PERLUASAN', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" />'],
                'perluasan_jtm' => ['label' => 'PERLUASAN JTM', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3" />'],
                'perluasan_jtr' => ['label' => 'PERLUASAN JTR', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />'],
                'proses_perluasan' => ['label' => 'PROSES PERLUASAN', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />'],
                'restitusi' => ['label' => 'RESTITUSI', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />'],
                'pengoperasian' => ['label' => 'PENGOPERASIAN', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />'],
                'pencarian' => ['label' => 'PENCARIAN', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />'],
                'laporan' => ['label' => 'LAPORAN', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />'],
                'notifikasi' => ['label' => 'NOTIFIKASI', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />'],
                'survey' => ['label' => 'SURVEY', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />'],
                'ba_operasi' => ['label' => 'BA OPERASI', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375a9 9 0 019 9v.375M10.125 2.25A3.375 3.375 0 0113.5 5.625v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 013.375 3.375M9 15l2.25 2.25L15 12" />'],
            ];

            $menus = [];
            foreach ($activeRoleMenus as $menuKey) {
                if (isset($menuConfig[$menuKey])) {
                    $menus[] = [
                        'route' => $prefix . '.' . $menuKey,
                        'label' => $menuConfig[$menuKey]['label'],
                        'icon' => $menuConfig[$menuKey]['icon']
                    ];
                }
            }
        @endphp

        <nav class="flex-1 px-3 py-0 space-y-1 overflow-hidden">
            @foreach($menus as $menu)
                @if(Route::has($menu['route']))
                    <a href="{{ route($menu['route']) }}"
                       class="sidebar-link flex items-center px-2 py-2 rounded-md transition-all duration-200
                              {{ request()->routeIs($menu['route']) ? 'bg-[rgba(0,0,0,0.07)] border-l-[3px] border-[#3b6ea5]' : 'hover:bg-[rgba(0,0,0,0.04)]' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-[1.1rem] h-[1.1rem] mr-2.5 text-black flex-shrink-0">
                            {!! $menu['icon'] !!}
                        </svg>
                        <span class="whitespace-nowrap">{{ $menu['label'] }}</span>
                    </a>
                @endif
            @endforeach
        </nav>
        
        {{-- Footer Rail Accent --}}
        <div class="mt-auto">
            <div class="rail-line"></div>
        </div>
    </aside>

    {{-- ========================================= --}}
    {{-- MAIN AREA --}}
    {{-- ========================================= --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        {{-- PLN Header --}}
        <header class="bg-white border-b border-slate-200 flex-shrink-0" style="background-color: var(--header-blue);">
            <div class="flex items-stretch" style="background:white; min-height:56px;">
                {{-- LOGO PLN PERSERO --}}
                <div class="flex-shrink-0 bg-[#ffff] px-1 flex items-center">
                    <div class="px-3 flex items-center">
                        <img
                            src="{{ asset('images/logo-pln.jpeg') }}"
                            alt="Logo PLN"
                            class="h-10 w-auto"
                        >
                    </div>
                </div>
                {{-- Header Text --}}
                <div class="flex flex-col justify-center leading-tight px-4 border-r border-slate-200 flex-1">
                    <span class="pln-header-font text-[0.7rem] font-bold text-[#3b6ea5] tracking-wider uppercase">PT PLN (PERSERO)</span>
                    <span class="pln-header-font text-[0.82rem] font-bold text-slate-800 tracking-wide uppercase">UNIT PELAKSANA PELAYANAN PELANGGAN BOJONEGORO</span>
                    <span class="pln-header-font text-[0.72rem] text-slate-600 tracking-wide">Unit Layanan Pelanggan Lamongan</span>
                </div>
                {{-- Logout --}}
                <div class="flex items-center px-4">
                    <form method="POST" action="{{ route('auth.logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="text-slate-500 hover:text-red-500 transition-colors" title="Logout">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            
            {{-- Blue Banner (Manager ULP bar) --}}
            <div class="text-white px-6 py-1.5 flex justify-between items-center" style="background-color: var(--banner-blue);">
                <div class="flex-1 text-center font-bold text-[0.9rem] tracking-[0.2em] uppercase" style="font-family: 'Inter', sans-serif;">
                    -- {{ strtoupper(str_replace(['managerULP','managerUP3'], ['Manager ULP','Manager UP3'], Auth::user()->role ?? 'Manager ULP')) }} --
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-xs font-medium opacity-80">{{ Auth::user()->name ?? '' }}</span>
                    <span class="w-7 h-7 rounded-full border border-white/60 flex items-center justify-center text-[0.7rem] font-bold bg-white/10">
                        {{ substr(Auth::user()->name ?? 'M', 0, 1) }}
                    </span>
                </div>
            </div>
        </header>

        {{-- Main Content --}}
        <main class="flex-1 min-h-0 overflow-y-auto overflow-x-hidden p-0">
            @yield('content')
        </main>
        
    </div>

</body>
</html>