<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- AlpineJS -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-100">
        <div class="flex h-screen">

            {{-- ========================================= --}}
            {{-- SIDEBAR --}}
            {{-- ========================================= --}}
            <aside class="w-64 bg-white border-r border-gray-200 flex flex-col justify-between flex-shrink-0">
                <div class="p-6">
                    {{-- Logo / Brand --}}
                    <div class="flex items-center justify-between mb-6">
                        <span class="text-xl font-bold text-gray-800 tracking-wider">FastOn</span>
                    </div>

                    {{-- Navigation Menu (Dinamis berdasarkan role) --}}
                    @php
                        $role = Auth::user()->role;

                        // Map role ke prefix route yang dipakai di web.php
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

                        // Definisi menu sidebar
                        $menus = [
                            ['route' => $prefix . '.dashboard',          'label' => 'DASHBOARD'],
                            ['route' => $prefix . '.data_pbpd',          'label' => 'DATA PB/PD'],
                            ['route' => $prefix . '.proses_perluasan',   'label' => 'PROSES PERLUASAN'],
                            ['route' => $prefix . '.restitusi',          'label' => 'RESTITUSI'],
                            ['route' => $prefix . '.laporan',            'label' => 'LAPORAN'],
                            ['route' => $prefix . '.notifikasi',         'label' => 'NOTIFIKASI'],
                        ];
                    @endphp

                    <nav class="space-y-2">
                        @foreach($menus as $menu)
                            @if(Route::has($menu['route']))
                                <a href="{{ route($menu['route']) }}"
                                   class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors
                                          {{ request()->routeIs($menu['route']) 
                                              ? 'text-gray-700 bg-gray-200 font-semibold' 
                                              : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <span>{{ $menu['label'] }}</span>
                                </a>
                            @endif
                        @endforeach
                    </nav>
                </div>

                {{-- User Info + Logout --}}
                <div class="p-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col">
                            <span class="text-sm font-semibold text-gray-700">{{ Auth::user()->user_id ?? 'User' }}</span>
                            <span class="text-xs text-gray-500">{{ Auth::user()->role ?? 'Role' }}</span>
                        </div>
                        <form method="POST" action="{{ route('auth.logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            {{-- ========================================= --}}
            {{-- MAIN CONTENT AREA --}}
            {{-- ========================================= --}}
            <div class="flex-1 flex flex-col overflow-hidden">
                {{-- Header --}}
                <header class="bg-white border-b border-gray-200 py-4 px-6 flex justify-between items-center">
                    <h1 class="text-lg font-semibold text-gray-800">
                        @yield('header-title', 'Dashboard')
                    </h1>
                    <div class="text-sm text-gray-500">
                        {{ now()->format('d M Y') }}
                    </div>
                </header>

                {{-- Page Content --}}
                <main class="flex-1 overflow-x-hidden overflow-y-auto p-6">
                    @yield('content')
                </main>
            </div>

        </div>
    </body>
</html>