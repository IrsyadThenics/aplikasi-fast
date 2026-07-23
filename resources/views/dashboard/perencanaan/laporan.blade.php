@extends('layouts.app')

@section('content')
<div class="h-full flex flex-col bg-slate-50 p-5">

    {{-- ===== FILTER CARD ===== --}}
    <div class="bg-white rounded-2xl shadow-md border border-slate-200 overflow-hidden flex-shrink-0">

        {{-- Card Header --}}
        <div class="bg-gradient-to-r from-[#0D1B8C] to-[#0D1B8C] px-6 py-3 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white/80" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
            </svg>
            <div>
                <p class="text-white font-bold text-sm tracking-wide">LAPORAN</p>
                <p class="text-blue-100 text-xs">Laporan Data Layanan</p>
            </div>
        </div>

        {{-- Filter Fields --}}
        <div class="px-6 py-4 flex flex-col gap-4">
            
            {{-- Dropdowns Row --}}
            <div class="flex items-center gap-3 flex-wrap">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-semibold text-slate-600">ULP :</span>
                    <select class="border border-slate-200 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-slate-50 text-slate-700 transition min-w-[150px]">
                        <option>--- semua ---</option>
                    </select>
                </div>
                
                <span class="text-lg font-light text-slate-300 mx-2">|</span>
                
                <div class="flex items-center gap-2">
                    <span class="text-sm font-semibold text-slate-600">Jenis Transaksi :</span>
                    <select class="border border-slate-200 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-slate-50 text-slate-700 transition min-w-[150px]">
                        <option>--- semua ---</option>
                    </select>
                </div>
            </div>

            {{-- Date Row --}}
            <div class="flex items-center gap-3 flex-wrap mt-2">
                <span class="text-sm font-semibold text-slate-600">Periode Tanggal :</span>
                <input type="date" value="2026-07-23" class="border border-slate-200 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-slate-50 text-slate-700 transition" />
                <span class="text-sm text-slate-500 font-medium mx-1">s/d</span>
                <input type="date" value="2026-07-23" class="border border-slate-200 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-slate-50 text-slate-700 transition" />
                
                <button class="ml-2 bg-[#0D1B8C] hover:bg-[#FACC15] text-white text-sm font-semibold px-5 py-1.5 rounded-lg shadow-sm transition-all active:scale-95 flex items-center gap-2">
                    Tampilkan
                </button>
            </div>
            
        </div>
    </div>
</div>
@endsection