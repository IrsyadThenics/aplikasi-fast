<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? ($pageTitle ?? 'Vendor FAST') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @include('layouts.theme')
</head>
<body class="min-h-screen bg-gradient-to-br from-[#091267] via-[#0D1B8C] to-[#0D1B8C] text-white">
    <header class="border-b border-white/20 bg-[#08185e]/60 backdrop-blur px-5 py-4">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-4">
            <a href="{{ route($routePrefix.'.dashboard') }}" class="font-bold tracking-wide">FAST · {{ strtoupper($pageTitle) }}</a>
            <nav class="flex items-center gap-2 text-sm">
                <a class="rounded-lg px-3 py-2 hover:bg-white/10" href="{{ route($routePrefix.'.dashboard') }}">Agenda</a>
                <a class="rounded-lg px-3 py-2 hover:bg-white/10" href="{{ route($routePrefix.'.history') }}">Riwayat</a>
                <span class="hidden border-l border-white/20 pl-3 sm:inline">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('auth.logout') }}">@csrf<button class="rounded-lg bg-white/10 px-3 py-2 hover:bg-white/20">Keluar</button></form>
            </nav>
        </div>
    </header>
    <main class="mx-auto max-w-6xl px-5 py-7">
        @if(session('success')) <div class="mb-5 rounded-xl border border-emerald-300/50 bg-emerald-500/20 px-4 py-3 text-sm">✓ {{ session('success') }}</div> @endif
        @if($errors->any()) <div class="mb-5 rounded-xl border border-red-300/50 bg-red-500/20 px-4 py-3 text-sm"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div> @endif
        @yield('content')
    </main>
</body>
</html>
