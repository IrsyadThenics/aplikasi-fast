@extends('layouts.app')

@section('content')
<div class="h-full flex flex-col bg-slate-50 p-5">

    {{-- ===== FILTER CARD ===== --}}
    <div class="bg-white rounded-2xl shadow-md border border-slate-200 overflow-hidden flex-shrink-0">
        {{-- Card Header --}}
        <div class="bg-gradient-to-r from-[#0D1B8C] to-[#2B73FE] px-6 py-3 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white/80" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
            </svg>
            <div>
                <p class="text-white font-bold text-sm tracking-wide">TANPA PERLUASAN</p>
                <p class="text-blue-100 text-xs">Data yang dikirim ke Tanpa Perluasan (Langsung Sambung)</p>
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
                    <span class="text-white font-bold text-sm tracking-wide">DATA TRANSAKSI TANPA PERLUASAN</span>
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
                                    <span>Belum ada data yang dikirim ke Tanpa Perluasan</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Footer --}}
            <div class="border-t border-slate-100 px-5 py-2 bg-slate-50 flex-shrink-0">
                <span class="text-xs text-slate-400 font-mono" id="footerCount">Data 0 dari 0</span>
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

        // Clear existing dynamic rows
        var existingRows = tbody.querySelectorAll('tr:not(#emptyRow)');
        existingRows.forEach(function(r) { r.remove(); });

        if (!items || items.length === 0) {
            if (emptyRow) emptyRow.style.display = '';
            if (countEl)  countEl.textContent = '0 data';
            if (footerEl) footerEl.textContent = 'Data 0 dari 0';
            return;
        }

        if (emptyRow) emptyRow.style.display = 'none';
        if (countEl)  countEl.textContent = items.length + ' data';
        if (footerEl) footerEl.textContent = 'Data 1–' + items.length + ' dari ' + items.length;

        items.forEach(function(item, i) {
            var tr = document.createElement('tr');
            tr.className = 'bg-white hover:bg-slate-50 transition border-b border-slate-100 text-slate-700';
            var agendaKey = (item.no_agenda || '').replace(/'/g, "\\'");
            var itemJson = JSON.stringify(item).replace(/\\/g, '\\\\').replace(/'/g, "\\'");
            tr.innerHTML =
                '<td class="border border-slate-200 px-3 py-2 text-center text-slate-400 font-mono">' + (i+1) + '.</td>' +
                '<td class="border border-slate-200 px-3 py-2 text-center font-medium font-mono text-[11px]">' + (item.no_agenda || '-') + '</td>' +
                '<td class="border border-slate-200 px-3 py-2 text-center">' +
                    '<button onclick=\'openDetailModal_tp(' + itemJson + ')\' class="text-slate-500 hover:text-blue-600 transition" title="Lihat Detail">' +
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
        loadSentItems('tanpa_perluasan').then(function(items) {
            allItems = items;
            var tableArea = document.getElementById('tableArea');
            if (tableArea && !tableArea.classList.contains('hidden')) {
                filterData();
            }
        }).catch(function(e) {
            console.warn('Error loading Tanpa Perluasan data:', e);
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
<div id="detailModal_tp" class="fixed inset-0 z-[100] hidden items-start justify-center bg-black/50 backdrop-blur-sm p-4 py-6 overflow-y-auto">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[calc(100vh-3rem)] relative overflow-hidden flex flex-col border border-slate-200">
        <!-- Title Bar -->
        <div class="bg-gradient-to-r from-[#0D1B8C] to-[#2B73FE] px-5 py-3 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span class="text-white font-bold text-sm tracking-wide">Detail Data Pelanggan</span>
            </div>
            <button onclick="closeDetailModal_tp()" class="text-white/70 hover:text-white p-1 rounded-full hover:bg-white/20 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Content -->
        <div class="min-h-0 overflow-y-auto p-6 text-[13.5px] text-slate-800 font-mono flex flex-col gap-4">

            <div class="grid grid-cols-[150px_1fr] gap-2 items-center">
                <div class="font-bold text-slate-700 uppercase tracking-wider">ULP</div>
                <div class="uppercase font-bold text-black">: <span id="tp-mdl-ulp"></span></div>
            </div>

            <hr class="border-slate-200">

            <div class="grid grid-cols-[150px_1fr] gap-2 items-center">
                <div class="font-bold text-slate-700 uppercase tracking-wider">Transaksi</div>
                <div class="uppercase font-bold text-blue-700">: <span id="tp-mdl-transaksi" class="bg-blue-50 px-2 py-1 rounded"></span></div>
                <div class="font-bold text-slate-700 uppercase tracking-wider">Status</div>
                <div class="uppercase font-bold text-amber-700">: <span id="tp-mdl-status" class="bg-amber-50 px-2 py-1 rounded"></span></div>
            </div>

            <hr class="border-slate-200">

            <div class="grid grid-cols-[150px_1fr] gap-2 items-center">
                <div class="font-bold text-slate-700 uppercase tracking-wider">No. Agenda</div>
                <div class="font-bold">: <span id="tp-mdl-agenda"></span></div>
                <div class="font-bold text-slate-700 uppercase tracking-wider">Nama</div>
                <div class="uppercase font-bold">: <span id="tp-mdl-nama"></span></div>
                <div class="font-bold text-slate-700 uppercase tracking-wider">Alamat</div>
                <div class="uppercase font-bold">: <span id="tp-mdl-alamat"></span></div>
                <div class="font-bold text-slate-700 uppercase tracking-wider">Tarif / Daya</div>
                <div class="uppercase font-bold flex items-center gap-2">
                    : <span class="bg-emerald-50 text-emerald-700 px-2 py-1 rounded">BARU : <span id="tp-mdl-tbaru"></span> / <span id="tp-mdl-dbaru"></span></span>
                    <span class="text-slate-300">|</span>
                    <span class="bg-slate-50 text-slate-600 px-2 py-1 rounded">LAMA : <span id="tp-mdl-tlama"></span> / <span id="tp-mdl-dlama"></span></span>
                </div>
            </div>

            <hr class="border-slate-200">

            {{-- RAB --}}
            <div id="rab-section_tp" class="grid grid-cols-[150px_1fr] gap-2 items-center bg-blue-50/50 p-3 rounded-lg border border-blue-100">
                <div class="font-bold text-[#0D1B8C] uppercase tracking-wider flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    RAB
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-slate-600 font-bold text-sm">Rp.</span>
                    <input type="number" id="tp-mdl-rab-input" class="border border-slate-300 rounded-lg px-3 py-1.5 text-sm w-40 focus:outline-none focus:ring-2 focus:ring-[#2B73FE] transition bg-white" placeholder="0" @if (!str_starts_with(Auth::user()->role ?? '', 'managerULP') && (Auth::user()->role ?? '') !== 'perencanaan') readonly @endif />
                    @if (str_starts_with(Auth::user()->role ?? '', 'managerULP') || (Auth::user()->role ?? '') === 'perencanaan')
                    <button onclick="saveRabField_tp()" class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-1.5 rounded-lg shadow-sm text-[13.5px] font-bold transition flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        Simpan
                    </button>
                    @endif
                    <span id="tp-mdl-rab-indicator" class="text-emerald-600 text-[12.5px] font-bold opacity-0 transition-opacity flex items-center gap-1 ml-1">Tersimpan!</span>
                </div>
            </div>

            {{-- Berkas WO / Dokumen Transaksi --}}
            <div id="wo-section_tp" class="bg-amber-50/60 border border-amber-200 rounded-lg p-3 flex flex-col gap-2">
                <div class="font-bold text-amber-800 uppercase tracking-wider flex items-center gap-1.5 text-[13px]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    <span id="wo-title_tp">Berkas & Dokumen</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    {{-- Berkas WO --}}
                    <div id="wo-controls_tp" class="flex flex-col gap-1.5 bg-white p-2.5 rounded-lg border border-amber-200">
                        <div class="flex items-center justify-between">
                            <label class="text-[11px] font-bold text-amber-900 uppercase tracking-wider">Berkas WO</label>
                            @if ((Auth::user()->role ?? '') === 'perencanaan')
                            <label id="tp-mdl-wo-upload" class="cursor-pointer bg-amber-600 hover:bg-amber-700 text-white text-[10.5px] font-bold px-2 py-1 rounded shadow-sm transition">
                                Upload WO
                                <input type="file" id="tp-mdl-wo-input" accept=".pdf,.jpg,.jpeg,.png" class="hidden" onchange="handleWoUpload_tp(this)" />
                            </label>
                            @endif
                        </div>
                        <ul id="tp-mdl-wo-list" class="flex flex-col gap-1 mt-1"></ul>
                    </div>

                    {{-- Dokumen Transaksi --}}
                    <div id="excel-controls_tp" class="flex flex-col gap-1.5 bg-white p-2.5 rounded-lg border border-emerald-200">
                        <div class="flex items-center justify-between">
                            <label class="text-[11px] font-bold text-emerald-900 uppercase tracking-wider">berkas BA cek KWH meter</label>
                            @if ((Auth::user()->role ?? '') === 'transaksi')
                            <label id="tp-mdl-dokumen-upload" class="cursor-pointer bg-emerald-600 hover:bg-emerald-700 text-white text-[10.5px] font-bold px-2 py-1 rounded shadow-sm transition">
                                Upload Dokumen
                                <input type="file" id="tp-mdl-excel-input" class="hidden" accept=".pdf,.jpg,.jpeg,.png" onchange="handleExcelUpload_tp(this)" />
                            </label>
                            @endif
                        </div>
                        <ul id="tp-mdl-excel-list" class="flex flex-col gap-1 mt-1"></ul>
                    </div>
                    <div class="flex flex-col gap-1.5 bg-white p-2.5 rounded-lg border border-blue-200">
                        <div class="flex items-center justify-between">
                            <label class="text-[11px] font-bold text-blue-900 uppercase tracking-wider">Berkas BA Checklist</label>
                            @if ((Auth::user()->role ?? '') === 'konstruksi')
                            <label class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white text-[10.5px] font-bold px-2 py-1 rounded shadow-sm transition">
                                Upload BA Checklist
                                <input type="file" id="tp-mdl-ba-cek-input" class="hidden" accept=".pdf,.jpg,.jpeg,.png" onchange="handleBaCekUpload_tp(this)" />
                            </label>
                            @endif
                        </div>
                        <ul id="tp-mdl-ba-cek-list" class="flex flex-col gap-1 mt-1"></ul>
                    </div>
                    <div class="flex flex-col gap-1.5 bg-white p-2.5 rounded-lg border border-violet-200"><div class="flex items-center justify-between"><label class="text-[11px] font-bold text-violet-900 uppercase tracking-wider">Berkas BA Operasi</label>@if ((Auth::user()->role ?? '') === 'jaringan')<label class="cursor-pointer bg-violet-600 hover:bg-violet-700 text-white text-[10.5px] font-bold px-2 py-1 rounded shadow-sm transition">Upload BA Operasi<input type="file" id="tp-mdl-ba-operasi-input" class="hidden" accept=".pdf,.jpg,.jpeg,.png" onchange="handleBaOperasiUpload_tp(this)" /></label>@endif</div><ul id="tp-mdl-ba-operasi-list" class="flex flex-col gap-1 mt-1"></ul></div>
                </div>
                <p id="tp-vendor-report-upload-note" class="hidden text-[11px] font-semibold text-amber-700">Unggah WO atau dokumen tersedia setelah laporan vendor masuk.</p>
            </div>

            @if (!str_starts_with(Auth::user()->role ?? '', 'managerULP') && !in_array((Auth::user()->role ?? ''), ['transaksi', 'jaringan', 'pelayanan']))
            {{-- Tujuan PT & Kelayakan --}}
            <div id="vendor-section_tp" class="bg-slate-50 border border-slate-200 rounded-lg p-4 flex flex-col gap-4">
                @if ((Auth::user()->role ?? '') !== 'konstruksi')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Pilih PT --}}
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-1.5 uppercase tracking-wider">Tujuan Kirim ke PT</label>
                        <select id="tp-mdl-pt" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
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
                                <input type="radio" name="tp_status_layak" value="layak" class="w-4 h-4 text-blue-600">
                                <span class="text-sm font-bold text-emerald-600 group-hover:text-emerald-700">LAYAK</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="tp_status_layak" value="tidak_layak" class="w-4 h-4 text-red-600">
                                <span class="text-sm font-bold text-red-600 group-hover:text-red-700">TIDAK LAYAK</span>
                            </label>
                        </div>
                    </div> 
                </div>
                @endif

                @if ((Auth::user()->role ?? '') === 'konstruksi')
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 mb-1.5 uppercase tracking-wider">Tujuan Kirim Vendor Konstruksi</label>
                    <select id="tp-mdl-vendor-konstruksi" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                        <option value="">-- Pilih Vendor Konstruksi --</option>
                        @foreach($vendorKonstruksiUsers as $vendor)
                        <option value="{{ $vendor->id }}">{{ $vendor->name }} ({{ $vendor->user_id }})</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if ((Auth::user()->role ?? '') !== 'perencanaan')
                    {{-- Upload Berkas Kelayakan / WO --}}
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-1.5 uppercase tracking-wider">{{ (Auth::user()->role ?? '') === 'konstruksi' ? 'Upload Berkas WO' : 'Upload Berkas Kelayakan' }}</label>
                        <div class="flex items-center gap-2">
                            <input type="file" id="tp-mdl-file-kelayakan" accept=".pdf,.jpg,.jpeg,.png" class="text-xs border border-slate-300 rounded-lg p-1.5 w-full bg-white">
                        </div>
                    </div>
                    @endif
                    @if ((Auth::user()->role ?? '') !== 'konstruksi')
                    {{-- Upload Berkas WO Tiang --}}
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-1.5 uppercase tracking-wider">Upload Berkas WO Tiang</label>
                        <div class="flex items-center gap-2">
                            <input type="file" id="tp-mdl-file-wo-tiang" accept=".pdf,.jpg,.jpeg,.png" class="text-xs border border-slate-300 rounded-lg p-1.5 w-full bg-white">
                        </div>
                    </div>
                    @endif
                </div>
                <div class="flex justify-end">
                    <button onclick="kirimVendor_tp()" class="bg-[#0D1B8C] hover:bg-blue-800 text-white px-6 py-2 rounded-lg font-bold text-sm shadow-md transition flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                        Kirim ke Vendor
                    </button>
                </div>
            </div>
            @endif

            <hr class="border-slate-200 mb-2">

            @include('dashboard.shared.vendor_reports')

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
                            <td class="border border-slate-200 py-2 font-bold" id="tp-mdl-tgl-mohon">-</td>
                            <td class="border border-slate-200 py-2 font-bold" id="tp-mdl-tgl-kirim">-</td>
                        </tr>
                    </tbody>
                </table>
                <div class="mt-2 overflow-hidden rounded-lg border border-slate-200 text-[11.5px]"><table class="w-full border-collapse"><thead class="bg-slate-100 text-slate-600"><tr><th class="border-b border-slate-200 px-3 py-2 text-left">Proses</th><th class="border-b border-slate-200 px-3 py-2 text-left">Status</th><th class="border-b border-slate-200 px-3 py-2 text-center">Tanggal Lengkap</th></tr></thead><tbody class="bg-white"><tr><td class="border-b border-slate-100 px-3 py-2 font-medium text-slate-600">Pengiriman ULP</td><td id="tp-mdl-status-ulp-kirim" class="border-b border-slate-100 px-3 py-2 font-bold text-red-600">✕ Belum dikirim</td><td id="tp-mdl-tgl-ulp-kirim" class="border-b border-slate-100 px-3 py-2 text-center">-</td></tr><tr><td class="border-b border-slate-100 px-3 py-2 font-medium text-slate-600">Berkas WO Perencanaan</td><td id="tp-mdl-status-wo-perencanaan" class="border-b border-slate-100 px-3 py-2 font-bold text-red-600">✕ Belum diunggah</td><td id="tp-mdl-tgl-wo-perencanaan" class="border-b border-slate-100 px-3 py-2 text-center">-</td></tr><tr><td class="border-b border-slate-100 px-3 py-2 font-medium text-slate-600">Berkas WO Konstruksi</td><td id="tp-mdl-status-wo-konstruksi" class="border-b border-slate-100 px-3 py-2 font-bold text-red-600">✕ Belum diunggah</td><td id="tp-mdl-tgl-wo-konstruksi" class="border-b border-slate-100 px-3 py-2 text-center">-</td></tr><tr><td class="border-b border-slate-100 px-3 py-2 font-medium text-slate-600">BA Cek Konstruksi</td><td id="tp-mdl-status-ba-cek" class="border-b border-slate-100 px-3 py-2 font-bold text-red-600">✕ Belum diunggah</td><td id="tp-mdl-tgl-ba-cek" class="border-b border-slate-100 px-3 py-2 text-center">-</td></tr><tr><td class="border-b border-slate-100 px-3 py-2 font-medium text-slate-600">BA Acara Transaksi</td><td id="tp-mdl-status-ba-acara" class="border-b border-slate-100 px-3 py-2 font-bold text-red-600">✕ Belum diunggah</td><td id="tp-mdl-tgl-ba-acara" class="border-b border-slate-100 px-3 py-2 text-center">-</td></tr><tr><td class="px-3 py-2 font-medium text-slate-600">BA Operasi Jaringan</td><td id="tp-mdl-status-ba-operasi" class="px-3 py-2 font-bold text-red-600">✕ Belum diunggah</td><td id="tp-mdl-tgl-ba-operasi" class="px-3 py-2 text-center">-</td></tr></tbody></table></div>
            </div>

            <div class="mt-2 flex justify-end">
                <button onclick="closeDetailModal_tp()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2 rounded-lg font-bold text-sm transition focus:outline-none focus:ring-2 focus:ring-slate-300">Tutup</button>
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

    var itemData = (typeof allItems !== 'undefined') ? allItems.find(function(x) { return String(x.no_agenda || '') === String(agendaKey || ''); }) : null;

    var req = indexedDB.open('FastOnDocs', 2);
    req.onsuccess = function(e) {
        var db = e.target.result;
        var d = null;
        if (db.objectStoreNames.contains('uploadedDocs')) {
            var tx     = db.transaction('uploadedDocs', 'readonly');
            var store  = tx.objectStore('uploadedDocs');
            var getReq = store.get(agendaKey);
            getReq.onsuccess = function() {
                d = getReq.result;
                renderAllBerkas();
            };
            getReq.onerror = function() { renderAllBerkas(); };
        } else {
            renderAllBerkas();
        }

        function renderAllBerkas() {
            list.innerHTML = '';
            var allFiles = [];
            if (d) {
                (d.ktp || []).forEach(function(f) { allFiles.push({ label: 'BP/KTP', file: f }); });
                (d.itt || []).forEach(function(f) { allFiles.push({ label: 'ITT',    file: f }); });
                (d.wo || []).forEach(function(f) { allFiles.push({ label: 'WO',     file: f }); });
                (d.excel || []).forEach(function(f) { allFiles.push({ label: 'DOKUMEN', file: f }); });
            }
            if (itemData && itemData.berkas && itemData.berkas.length) {
                itemData.berkas.forEach(function(b) {
                    allFiles.push({ label: (b.jenis_berkas || 'BERKAS').toUpperCase(), file: { name: b.nama_file || b.path_file, path: b.path_file } });
                });
            }
            if (allFiles.length === 0) {
                list.innerHTML = '<li class="text-center py-6 text-slate-400 italic text-sm">Tidak ada berkas untuk no. agenda ini</li>';
                return;
            }
            allFiles.forEach(function(itemFile) {
                var li = document.createElement('li');
                li.className = 'flex items-center justify-between py-2.5 px-1 text-xs border-b border-slate-100';
                
                var div = document.createElement('div');
                div.className = 'flex items-center gap-2 min-w-0';
                div.innerHTML = '<span class="shrink-0 px-1.5 py-0.5 rounded text-[10px] font-bold ' +
                                (itemFile.label === 'BP/KTP' ? 'bg-emerald-100 text-emerald-700' : (itemFile.label === 'ITT' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700')) + '">' +
                                itemFile.label + '</span>' +
                                '<span class="truncate text-slate-700 font-medium">' + itemFile.file.name + '</span>';
                
                var btn = document.createElement('button');
                btn.className = 'shrink-0 ml-3 text-blue-600 font-semibold hover:underline cursor-pointer';
                btn.textContent = 'Lihat';
                btn.onclick = function() {
                    if (itemFile.file.url) {
                        try {
                            var byteString = atob(itemFile.file.url.split(',')[1]);
                            var mimeString = itemFile.file.url.split(',')[0].split(':')[1].split(';')[0];
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
                    } else if (itemFile.file.path) {
                        window.open('/storage/' + itemFile.file.path, '_blank');
                    }
                };
                
                li.appendChild(div);
                li.appendChild(btn);
                list.appendChild(li);
            });
        }
    };
}
function closeFileModal() {
    var modal = document.getElementById('fileModal');
    if (modal) { modal.classList.add('hidden'); modal.classList.remove('flex'); }
}

// ===== DETAIL MODAL =====
function openDetailModal_tp(item) {
    document.getElementById('tp-mdl-ulp').textContent       = item.ulp || '-';
    document.getElementById('tp-mdl-transaksi').textContent = item.transaksi || '-';
    document.getElementById('tp-mdl-status').textContent    = item.status || '-';
    document.getElementById('tp-mdl-agenda').textContent    = item.no_agenda || '-';
    document.getElementById('tp-mdl-nama').textContent      = item.nama || '-';
    document.getElementById('tp-mdl-alamat').textContent    = item.alamat || '-';
    document.getElementById('tp-mdl-tbaru').textContent     = item.tarif_baru || '-';
    document.getElementById('tp-mdl-dbaru').textContent     = (item.daya_baru || '0') + ' VA';
    document.getElementById('tp-mdl-tlama').textContent     = item.tarif_lama || '-';
    document.getElementById('tp-mdl-dlama').textContent     = (item.daya_lama || '0') + ' VA';

    // Tanggal
    if (item.created_at) {
        var d = new Date(item.created_at);
        var ds = d.toISOString().split('T')[0];
        document.getElementById('tp-mdl-tgl-mohon').textContent = ds;
    }
    if (item.sentAt) {
        var d2 = new Date(item.sentAt);
        document.getElementById('tp-mdl-tgl-kirim').textContent = d2.toLocaleDateString('id-ID');
    }
    var ulpStatus = document.getElementById('tp-mdl-status-ulp-kirim');
    if (ulpStatus) {
        var sudahDikirimUlp = !!item.sentAt;
        ulpStatus.textContent = sudahDikirimUlp ? '✓ Sudah dikirim ULP' : '✕ Belum dikirim ULP';
        ulpStatus.className = sudahDikirimUlp ? 'text-emerald-600' : 'text-red-600';
        document.getElementById('tp-mdl-tgl-ulp-kirim').textContent = sudahDikirimUlp ? formatStatusDate_tp(item.sentAt) : '-';
    }
    updateUploadStatus_tp(item);

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
            document.getElementById('tp-mdl-rab-input').value = (d && d.rab) ? d.rab : (item.total_biaya || 0);
        };
    };

    window.currentItem_tp = item;
    updateVendorReportUploadState_tp(item.no_agenda);
    var ptEl = document.getElementById('tp-mdl-pt');
    if (ptEl) ptEl.value = item.vendor_pt || '';
    var vendorKonstruksiEl = document.getElementById('tp-mdl-vendor-konstruksi');
    if (vendorKonstruksiEl) vendorKonstruksiEl.value = item.konstruksi_vendor_user_id || '';
    var layakRadios = document.querySelectorAll('input[name="tp_status_layak"]');
    layakRadios.forEach(function(r) {
        r.checked = (item.vendor_status_layak && r.value === item.vendor_status_layak);
    });

    renderWoList_tp(agendaKey);
    renderExcelList_tp(agendaKey);
    renderBaCekList_tp(agendaKey);
    renderBaOperasiList_tp(agendaKey);
    if (typeof renderVendorReportsForAgenda === 'function') {
        renderVendorReportsForAgenda(item.no_agenda);
    }

    var m = document.getElementById('detailModal_tp');
    if (m) { m.classList.remove('hidden'); m.classList.add('flex'); }
}

function hasVendorReport_tp(noAgenda) {
    return (window._vendorReports || []).some(function(report) {
        return String(report.no_agenda || '') === String(noAgenda || '');
    });
}

function updateVendorReportUploadState_tp(noAgenda) {
    var enabled = hasVendorReport_tp(noAgenda);
    var controls = ['tp-mdl-wo-upload', 'tp-mdl-dokumen-upload'];
    var canUpload = controls.some(function(id) { return document.getElementById(id); });
    controls.forEach(function(id) {
        var control = document.getElementById(id);
        if (control) control.classList.toggle('hidden', !enabled);
    });
    var note = document.getElementById('tp-vendor-report-upload-note');
    if (note) note.classList.toggle('hidden', enabled || !canUpload);
}

function updateUploadStatus_tp(item) {
    var berkas = (item && item.berkas) || [];
    var konstruksiRow = document.getElementById('tp-mdl-status-wo-konstruksi'); if (konstruksiRow) konstruksiRow.closest('tr').remove();
    [['tp-mdl-status-wo-perencanaan', 'tp-mdl-tgl-wo-perencanaan', 'wo_perencanaan'], ['tp-mdl-status-ba-cek', 'tp-mdl-tgl-ba-cek', 'ba_cek'], ['tp-mdl-status-ba-acara', 'tp-mdl-tgl-ba-acara', 'dokumen'], ['tp-mdl-status-ba-operasi', 'tp-mdl-tgl-ba-operasi', 'ba_operasi']].forEach(function(config) {
        var status = document.getElementById(config[0]);
        if (!status) return;
        var file = berkas.find(function(file) { return file.jenis_berkas === config[2]; }); var sudahAda = !!file;
        status.textContent = sudahAda ? '✓ Sudah diunggah' : '✕ Belum diunggah';
        status.className = sudahAda ? 'text-emerald-600' : 'text-red-600';
        document.getElementById(config[1]).textContent = sudahAda ? formatStatusDate_tp(file.created_at) : '-';
    });
}

function formatStatusDate_tp(value) { if (!value) return '-'; var date = new Date(value); return isNaN(date) ? '-' : date.toLocaleDateString('id-ID'); }

function uploadServerBerkas(noAgenda, agendaKey, jenis, file) {
    var formData = new FormData();
    formData.append('no_agenda', noAgenda || '');
    formData.append('agendaKey', agendaKey || '');
    formData.append('jenis_berkas', jenis);
    formData.append('file', file);

    fetch('/' + _currentRole + '/api/upload-berkas', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(function(r) { return r.json(); })
    .then(function(res) {
        if (res.success && window.currentItem_tp) {
            if (!window.currentItem_tp.berkas) window.currentItem_tp.berkas = [];
            window.currentItem_tp.berkas.push(res.data);
            if (jenis === 'wo_perencanaan' || jenis === 'ba_cek' || jenis === 'dokumen') updateUploadStatus_tp(window.currentItem_tp);
            if (jenis === 'dokumen') renderExcelList_tp(agendaKey);
            if (jenis === 'ba_cek') renderBaCekList_tp(agendaKey);
            if (jenis === 'ba_operasi') renderBaOperasiList_tp(agendaKey);
        }
    })
    .catch(function(err) { console.error('Upload server berkas error:', err); });
}

function syncIndexedDBFilesToServer(agendaKey, noAgenda) {
    if (!agendaKey) return;
    var req = indexedDB.open('FastOnDocs', 2);
    req.onsuccess = function(e) {
        var db = e.target.result;
        if (!db || !db.objectStoreNames.contains('uploadedDocs')) return;
        var tx = db.transaction('uploadedDocs', 'readonly');
        var store = tx.objectStore('uploadedDocs');
        var getReq = store.get(agendaKey);
        getReq.onsuccess = function() {
            var d = getReq.result;
            if (!d) return;
            var types = ['ktp', 'itt', 'wo', 'excel'];
            types.forEach(function(type) {
                if (d[type] && d[type].length) {
                    d[type].forEach(function(f) {
                        if (f.url && f.name) {
                            try {
                                var parts = f.url.split(',');
                                if (parts.length < 2) return;
                                var mime = parts[0].split(':')[1].split(';')[0];
                                var bstr = atob(parts[1]);
                                var n = bstr.length;
                                var u8arr = new Uint8Array(n);
                                while(n--){ u8arr[n] = bstr.charCodeAt(n); }
                                var blob = new Blob([u8arr], {type: mime});
                                var file = new File([blob], f.name, {type: mime});
                                uploadServerBerkas(noAgenda, agendaKey, type, file);
                            } catch(err) { console.error('Error syncing file:', err); }
                        }
                    });
                }
            });
        };
    };
}

function kirimVendor_tp() {
    var isKonstruksi = @json((Auth::user()->role ?? '') === 'konstruksi');
    var pt = document.getElementById('tp-mdl-pt');
    var layak = document.querySelector('input[name="tp_status_layak"]:checked');

    if (!isKonstruksi) {
        if (!pt || !pt.value) { alert('Pilih Tujuan PT terlebih dahulu.'); return; }
        if (!layak) { alert('Pilih Status Kelayakan terlebih dahulu.'); return; }
    }

    var item = window.currentItem_tp;
    if (!item) { alert('Data agenda tidak ditemukan.'); return; }

    var ptVal = pt ? pt.value : '';
    var layakVal = layak ? layak.value : '';
    var vendorKonstruksi = document.getElementById('tp-mdl-vendor-konstruksi');
    if (isKonstruksi && (!vendorKonstruksi || !vendorKonstruksi.value)) { alert('Pilih tujuan Vendor Konstruksi terlebih dahulu.'); return; }

    syncIndexedDBFilesToServer(item.agendaKey || item.no_agenda, item.no_agenda);

    var formData = new FormData();
    formData.append('agendaKey', item.agendaKey || '');
    formData.append('no_agenda', item.no_agenda || '');
    formData.append('vendor_pt', ptVal);
    formData.append('vendor_status_layak', layakVal);
    if (vendorKonstruksi) formData.append('vendor_konstruksi_user_id', vendorKonstruksi.value);

    var fileKelayakan = document.getElementById('tp-mdl-file-kelayakan');
    if (fileKelayakan && fileKelayakan.files && fileKelayakan.files[0]) {
        formData.append('file_kelayakan', fileKelayakan.files[0]);
    }

    var fileWoTiang = document.getElementById('tp-mdl-file-wo-tiang');
    if (fileWoTiang && fileWoTiang.files && fileWoTiang.files[0]) {
        formData.append('file_wo_tiang', fileWoTiang.files[0]);
    }

    fetch('/' + _currentRole + '/api/kirim-vendor', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(function(r) { return r.json(); })
    .then(function(res) {
        if (res.success) {
            alert(isKonstruksi
                ? 'Data berhasil dikirim.'
                : 'Data berhasil dikirim ke ' + ptVal + ' dengan status: ' + layakVal.toUpperCase());
            if (window.currentItem_tp) {
                window.currentItem_tp.vendor_sent = 1;
                window.currentItem_tp.vendor_pt = ptVal;
                window.currentItem_tp.vendor_status_layak = layakVal;
                if (res.data && res.data.berkas) {
                    window.currentItem_tp.berkas = res.data.berkas;
                }
            }
            closeDetailModal_tp();
        } else {
            alert('Gagal mengirim data: ' + (res.message || 'Error server'));
        }
    })
    .catch(function(err) {
        console.error(err);
        alert('Terjadi kesalahan saat mengirim data ke vendor.');
    });
}
function closeDetailModal_tp() {
    var m = document.getElementById('detailModal_tp');
    if (m) { m.classList.add('hidden'); m.classList.remove('flex'); }
}

function saveRabField_tp() {
    if (!window.currentItem_tp) return;
    var agendaKey = window.currentItem_tp.no_agenda || 'default';
    var rabVal = document.getElementById('tp-mdl-rab-input').value;
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
                var ind = document.getElementById('tp-mdl-rab-indicator');
                if (ind) { ind.style.opacity = '1'; setTimeout(function(){ ind.style.opacity='0'; }, 2000); }
            };
        };
    };
}

// ===== WO UPLOAD =====
function handleWoUpload_tp(input) {
    if (!window.currentItem_tp) return;
    if (!hasVendorReport_tp(window.currentItem_tp.no_agenda)) {
        alert('Unggah WO tersedia setelah laporan vendor masuk.');
        input.value = '';
        return;
    }
    var agendaKey = window.currentItem_tp.no_agenda || 'default';
    var files = Array.from(input.files);
    if (!files.length) return;

    files.forEach(function(file) {
        uploadServerBerkas(window.currentItem_tp.no_agenda, agendaKey, 'wo_perencanaan', file);
    });

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
                tx.oncomplete = function() { renderWoList_tp(agendaKey); };
            };
        };
    });
    input.value = '';
}

function handleExcelUpload_tp(input) {
    if (!window.currentItem_tp) return;
    if (!hasVendorReport_tp(window.currentItem_tp.no_agenda)) {
        alert('Unggah dokumen tersedia setelah laporan vendor masuk.');
        input.value = '';
        return;
    }
    var agendaKey = window.currentItem_tp.no_agenda || "default";
    var files = Array.from(input.files);
    if (!files.length) return;
    if (files.some(function(file) { return !/\.(pdf|jpe?g|png)$/i.test(file.name); })) {
        alert('Hanya dokumen PDF, JPG, JPEG, atau PNG yang dapat diunggah.');
        input.value = '';
        return;
    }

    files.forEach(function(file) {
        uploadServerBerkas(window.currentItem_tp.no_agenda, agendaKey, 'dokumen', file);
    });

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
                tx.oncomplete = function() { renderExcelList_tp(agendaKey); };
            };
        };
    });
    input.value = "";
}

function renderWoList_tp(agendaKey) {
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
            var list = document.getElementById("tp-mdl-wo-list");
            if (!list) return;
            list.innerHTML = "";
            if (!d || !d.wo || !d.wo.length) {
                list.innerHTML = '<li class="text-[11px] text-slate-400 italic py-0.5">Belum ada berkas WO</li>';
                return;
            }
            d.wo.forEach(function(f, idx) {
                var li = document.createElement("li");
                li.className = "flex items-center justify-between bg-amber-50/50 border border-amber-100 rounded px-2 py-1 text-[11.5px]";
                var span = document.createElement("span");
                span.className = "truncate max-w-[180px] text-slate-700 font-medium";
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
                div.appendChild(viewBtn);

                if (role === "perencanaan") {
                    var delBtn = document.createElement("button");
                    delBtn.className = "text-red-500 hover:text-red-700 font-bold text-xs";
                    delBtn.textContent = "Hapus";
                    delBtn.onclick = (function(keyRef, idxRef) { return function() {
                        deleteWoFile_tp(keyRef, idxRef);
                    }; })(agendaKey, idx);
                    div.appendChild(delBtn);
                }

                li.appendChild(span);
                li.appendChild(div);
                list.appendChild(li);
            });
        };
    };
}

function deleteWoFile_tp(agendaKey, idx) {
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
                tx.oncomplete = function() { renderWoList_tp(agendaKey); };
            }
        };
    };
}

function handleBaCekUpload_tp(input) {
    if (!window.currentItem_tp) return;
    var files = Array.from(input.files);
    if (!files.length) return;
    if (files.some(function(file) { return !/\.(pdf|jpe?g|png)$/i.test(file.name); })) {
        alert('BA Cek harus berupa PDF, JPG, JPEG, atau PNG.'); input.value = ''; return;
    }
    var agendaKey = window.currentItem_tp.no_agenda || 'default';
    files.forEach(function(file) { uploadServerBerkas(window.currentItem_tp.no_agenda, agendaKey, 'ba_cek', file); });
    Promise.all(files.map(function(file) { return new Promise(function(resolve) {
        var reader = new FileReader(); reader.onload = function(e) { resolve({ name: file.name, url: e.target.result }); }; reader.readAsDataURL(file);
    }); })).then(function(newFiles) {
        var req = indexedDB.open('FastOnDocs', 2); req.onsuccess = function(e) {
            var tx = e.target.result.transaction('uploadedDocs', 'readwrite'), store = tx.objectStore('uploadedDocs'), gr = store.get(agendaKey);
            gr.onsuccess = function() { var d = gr.result || {}; d.agendaKey = agendaKey; d.ba_cek = (d.ba_cek || []).concat(newFiles); store.put(d); tx.oncomplete = function() { renderBaCekList_tp(agendaKey); }; };
        };
    });
    input.value = '';
}

function renderBaCekList_tp(agendaKey) {
    var req = indexedDB.open('FastOnDocs', 2); req.onsuccess = function(e) {
        var tx = e.target.result.transaction('uploadedDocs', 'readonly'), gr = tx.objectStore('uploadedDocs').get(agendaKey);
        gr.onsuccess = function() {
            var list = document.getElementById('tp-mdl-ba-cek-list'), localFiles = (gr.result && gr.result.ba_cek) || [];
            var files = ((window.currentItem_tp && window.currentItem_tp.berkas) || []).filter(function(file) { return file.jenis_berkas === 'ba_cek'; }).map(function(file) { return { id: file.id, name: file.nama_file, path: file.path_file }; });
            localFiles.forEach(function(file) { if (!files.some(function(serverFile) { return serverFile.name === file.name; })) files.push(file); });
            if (!list) return; list.innerHTML = '';
            if (!files.length) { list.innerHTML = '<li class="text-[11px] text-slate-400 italic">Belum ada BA Cek</li>'; return; }
            files.forEach(function(file) { var li = document.createElement('li'); li.className = 'flex items-center justify-between text-[11.5px] text-slate-700'; var name = document.createElement('span'); name.className = 'truncate'; name.textContent = file.name; var actions = document.createElement('div'); var view = document.createElement('button'); view.className = 'text-blue-600 font-bold hover:underline ml-2'; view.textContent = 'Lihat'; view.onclick = function() { window.open(file.url || '/storage/' + file.path, '_blank'); }; actions.appendChild(view); @if ((Auth::user()->role ?? '') === 'konstruksi') if (file.id) { var del = document.createElement('button'); del.className = 'text-red-500 font-bold hover:underline ml-2'; del.textContent = 'Hapus'; del.onclick = function() { deleteBaCek_tp(file.id, file.name, agendaKey); }; actions.appendChild(del); } @endif li.appendChild(name); li.appendChild(actions); list.appendChild(li); });
        };
    };
}

function handleBaOperasiUpload_tp(input) { if (!window.currentItem_tp) return; var files = Array.from(input.files); if (!files.length) return; if (files.some(function(file) { return !/\.(pdf|jpe?g|png)$/i.test(file.name); })) { alert('BA Operasi harus berupa PDF, JPG, JPEG, atau PNG.'); input.value = ''; return; } var key = window.currentItem_tp.no_agenda || 'default'; files.forEach(function(file) { uploadServerBerkas(window.currentItem_tp.no_agenda, key, 'ba_operasi', file); }); input.value = ''; }
function renderBaOperasiList_tp(agendaKey) { var list = document.getElementById('tp-mdl-ba-operasi-list'); if (!list) return; var files = ((window.currentItem_tp && window.currentItem_tp.berkas) || []).filter(function(file) { return file.jenis_berkas === 'ba_operasi'; }); list.innerHTML = files.length ? '' : '<li class="text-[11px] text-slate-400 italic">Belum ada BA Operasi</li>'; files.forEach(function(file) { var li = document.createElement('li'); var name = document.createElement('span'); var actions = document.createElement('span'); var view = document.createElement('a'); var remove = document.createElement('button'); li.className = 'flex justify-between text-[11.5px]'; name.className = 'truncate'; name.textContent = file.nama_file; view.className = 'ml-2 text-blue-600 font-bold'; view.href = '/storage/' + file.path_file; view.target = '_blank'; view.textContent = 'Lihat'; remove.type = 'button'; remove.className = 'ml-2 text-red-500 font-bold'; remove.textContent = 'Hapus'; remove.addEventListener('click', function() { deleteBaOperasi_tp(file.id, agendaKey); }); actions.appendChild(view); actions.appendChild(remove); li.appendChild(name); li.appendChild(actions); list.appendChild(li); }); }
function deleteBaOperasi_tp(id, agendaKey) { if (!confirm('Hapus BA Operasi ini?')) return; fetch('/' + _currentRole + '/api/berkas/' + id + '/ba-operasi', {method:'DELETE', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}}).then(function(r){return r.json();}).then(function(res){if(!res.success)throw new Error(res.message); window.currentItem_tp.berkas=window.currentItem_tp.berkas.filter(function(file){return file.id!==id;});renderBaOperasiList_tp(agendaKey);}).catch(function(error){alert(error.message||'Gagal menghapus BA Operasi.');}); }

function deleteBaCek_tp(id, fileName, agendaKey) {
    if (!confirm('Hapus berkas BA Cek ini?')) return;
    fetch('/' + _currentRole + '/api/berkas/' + id + '/ba-cek', { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
        .then(function(r) { return r.json(); }).then(function(res) { if (!res.success) throw new Error(res.message); window.currentItem_tp.berkas = (window.currentItem_tp.berkas || []).filter(function(file) { return file.id !== id; }); removeLocalBaCek_tp(agendaKey, fileName); })
        .catch(function(err) { alert(err.message || 'Gagal menghapus BA Cek.'); });
}

function removeLocalBaCek_tp(agendaKey, fileName) {
    var req = indexedDB.open('FastOnDocs', 2); req.onsuccess = function(e) {
        var db = e.target.result; if (!db.objectStoreNames.contains('uploadedDocs')) { renderBaCekList_tp(agendaKey); return; }
        var tx = db.transaction('uploadedDocs', 'readwrite'), store = tx.objectStore('uploadedDocs'), gr = store.get(agendaKey);
        gr.onsuccess = function() { var d = gr.result; if (d && d.ba_cek) { d.ba_cek = d.ba_cek.filter(function(file) { return file.name !== fileName; }); store.put(d); } tx.oncomplete = function() { renderBaCekList_tp(agendaKey); }; };
    };
}

function handleExcelUpload_tp(input) {
    if (!window.currentItem_tp) return;
    if (!hasVendorReport_tp(window.currentItem_tp.no_agenda)) {
        alert('Unggah dokumen tersedia setelah laporan vendor masuk.');
        input.value = '';
        return;
    }
    var agendaKey = window.currentItem_tp.no_agenda || "default";
    var files = Array.from(input.files);
    if (!files.length) return;
    if (files.some(function(file) { return !/\.(pdf|jpe?g|png)$/i.test(file.name); })) {
        alert('Hanya dokumen PDF, JPG, JPEG, atau PNG yang dapat diunggah.');
        input.value = '';
        return;
    }

    files.forEach(function(file) {
        uploadServerBerkas(window.currentItem_tp.no_agenda, agendaKey, 'dokumen', file);
    });

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
                tx.oncomplete = function() { renderExcelList_tp(agendaKey); };
            };
        };
    });
    input.value = "";
}

function renderExcelList_tp(agendaKey) {
    var list = document.getElementById('tp-mdl-excel-list');
    if (!list) return;
    var render = function(files) {
        list.innerHTML = files.length ? '' : '<li class="text-[11px] text-emerald-600 italic">Belum ada dokumen</li>';
        files.forEach(function(file) {
        var li = document.createElement('li'); var name = document.createElement('span'); var view = document.createElement('a');
        li.className = 'flex items-center justify-between bg-white border border-emerald-100 rounded px-2 py-1 text-[11.5px]';
        name.className = 'truncate max-w-[220px] text-slate-700'; name.textContent = file.nama_file;
        view.className = 'ml-2 text-blue-600 font-bold hover:underline'; view.href = '/storage/' + file.path_file; view.target = '_blank'; view.textContent = 'Lihat';
        li.appendChild(name); li.appendChild(view); list.appendChild(li);
        });
    };
    render(((window.currentItem_tp && window.currentItem_tp.berkas) || []).filter(function(file) { return file.jenis_berkas === 'dokumen'; }));
    fetch('/' + _currentRole + '/api/get-pengiriman?dest=tanpa_perluasan').then(function(response) { return response.json(); }).then(function(items) {
        var item = items.find(function(row) { return String(row.no_agenda || '') === String(agendaKey || ''); });
        if (!item) return; window.currentItem_tp.berkas = item.berkas || [];
        render(window.currentItem_tp.berkas.filter(function(file) { return file.jenis_berkas === 'dokumen'; }));
    });
}

function deleteExcelFile_tp(agendaKey, idx) {
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
                tx.oncomplete = function() { renderExcelList_tp(agendaKey); };
            }
        };
    };
}
</script>

@endsection
