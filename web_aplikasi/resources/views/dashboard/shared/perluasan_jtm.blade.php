@extends('layouts.app')

@section('content')
<div class="h-full flex flex-col bg-slate-50 p-5">

    {{-- ===== FILTER CARD ===== --}}
    <div class="bg-white rounded-2xl shadow-md border border-slate-200 overflow-hidden flex-shrink-0">
        {{-- Card Header --}}
        <div class="bg-gradient-to-r from-[#0D1B8C] to-[#2B73FE] px-6 py-3 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white/80" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3" />
            </svg>
            <div>
                <p class="text-white font-bold text-sm tracking-wide">PERLUASAN JTM</p>
                <p class="text-blue-100 text-xs">Data yang dikirim ke Jaringan Tegangan Menengah</p>
            </div>
        </div>

        {{-- Filter Fields --}}
        <div class="px-6 py-4">
            <div class="grid grid-cols-3 gap-4 mb-4">
                {{-- No Agenda --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase tracking-widest">No Agenda</label>
                    <input type="text" id="filterAgenda" placeholder="Cari no. agenda..." oninput="filterData()"
                        class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-slate-50 text-slate-700 transition" />
                </div>
                 {{-- Transaksi --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase tracking-widest">Transaksi</label>
                    <select id="filterTransaksi" onchange="filterData()" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-slate-50 text-slate-700 transition">
                        <option value="">--- semua ---</option>
                        <option value="pasang baru">Pasang baru</option>
                        <option value="perubahan daya">Perubahan daya</option>
                    </select>
                </div>
                {{-- Status --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase tracking-widest">Status</label>
                    <select id="filterStatus" onchange="filterData()" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-slate-50 text-slate-700 transition">
                        <option value="">--- semua ---</option>
                        <option value="mohon">Mohon</option>
                        <option value="bayar">Bayar</option>
                    </select>
                </div>
            </div>

            {{-- Date Row --}}
            <div class="flex items-center gap-3 flex-wrap">
                <span class="text-sm font-medium text-slate-600">Tanggal Kirim : Dari</span>
                <input type="date" id="filterDateStart" onchange="filterData()"
                    class="border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-slate-50 text-slate-700 transition" />
                <span class="text-sm text-slate-500 font-medium">s/d</span>
                <input type="date" id="filterDateEnd" onchange="filterData()"
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
                    <span class="text-white font-bold text-sm tracking-wide">RECORD, JUMLAH TRANSAKSI PERLUASAN JTM</span>
                    <span class="bg-white/20 text-white text-xs px-2 py-0.5 rounded-full font-mono" id="recordCount">0 data</span>
                </div>
            </div>

            {{-- Scrollable Table --}}
            <div class="overflow-auto flex-1">
                <table class="w-full text-xs border-collapse">
                    <thead class="sticky top-0 z-10">
                        <tr class="bg-[#0D1B8C] text-white">
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold" rowspan="2">NO.</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold" rowspan="2">NO AGENDA</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold" rowspan="2">DETAIL</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold" rowspan="2">NAMA PELANGGAN</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold" rowspan="2">ALAMAT</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold" rowspan="2">TRANSAKSI</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold" rowspan="2">STATUS</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold" colspan="2">LAMA</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold" colspan="2">BARU</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold" rowspan="2">BERKAS</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold" rowspan="2">TANGGAL KIRIM</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold" rowspan="2">ASAL ULP</th>
                        </tr>
                        <tr class="bg-[#0D1B8C] text-white">
                            <th class="border border-blue-600 px-3 py-1.5 text-center font-medium">TARIF</th>
                            <th class="border border-blue-600 px-3 py-1.5 text-center font-medium">DAYA</th>
                            <th class="border border-blue-600 px-3 py-1.5 text-center font-medium">TARIF</th>
                            <th class="border border-blue-600 px-3 py-1.5 text-center font-medium">DAYA</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr id="emptyRow">
                            <td colspan="15" class="text-center py-12 text-slate-400 italic text-xs">
                                <div class="flex flex-col items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                    </svg>
                                    <span>Belum ada data yang dikirim ke JTM</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Footer --}}
            <div class="border-t border-slate-100 px-5 py-2 bg-slate-50 flex-shrink-0">
                <span class="text-xs text-slate-400 font-mono" id="footerCount">Records 0 to 0 of 0</span>
            </div>

        </div>
    </div>

</div>

<script>
var _currentRole = '{{ Auth::user()->role ?? '' }}';
var _ulpRoleMap = {
    'managerULP': 'LAMONGAN',
    'managerULP_babat': 'BABAT',
    'managerULP_brondong': 'BRONDONG',
    'managerULP_padangan': 'PADANGAN',
    'managerULP_bjn': 'BOJONEGORO',
    'managerULP_sumberejo': 'SUMBEREJO',
    'managerULP_tuban': 'TUBAN',
    'managerULP_jatirogo': 'JATIROGO',
};
var _ulpRoleFilter = _ulpRoleMap[_currentRole] || null;
</script>
<script>
(function() {
    var DB_NAME    = 'FastOnDocs';
    var SENT_STORE = 'sentItems';
    var allItems   = [];

    function openDB() {
        return new Promise(function(resolve, reject) {
            var req = indexedDB.open(DB_NAME, 2);
            req.onupgradeneeded = function(e) {
                var db = e.target.result;
                if (!db.objectStoreNames.contains('uploadedDocs')) db.createObjectStore('uploadedDocs', { keyPath: 'agendaKey' });
                if (!db.objectStoreNames.contains(SENT_STORE))    db.createObjectStore(SENT_STORE,    { keyPath: 'agendaKey' });
            };
            req.onsuccess = function(e) { resolve(e.target.result); };
            req.onerror   = function(e) { reject(e); };
        });
    }

    function loadSentItems(dest) {
        var rolePrefix = window.location.pathname.split('/')[1] || 'ulp';
        return fetch('/' + rolePrefix + '/api/get-pengiriman?dest=' + dest)
            .then(function(res) { return res.json(); });
    }

    function formatDate(iso) {
        if (!iso) return '-';
        var d = new Date(iso);
        return d.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' }) + ' ' +
               d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    }

    function renderTable(items) {
        var tbody    = document.getElementById('tableBody');
        var emptyRow = document.getElementById('emptyRow');
        var countEl  = document.getElementById('recordCount');
        var footerEl = document.getElementById('footerCount');

        var existingRows = tbody.querySelectorAll('tr:not(#emptyRow)');
        existingRows.forEach(function(r) { r.remove(); });

        if (!items || items.length === 0) {
            if (emptyRow) emptyRow.style.display = '';
            if (countEl)  countEl.textContent = '0 data';
            if (footerEl) footerEl.textContent = 'Records 0 to 0 of 0';
            return;
        }

        if (emptyRow) emptyRow.style.display = 'none';
        if (countEl)  countEl.textContent = items.length + ' data';
        if (footerEl) footerEl.textContent = 'Records 1 to ' + items.length + ' of ' + items.length;

        items.forEach(function(item, i) {
            var tr = document.createElement('tr');
            tr.className = 'bg-white hover:bg-slate-50 transition border-b border-slate-100 text-slate-700';
            var agendaKey = (item.no_agenda || '').replace(/'/g, "\\'");
            var itemJson = JSON.stringify(item).replace(/\\/g, '\\\\').replace(/'/g, "\\'");
            tr.innerHTML =
                '<td class="border border-slate-200 px-3 py-2 text-center text-slate-400 font-mono">' + (i+1) + '.</td>' +
                '<td class="border border-slate-200 px-3 py-2 text-center font-medium font-mono text-[11px]">' + (item.no_agenda || '-') + '</td>' +
                '<td class="border border-slate-200 px-3 py-2 text-center">' +
                    '<button onclick=\'openDetailModal_jtm(' + itemJson + ')\' class="text-slate-500 hover:text-blue-600 transition" title="Lihat Detail">' +
                    '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>' +
                    '</button></td>' +
                '<td class="border border-slate-200 px-3 py-2 text-left">' + (item.nama || '-') + '</td>' +
                '<td class="border border-slate-200 px-3 py-2 text-left max-w-xs truncate" title="' + (item.alamat || '') + '">' + (item.alamat || '-') + '</td>' +
                '<td class="border border-slate-200 px-3 py-2 text-center">' + (item.transaksi || '-') + '</td>' +
                '<td class="border border-slate-200 px-3 py-2 text-center">' + (item.status || '-') + '</td>' +
                '<td class="border border-slate-200 px-3 py-2 text-center">' + (item.tarif_lama || '-') + '</td>' +
                '<td class="border border-slate-200 px-3 py-2 text-center">' + (item.daya_lama || '-') + ' VA</td>' +
                '<td class="border border-slate-200 px-3 py-2 text-center font-semibold text-blue-900">' + (item.tarif_baru || '-') + '</td>' +
                '<td class="border border-slate-200 px-3 py-2 text-center font-semibold text-blue-900">' + (item.daya_baru || '-') + ' VA</td>' +
                '<td class="border border-slate-200 px-3 py-2 text-center">' +
                    '<button onclick="showBerkasModal(\'' + agendaKey + '\')" class="inline-flex items-center gap-1 text-[10px] cursor-pointer hover:opacity-80 transition">' +
                    '<span class="' + (item.ktpCount > 0 ? 'bg-emerald-500 text-white' : 'bg-emerald-100 text-emerald-700') + ' px-1.5 py-0.5 rounded font-semibold">BP:' + (item.ktpCount || 0) + '</span>' +
                    '<span class="' + (item.ittCount > 0 ? 'bg-amber-500 text-white' : 'bg-amber-100 text-amber-700') + ' px-1.5 py-0.5 rounded font-semibold">ITT:' + (item.ittCount || 0) + '</span>' +
                    '</button></td>' +
                '<td class="border border-slate-200 px-3 py-2 text-center text-[10px] text-slate-500 whitespace-nowrap">' + formatDate(item.sentAt) + '</td>' +
                '<td class="border border-slate-200 px-3 py-2 text-center"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800 whitespace-nowrap">' + (item.ulp || '-') + '</span></td>';
            tbody.appendChild(tr);
        });
    }

    window.filterData = function() {
        var agendaFilter = (document.getElementById('filterAgenda').value || '').toLowerCase();
        var transFilter  = (document.getElementById('filterTransaksi').value || '').toLowerCase();
        var statusFilter = (document.getElementById('filterStatus').value || '').toLowerCase();
        var dateStart    = document.getElementById('filterDateStart').value;
        var dateEnd      = document.getElementById('filterDateEnd').value;

        var filtered = allItems.filter(function(item) {
            var matchAgenda = true, matchTrans = true, matchStatus = true, matchDate = true;
            if (agendaFilter) matchAgenda = (item.no_agenda || '').toLowerCase().includes(agendaFilter);
            if (transFilter)  matchTrans  = (item.transaksi || '').toLowerCase().includes(transFilter);
            if (statusFilter) matchStatus = (item.status || '').toLowerCase().includes(statusFilter);
            if (dateStart && dateEnd && item.sentAt) {
                var sentDate = item.sentAt.split('T')[0];
                matchDate = (sentDate >= dateStart && sentDate <= dateEnd);
            }
            return matchAgenda && matchTrans && matchStatus && matchDate;
        });

        renderTable(filtered);
    };

    window.tampilkanTabel = function() {
        var tbl = document.getElementById('tableArea');
        if (tbl) tbl.classList.remove('hidden');
        filterData();
    };

    document.addEventListener('DOMContentLoaded', function() {
        loadSentItems('jtm').then(function(items) {
            allItems = items;
        }).catch(function(e) {
            console.warn('Error loading JTM data:', e);
        });
    });
})();
</script>

<!-- Berkas Modal -->
<div id="fileModal" class="fixed inset-0 z-[110] hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg relative border border-slate-200 overflow-hidden">
        <div class="bg-gradient-to-r from-[#0D1B8C] to-[#2B73FE] px-5 py-3 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="text-white font-bold text-sm">Berkas Pelanggan</span>
                <span id="fileMdlAgenda" class="text-blue-200 text-xs font-mono"></span>
            </div>
            <button onclick="closeFileModal()" class="text-white/70 hover:text-white p-1 rounded-full hover:bg-white/20 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-5">
            <ul id="fileList" class="divide-y divide-slate-100 max-h-72 overflow-y-auto"></ul>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div id="detailModal_jtm" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4 overflow-y-auto">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl relative overflow-hidden flex flex-col border border-slate-200">
        <!-- Title Bar -->
        <div class="bg-gradient-to-r from-[#0D1B8C] to-[#2B73FE] px-5 py-3 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span class="text-white font-bold text-sm tracking-wide">Detail Data Pelanggan</span>
            </div>
            <button onclick="closeDetailModal_jtm()" class="text-white/70 hover:text-white p-1 rounded-full hover:bg-white/20 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Content -->
        <div class="p-6 text-[13.5px] text-slate-800 font-mono flex flex-col gap-4">

            <div class="grid grid-cols-[150px_1fr] gap-2 items-center">
                <div class="font-bold text-slate-700 uppercase tracking-wider">ULP</div>
                <div class="uppercase font-bold text-black">: <span id="jtm-mdl-ulp"></span></div>
            </div>

            <hr class="border-slate-200">

            <div class="grid grid-cols-[150px_1fr] gap-2 items-center">
                <div class="font-bold text-slate-700 uppercase tracking-wider">Transaksi</div>
                <div class="uppercase font-bold text-blue-700">: <span id="jtm-mdl-transaksi" class="bg-blue-50 px-2 py-1 rounded"></span></div>
                <div class="font-bold text-slate-700 uppercase tracking-wider">Status</div>
                <div class="uppercase font-bold text-amber-700">: <span id="jtm-mdl-status" class="bg-amber-50 px-2 py-1 rounded"></span></div>
            </div>

            <hr class="border-slate-200">

            <div class="grid grid-cols-[150px_1fr] gap-2 items-center">
                <div class="font-bold text-slate-700 uppercase tracking-wider">No. Agenda</div>
                <div class="font-bold">: <span id="jtm-mdl-agenda"></span></div>
                <div class="font-bold text-slate-700 uppercase tracking-wider">Nama</div>
                <div class="uppercase font-bold">: <span id="jtm-mdl-nama"></span></div>
                <div class="font-bold text-slate-700 uppercase tracking-wider">Alamat</div>
                <div class="uppercase font-bold">: <span id="jtm-mdl-alamat"></span></div>
                <div class="font-bold text-slate-700 uppercase tracking-wider">Tarif / Daya</div>
                <div class="uppercase font-bold flex items-center gap-2">
                    : <span class="bg-emerald-50 text-emerald-700 px-2 py-1 rounded">BARU : <span id="jtm-mdl-tbaru"></span> / <span id="jtm-mdl-dbaru"></span></span>
                    <span class="text-slate-300">|</span>
                    <span class="bg-slate-50 text-slate-600 px-2 py-1 rounded">LAMA : <span id="jtm-mdl-tlama"></span> / <span id="jtm-mdl-dlama"></span></span>
                </div>
            </div>

            <hr class="border-slate-200">

            {{-- RAB --}}
            <div id="rab-section_jtm" class="grid grid-cols-[150px_1fr] gap-2 items-center bg-blue-50/50 p-3 rounded-lg border border-blue-100">
                <div class="font-bold text-[#0D1B8C] uppercase tracking-wider flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    RAB
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-slate-600 font-bold text-sm">Rp.</span>
                    <input type="number" id="jtm-mdl-rab-input" class="border border-slate-300 rounded-lg px-3 py-1.5 text-sm w-40 focus:outline-none focus:ring-2 focus:ring-[#2B73FE] transition bg-white" placeholder="0" />
                    <button onclick="saveRabField_jtm()" class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-1.5 rounded-lg shadow-sm text-[13.5px] font-bold transition flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        Simpan
                    </button>
                    <span id="jtm-mdl-rab-indicator" class="text-emerald-600 text-[12.5px] font-bold opacity-0 transition-opacity flex items-center gap-1 ml-1">Tersimpan!</span>
                </div>
            </div>

                        {{-- Berkas WO / Excel Section --}}
            <div id="wo-section_jtm" class="bg-amber-50/60 border border-amber-200 rounded-lg p-3 flex flex-col gap-2">
                <div class="font-bold text-amber-800 uppercase tracking-wider flex items-center gap-1.5 text-[13px]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    <span id="wo-title_jtm">Berkas WO</span>
                </div>

                {{-- Perencanaan: Upload WO --}}
                <div id="wo-controls_jtm" class="hidden flex-col gap-2">
                    <label class="block text-[11px] font-bold text-slate-500 mb-1.5 uppercase tracking-wider">Berkas WO</label>
                    <div class="flex items-center gap-2">
                        <input type="file" id="jtm-mdl-wo-input" accept=".pdf,.jpg,.jpeg,.png" class="text-xs border border-slate-300 rounded-lg p-1.5 w-full bg-white" onchange="handleWoUpload_jtm(this)" />
                    </div>
                    <ul id="jtm-mdl-wo-list" class="flex flex-col gap-1 mt-1"></ul>
                </div>

                {{-- Transaksi: Upload Excel --}}
                <div id="excel-controls_jtm" class="hidden flex-col gap-2">
                    <div class="flex items-center gap-2 flex-wrap">
                        <label class="cursor-pointer flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            Pilih File Excel
                            <input type="file" id="jtm-mdl-excel-input" class="hidden" accept=".xlsx,.xls,.csv" onchange="handleExcelUpload_jtm(this)" />
                        </label>
                        <span class="text-[11px] text-emerald-700 italic">XLS / XLSX / CSV</span>
                    </div>
                    <ul id="jtm-mdl-excel-list" class="flex flex-col gap-1 mt-1"></ul>
                </div>

                {{-- Konstruksi & Lainnya: Checklist --}}
                <div id="wo-checklist_jtm" class="hidden flex-col gap-2">
                    <div class="flex items-center gap-2 bg-white px-3 py-2 border border-amber-200 rounded-lg shadow-sm">
                        <input type="checkbox" id="wo-check-status_jtm" disabled class="w-4 h-4 accent-emerald-600 rounded border-slate-300">
                        <label class="text-xs font-bold text-slate-700 cursor-default">Berkas WO Tersedia <span class="text-slate-400 font-normal">(dari Perencanaan)</span></label>
                    </div>
                    <div class="flex items-center gap-2 bg-white px-3 py-2 border border-amber-200 rounded-lg shadow-sm">
                        <input type="checkbox" id="excel-check-status_jtm" disabled class="w-4 h-4 accent-emerald-600 rounded border-slate-300">
                        <label class="text-xs font-bold text-slate-700 cursor-default">File Excel Tersedia <span class="text-slate-400 font-normal">(dari Transaksi)</span></label>
                    </div>
                </div>
            </div>

            {{-- Tujuan PT & Kelayakan --}}
            <div id="vendor-section_jtm" class="bg-slate-50 border border-slate-200 rounded-lg p-4 flex flex-col gap-4">
                @if ((Auth::user()->role ?? '') !== 'konstruksi')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Pilih PT --}}
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-1.5 uppercase tracking-wider">Tujuan Kirim ke PT</label>
                        <select id="jtm-mdl-pt" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                            <option value="">-- Pilih PT --</option>
                            <option value="PT. A">PT. ALPHA</option>
                            <option value="PT. B">PT. BRAVO</option>
                            <option value="PT. C">PT. CHARLIE</option>
                        </select>
                    </div>
                    {{-- Status Layak --}}
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-1.5 uppercase tracking-wider">Status Kelayakan</label>
                        <div class="flex items-center gap-4 mt-1">
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="jtm_status_layak" value="layak" class="w-4 h-4 text-blue-600">
                                <span class="text-sm font-bold text-emerald-600 group-hover:text-emerald-700">LAYAK</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="jtm_status_layak" value="tidak_layak" class="w-4 h-4 text-red-600">
                                <span class="text-sm font-bold text-red-600 group-hover:text-red-700">TIDAK LAYAK</span>
                            </label>
                        </div>
                    </div>
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Upload Berkas Kelayakan / WO --}}
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-1.5 uppercase tracking-wider">{{ (Auth::user()->role ?? '') === 'konstruksi' ? 'Upload Berkas WO' : 'Upload Berkas Kelayakan' }}</label>
                        <div class="flex items-center gap-2">
                            <input type="file" id="jtm-mdl-file-kelayakan" accept=".pdf,.jpg,.jpeg,.png" class="text-xs border border-slate-300 rounded-lg p-1.5 w-full bg-white">
                        </div>
                    </div>
                    {{-- Upload Berkas WO Tiang --}}
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-1.5 uppercase tracking-wider">Upload Berkas WO Tiang</label>
                        <div class="flex items-center gap-2">
                            <input type="file" id="jtm-mdl-file-wo-tiang" accept=".pdf,.jpg,.jpeg,.png" class="text-xs border border-slate-300 rounded-lg p-1.5 w-full bg-white">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button onclick="kirimVendor_jtm()" class="bg-[#0D1B8C] hover:bg-blue-800 text-white px-6 py-2 rounded-lg font-bold text-sm shadow-md transition flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                        Kirim ke Vendor
                    </button>
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
                            <th class="border border-slate-200 py-1.5 uppercase font-bold text-[12.5px]">Kirim</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <tr>
                            <td class="border border-slate-200 py-2 font-bold" id="jtm-mdl-tgl-mohon">-</td>
                            <td class="border border-slate-200 py-2 font-bold" id="jtm-mdl-tgl-kirim">-</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-2 flex justify-end">
                <button onclick="closeDetailModal_jtm()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2 rounded-lg font-bold text-sm transition focus:outline-none focus:ring-2 focus:ring-slate-300">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
function showBerkasModal(agendaKey) {
    var modal = document.getElementById('fileModal');
    var list  = document.getElementById('fileList');
    var agendaSpan = document.getElementById('fileMdlAgenda');
    if (agendaSpan) agendaSpan.textContent = '— ' + agendaKey;
    list.innerHTML = '<li class="text-center py-6 text-slate-400 text-sm">Memuat berkas...</li>';
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    var req = indexedDB.open('FastOnDocs', 2);
    req.onsuccess = function(e) {
        var db = e.target.result;
        if (!db.objectStoreNames.contains('uploadedDocs')) {
            list.innerHTML = '<li class="text-center py-6 text-slate-400 italic text-sm">Database tidak ditemukan</li>';
            return;
        }
        var tx     = db.transaction('uploadedDocs', 'readonly');
        var store  = tx.objectStore('uploadedDocs');
        var getReq = store.get(agendaKey);
        getReq.onsuccess = function() {
            var d = getReq.result;
            list.innerHTML = '';
            var allFiles = [];
            if (d) {
                (d.ktp || []).forEach(function(f) { allFiles.push({ label: 'BP/KTP', file: f }); });
                (d.itt || []).forEach(function(f) { allFiles.push({ label: 'ITT',    file: f }); });
            }
            if (allFiles.length === 0) {
                list.innerHTML = '<li class="text-center py-6 text-slate-400 italic text-sm">Tidak ada berkas untuk no. agenda ini</li>';
                return;
            }
            allFiles.forEach(function(item) {
                var li = document.createElement('li');
                li.className = 'flex items-center justify-between py-2.5 px-1 text-xs';
                
                var div = document.createElement('div');
                div.className = 'flex items-center gap-2 min-w-0';
                div.innerHTML = '<span class="shrink-0 px-1.5 py-0.5 rounded text-[10px] font-bold ' +
                                (item.label === 'BP/KTP' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700') + '">' +
                                item.label + '</span>' +
                                '<span class="truncate text-slate-700 font-medium">' + item.file.name + '</span>';
                
                var btn = document.createElement('button');
                btn.className = 'shrink-0 ml-3 text-blue-600 font-semibold hover:underline cursor-pointer';
                btn.textContent = 'Lihat';
                btn.onclick = function() {
                    try {
                        var byteString = atob(item.file.url.split(',')[1]);
                        var mimeString = item.file.url.split(',')[0].split(':')[1].split(';')[0];
                        var ab = new ArrayBuffer(byteString.length);
                        var ia = new Uint8Array(ab);
                        for (var i = 0; i < byteString.length; i++) {
                            ia[i] = byteString.charCodeAt(i);
                        }
                        var blob = new Blob([ab], {type: mimeString});
                        var blobUrl = URL.createObjectURL(blob);
                        window.open(blobUrl, '_blank');
                    } catch(e) {
                        alert('Gagal membuka preview: ' + e);
                    }
                };
                
                li.appendChild(div);
                li.appendChild(btn);
                list.appendChild(li);
            });
        };
    };
}
function closeFileModal() {
    var modal = document.getElementById('fileModal');
    if (modal) { modal.classList.add('hidden'); modal.classList.remove('flex'); }
}

// ===== DETAIL MODAL =====
function openDetailModal_jtm(item) {
    document.getElementById('jtm-mdl-ulp').textContent       = item.ulp || '-';
    document.getElementById('jtm-mdl-transaksi').textContent = item.transaksi || '-';
    document.getElementById('jtm-mdl-status').textContent    = item.status || '-';
    document.getElementById('jtm-mdl-agenda').textContent    = item.no_agenda || '-';
    document.getElementById('jtm-mdl-nama').textContent      = item.nama || '-';
    document.getElementById('jtm-mdl-alamat').textContent    = item.alamat || '-';
    document.getElementById('jtm-mdl-tbaru').textContent     = item.tarif_baru || '-';
    document.getElementById('jtm-mdl-dbaru').textContent     = (item.daya_baru || '0') + ' VA';
    document.getElementById('jtm-mdl-tlama').textContent     = item.tarif_lama || '-';
    document.getElementById('jtm-mdl-dlama').textContent     = (item.daya_lama || '0') + ' VA';

    // Tanggal
    if (item.created_at) {
        var d = new Date(item.created_at);
        var ds = d.toISOString().split('T')[0];
        document.getElementById('jtm-mdl-tgl-mohon').textContent = ds;
    }
    if (item.sentAt) {
        var d2 = new Date(item.sentAt);
        document.getElementById('jtm-mdl-tgl-kirim').textContent = d2.toLocaleDateString('id-ID');
    }

    // RAB
    var agendaKey = item.no_agenda || 'default';
    var req = indexedDB.open('FastOnDocs', 2);
    req.onsuccess = function(e) {
        var db = e.target.result;
        if (!db.objectStoreNames.contains('uploadedDocs')) return;
        var tx = db.transaction('uploadedDocs', 'readonly');
        var store = tx.objectStore('uploadedDocs');
        var gr = store.get(agendaKey);
        gr.onsuccess = function() {
            var d = gr.result;
            document.getElementById('jtm-mdl-rab-input').value = (d && d.rab) ? d.rab : (item.total_biaya || 0);
        };
    };

    window.currentItem_jtm = item; // Penting untuk inisialisasi
    renderWoList_jtm(agendaKey);

    var m = document.getElementById('detailModal_jtm');
    if (m) { m.classList.remove('hidden'); m.classList.add('flex'); }
}
function kirimVendor_jtm() {
    var isKonstruksi = @json((Auth::user()->role ?? '') === 'konstruksi');
    var pt = document.getElementById('jtm-mdl-pt');
    var layak = document.querySelector('input[name="jtm_status_layak"]:checked');

    if (!isKonstruksi) {
        if (!pt || !pt.value) { alert('Pilih Tujuan PT terlebih dahulu.'); return; }
        if (!layak) { alert('Pilih Status Kelayakan terlebih dahulu.'); return; }
    }

    alert(isKonstruksi
        ? 'Data berhasil dikirim.'
        : 'Data berhasil dikirim ke ' + pt.value + ' dengan status: ' + layak.value.toUpperCase());
    closeDetailModal_jtm();
}

function saveRabField_jtm() {
    if (!window.currentItem_jtm) return;
    var agendaKey = window.currentItem_jtm.no_agenda || 'default';
    var rabVal = document.getElementById('jtm-mdl-rab-input').value;
    if (rabVal === '') rabVal = 0;
    var req = indexedDB.open('FastOnDocs', 2);
    req.onsuccess = function(e) {
        var db = e.target.result;
        var tx = db.transaction('uploadedDocs', 'readwrite');
        var store = tx.objectStore('uploadedDocs');
        var gr = store.get(agendaKey);
        gr.onsuccess = function() {
            var d = gr.result || { ktp: [], itt: [], wo: [] };
            d.rab = rabVal;
            d.agendaKey = agendaKey; store.put(d);
            tx.oncomplete = function() {
                var ind = document.getElementById('jtm-mdl-rab-indicator');
                if (ind) { ind.style.opacity = '1'; setTimeout(function(){ ind.style.opacity='0'; }, 2000); }
            };
        };
    };
}

// ===== WO UPLOAD =====
function handleWoUpload_jtm(input) {
    if (!window.currentItem_jtm) return;
    var agendaKey = window.currentItem_jtm.no_agenda || 'default';
    var files = Array.from(input.files);
    if (!files.length) return;
    
    var readers = files.map(function(file) {
        return new Promise(function(resolve) {
            var reader = new FileReader();
            reader.onload = function(ev) { resolve({ name: file.name, url: ev.target.result }); };
            reader.readAsDataURL(file);
        });
    });
    
    Promise.all(readers).then(function(newFiles) {
        var req = indexedDB.open('FastOnDocs', 2);
        req.onsuccess = function(e) {
            var db = e.target.result;
            var tx = db.transaction('uploadedDocs', 'readwrite');
            var store = tx.objectStore('uploadedDocs');
            var gr = store.get(agendaKey);
            gr.onsuccess = function() {
                var d = gr.result || { ktp: [], itt: [], wo: [] };
                d.agendaKey = agendaKey;
                if (!d.wo) d.wo = [];
                newFiles.forEach(function(nf) { d.wo.push(nf); });
                store.put(d);
                tx.oncomplete = function() { renderWoList_jtm(agendaKey); };
            };
        };
    });
    input.value = '';
}

function renderWoList_jtm(agendaKey) {
    var req = indexedDB.open('FastOnDocs', 2);
    req.onsuccess = function(e) {
        var db = e.target.result;
        if (!db.objectStoreNames.contains('uploadedDocs')) return;
        var tx = db.transaction('uploadedDocs', 'readonly');
        var store = tx.objectStore('uploadedDocs');
        var gr = store.get(agendaKey);
        gr.onsuccess = function() {
            var d = gr.result;
            var role = (typeof _currentRole !== "undefined") ? _currentRole : "";
            var ctrlWo    = document.getElementById("wo-controls_jtm");
            var ctrlXls   = document.getElementById("excel-controls_jtm");
            var ctrlChk   = document.getElementById("wo-checklist_jtm");
            var titleEl   = document.getElementById("wo-title_jtm");
            var chkWo     = document.getElementById("wo-check-status_jtm");
            var chkXls    = document.getElementById("excel-check-status_jtm");

            if (role === "perencanaan") {
                if (ctrlWo)  { ctrlWo.style.display  = "flex"; ctrlWo.classList.remove("hidden"); }
                if (ctrlXls) { ctrlXls.style.display = "none"; }
                if (ctrlChk) { ctrlChk.style.display = "none"; }
                if (titleEl) titleEl.textContent = "Upload Berkas WO";
            } else if (role === "transaksi") {
                if (ctrlWo)  { ctrlWo.style.display  = "none"; }
                if (ctrlXls) { ctrlXls.style.display = "flex"; ctrlXls.classList.remove("hidden"); }
                if (ctrlChk) { ctrlChk.style.display = "none"; }
                if (titleEl) titleEl.textContent = "Upload File Excel";
                // render excel list
                renderExcelList_jtm(agendaKey);
            } else {
                if (ctrlWo)  { ctrlWo.style.display  = "none"; }
                if (ctrlXls) { ctrlXls.style.display = "none"; }
                if (ctrlChk) { ctrlChk.style.display = "flex"; ctrlChk.classList.remove("hidden"); }
                if (titleEl) titleEl.textContent = "Status Berkas";
                var hasWo  = (d && d.wo    && d.wo.length    > 0);
                var hasXls = (d && d.excel && d.excel.length > 0);
                if (chkWo)  chkWo.checked  = hasWo;
                if (chkXls) chkXls.checked = hasXls;
                return;
            }

            // render WO list (only for perencanaan)
            var list = document.getElementById("jtm-mdl-wo-list");
            if (!list) return;
            list.innerHTML = "";
            if (!d || !d.wo || !d.wo.length) {
                list.innerHTML = '<li class="text-[11px] text-amber-600 italic">Belum ada berkas WO</li>';
                return;
            }
            d.wo.forEach(function(f, idx) {
                var li = document.createElement("li");
                li.className = "flex items-center justify-between bg-white border border-amber-100 rounded px-2 py-1 text-[11.5px]";
                var span = document.createElement("span");
                span.className = "truncate max-w-[220px] text-slate-700";
                span.textContent = f.name;
                var div = document.createElement("div");
                div.className = "flex items-center gap-2 ml-2 flex-shrink-0";
                var viewBtn = document.createElement("button");
                viewBtn.className = "text-blue-600 font-bold hover:underline";
                viewBtn.textContent = "Lihat";
                viewBtn.onclick = (function(fRef) { return function() {
                    try {
                        var bs = atob(fRef.url.split(",")[1]);
                        var mime = fRef.url.split(",")[0].split(":")[1].split(";")[0];
                        var ab = new ArrayBuffer(bs.length);
                        var ia = new Uint8Array(ab);
                        for (var i = 0; i < bs.length; i++) ia[i] = bs.charCodeAt(i);
                        window.open(URL.createObjectURL(new Blob([ab], {type: mime})), "_blank");
                    } catch(e) { alert("Gagal preview: " + e); }
                }; })(f);
                var delBtn = document.createElement("button");
                delBtn.className = "text-red-500 hover:text-red-700 font-bold text-xs";
                delBtn.textContent = "Hapus";
                delBtn.onclick = (function(keyRef, idxRef) { return function() {
                    deleteWoFile_jtm(keyRef, idxRef);
                }; })(agendaKey, idx);
                div.appendChild(viewBtn);
                div.appendChild(delBtn);
                li.appendChild(span);
                li.appendChild(div);
                list.appendChild(li);
            });
        };
    };
}

function deleteWoFile_jtm(agendaKey, idx) {
    var req = indexedDB.open('FastOnDocs', 2);
    req.onsuccess = function(e) {
        var db = e.target.result;
        var tx = db.transaction('uploadedDocs', 'readwrite');
        var store = tx.objectStore('uploadedDocs');
        var gr = store.get(agendaKey);
        gr.onsuccess = function() {
            var d = gr.result;
            if (d && d.wo) {
                d.wo.splice(idx, 1);
                d.agendaKey = agendaKey; store.put(d);
                tx.oncomplete = function() { renderWoList_jtm(agendaKey); };
            }
        };
    };
}

function handleExcelUpload_jtm(input) {
    if (!window.currentItem_jtm) return;
    var agendaKey = window.currentItem_jtm.no_agenda || "default";
    var files = Array.from(input.files);
    if (!files.length) return;
    var readers = files.map(function(file) {
        return new Promise(function(resolve) {
            var reader = new FileReader();
            reader.onload = function(ev) { resolve({ name: file.name, url: ev.target.result }); };
            reader.readAsDataURL(file);
        });
    });
    Promise.all(readers).then(function(newFiles) {
        var req = indexedDB.open('FastOnDocs', 2);
        req.onsuccess = function(e) {
            var db = e.target.result;
            var tx = db.transaction('uploadedDocs', 'readwrite');
            var store = tx.objectStore('uploadedDocs');
            var gr = store.get(agendaKey);
            gr.onsuccess = function() {
                var d = gr.result || { ktp: [], itt: [], wo: [], excel: [] };
                d.agendaKey = agendaKey;
                if (!d.excel) d.excel = [];
                newFiles.forEach(function(nf) { d.excel.push(nf); });
                store.put(d);
                tx.oncomplete = function() { renderExcelList_jtm(agendaKey); };
            };
        };
    });
    input.value = "";
}

function renderExcelList_jtm(agendaKey) {
    var req = indexedDB.open('FastOnDocs', 2);
    req.onsuccess = function(e) {
        var db = e.target.result;
        if (!db.objectStoreNames.contains('uploadedDocs')) return;
        var tx = db.transaction('uploadedDocs', 'readonly');
        var store = tx.objectStore('uploadedDocs');
        var gr = store.get(agendaKey);
        gr.onsuccess = function() {
            var d = gr.result;
            var list = document.getElementById("jtm-mdl-excel-list");
            if (!list) return;
            list.innerHTML = "";
            if (!d || !d.excel || !d.excel.length) {
                list.innerHTML = '<li class="text-[11px] text-emerald-600 italic">Belum ada file Excel</li>';
                return;
            }
            d.excel.forEach(function(f, idx) {
                var li = document.createElement("li");
                li.className = "flex items-center justify-between bg-white border border-emerald-100 rounded px-2 py-1 text-[11.5px]";
                var span = document.createElement("span");
                span.className = "truncate max-w-[220px] text-slate-700";
                span.textContent = f.name;
                var div = document.createElement("div");
                div.className = "flex items-center gap-2 ml-2 flex-shrink-0";
                var viewBtn = document.createElement("button");
                viewBtn.className = "text-blue-600 font-bold hover:underline";
                viewBtn.textContent = "Lihat";
                viewBtn.onclick = (function(fRef) { return function() {
                    try {
                        var bs = atob(fRef.url.split(",")[1]);
                        var mime = fRef.url.split(",")[0].split(":")[1].split(";")[0];
                        var ab = new ArrayBuffer(bs.length);
                        var ia = new Uint8Array(ab);
                        for (var i = 0; i < bs.length; i++) ia[i] = bs.charCodeAt(i);
                        window.open(URL.createObjectURL(new Blob([ab], {type: mime})), "_blank");
                    } catch(e) { alert("Gagal preview: " + e); }
                }; })(f);
                var delBtn = document.createElement("button");
                delBtn.className = "text-red-500 hover:text-red-700 font-bold text-xs";
                delBtn.textContent = "Hapus";
                delBtn.onclick = (function(keyRef, idxRef) { return function() {
                    deleteExcelFile_jtm(keyRef, idxRef);
                }; })(agendaKey, idx);
                div.appendChild(viewBtn);
                div.appendChild(delBtn);
                li.appendChild(span);
                li.appendChild(div);
                list.appendChild(li);
            });
        };
    };
}

function deleteExcelFile_jtm(agendaKey, idx) {
    var req = indexedDB.open('FastOnDocs', 2);
    req.onsuccess = function(e) {
        var db = e.target.result;
        var tx = db.transaction('uploadedDocs', 'readwrite');
        var store = tx.objectStore('uploadedDocs');
        var gr = store.get(agendaKey);
        gr.onsuccess = function() {
            var d = gr.result;
            if (d && d.excel) {
                d.excel.splice(idx, 1);
                d.agendaKey = agendaKey;
                store.put(d);
                tx.oncomplete = function() { renderExcelList_jtm(agendaKey); };
            }
        };
    };
}
</script>

@endsection
