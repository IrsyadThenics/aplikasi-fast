@extends('layouts.app')

@section('content')
<div class="h-full flex flex-col bg-slate-50 p-5">

    {{-- ===== FILTER CARD ===== --}}
    <div class="bg-white rounded-2xl shadow-md border border-slate-200 overflow-hidden flex-shrink-0">

        {{-- Card Header --}}
        <div class="bg-gradient-to-r from-[#0D1B8C] to-[#0D1B8C] px-6 py-3 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white/80" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3" />
            </svg>
            <div>
                <p class="text-white font-bold text-sm tracking-wide">DAFTAR TRANSAKSI</p>
                <p class="text-blue-100 text-xs">Daftar Transaksi Perluasan JTR</p>
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
                    <span class="text-white font-bold text-sm tracking-wide">RECORD, JUMLAH TRANSAKSI PERLUASAN JTR</span>
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
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold whitespace-nowrap" rowspan="2">ULP</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold whitespace-nowrap" rowspan="2">TRANSAKSI</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold whitespace-nowrap" rowspan="2">STATUS</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold whitespace-nowrap" rowspan="2">NO AGENDA</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold whitespace-nowrap" rowspan="2">NAMA PELANGGAN</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold whitespace-nowrap" rowspan="2">ALAMAT</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold whitespace-nowrap" colspan="2">LAMA</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold whitespace-nowrap" colspan="2">BARU</th>
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
                                <button onclick="openDetailModal({{ json_encode($item) }})" class="text-slate-500 hover:text-blue-600 transition" title="Lihat Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                </button>
                            </td>
                            <td class="border border-slate-200 px-3 py-2 text-center whitespace-nowrap">{{ $item->ulp ?? 'LAMONGAN' }}</td>
                            <td class="border border-slate-200 px-3 py-2 text-left whitespace-nowrap">
                                <span class="px-2 py-1 rounded text-[10px] font-semibold bg-indigo-100 text-indigo-700">{{ $item->transaksi ?? 'PASANG BARU - PASANG BARU' }}</span>
                            </td>
                            <td class="border border-slate-200 px-3 py-2 text-left whitespace-nowrap">
                                <span class="px-2 py-1 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-700">{{ $item->status ?? 'BAYAR' }}</span>
                            </td>
                            <td class="border border-slate-200 px-3 py-2 text-center font-mono">{{ $item->no_agenda ?? '518039912601284909' }}</td>
                            <td class="border border-slate-200 px-3 py-2 text-left">Pelanggan {{ $item->no_agenda ?? '' }}</td>
                            <td class="border border-slate-200 px-3 py-2 text-left max-w-xs truncate" title="{{ $item->alamat }}">{{ $item->alamat ?? 'JL TAMAN PRIJEG' }}</td>
                            <td class="border border-slate-200 px-3 py-2 text-center">{{ $item->tarif_lama ?? '-' }}</td>
                            <td class="border border-slate-200 px-3 py-2 text-center">{{ $item->daya_lama ?? 0 }} VA</td>
                            <td class="border border-slate-200 px-3 py-2 text-center font-semibold text-blue-900">{{ $item->tarif_baru ?? 'B2T' }}</td>
                            <td class="border border-slate-200 px-3 py-2 text-center font-semibold text-blue-900">{{ $item->daya_baru ?? '13200' }} VA</td>
                        </tr>
                        @empty
                        <!-- Example row if data is empty -->
                        <tr class="bg-white hover:bg-slate-50 transition border-b border-slate-100 text-slate-700">
                            <td class="border border-slate-200 px-3 py-2 text-center">1.</td>
                            <td class="border border-slate-200 px-3 py-2 text-center">
                                <button onclick="openDetailModal({})" class="text-slate-500 hover:text-blue-600 transition" title="Lihat Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                </button>
                            </td>
                            <td class="border border-slate-200 px-3 py-2 text-center">LAMONGAN</td>
                            <td class="border border-slate-200 px-3 py-2 text-left whitespace-nowrap">
                                <span class="px-2 py-1 rounded text-[10px] font-semibold bg-indigo-100 text-indigo-700">PASANG BARU - PASANG BARU</span>
                            </td>
                            <td class="border border-slate-200 px-3 py-2 text-left whitespace-nowrap">
                                <span class="px-2 py-1 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-700">BAYAR</span>
                            </td>
                            <td class="border border-slate-200 px-3 py-2 text-center font-mono">518039912601284909</td>
                            <td class="border border-slate-200 px-3 py-2 text-left">SPPG TAMANPRIJEG KEC LARE</td>
                            <td class="border border-slate-200 px-3 py-2 text-left max-w-xs truncate">JL TAMAN PRIJEG TAMANPRIJEG, LAREN, KAB. LAMONGAN, JAWA TIMUR</td>
                            <td class="border border-slate-200 px-3 py-2 text-center">-</td>
                            <td class="border border-slate-200 px-3 py-2 text-center">0 VA</td>
                            <td class="border border-slate-200 px-3 py-2 text-center font-semibold text-blue-900">B2T</td>
                            <td class="border border-slate-200 px-3 py-2 text-center font-semibold text-blue-900">13200 VA</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer --}}
            <div class="border-t border-slate-100 px-5 py-2 bg-slate-50 flex-shrink-0">
                <span class="text-xs text-slate-400 font-mono">Records {{ count($data ?? []) > 0 ? "1 to " . count($data) : "1 to 1" }}</span>
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

<!-- DTL Modal (Perluasan JTR) -->
<div id="detailModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-5xl relative overflow-hidden flex flex-col border border-slate-300">
        
        <!-- Modal Content Container -->
        <div class="p-8 pt-6 text-[13px] text-slate-800 font-sans flex flex-col gap-4">
            
            <div class="absolute top-4 right-6 text-slate-700 cursor-pointer" onclick="closeDetailModal()">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 hover:text-black transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>

            <div class="inline-block bg-[#2B73FE] text-white font-bold px-4 py-1.5 rounded-full text-sm shadow-sm w-max mb-1 border-2 border-[#1c5ce6]">
                Detail Data Pelanggan
            </div>

            <div class="grid grid-cols-[160px_1fr] gap-x-2 gap-y-1 items-start mt-2">
                <div>UP3</div>
                <div>: <span id="mdl-up3">BOJONEGORO</span></div>
                <div>ULP</div>
                <div>: <span id="mdl-ulp">LAMONGAN</span></div>
            </div>
            
            <hr class="border-slate-300">
            
            <div class="grid grid-cols-[160px_1fr] gap-x-2 gap-y-1 items-start uppercase">
                <div>Transaksi</div>
                <div>: <span id="mdl-transaksi">PASANG BARU - PASANG BARU</span></div>
                <div>Status Permohonan</div>
                <div>: <span id="mdl-status">BAYAR</span></div>
            </div>

            <hr class="border-slate-300">
            
            <div class="grid grid-cols-[160px_1fr] gap-x-2 gap-y-1 items-start">
                <div class="uppercase">No. Agenda</div>
                <div>: <span id="mdl-agenda">518039912601284909</span></div>
                <div class="uppercase">ID Pelanggan</div>
                <div>: <span id="mdl-idpel">518032009932</span></div>
                <div class="uppercase">Nama</div>
                <div class="uppercase">: <span id="mdl-nama">SPPG TAMANPRIJEG KEC LARE</span></div>
                <div class="uppercase">Alamat</div>
                <div class="uppercase">: <span id="mdl-alamat">JL TAMAN PRIJEG TAMANPRIJEG, LAREN, KAB. LAMONGAN, JAWA TIMUR</span></div>
                <div class="uppercase">Tarif / Daya</div>
                <div class="uppercase">: BARU : <span id="mdl-tbaru">B2T</span> / <span id="mdl-dbaru">13200</span></div>
            </div>
            
            <br>
            
            <div class="grid grid-cols-[160px_1fr] gap-x-2 gap-y-1 items-start">
                <div class="uppercase font-semibold text-slate-800">RAB</div>
                <div class="font-semibold text-slate-800">: Rp. 12850800,-</div>
            </div>
            
            <hr class="border-slate-300 my-1 border-[1px]">
            
            <div class="w-full mt-2">
                <table class="w-full text-center border-collapse border border-slate-300">
                    <thead>
                        <tr class="bg-[#fbc2c4]">
                            <th colspan="2" class="border border-slate-300 py-1.5 uppercase text-sm font-semibold text-slate-800">PERSYARATAN PROSES FASTON 360&deg;</th>
                        </tr>
                        <tr class="bg-[#fbc2c4]">
                            <th class="border border-slate-300 py-1.5 uppercase font-medium text-sm text-slate-800 w-1/2">UPLOAD KTP</th>
                            <th class="border border-slate-300 py-1.5 uppercase font-medium text-sm text-slate-800 w-1/2">UPLOAD IJIN TANAM TIANG</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-slate-300 py-3 px-4 text-center bg-white">
                                <div class="flex items-center justify-center gap-2">
                                    <input type="file" class="text-xs border border-slate-300 p-1 w-52 bg-white text-slate-700 rounded-sm hover:cursor-pointer file:cursor-pointer file:mr-2 file:py-1 file:px-3 file:border file:border-slate-300 file:bg-slate-100 file:text-slate-700 file:rounded-sm" />
                                    <button class="bg-[#3498db] text-white px-3 py-1.5 text-xs font-semibold rounded-[3px] hover:bg-blue-600 transition shadow-sm">Upload KTP</button>
                                </div>
                            </td>
                            <td class="border border-slate-300 py-3 px-4 text-center bg-white">
                                <div class="flex items-center justify-center gap-2">
                                    <input type="file" class="text-xs border border-slate-300 p-1 w-52 bg-white text-slate-700 rounded-sm hover:cursor-pointer file:cursor-pointer file:mr-2 file:py-1 file:px-3 file:border file:border-slate-300 file:bg-slate-100 file:text-slate-700 file:rounded-sm" />
                                    <button class="bg-[#3498db] text-white px-3 py-1.5 text-xs font-semibold rounded-[3px] hover:bg-blue-600 transition shadow-sm">Upload ITT</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-slate-300 py-6 text-sm font-semibold text-slate-800 bg-white">0</td>
                            <td class="border border-slate-300 py-6 text-sm font-semibold text-slate-800 bg-white">0</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex justify-end gap-2 hidden">
                <button onclick="closeDetailModal()" class="bg-slate-500 hover:bg-slate-600 text-white px-5 py-2 rounded-lg shadow text-sm font-medium transition cursor-pointer">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
function openDetailModal(item) {
    if(item && item.no_agenda) {
        document.getElementById('mdl-transaksi').textContent = (item.transaksi || 'PASANG BARU - PASANG BARU').toUpperCase();
        document.getElementById('mdl-status').textContent = (item.status || 'BAYAR').toUpperCase();
        document.getElementById('mdl-agenda').textContent = item.no_agenda || '-';
        document.getElementById('mdl-idpel').textContent = item.no_agenda || '-';
        document.getElementById('mdl-nama').textContent = (item.nama || 'Pelanggan ' + (item.no_agenda || '')).toUpperCase();
        document.getElementById('mdl-alamat').textContent = (item.alamat || '').toUpperCase();
        document.getElementById('mdl-tbaru').textContent = item.tarif_baru || 'B2T';
        document.getElementById('mdl-dbaru').textContent = item.daya_baru || '13200';
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
