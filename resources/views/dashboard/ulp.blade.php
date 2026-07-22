@extends('layouts.app')

@section('content')
<div class="w-full max-w-7xl mx-auto h-full flex flex-col gap-6 pt-2">
    
    {{-- STATS ROW (Matching the 4 cards in the image) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        
        {{-- Card 1 --}}
        <div class="glass-card p-5 h-32 flex flex-col relative group cursor-pointer transition-transform hover:-translate-y-1">
            <div class="wave-bg opacity-70 group-hover:opacity-100 transition-opacity"></div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="w-11 h-11 rounded-full bg-[#1e4d8c] text-white flex items-center justify-center flex-shrink-0 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-3xl font-extrabold text-slate-800 tracking-tight leading-none mb-1">450</span>
                    <span class="text-[0.8rem] font-semibold text-slate-600">Total PB/PD</span>
                </div>
            </div>
        </div>

        {{-- Card 2 --}}
        <div class="glass-card p-5 h-32 flex flex-col relative group cursor-pointer transition-transform hover:-translate-y-1">
            <div class="wave-bg opacity-70 group-hover:opacity-100 transition-opacity"></div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="w-11 h-11 rounded-full text-white flex items-center justify-center flex-shrink-0 shadow-md" style="background-color: var(--sidebar-bg);">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-3xl font-extrabold text-slate-800 tracking-tight leading-none mb-1">225</span>
                    <span class="text-[0.8rem] font-semibold text-slate-600">Proses Perluasan</span>
                </div>
            </div>
        </div>

        {{-- Card 3 --}}
        <div class="glass-card p-5 h-32 flex flex-col relative group cursor-pointer transition-transform hover:-translate-y-1">
            <div class="wave-bg opacity-70 group-hover:opacity-100 transition-opacity"></div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="w-11 h-11 rounded-full bg-blue-500 text-white flex items-center justify-center flex-shrink-0 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-3xl font-extrabold text-slate-800 tracking-tight leading-none mb-1">225</span>
                    <span class="text-[0.8rem] font-semibold text-slate-600">Selesai</span>
                </div>
            </div>
        </div>

        {{-- Card 4 --}}
        <div class="glass-card p-5 h-32 flex flex-col relative group cursor-pointer transition-transform hover:-translate-y-1">
            <div class="wave-bg opacity-70 group-hover:opacity-100 transition-opacity"></div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="w-11 h-11 rounded-full bg-slate-600 text-white flex items-center justify-center flex-shrink-0 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-3xl font-extrabold text-slate-800 tracking-tight leading-none mb-1">80</span>
                    <span class="text-[0.8rem] font-semibold text-slate-600">Peak Layanan</span>
                </div>
            </div>
        </div>
        
    </div>

    {{-- CHART AREA (Matching the image) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 flex-1 min-h-[300px]">
        {{-- Bar Chart Card --}}
        <div class="glass-card p-6 lg:col-span-2 flex flex-col relative overflow-visible h-full">
            <div class="flex justify-between items-start mb-6">
                <h3 class="font-bold text-sm text-slate-700 leading-tight">Transaksi<br>Statistic</h3>
                <div class="flex text-[0.65rem] font-semibold bg-white border border-slate-200 rounded overflow-hidden">
                    <span class="bg-blue-400 text-white px-2 py-0.5 cursor-pointer">Week</span>
                    <span class="text-slate-500 px-2 py-0.5 hover:bg-slate-50 cursor-pointer border-l border-slate-200">Month</span>
                    <span class="text-slate-500 px-2 py-0.5 hover:bg-slate-50 cursor-pointer border-l border-slate-200">Year</span>
                </div>
            </div>
            
            {{-- Fake bar chart to match image --}}
            <div class="flex-1 flex items-end justify-between px-4 pb-8 pt-4 relative border-l-2 border-b-2 border-slate-700">
                <div class="absolute top-0 -left-1 w-2 h-2 bg-slate-700 rotate-45 transform"></div>
                <div class="absolute -right-1 bottom-[-5px] w-2 h-2 bg-slate-700 rotate-45 transform"></div>
                
                {{-- Bars --}}
                <div class="w-10 bg-gradient-to-t from-blue-300 to-blue-500 h-[60%] rounded-sm relative group cursor-pointer"><span class="absolute -top-5 left-1/2 -translate-x-1/2 text-xs font-semibold text-slate-600 opacity-0 group-hover:opacity-100">fri</span></div>
                <div class="w-10 bg-gradient-to-t from-blue-300 to-blue-500 h-[40%] rounded-sm relative group cursor-pointer"><span class="absolute -top-5 left-1/2 -translate-x-1/2 text-xs font-semibold text-slate-600 opacity-0 group-hover:opacity-100">thur</span></div>
                <div class="w-10 bg-gradient-to-t from-blue-300 to-blue-500 h-[30%] rounded-sm relative group cursor-pointer"><span class="absolute -top-5 left-1/2 -translate-x-1/2 text-xs font-semibold text-slate-600 opacity-0 group-hover:opacity-100">we</span></div>
                <div class="w-10 bg-gradient-to-t from-blue-300 to-blue-500 h-[70%] rounded-sm relative group cursor-pointer"><span class="absolute -top-5 left-1/2 -translate-x-1/2 text-xs font-semibold text-slate-600 opacity-0 group-hover:opacity-100">tue</span></div>
                <div class="w-10 bg-gradient-to-t from-blue-300 to-blue-500 h-[90%] rounded-sm relative group cursor-pointer"><span class="absolute -top-5 left-1/2 -translate-x-1/2 text-xs font-semibold text-slate-600 opacity-0 group-hover:opacity-100">mon</span></div>
            </div>
        </div>
        
        {{-- Donut Chart Card --}}
        <div class="glass-card p-6 flex flex-col relative h-full">
            <div class="wave-bg opacity-70"></div>
            <h3 class="font-bold text-sm text-slate-700 leading-tight z-10">Layanan Type</h3>
            <div class="flex-1 flex flex-col justify-center items-center relative z-10 mt-4">
                <div class="w-40 h-40 rounded-full border-[24px] border-l-[#29465B] border-t-[#d1d5db] border-r-blue-400 border-b-[#20b2aa]"></div>
                
                <div class="grid grid-cols-2 gap-x-6 gap-y-2 text-[0.65rem] font-semibold text-slate-600 mt-6 left-2 absolute top-0 bg-white/80 p-2 rounded w-fit">
                    <div class="flex items-center gap-1.5"><div class="w-3 h-3 bg-[#29465B]"></div> PB/PD</div>
                    <div class="flex items-center gap-1.5"><div class="w-3 h-3 bg-blue-400"></div> JTM</div>
                    <div class="flex items-center gap-1.5"><div class="w-3 h-3 bg-[#20b2aa]"></div> JTR</div>
                    <div class="flex items-center gap-1.5"><div class="w-3 h-3 bg-[#d1d5db]"></div> Lainnya</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
