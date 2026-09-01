@extends('layouts.app')

@section('content')
<div class="h-full flex flex-col bg-slate-50 p-5">

    {{-- ===== FILTER CARD ===== --}}
    <div class="bg-white rounded-2xl shadow-md border border-slate-200 overflow-hidden flex-shrink-0">

        {{-- Card Header --}}
        <div class="bg-gradient-to-r from-[#0D1B8C] to-[#0D1B8C] px-6 py-3 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white/80" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
            </svg>
            <div>
                <p class="text-white font-bold text-sm tracking-wide">DAFTAR TRANSAKSI</p>
                <p class="text-blue-100 text-xs">Daftar Transaksi Tanpa Perluasan UP3 Bojonegoro</p>
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
                {{-- Dropdown Transaksi --}} 
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase tracking-widest">Transaksi</label>
                    <select class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-slate-50 text-slate-700 transition">
                        <option>--- semua ---</option>
                        <option>Pasang baru</option>
                        <option>Perubahan daya</option>
                    </select>
                </div>
                {{-- Dropdown Status Mohon --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase tracking-widest">Status Mohon</label>
                    <select class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-slate-50 text-slate-700 transition">
                        <option>--- semua ---</option>
                        <option>Mohon</option>
                        <option>Bayar</option>
                    </select>
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
                    <span class="text-white font-bold text-sm tracking-wide">RECORD, JUMLAH TRANSAKSI TANPA PERLUASAN</span>
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
                    <thead class="sticky top-0 z-10">
                        <tr class="bg-[#0D1B8C] text-white">
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold whitespace-nowrap" rowspan="2">NO.</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold whitespace-nowrap" rowspan="2">ASAL ULP</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold whitespace-nowrap" rowspan="2">DTL</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold whitespace-nowrap" rowspan="2">TRANSAKSI</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold whitespace-nowrap" rowspan="2">STATUS</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold whitespace-nowrap" rowspan="2">NO AGENDA</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold whitespace-nowrap" rowspan="2">NAMA PELANGGAN</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold whitespace-nowrap" rowspan="2">ALAMAT</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold whitespace-nowrap" colspan="2">LAMA</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold whitespace-nowrap" colspan="2">BARU</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold whitespace-nowrap" rowspan="2">KET</th>
                        </tr>
                        <tr class="bg-[#0D1B8C] text-white">
                            <th class="border border-blue-600 px-3 py-1.5 text-center font-medium whitespace-nowrap">TARIF</th>
                            <th class="border border-blue-600 px-3 py-1.5 text-center font-medium whitespace-nowrap">DAYA</th>
                            <th class="border border-blue-600 px-3 py-1.5 text-center font-medium whitespace-nowrap">TARIF</th>
                            <th class="border border-blue-600 px-3 py-1.5 text-center font-medium whitespace-nowrap">DAYA</th>
                        </tr>
                    </thead>
                                        <tbody id="tableBody">
                        @forelse($data ?? [] as $index => $item)
                        <tr class="bg-white hover:bg-slate-50 transition border-b border-slate-100 text-slate-700">
                            <td class="border border-slate-200 px-3 py-2 text-center">{{ $index + 1 }}.</td>
                            <td class="border border-slate-200 px-3 py-2 text-center text-[10px] font-bold">{{ $item->ulp ?? '-' }}</td>
                            <td class="border border-slate-200 px-3 py-2 text-center">
                                @if(strtolower($item->dtl) === 'ada' || strtolower($item->dtl) === 'tidak ada' || true)
                                <button onclick="openDetailModal({{ json_encode($item) }})" class="text-slate-500 hover:text-blue-600 transition" title="Lihat Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                </button>
                                @endif
                            </td>
                            <td class="border border-slate-200 px-3 py-2 text-left whitespace-nowrap">
                                @if(strtolower($item->transaksi) === 'pasang baru')
                                    <span class="px-2 py-1 rounded text-[10px] font-semibold bg-indigo-100 text-indigo-700">Pasang Baru</span>
                                @elseif(strtolower($item->transaksi) === 'perubahan daya')
                                    <span class="px-2 py-1 rounded text-[10px] font-semibold bg-purple-100 text-purple-700">Perubahan Daya</span>
                                @else
                                    <span class="px-2 py-1 rounded text-[10px] font-semibold bg-slate-100 text-slate-700">{{ $item->transaksi }}</span>
                                @endif
                            </td>
                            <td class="border border-slate-200 px-3 py-2 text-left whitespace-nowrap">
                                @if(strtolower($item->status) === 'mohon')
                                    <span class="px-2 py-1 rounded text-[10px] font-semibold bg-amber-100 text-amber-700">Mohon</span>
                                @elseif(strtolower($item->status) === 'bayar')
                                    <span class="px-2 py-1 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-700">Bayar</span>
                                @else
                                    <span class="px-2 py-1 rounded text-[10px] font-semibold bg-blue-100 text-blue-800">{{ $item->status }}</span>
                                @endif
                            </td>
                            <td class="border border-slate-200 px-3 py-2 text-center font-mono">{{ $item->no_agenda }}</td>
                            <td class="border border-slate-200 px-3 py-2 text-left">Pelanggan {{ $item->no_agenda }}</td>
                            <td class="border border-slate-200 px-3 py-2 text-left max-w-xs truncate" title="{{ $item->alamat }}">{{ $item->alamat }}</td>
                            <td class="border border-slate-200 px-3 py-2 text-center">{{ $item->tarif_lama ?? '-' }}</td>
                            <td class="border border-slate-200 px-3 py-2 text-center">{{ $item->daya_lama ?? 0 }} VA</td>
                            <td class="border border-slate-200 px-3 py-2 text-center font-semibold text-blue-900">{{ $item->tarif_baru ?? '-' }}</td>
                            <td class="border border-slate-200 px-3 py-2 text-center font-semibold text-blue-900">{{ $item->daya_baru ?? 0 }} VA</td>
                            <td class="border border-slate-200 px-3 py-2 text-center">-</td>
                        </tr>
                        @empty
                        <tr id="emptyRow">
                            <td colspan="13" class="text-center py-12 text-slate-400 italic text-xs">
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
                <span class="text-xs text-slate-400 font-mono">Records {{ count($data ?? []) > 0 ? "1 to " . count($data) : "0 to 0" }} of {{ count($data ?? []) }}</span>
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
        
        // Generic filtering logic combining all text inputs and selects in the filter card
        var filterContainer = document.querySelector('.px-6.py-4 .grid');
        var filterInputs = filterContainer ? filterContainer.querySelectorAll('input, select') : [];
        var activeFilters = [];
        
        filterInputs.forEach(function(input) {
            var val = input.value.toLowerCase().trim();
            if (val !== '' && !val.includes('semua') && !val.includes('---')) {
                activeFilters.push(val);
            }
        });
        
        var tableBody = document.getElementById('tableBody');
        if (!tableBody) return;
        
        var rows = tableBody.querySelectorAll('tr:not(#emptyRow)');
        var visibleCount = 0;
        
        rows.forEach(function(row) {
            var rowText = row.innerText.toLowerCase();
            var matchesAll = true;
            
            for (var i = 0; i < activeFilters.length; i++) {
                if (!rowText.includes(activeFilters[i])) {
                    matchesAll = false;
                    break;
                }
            }
            
            if (matchesAll) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        var emptyRow = document.getElementById('emptyRow');
        if (emptyRow) {
            emptyRow.style.display = visibleCount === 0 ? '' : 'none';
        }
        
        var recordCount = document.getElementById('recordCount');
        if (recordCount) {
            recordCount.innerText = visibleCount + ' data';
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

<!-- DTL Modal -->
<div id="detailModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4 overflow-y-auto">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl relative overflow-hidden flex flex-col border border-slate-200">
        <!-- Title Bar -->
        <div class="bg-gradient-to-r from-[#0D1B8C] to-[#2B73FE] px-5 py-3 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span class="text-white font-bold text-sm tracking-wide">Detail Data Pelanggan</span>
            </div>
            <button onclick="closeDetailModal()" class="text-white/70 hover:text-white p-1 rounded-full hover:bg-white/20 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        
        <!-- Content -->
        <div class="p-6 text-[13.5px] text-slate-800 font-mono flex flex-col gap-4">
            
            <div class="grid grid-cols-[150px_1fr] gap-2 items-center">
                <div class="font-bold text-slate-700 uppercase tracking-wider">UP3</div>
                <div class="uppercase font-bold text-black">: <span id="mdl-up3">BOJONEGORO</span></div>
                <div class="font-bold text-slate-700 uppercase tracking-wider">ULP</div>
                <div class="uppercase font-bold text-black">: <span id="mdl-ulp">LAMONGAN</span></div>
            </div>
            
            <hr class="border-slate-200">
            
            <div class="grid grid-cols-[150px_1fr] gap-2 items-center">
                <div class="font-bold text-slate-700 uppercase tracking-wider">Transaksi</div>
                <div class="uppercase font-bold text-blue-700">: <span id="mdl-transaksi" class="bg-blue-50 px-2 py-1 rounded"></span></div>
                <div class="font-bold text-slate-700 uppercase tracking-wider">Status Permohonan</div>
                <div class="uppercase font-bold text-amber-700">: <span id="mdl-status" class="bg-amber-50 px-2 py-1 rounded"></span></div>
            </div>

            <hr class="border-slate-200">
            
            <div class="grid grid-cols-[150px_1fr] gap-2 items-center">
                <div class="font-bold text-slate-700 uppercase tracking-wider">No. Agenda</div>
                <div class="font-bold">: <span id="mdl-agenda"></span></div>
                <div class="font-bold text-slate-700 uppercase tracking-wider">ID Pelanggan</div>
                <div class="font-bold">: <span id="mdl-idpel"></span></div>
                <div class="font-bold text-slate-700 uppercase tracking-wider">Nama</div>
                <div class="uppercase font-bold">: <span id="mdl-nama"></span></div>
                <div class="font-bold text-slate-700 uppercase tracking-wider">Alamat</div>
                <div class="uppercase font-bold">: <span id="mdl-alamat"></span></div>
                <div class="font-bold text-slate-700 uppercase tracking-wider">Tarif / Daya</div>
                <div class="uppercase font-bold flex items-center gap-2">
                    : <span class="bg-emerald-50 text-emerald-700 px-2 py-1 rounded">BARU : <span id="mdl-tbaru"></span> / <span id="mdl-dbaru"></span></span>
                    <span class="text-slate-300">|</span> 
                    <span class="bg-slate-50 text-slate-600 px-2 py-1 rounded">LAMA : <span id="mdl-tlama"></span> / <span id="mdl-dlama"></span></span>
                </div>
            </div>
            
            <hr class="border-slate-200">
            
            <div class="grid grid-cols-[150px_1fr] gap-2 items-center bg-blue-50/50 p-3 rounded-lg border border-blue-100">
                <div class="font-bold text-[#0D1B8C] uppercase tracking-wider flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    RAB
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-slate-600 font-bold text-sm">Rp.</span>
                    <input type="number" id="mdl-rab-input" class="border border-slate-300 rounded-lg px-3 py-1.5 text-sm w-40 focus:outline-none focus:ring-2 focus:ring-[#2B73FE] transition bg-white" placeholder="0" />
                    <button onclick="saveRabField()" class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-1.5 rounded-lg shadow-sm text-[13.5px] font-bold transition flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        Simpan
                    </button>
                    <span id="mdl-rab-indicator" class="text-emerald-600 text-[12.5px] font-bold opacity-0 transition-opacity flex items-center gap-1 ml-1">
                        Tersimpan!
                    </span>
                </div>
            </div>
            
            <hr class="border-slate-200 mb-2">
            
            <div class="w-full md:w-1/2">
                <table class="w-full text-center border-collapse border border-slate-200 rounded-lg overflow-hidden shadow-sm">
                    <thead>
                        <tr class="bg-gradient-to-r from-red-100 to-red-50 text-red-800">
                            <th colspan="2" class="border border-red-100 py-2 uppercase text-[12.5px] font-bold tracking-wide">Tanggal Proses Faston 360°</th>
                        </tr>
                        <tr class="bg-slate-50 text-slate-600">
                            <th class="border border-slate-200 py-1.5 uppercase font-bold text-[12.5px]">Mohon</th>
                            <th class="border border-slate-200 py-1.5 uppercase font-bold text-[12.5px]">Bayar</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <tr>
                            <td class="border border-slate-200 py-2 font-bold" id="mdl-tgl-mohon">2026-03-30</td>
                            <td class="border border-slate-200 py-2 font-bold" id="mdl-tgl-bayar">2026-03-30</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-2 flex justify-end">
                <button onclick="closeDetailModal()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2 rounded-lg font-bold text-sm transition focus:outline-none focus:ring-2 focus:ring-slate-300">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
function openDetailModal(item) {
    document.getElementById('mdl-up3').textContent = 'BOJONEGORO';
    document.getElementById('mdl-ulp').textContent = item.ulp || '-';
    document.getElementById('mdl-transaksi').textContent = item.transaksi || '-';
    document.getElementById('mdl-status').textContent = item.status || '-';
    document.getElementById('mdl-agenda').textContent = item.no_agenda || '-';
    document.getElementById('mdl-idpel').textContent = item.no_agenda || '-';
    document.getElementById('mdl-nama').textContent = 'Pelanggan ' + (item.no_agenda || '');
    document.getElementById('mdl-alamat').textContent = item.alamat || '-';
    document.getElementById('mdl-tbaru').textContent = item.tarif_baru || '-';
    document.getElementById('mdl-dbaru').textContent = item.daya_baru || '0';
    document.getElementById('mdl-tlama').textContent = item.tarif_lama || '-';
    document.getElementById('mdl-dlama').textContent = item.daya_lama || '0';
    
    // RAB
    var agendaKey = item.no_agenda || 'default';
    var rabVal = item.total_biaya || 0;
    if (typeof uploadedDocs !== 'undefined' && uploadedDocs[agendaKey] && uploadedDocs[agendaKey].rab) {
        rabVal = uploadedDocs[agendaKey].rab;
    }
    document.getElementById('mdl-rab-input').value = rabVal;
    
    // Store current item globally for saveRabField
    window.currentItem = item;
    
    if(item.created_at) {
        let d = new Date(item.created_at);
        let ds = d.toISOString().split('T')[0];
        document.getElementById('mdl-tgl-mohon').textContent = ds;
        document.getElementById('mdl-tgl-bayar').textContent = ds;
    }

    let m = document.getElementById('detailModal');
    if(m) {
        m.classList.remove('hidden');
        m.classList.add('flex');
    }
}
function closeDetailModal() {
    let m = document.getElementById('detailModal');
    if(m) {
        m.classList.add('hidden');
        m.classList.remove('flex');
    }
}

function saveRabField() {
    if (!window.currentItem) return;
    var agendaKey = window.currentItem.no_agenda || 'default';
    var rabVal = document.getElementById('mdl-rab-input').value;
    if (rabVal === '') rabVal = 0;
    
    if (typeof uploadedDocs === 'undefined') return;
    if (!uploadedDocs[agendaKey]) uploadedDocs[agendaKey] = { ktp: [], itt: [] };
    
    uploadedDocs[agendaKey].rab = rabVal;
    
    if (typeof saveAgendaDocs === 'function') {
        saveAgendaDocs(agendaKey).then(function() {
            var indicator = document.getElementById('mdl-rab-indicator');
            if (indicator) {
                indicator.style.opacity = '1';
                setTimeout(function() {
                    indicator.style.opacity = '0';
                }, 2000);
            }
        }).catch(function(e) {
            alert('Gagal menyimpan RAB: ' + e);
        });
    }
}
</script>

@endsection
