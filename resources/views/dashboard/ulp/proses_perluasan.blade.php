@extends('layouts.app')

@section('content')
<div class="h-full flex flex-col pt-4">

    <div class="glass-card flex flex-col flex-1 min-h-[400px] overflow-hidden border-0 shadow-[0_4px_20px_rgba(0,0,0,0.08)]">
        
        {{-- Card Header matching the table header style --}}
        <div class="px-6 py-4 flex items-center justify-between flex-shrink-0" style="background-color: var(--sidebar-bg); border-top-left-radius: 12px; border-top-right-radius: 12px;">
            <div class="flex items-center gap-3">
                <span class="text-white font-bold text-[1.05rem] tracking-wide">Proses Perluasan - ULP</span>
            </div>
            
            <button class="bg-[#3b82f6] text-white text-xs font-semibold px-4 py-1.5 rounded-full flex items-center gap-1 shadow-sm transiton hover:bg-blue-600">
                Action
            </button>
        </div>
        
        <div class="p-8 flex flex-col items-center justify-center flex-1 bg-white">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.83-5.83a5.5 5.5 0 00-6.72-8.31 5.501 5.501 0 00-8.31 6.72 5.5 5.5 0 008.28 5.34z" />
                </svg>
            </div>
            <h2 class="text-lg font-bold text-slate-700 mb-2">Halaman Sedang Dalam Pengembangan</h2>
            <p class="text-sm text-slate-500 text-center max-w-md">Fitur proses perluasan akan segera hadir dengan desain tabel dan manajemen data yang ditingkatkan.</p>
        </div>
    </div>
</div>
@endsection
