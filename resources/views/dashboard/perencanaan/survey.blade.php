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
                <p class="text-white font-bold text-sm tracking-wide">DAFTAR RESTITUSI</p>
                <p class="text-blue-100 text-xs">Daftar Restitusi UP3 Bojonegoro</p>
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
    <div class="bg-white w-full max-w-5xl relative border border-slate-300 shadow-xl my-4">
        <!-- Title Bar -->
        <div class="bg-[#2B73FE] text-white font-bold px-3 py-1.5 text-[13px] flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Detail Proses FASTON 360°
            <button onclick="closeDetailModal()" class="ml-auto text-white hover:text-red-200 transition font-normal text-lg leading-none">&times;</button>
        </div>

        <div class="absolute top-10 right-6 text-blue-700 font-bold text-xl uppercase tracking-widest z-10" id="mdl-hasil-survey-badge">
            LAYAK
        </div>

        <!-- Info Grid -->
        <div class="px-4 py-3 text-[11px] font-mono text-slate-800 grid grid-cols-[160px_1fr] gap-y-0.5">
            <div class="font-semibold uppercase">UP3</div>
            <div class="uppercase">: <span id="mdl-up3">BOJONEGORO</span></div>
            <div class="font-semibold uppercase">ULP</div>
            <div class="uppercase">: <span id="mdl-ulp">LAMONGAN</span></div>

            <div class="col-span-2 border-t border-dashed border-slate-300 my-1"></div>

            <div class="font-semibold uppercase">Transaksi</div>
            <div class="uppercase">: <span id="mdl-transaksi"></span></div>
            <div class="font-semibold uppercase">Status Permohonan</div>
            <div class="uppercase">: <span id="mdl-status"></span></div>

            <div class="col-span-2 border-t border-dashed border-slate-300 my-1"></div>

            <div class="font-semibold uppercase">No. Agenda</div>
            <div>: <span id="mdl-agenda"></span></div>
            <div class="font-semibold uppercase">ID Pelanggan</div>
            <div>: <span id="mdl-idpel"></span></div>
            <div class="font-semibold uppercase">Nama</div>
            <div class="uppercase">: <span id="mdl-nama"></span></div>
            <div class="font-semibold uppercase">Alamat</div>
            <div class="uppercase break-all">: <span id="mdl-alamat"></span></div>
            <div class="font-semibold uppercase">Tarif / Daya</div>
            <div>: <span id="mdl-tarifinfo"></span></div>

            <div class="col-span-2 border-t border-dashed border-slate-300 my-1"></div>

            <div class="font-semibold uppercase">RAB</div>
            <div>: Rp. <span id="mdl-rab">-</span>,-</div>

            <div class="col-span-2 border-t border-dashed border-slate-300 my-1"></div>

            <div class="font-semibold uppercase">KTP</div>
            <div>: <span id="mdl-ktp" class="text-slate-800">-</span></div>
            <div class="font-semibold uppercase">Ijin Tanam Tiang</div>
            <div>: <span id="mdl-itt" class="text-slate-800">-</span></div>

            <div class="col-span-2 border-t border-dashed border-slate-300 my-1"></div>

            <div class="font-semibold uppercase">Vendor Tiang</div>
            <div class="flex items-center gap-1">:
                <select class="ml-1 border border-slate-300 rounded px-2 py-0.5 text-[11px] bg-white text-slate-800 focus:outline-none">
                    <option>PT. JAYA BETON</option>
                    <option>PT. OTHER</option>
                </select>
            </div>
        </div>

        <!-- TANGGAL PROSES FASTON 360 Table -->
        <div class="px-4 pb-6">
            <table class="w-full border-collapse text-[10.5px] text-center font-mono">
                <thead>
                    <tr class="bg-[#ffb6c1]">
                        <th colspan="6" class="border border-slate-400 py-1.5 uppercase font-bold tracking-wide">Tanggal Proses FASTON 360°</th>
                    </tr>
                    <tr class="bg-[#ffb6c1]">
                        <th class="border border-slate-400 px-2 py-1 uppercase font-semibold">Mohon</th>
                        <th class="border border-slate-400 px-2 py-1 uppercase font-semibold">Bayar</th>
                        <th class="border border-slate-400 px-2 py-1 uppercase font-semibold">PK</th>
                        <th class="border border-slate-400 px-2 py-1 uppercase font-semibold">Nyala</th>
                        <th class="border border-slate-400 px-2 py-1 uppercase font-semibold">PDL</th>
                        <th class="border border-slate-400 px-2 py-1 uppercase font-semibold">Update</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-white">
                        <td class="border border-slate-400 px-2 py-1" id="mdl-tgl-mohon">-</td>
                        <td class="border border-slate-400 px-2 py-1" id="mdl-tgl-bayar">-</td>
                        <td class="border border-slate-400 px-2 py-1">1900-01-01</td>
                        <td class="border border-slate-400 px-2 py-1">1900-01-01</td>
                        <td class="border border-slate-400 px-2 py-1"></td>
                        <td class="border border-slate-400 px-2 py-1" id="mdl-tgl-update">-</td>
                    </tr>
                    <tr class="bg-slate-100 font-semibold">
                        <td class="border border-slate-400 px-2 py-1 uppercase">KRM KE UP3</td>
                        <td class="border border-slate-400 px-2 py-1 uppercase">Survey</td>
                        <td class="border border-slate-400 px-2 py-1 uppercase">Checklist</td>
                        <td class="border border-slate-400 px-2 py-1 uppercase">BA Ops</td>
                        <td colspan="2" class="border border-slate-400 px-2 py-1 uppercase">Ket</td>
                    </tr>
                    <tr class="font-mono">
                        <td class="border border-slate-400 px-2 py-1" id="mdl-tgl-krm">-</td>
                        <td class="border border-slate-400 px-2 py-1 bg-cyan-300 font-bold" id="mdl-tgl-survey">-</td>
                        <td class="border border-slate-400 px-2 py-1" id="mdl-tgl-survey2"></td>
                        <td class="border border-slate-400 px-2 py-1" id="mdl-tgl-survey3"></td>
                        <td colspan="2" class="border border-slate-400 px-2 py-1 bg-yellow-300 font-bold" id="mdl-ket">(*) Perluasan JTR</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function openDetailModal(item) {
    document.getElementById('mdl-up3').textContent = 'BOJONEGORO';
    document.getElementById('mdl-ulp').textContent = item.ulp || 'LAMONGAN';
    document.getElementById('mdl-transaksi').textContent = (item.transaksi || '-') + ' - ' + (item.transaksi || '-');
    document.getElementById('mdl-status').textContent = item.status || '-';
    document.getElementById('mdl-agenda').textContent = item.no_agenda || '-';
    
    let noAgenda = (item.no_agenda || '').toString();
    document.getElementById('mdl-idpel').textContent = noAgenda ? noAgenda.substring(0,3) + '031' + noAgenda.substring(7) : '-';
    
    document.getElementById('mdl-nama').textContent = item.nama || ('Pelanggan ' + (item.no_agenda || ''));
    document.getElementById('mdl-alamat').textContent = item.alamat || '-';
    
    let tLama = item.tarif_lama || 'B1T';
    let dLama = item.daya_lama || '900';
    let tBaru = item.tarif_baru || 'B2T';
    let dBaru = item.daya_baru || '16500';
    document.getElementById('mdl-tarifinfo').textContent = 'BARU : ' + tBaru + ' / ' + dBaru + ' | LAMA : ' + tLama + ' / ' + dLama;
    
    document.getElementById('mdl-rab').textContent = item.rab ? new Intl.NumberFormat('id-ID').format(item.rab) : new Intl.NumberFormat('id-ID').format(15326400);
    document.getElementById('mdl-ktp').textContent = 'KTP_' + (item.no_agenda || '000119') + 'd3f082a175d.PDF';

    let d = new Date();
    if (item.created_at) {
        d = new Date(item.created_at);
    }
    
    // Format dates manually to match logic (simulate history)
    let ds = d.toISOString().split('T')[0];
    
    let dMohon = new Date(d); dMohon.setDate(dMohon.getDate() - 60);
    let dBayar = new Date(d); dBayar.setDate(dBayar.getDate() - 57);
    let dKrm = new Date(d); dKrm.setDate(dKrm.getDate() - 14);
    let dsMohon = dMohon.toISOString().split('T')[0];
    let dsBayar = dBayar.toISOString().split('T')[0];
    let dsKrm = dKrm.toISOString().split('T')[0];
    
    document.getElementById('mdl-tgl-mohon').textContent = dsMohon;
    document.getElementById('mdl-tgl-bayar').textContent = dsBayar;
    document.getElementById('mdl-tgl-update').textContent = dsBayar;
    document.getElementById('mdl-tgl-krm').textContent = dsKrm;
    document.getElementById('mdl-tgl-survey').textContent = ds;
    document.getElementById('mdl-tgl-survey2').textContent = ds;
    document.getElementById('mdl-tgl-survey3').textContent = ds;
    
    document.getElementById('mdl-hasil-survey-badge').textContent = 'LAYAK';
    document.getElementById('mdl-hasil-survey-badge').className = 'absolute top-10 right-6 text-blue-700 font-bold text-xl uppercase tracking-widest z-10';

    let m = document.getElementById('detailModal');
    if (m) {
        m.classList.remove('hidden');
        m.classList.add('flex');
    }
}
function closeDetailModal() {
    let m = document.getElementById('detailModal');
    if (m) {
        m.classList.add('hidden');
        m.classList.remove('flex');
    }
}
</script>

@endsection
