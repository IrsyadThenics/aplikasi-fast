@extends('layouts.app')

@section('content')
<div class="h-full flex flex-col pt-1">

    {{-- ===== FILTER CARD ===== --}}
    <div class="glass-card overflow-hidden flex-shrink-0 mb-6 border-0 shadow-[0_4px_20px_rgba(0,0,0,0.08)]">
        
        {{-- Card Header matching the table header style --}}
        <div class="px-6 py-3 flex items-center justify-between" style="background-color: var(--sidebar-bg); border-top-left-radius: 12px; border-top-right-radius: 12px;">
            <div class="flex items-center gap-3">
                <p class="text-white font-bold text-[0.95rem] tracking-wide">Filter Transaksi perluasan</p>
            </div>
        </div>

        {{-- Filter Fields --}}
        <div class="px-6 py-5 bg-white">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
                {{-- No Agenda --}}
                <div>
                    <label class="block text-[0.7rem] font-bold text-slate-500 mb-1.5 uppercase tracking-wider">No Agenda</label>
                    <input type="text" placeholder="Cari no. agenda..."
                        class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 bg-slate-50 text-slate-700 transition placeholder:text-slate-400" />
                </div>
                {{-- Transaksi --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase tracking-widest">Transaksi</label>
                    <select class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-slate-50 text-slate-700 transition">
                        <option>--- semua ---</option>
                        <option>Pasang baru</option>
                        <option>Perubahan daya</option>
                    </select>
                </div>
                {{-- Status Mohon --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase tracking-widest">Status Mohon</label>
                    <select class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-slate-50 text-slate-700 transition">
                        <option>--- semua ---</option>
                        <option>Mohon</option>
                        <option>Bayar</option>
                    </select>
                </div>

            {{-- Date Row --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-4 border-t border-slate-100">
                <div class="flex items-center gap-3 flex-wrap">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Tanggal Bayar :</span>
                    <div class="flex items-center gap-2 bg-[#ffece8] rounded-lg px-2 border border-red-100 text-red-700 text-sm">
                        <input type="date" value="2026-08-07" class="bg-transparent border-none focus:ring-0 text-sm py-1.5 w-[120px] cursor-pointer" />
                        <span class="text-red-300">-</span>
                        <input type="date" value="2026-08-07" class="bg-transparent border-none focus:ring-0 text-sm py-1.5 w-[120px] cursor-pointer" />
                    </div>
                </div>
                
                <button onclick="tampilkanTabel()"
                    class="bg-[#0D1B8C] hover:bg-[#FACC15] text-white text-sm font-semibold px-6 py-2 rounded-lg shadow-[0_4px_10px_rgba(59,130,246,0.3)] transition-all active:scale-95 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    Tampilkan Data
                </button>
            </div>
        </div>
    </div>

    {{-- ===== TABLE AREA ===== --}}
    <div id="tableArea" class="hidden mt-2 flex flex-col flex-1 min-h-0">
        <div class="glass-card flex flex-col flex-1 min-h-0 overflow-hidden border-0 shadow-[0_4px_20px_rgba(0,0,0,0.08)]">

            {{-- Table header bar matching image "Recent Visitor" --}}
            <div class="px-5 py-3 flex items-center justify-between flex-shrink-0" style="background-color: var(--sidebar-bg); border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <div class="flex items-center gap-3">
                    <span class="text-white font-bold text-[0.95rem] tracking-wide">Data Transaksi PB/PD</span>
                </div>
                
                <div class="flex items-center gap-3">
                    {{-- Match the search / filter dropdown from image --}}
                    <div class="relative bg-white rounded-full overflow-hidden flex items-center px-3 py-1 w-48">
                        <input type="text" placeholder="Search" class="bg-transparent border-none focus:ring-0 text-xs text-slate-700 w-full p-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                    </div>
                    
                    <button class="bg-[#3b82f6] text-white text-xs font-semibold px-3 py-1.5 rounded-full flex items-center gap-1">
                        Filter By
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    
                    <button onclick="tutupTabel()" class="ml-2 w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition text-[10px]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Scrollable Table --}}
            <div class="overflow-auto flex-1 bg-white">
                <table class="w-full text-[0.75rem] border-collapse relative">
                    <thead class="sticky top-0 z-10 bg-white shadow-sm font-bold text-slate-700">
                        <tr>
                            <th class="border-b border-slate-200 px-4 py-3 text-center whitespace-nowrap" rowspan="2">NO.</th>
                            <th class="border-b border-slate-200 px-4 py-3 text-center whitespace-nowrap" rowspan="2">DTL</th>
                            <th class="border-b border-slate-200 px-4 py-2 text-center whitespace-nowrap" colspan="2">SYARAT</th>
                            <th class="border-b border-slate-200 px-4 py-3 text-left whitespace-nowrap" rowspan="2">TRANSAKSI</th>
                            <th class="border-b border-slate-200 px-4 py-3 text-center whitespace-nowrap" rowspan="2">STATUS</th>
                            <th class="border-b border-slate-200 px-4 py-3 text-center whitespace-nowrap" rowspan="2">NO AGENDA</th>
                            <th class="border-b border-slate-200 px-4 py-3 text-left whitespace-nowrap" rowspan="2">NAMA PELANGGAN</th>
                            <th class="border-b border-slate-200 px-4 py-3 text-left whitespace-nowrap" rowspan="2">ALAMAT</th>
                            <th class="border-b border-slate-200 px-4 py-2 text-center whitespace-nowrap" colspan="2">LAMA</th>
                            <th class="border-b border-slate-200 px-4 py-2 text-center whitespace-nowrap" colspan="2">BARU</th>
                            <th class="border-b border-slate-200 px-4 py-3 text-center whitespace-nowrap" rowspan="2">KET</th>
                        </tr>
                        <tr>
                            <th class="border-b border-slate-200 px-3 py-1.5 text-center text-[0.7rem]">KTP</th>
                            <th class="border-b border-slate-200 px-3 py-1.5 text-center text-[0.7rem]">IZIN</th>
                            <th class="border-b border-slate-200 px-3 py-1.5 text-center text-[0.7rem]">TARIF</th>
                            <th class="border-b border-slate-200 px-3 py-1.5 text-center text-[0.7rem]">DAYA</th>
                            <th class="border-b border-slate-200 px-3 py-1.5 text-center text-[0.7rem]">TARIF</th>
                            <th class="border-b border-slate-200 px-3 py-1.5 text-center text-[0.7rem]">DAYA</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @forelse($data ?? [] as $index => $item)
                        <tr class="bg-white hover:bg-slate-50 transition border-b border-slate-100 text-slate-700">
                            <td class="border border-slate-200 px-3 py-2 text-center">{{ $index + 1 }}.</td>
                            <td class="border border-slate-200 px-3 py-2 text-center">
                                @if(strtolower($item->dtl) === 'ada' || strtolower($item->dtl) === 'tidak ada' || true)
                                <button onclick="openDetailModal({{ json_encode($item) }})" class="text-slate-500 hover:text-blue-600 transition" title="Lihat Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                </button>
                                @endif
                            </td>
                            <td class="border border-slate-200 px-3 py-2 text-center">
                                <button onclick="openDetailModal({{ json_encode($item) }})" class="text-indigo-500 hover:text-indigo-700 transition" title="KTP">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-auto" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                      <rect x="2" y="6" width="20" height="12" rx="2" ry="2"></rect>
                                      <circle cx="8" cy="12" r="3"></circle>
                                      <line x1="14" y1="10" x2="19" y2="10"></line>
                                      <line x1="14" y1="14" x2="19" y2="14"></line>
                                    </svg>
                                </button>
                            </td>
                            <td class="border border-slate-200 px-3 py-2 text-center">
                                <button onclick="openDetailModal({{ json_encode($item) }})" class="text-teal-500 hover:text-teal-700 transition" title="IZIN">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-auto" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                      <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                      <polyline points="14 2 14 8 20 8"></polyline>
                                      <line x1="16" y1="13" x2="8" y2="13"></line>
                                      <line x1="16" y1="17" x2="8" y2="17"></line>
                                      <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                </button>
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
            <div class="px-5 py-3 bg-slate-50 flex items-center justify-between border-t border-slate-200">
                <span class="text-xs text-slate-500 font-medium font-mono">0 record(s)</span>
                <span class="text-[0.65rem] text-slate-400">FASTON360</span>
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
<div id="detailModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-5xl relative overflow-hidden flex flex-col border border-slate-300">
        <!-- Title Badge -->
        <div class="absolute -top-0 -left-0">
            <div class="bg-[#2B73FE] text-white font-bold px-5 py-1.5 rounded-br-2xl text-sm border-r-2 border-b-2 border-white shadow-sm flex items-center gap-1">
                Detail Data Pelanggan
            </div>
        </div>
        
        <!-- Content -->
        <div class="p-6 pt-12 text-xs text-slate-800 font-mono flex flex-col gap-3 relative">
            
            <div class="absolute top-4 right-4 text-amber-900 bg-amber-50 p-2 rounded cursor-pointer border border-amber-200 hover:bg-amber-100 transition" onclick="closeDetailModal()">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </div>

            <div class="grid grid-cols-[160px_1fr] gap-1">
                <div class="font-semibold uppercase">UP3</div>
                <div class="uppercase">: <span id="mdl-up3">BOJONEGORO</span></div>
                <div class="font-semibold uppercase">ULP</div>
                <div class="uppercase">: <span id="mdl-ulp">LAMONGAN</span></div>
            </div>
            
            <hr class="border-slate-300 border-dashed my-1">
            
            <div class="grid grid-cols-[160px_1fr] gap-1">
                <div class="font-semibold uppercase">Transaksi</div>
                <div class="uppercase">: <span id="mdl-transaksi"></span></div>
                <div class="font-semibold uppercase">Status Permohonan</div>
                <div class="uppercase">: <span id="mdl-status"></span></div>
            </div>

            <hr class="border-slate-300 border-dashed my-1">
            
            <div class="grid grid-cols-[160px_1fr] gap-1">
                <div class="font-semibold uppercase">No. Agenda</div>
                <div class="uppercase">: <span id="mdl-agenda"></span></div>
                <div class="font-semibold uppercase">ID Pelanggan</div>
                <div class="uppercase">: <span id="mdl-idpel"></span></div>
                <div class="font-semibold uppercase">Nama</div>
                <div class="uppercase">: <span id="mdl-nama"></span></div>
                <div class="font-semibold uppercase">Alamat</div>
                <div class="uppercase">: <span id="mdl-alamat"></span></div>
                <div class="font-semibold uppercase">Tarif / Daya</div>
                <div class="uppercase">: BARU : <span id="mdl-tbaru"></span> / <span id="mdl-dbaru"></span> <span class="text-slate-300 mx-1">|</span> LAMA : <span id="mdl-tlama"></span> / <span id="mdl-dlama"></span></div>
            </div>
            
            <hr class="border-slate-300 border-dashed my-1">
            
            <div class="grid grid-cols-[160px_1fr] gap-1 mb-2">
                <div class="font-semibold uppercase">RAB</div>
                <div>: Rp. <span id="mdl-rab">12850800</span>,-</div>
            </div>
            
            <div class="w-full overflow-hidden border border-slate-300">
                <table class="w-full text-center border-collapse">
                    <thead>
                        <tr class="bg-[#ffb6c1]">
                            <th colspan="2" class="border border-slate-300 py-2 uppercase text-xs tracking-wide">Persyaratan Proses Faston 360°</th>
                        </tr>
                        <tr class="bg-[#ffb6c1]">
                            <th class="border border-slate-300 py-1.5 uppercase font-medium text-[11px] w-1/2">Upload KTP</th>
                            <th class="border border-slate-300 py-1.5 uppercase font-medium text-[11px] w-1/2">Upload Ijin Tanam Tiang</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white">
                            <td class="border border-slate-300 py-2 px-3">
                                <div class="flex items-center justify-center gap-2">
                                    <input type="file" class="text-[10px] w-full max-w-[200px] border border-slate-300 bg-white file:cursor-pointer file:border-0 file:bg-slate-100 file:text-slate-700 file:px-2 file:py-1 file:mr-2 placeholder:text-slate-500 rounded-sm focus:outline-none focus:border-blue-400" />
                                    <button class="bg-[#2B73FE] text-white text-[10px] px-3 py-1 font-bold rounded-sm whitespace-nowrap shadow-sm hover:bg-blue-600 transition">Upload KTP</button>
                                </div>
                            </td>
                            <td class="border border-slate-300 py-2 px-3">
                                <div class="flex items-center justify-center gap-2">
                                    <input type="file" class="text-[10px] w-full max-w-[200px] border border-slate-300 bg-white file:cursor-pointer file:border-0 file:bg-slate-100 file:text-slate-700 file:px-2 file:py-1 file:mr-2 placeholder:text-slate-500 rounded-sm focus:outline-none focus:border-blue-400" />
                                    <button class="bg-[#2B73FE] text-white text-[10px] px-3 py-1 font-bold rounded-sm whitespace-nowrap shadow-sm hover:bg-blue-600 transition">Upload ITT</button>
                                </div>
                            </td>
                        </tr>
                        <tr class="bg-white font-bold">
                            <td class="border border-slate-300 py-3">0</td>
                            <td class="border border-slate-300 py-3">0</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-2 flex justify-end">
                <button onclick="closeDetailModal()" class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-1.5 rounded shadow text-sm transition">Tutup Dialog</button>
            </div>
        </div>
    </div>
</div>

<script>
function openDetailModal(item) {
    document.getElementById('mdl-up3').textContent = 'BOJONEGORO';
    document.getElementById('mdl-ulp').textContent = item.ulp || 'LAMONGAN';
    document.getElementById('mdl-transaksi').textContent = item.transaksi ? item.transaksi + ' - ' + item.transaksi : '-';
    document.getElementById('mdl-status').textContent = item.status || '-';
    document.getElementById('mdl-agenda').textContent = item.no_agenda || '-';
    document.getElementById('mdl-idpel').textContent = item.no_agenda ? item.no_agenda.toString().replace('9', '2') : '-';
    document.getElementById('mdl-nama').textContent = 'SPPG TAMANPRIJEG KEC LARE (Pelanggan ' + (item.no_agenda || '') + ')';
    document.getElementById('mdl-alamat').textContent = item.alamat || '-';
    document.getElementById('mdl-tbaru').textContent = item.tarif_baru || '-';
    document.getElementById('mdl-dbaru').textContent = item.daya_baru || '0';
    document.getElementById('mdl-tlama').textContent = item.tarif_lama || '-';
    document.getElementById('mdl-dlama').textContent = item.daya_lama || '0';
    
    document.getElementById('mdl-rab').textContent = Math.floor(Math.random() * 20000000 + 5000000);

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
</script>

@endsection
