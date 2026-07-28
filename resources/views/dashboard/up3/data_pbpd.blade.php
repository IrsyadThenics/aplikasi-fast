@extends('layouts.app')

@section('content')
<div class="h-full flex flex-col bg-slate-50 p-5">

    {{-- ===== FILTER CARD ===== --}}
    <div class="bg-white rounded-2xl shadow-md border border-slate-200 overflow-hidden flex-shrink-0">

        {{-- Card Header --}}
        <div class="bg-gradient-to-r from-[#0D1B8C] to-[#0D1B8C] px-6 py-3 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white/80" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v16.5c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9zm3.75 11.625a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
            <div>
                <p class="text-white font-bold text-sm tracking-wide">DAFTAR TRANSAKSI</p>
                <p class="text-blue-100 text-xs">Daftar Transaksi PB/PD UP3 Bojonegoro</p>
            </div>
        </div>

        {{-- Filter Fields --}}
        <div class="px-6 py-4">
            <div class="grid grid-cols-3 gap-4 mb-4">
                {{-- No Agenda --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase tracking-widest">No Agenda</label>
                    <input type="text" placeholder="Cari no. agenda..."
                        class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-slate-50 text-slate-700 transition" />
                </div>
                {{-- Transaksi --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase tracking-widest">Transaksi</label>
                    <input type="text" placeholder="Jenis transaksi..."
                        class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-slate-50 text-slate-700 transition" />
                </div>
                {{-- Status Mohon --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase tracking-widest">Status Mohon</label>
                    <input type="text" placeholder="Status..."
                        class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-slate-50 text-slate-700 transition" />
                </div>
            </div>

            {{-- Date Row --}}
            <div class="flex items-center gap-3 flex-wrap">
                <span class="text-sm font-medium text-slate-600">Tanggal Bayar : Dari</span>
                <input type="date" value="2026-08-07"
                    class="border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-slate-50 text-slate-700 transition" />
                <span class="text-sm text-slate-500 font-medium">s/d</span>
                <input type="date" value="2026-08-07"
                    class="border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-slate-50 text-slate-700 transition" />
                <button onclick="tampilkanTabel()"
                    class="ml-2 bg-[#0D1B8C] hover:bg-[#FACC15] text-white text-sm font-semibold px-6 py-2 rounded-lg shadow-sm transition-all active:scale-95 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    Tampilkan
                </button>
            </div>
        </div>
    </div>

    {{-- ===== TABLE AREA ===== --}}
    <div id="tableArea" class="hidden mt-4 flex flex-col flex-1 min-h-0">
        <div class="bg-white rounded-2xl shadow-md border border-slate-200 flex flex-col flex-1 min-h-0 overflow-hidden">

            {{-- Table header bar --}}
            <div class="bg-gradient-to-r from-[#0D1B8C] to-[#0D1B8C] px-5 py-2.5 flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-2">
                    <span class="text-white font-bold text-sm tracking-wide">RECORD, JUMLAH TRANSAKSI PB/PD UP3</span>
                    <span class="bg-white/20 text-white text-xs px-2 py-0.5 rounded-full font-mono" id="recordCount">{{ count($data ?? []) }} data</span>
                </div>
                <button onclick="tutupTabel()" class="text-blue-200 hover:text-white text-xs transition flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Tutup
                </button>
            </div>

            {{-- Scrollable Table --}}
            <div class="overflow-auto flex-1">
                <table class="w-full text-xs border-collapse">
                    <thead class="sticky top-0 z-10 font-bold">
                        <tr class="bg-[#0D1B8C] text-white">
                            <th class="border border-blue-700 px-3 py-2 text-center" rowspan="2">NO.</th>
                            <th class="border border-blue-700 px-3 py-2 text-center" rowspan="2">DTL</th>
                            <th class="border border-blue-700 px-3 py-2 text-center" rowspan="2">ULP</th>
                            <th class="border border-blue-700 px-3 py-2 text-center" rowspan="2">TRANSAKSI</th>
                            <th class="border border-blue-700 px-3 py-2 text-center" rowspan="2">STATUS</th>
                            <th class="border border-blue-700 px-3 py-2 text-center" rowspan="2">NO AGENDA</th>
                            <th class="border border-blue-700 px-3 py-2 text-center" rowspan="2">NAMA PELANGGAN</th>
                            <th class="border border-blue-700 px-3 py-2 text-center" rowspan="2">ALAMAT</th>
                            <th class="border border-blue-700 px-3 py-2 text-center" colspan="2">LAMA</th>
                            <th class="border border-blue-700 px-3 py-2 text-center" colspan="2">BARU</th>
                        </tr>
                        <tr class="bg-[#0D1B8C] text-white">
                            <th class="border border-blue-600 px-3 py-1.5 text-center font-medium">TARIF</th>
                            <th class="border border-blue-600 px-3 py-1.5 text-center font-medium">DAYA</th>
                            <th class="border border-blue-600 px-3 py-1.5 text-center font-medium">TARIF</th>
                            <th class="border border-blue-600 px-3 py-1.5 text-center font-medium">DAYA</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @forelse($data ?? [] as $index => $item)
                        <tr class="bg-white hover:bg-slate-50 transition border-b border-slate-100 text-slate-700">
                            <td class="border border-slate-200 px-3 py-2 text-center">{{ $index + 1 }}.</td>
                            <td class="border border-slate-200 px-3 py-2 text-center">
                                @if(strtolower($item->dtl) === 'ada')
                                    <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-[10px] font-semibold">Ada</span>
                                @else
                                    <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-[10px]">Tidak</span>
                                @endif
                            </td>
                            <td class="border border-slate-200 px-3 py-2 text-center font-semibold">{{ $item->ulp }}</td>
                            <td class="border border-slate-200 px-3 py-2 text-left whitespace-nowrap">{{ $item->transaksi }}</td>
                            <td class="border border-slate-200 px-3 py-2 text-center whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-100 text-blue-800">{{ $item->status }}</span>
                            </td>
                            <td class="border border-slate-200 px-3 py-2 text-center font-mono">{{ $item->no_agenda }}</td>
                            <td class="border border-slate-200 px-3 py-2 text-left">Pelanggan {{ $item->no_agenda }}</td>
                            <td class="border border-slate-200 px-3 py-2 text-left max-w-xs truncate" title="{{ $item->alamat }}">{{ $item->alamat }}</td>
                            <td class="border border-slate-200 px-3 py-2 text-center">{{ $item->tarif_lama ?? '-' }}</td>
                            <td class="border border-slate-200 px-3 py-2 text-center">{{ $item->daya_lama ?? 0 }} VA</td>
                            <td class="border border-slate-200 px-3 py-2 text-center font-semibold text-blue-900">{{ $item->tarif_baru ?? '-' }}</td>
                            <td class="border border-slate-200 px-3 py-2 text-center font-semibold text-blue-900">{{ $item->daya_baru ?? 0 }} VA</td>
                        </tr>
                        @empty
                        <tr id="emptyRow">
                            <td colspan="12" class="text-center py-12 text-slate-400 italic text-xs">
                                <div class="flex flex-col items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                    </svg>
                                    <span>Tidak ada data ditemukan untuk filter yang dipilih</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer --}}
            <div class="border-t border-slate-100 px-5 py-2 bg-slate-50 flex-shrink-0">
                <span class="text-xs text-slate-400 font-mono">Records {{ count($data ?? []) ? '1 to ' . count($data) : '0 to 0' }} of {{ count($data ?? []) }}</span>
            </div>

        </div>
    </div>

</div>

<script>
    function tampilkanTabel() {
        var el = document.getElementById('tableArea');
        if (el) {
            el.classList.remove('hidden');
            el.classList.add('flex');
        }
    }
    function tutupTabel() {
        var el = document.getElementById('tableArea');
        if (el) {
            el.classList.add('hidden');
            el.classList.remove('flex');
        }
    }
</script>
@endsection
