@extends('layouts.app')

@section('content')
<div class="h-full flex flex-col bg-slate-50 p-5">

    {{-- ===== FILTER CARD ===== --}}
    <div class="bg-white rounded-2xl shadow-md border border-slate-200 overflow-hidden flex-shrink-0">

        {{-- Card Header --}}
        <div class="bg-gradient-to-r from-[#0D1B8C] to-[#0D1B8C] px-6 py-3 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white/80" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
            <div>
                <p class="text-white font-bold text-sm tracking-wide">QUICK ACCESS</p>
                <p class="text-blue-100 text-xs">Full Acceleration & Service Tracking ON 360°</p>
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
                {{-- ID Pelanggan --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase tracking-widest">ID Pelanggan</label>
                    <input type="text" placeholder="Cari ID pelanggan..."
                        class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-slate-50 text-slate-700 transition" />
                </div>
                {{-- Status Mohon --}}
                <!--<div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase tracking-widest">Status Mohon</label>
                    <input type="text" placeholder="Status..."
                        class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-slate-50 text-slate-700 transition" />
                </div>-->
            </div>

            {{-- Date Row --}}
            <div class="flex items-center gap-3 flex-wrap pt-2">
                <!-- Date inputs hidden for now 
                <span class="text-sm font-medium text-slate-600">Tanggal Bayar : Dari</span>
                <input type="date" value="2026-08-07"
                    class="border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-slate-50 text-slate-700 transition" />
                <span class="text-sm text-slate-500 font-medium">s/d</span>
                <input type="date" value="2026-08-07"
                    class="border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-slate-50 text-slate-700 transition" />-->
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
<div id="detailModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl relative overflow-hidden flex flex-col border border-slate-300">
        <!-- Title Badge -->
        <div class="absolute -top-0 -left-0">
            <div class="bg-[#2B73FE] text-white font-bold px-5 py-1.5 rounded-br-2xl text-sm border-r-2 border-b-2 border-white shadow-sm flex items-center gap-1">
                Detail Data Pelanggan
            </div>
        </div>
        
        <!-- Content -->
        <div class="p-6 pt-12 text-xs text-slate-800 font-mono flex flex-col gap-4">
            
            <div class="absolute top-4 right-4 text-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>

            <div class="grid grid-cols-[150px_1fr] gap-1">
                <div class="font-semibold">UP3</div>
                <div>: <span id="mdl-up3">BOJONEGORO</span></div>
                <div class="font-semibold">ULP</div>
                <div>: <span id="mdl-ulp">LAMONGAN</span></div>
            </div>
            
            <hr class="border-slate-300">
            
            <div class="grid grid-cols-[150px_1fr] gap-1">
                <div class="font-semibold uppercase">Transaksi</div>
                <div class="uppercase">: <span id="mdl-transaksi"></span></div>
                <div class="font-semibold uppercase">Status Permohonan</div>
                <div class="uppercase">: <span id="mdl-status"></span></div>
            </div>

            <hr class="border-slate-300">
            
            <div class="grid grid-cols-[150px_1fr] gap-1">
                <div class="font-semibold uppercase">No. Agenda</div>
                <div>: <span id="mdl-agenda"></span></div>
                <div class="font-semibold uppercase">ID Pelanggan</div>
                <div>: <span id="mdl-idpel"></span></div>
                <div class="font-semibold uppercase">Nama</div>
                <div class="uppercase">: <span id="mdl-nama"></span></div>
                <div class="font-semibold uppercase">Alamat</div>
                <div class="uppercase">: <span id="mdl-alamat"></span></div>
                <div class="font-semibold uppercase">Tarif / Daya</div>
                <div class="uppercase">: BARU : <span id="mdl-tbaru"></span> / <span id="mdl-dbaru"></span> <span class="text-slate-300 mx-1">|</span> LAMA : <span id="mdl-tlama"></span> / <span id="mdl-dlama"></span></div>
            </div>
            
            <hr class="border-slate-300">
            
            <div class="grid grid-cols-[150px_1fr] gap-1">
                <div class="font-semibold uppercase">RAB</div>
                <div>: Rp. 20000,-</div>
            </div>
            
            <hr class="border-slate-300 mb-2">
            
            <div class="w-1/2">
                <table class="w-full text-center border-collapse border border-slate-400">
                    <thead>
                        <tr class="bg-red-200">
                            <th colspan="2" class="border border-slate-400 py-1 uppercase text-[11px] font-bold">Tanggal Proses Faston 360°</th>
                        </tr>
                        <tr class="bg-slate-100">
                            <th class="border border-slate-400 py-1 uppercase font-semibold text-[11px]">Mohon</th>
                            <th class="border border-slate-400 py-1 uppercase font-semibold text-[11px]">Bayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-slate-400 py-1" id="mdl-tgl-mohon">2026-03-30</td>
                            <td class="border border-slate-400 py-1" id="mdl-tgl-bayar">2026-03-30</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex justify-end">
                <button onclick="closeDetailModal()" class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-1.5 rounded shadow text-sm transition">Tutup</button>
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
</script>

@endsection
