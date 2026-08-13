<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'FASTON360') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800|space-grotesk:400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            --sidebar-bg: #0D1B8C; /* Dark blue/slate from image mixed with PLN blue */
            --sidebar-hover: #FACC15;
            --sidebar-active-bg: #FACC15;
            --sidebar-active-text: #1E3A8A;
            --main-bg: #f0f4f8; /* Light gray-blue bg */
        }
        body { font-family: 'Inter', sans-serif; background-color: var(--main-bg); }
        h1, h2, h3, h4, h5, h6 { font-family: 'Inter', sans-serif; }
        
        .sidebar-link {
            color: #d1d5db; /* Light gray */
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            transition: all 0.2s ease;
        }
        .sidebar-link:hover:not(.active-link) {
            background-color: var(--sidebar-hover);
            color: #ffffff;
        }
        .sidebar-link.active-link {
            background-color: var(--sidebar-active-bg);
            color: var(--sidebar-active-text);
            border-radius: 9999px; /* Pill shape like image */
            font-weight: 700;
        }
        .sidebar-icon {
            color: inherit;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        /* Utility styles for cards */
        .glass-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid #e2e8f0;
            position: relative;
            overflow: hidden;
        }
        .wave-bg {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 40px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23e0e7ff' fill-opacity='0.5' d='M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,197.3C672,192,768,160,864,160C960,160,1056,192,1152,197.3C1248,203,1344,181,1392,170.7L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3Cpath fill='none' stroke='%233b82f6' stroke-width='3' stroke-opacity='0.4' d='M0,192L48,181.3C96,171,192,149,288,154.7C384,160,480,192,576,197.3C672,203,768,181,864,165.3C960,149,1056,139,1152,144C1248,149,1344,171,1392,181.3L1440,192'%3E%3C/path%3E%3C/svg%3E");
            background-size: cover;
            background-position: bottom;
            background-repeat: no-repeat;
            z-index: 1;
        }
    </style>
</head>
<body class="flex h-screen overflow-hidden text-slate-800" x-data="{ sidebarOpen: false }">
    
    {{-- ========================================= --}}
    {{-- SIDEBAR --}}
    {{-- ========================================= --}}
    <!-- Mobile overlay -->
    <div x-show="sidebarOpen" class="fixed inset-0 z-20 transition-opacity bg-black bg-opacity-50 lg:hidden" @click="sidebarOpen = false"></div>

    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-30 w-[17rem] flex flex-col flex-shrink-0 transition-transform duration-300 transform lg:translate-x-0 lg:static lg:inset-0 shadow-[4px_0_15px_rgba(0,0,0,0.1)] rounded-br-2xl" style="background-color: var(--sidebar-bg);">
        
        {{-- Logo / Brand area --}}
        <div class="pt-8 px-8 pb-4 flex flex-col items-center">
            <div class="text-center w-full relative">
                <!-- Close btn mobile -->
                <button @click="sidebarOpen = false" class="absolute -right-4 -top-2 text-white/70 hover:text-white lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <div class="flex items-center justify-center gap-2 mb-2">
                    <h1 class="text-2xl font-bold text-white">FAST</h1>
                    <h1 class="text-2xl font-bold text-yellow-400">ON</h1>
                    <h1 class="text-2xl font-bold text-yellow-400">360</h1>
                </div>
                {{-- <img src="{{ asset('images/faston.png') }}" alt="FASTON360 Logo" class="mx-auto w-16 h-16 object-contain" /> --}}
    <h2 class="text-[0.65rem] font-bold text-blue-200/70 tracking-[0.2em] leading-tight text-center uppercase">
        Dashboard Fast On
    </h2>
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
                'managerULP' => ['dashboard', 'data_pbpd', 'proses_perluasan', 'restitusi', 'pencarian', 'laporan', 'notifikasi'],
                'managerUP3' => ['dashboard','data_pbpd', 'tanpa_perluasan', 'perluasan_jtm', 'perluasan_jtr', 'proses_perluasan', 'restitusi', 'pengoperasian', 'pencarian', 'laporan', 'notifikasi'],
                'administrator' => ['dashboard','data_pbpd','proses_perluasan','perluasan_jtm', 'perluasan_jtr','pengoperasian','restitusi', 'pencarian', 'laporan', 'notifikasi'],
                'jaringan' => ['dashboard','data_pbpd','perluasan_jtm', 'perluasan_jtr','pengoperasian','pencarian', 'laporan', 'notifikasi'],
                'konstruksi' => ['dashboard','data_pbpd','perluasan_jtm', 'perluasan_jtr','checklist', 'pencarian', 'laporan', 'notifikasi'],
                'pelayanan' => ['dashboard','data_pbpd', 'perluasan_jtm', 'perluasan_jtr', 'tanpa_perluasan', 'restitusi', 'upload_data','laporan', 'notifikasi'],
                'perencanaan' => ['dashboard','data_pbpd', 'perluasan_jtm', 'perluasan_jtr', 'survey','pencarian', 'laporan', 'notifikasi'],
                'transaksi' => ['dashboard','data_pbpd', 'perluasan_jtm', 'perluasan_jtr', 'ba_operasi', 'pencarian', 'laporan', 'notifikasi'],
            ];
            
            $activeRoleMenus = $roleMenus[$role] ?? [];

            // Konfigurasi label dan icon untuk setiap menu
            $menuConfig = [
                'dashboard' => ['label' => 'Dashboard', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />'],
                'data_pbpd' => ['label' => 'Data PB/PD', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v16.5c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9zm3.75 11.625a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />'],
                'tanpa_perluasan' => ['label' => 'Tanpa Perluasan', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />'],
                'perluasan_jtm' => ['label' => 'Perluasan JTM', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3" />'],
                'perluasan_jtr' => ['label' => 'Perluasan JTR', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />'],
                'proses_perluasan' => ['label' => 'Proses Perluasan', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />'],
                'restitusi' => ['label' => 'Restitusi', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />'],
                'pengoperasian' => ['label' => 'Pengoperasian', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />'],
                'pencarian' => ['label' => 'Pencarian', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />'],
                'laporan' => ['label' => 'Laporan', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />'],
                'notifikasi' => ['label' => 'Notifikasi', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />'],
                'survey' => ['label' => 'Survey', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />'],
                'ba_operasi' => ['label' => 'BA Operasi', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375a9 9 0 019 9v.375M10.125 2.25A3.375 3.375 0 0113.5 5.625v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 013.375 3.375M9 15l2.25 2.25L15 12" />'],
                'checklist' => ['label' => 'checklist', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />'],
                'upload_data' => ['label' => 'Upload Data', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />']
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

        <nav class="flex-1 px-4 py-2 space-y-2 overflow-y-auto w-[90%] mx-auto mt-4">
            @foreach($menus as $menu)
                @if(Route::has($menu['route']))
                    @php $isActive = request()->routeIs($menu['route']); @endphp
                    <a href="{{ route($menu['route']) }}"
                       class="sidebar-link flex items-center px-4 py-2.5 rounded-[14px] {{ $isActive ? 'active-link' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-[1.2rem] h-[1.2rem] mr-3 sidebar-icon flex-shrink-0">
                            {!! $menu['icon'] !!}
                        </svg>
                        <span class="whitespace-nowrap">{{ $menu['label'] }}</span>
                    </a>
                @endif
            @endforeach
            
            <div class="pt-8 pb-4">
                <form method="POST" action="{{ route('auth.logout') }}" class="m-0 w-full">
                    @csrf
                    <button type="submit" class="w-full sidebar-link flex items-center px-4 py-2.5 rounded-[14px] text-left hover:bg-slate-700 transition group">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-[1.2rem] h-[1.2rem] mr-3 sidebar-icon flex-shrink-0 group-hover:text-red-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                        <span class="whitespace-nowrap group-hover:text-red-100">Login/Logout</span>
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    {{-- ========================================= --}}
    {{-- MAIN AREA --}}
    {{-- ========================================= --}}
    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
        
        {{-- Custom Minimalist Header --}}
        <header class="bg-transparent flex-shrink-0 z-10 px-6 py-4 flex items-center justify-between pointer-events-none mt-2">
            <div class="flex items-center gap-4 pointer-events-auto">
                <button @click="sidebarOpen = true" class="lg:hidden w-10 h-10 rounded-full bg-white shadow flex items-center justify-center text-slate-500 hover:text-blue-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                
                {{-- PLN minimal branding inline with background --}}
                <div class="hidden lg:flex items-center gap-3">
                    <img src="{{ asset('images/logo-pln.jpeg') }}" alt="Logo PLN" class="h-9 w-auto rounded mix-blend-multiply opacity-90">
                    <div class="flex flex-col leading-tight">
                        <span class="text-[0.7rem] font-bold text-[#1e4d8c] tracking-widest uppercase font-serif">PT PLN (PERSERO)</span>
                        <span class="text-[0.7rem] font-bold text-slate-700 tracking-wide uppercase font-serif opacity-80">UP3 BOJONEGORO / ULP LAMONGAN</span>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-4 pointer-events-auto">
                {{-- Date widget as in image (top left in image, but placing top right here nicely) --}}
                <!--<div class="hidden sm:flex items-center bg-[#ffece8] rounded overflow-hidden shadow-sm border border-red-50 text-[0.8rem] font-medium text-red-700">
                    <div class="px-3 py-1.5 flex items-center gap-1.5 opacity-80 bg-red-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z" /></svg>
                        17/09/2024
                    </div>
                    <span class="px-1 text-red-300">-</span>
                    <div class="px-3 py-1.5 flex items-center gap-1.5 bg-[#ffece8]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z" /></svg>
                        18/09/2024
                    </div>
                </div>
                
                <button class="bg-[#0D1B8C] hover:bg-blue-600 text-white rounded text-xs font-semibold px-4 py-1.5 shadow-sm shadow-blue-200 transition">
                    Filter
                </button>-->

                <div class="flex items-center gap-2 pl-2 border-l border-slate-300 ml-1">
                    <div x-data="{ profileOpen: false }" class="relative">
                        <button @click="profileOpen = !profileOpen" @click.away="profileOpen = false" class="w-8 h-8 rounded-full bg-[#0D1B8C] flex items-center justify-center text-white text-xs font-bold shadow ring-2 ring-white cursor-pointer hover:bg-blue-800 transition focus:outline-none">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </button>
                        
                        <!-- Dropdown -->
                        <div x-show="profileOpen" x-transition.opacity class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 border border-slate-100 z-50">
                            <div class="px-4 py-2 border-b border-slate-100">
                                <p class="text-sm font-semibold text-slate-800 truncate">{{ Auth::user()->name ?? 'User' }}</p>
                                <p class="text-xs text-slate-500 capitalize">{{ str_replace('_', ' ', Auth::user()->role) }}</p>
                            </div>
                            <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-blue-600 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Profil Saya
                            </a>
                            <!--<formmethod="POST"action="route('auth.logout') --}}" class="m-0">
                                
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Keluar
                                </button>-->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Main Content Window --}}
        <main class="flex-1 min-h-0 overflow-y-auto w-full p-4 sm:p-6 lg:p-8 lg:pt-2">
            @yield('content')
        </main>
        
    </div>

</body>
</html>