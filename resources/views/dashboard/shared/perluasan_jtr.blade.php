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
                <p class="text-white font-bold text-sm tracking-wide">PERLUASAN JTR</p>
                <p class="text-blue-100 text-xs">Data yang dikirim ke Jaringan Tegangan Rendah</p>
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
                    <span class="text-white font-bold text-sm tracking-wide">RECORD, JUMLAH TRANSAKSI PERLUASAN JTR</span>
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
                            <td colspan="14" class="text-center py-12 text-slate-400 italic text-xs">
                                <div class="flex flex-col items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                    </svg>
                                    <span>Belum ada data yang dikirim ke Perluasan JTR</span>
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
var _ulpRoleFilter = _ulpRoleMap[_currentRole] || null; // null means show all (UP3 roles)
</script>
<script>
(function() {
    var DB_NAME   = 'FastOnDocs';
    var SENT_STORE = 'sentItems';
    var allItems = []; // store all loaded items

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
        return openDB().then(function(db) {
            return new Promise(function(resolve, reject) {
                var tx    = db.transaction(SENT_STORE, 'readonly');
                var store = tx.objectStore(SENT_STORE);
                var req   = store.getAll();
                req.onsuccess = function() {
                    var items = (req.result || []).filter(function(r) { return r.dest === dest; });
                    // Filter per ULP jika role adalah managerULP
                    if (window._ulpRoleFilter) {
                        items = items.filter(function(r) {
                            return (r.ulp || '').toUpperCase().indexOf(window._ulpRoleFilter) !== -1;
                        });
                    }
                    resolve(items);
                };
                req.onerror = function(e) { reject(e); };
            });
        });
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
            if (footerEl) footerEl.textContent = 'Records 0 to 0 of 0';
            return;
        }

        if (emptyRow) emptyRow.style.display = 'none';
        if (countEl)  countEl.textContent = items.length + ' data';
        if (footerEl) footerEl.textContent = 'Records 1 to ' + items.length + ' of ' + items.length;

        items.forEach(function(item, i) {
            var tr = document.createElement('tr');
            tr.className = 'bg-white hover:bg-slate-50 transition border-b border-slate-100 text-slate-700';
            tr.innerHTML =
                '<td class="border border-slate-200 px-3 py-2 text-center text-slate-400 font-mono">' + (i+1) + '.</td>' +
                '<td class="border border-slate-200 px-3 py-2 text-center font-medium font-mono text-[11px]">' + (item.no_agenda || '-') + '</td>' +
                '<td class="border border-slate-200 px-3 py-2 text-left">' + (item.nama || '-') + '</td>' +
                '<td class="border border-slate-200 px-3 py-2 text-left max-w-xs truncate" title="' + (item.alamat || '') + '">' + (item.alamat || '-') + '</td>' +
                '<td class="border border-slate-200 px-3 py-2 text-center">' + (item.transaksi || '-') + '</td>' +
                '<td class="border border-slate-200 px-3 py-2 text-center">' + (item.status || '-') + '</td>' +
                '<td class="border border-slate-200 px-3 py-2 text-center">' + (item.tarif_lama || '-') + '</td>' +
                '<td class="border border-slate-200 px-3 py-2 text-center">' + (item.daya_lama || '-') + ' VA</td>' +
                '<td class="border border-slate-200 px-3 py-2 text-center font-semibold text-blue-900">' + (item.tarif_baru || '-') + '</td>' +
                '<td class="border border-slate-200 px-3 py-2 text-center font-semibold text-blue-900">' + (item.daya_baru || '-') + ' VA</td>' +
                '<td class="border border-slate-200 px-3 py-2 text-center">' +
                    '<span class="inline-flex items-center gap-1 text-[10px]">' +
                    '<span class="bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded font-semibold">BP:' + item.ktpCount + '</span>' +
                    '<span class="bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded font-semibold">ITT:' + item.ittCount + '</span>' +
                    '</span></td>' +
                '<td class="border border-slate-200 px-3 py-2 text-center text-[10px] text-slate-500 whitespace-nowrap">' + formatDate(item.sentAt) + '</td>' +
                '<td class="border border-slate-200 px-3 py-2 text-center"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800 whitespace-nowrap">' + (item.ulp || '-') + '</span></td>';
            tbody.appendChild(tr);
        });
    }

    window.filterData = function() {
        var agendaFilter = (document.getElementById('filterAgenda').value || '').toLowerCase();
        var transFilter  = (document.getElementById('filterTransaksi').value || '').toLowerCase();
        var statusFilter = (document.getElementById('filterStatus').value || '').toLowerCase();
        
        var dateStart = document.getElementById('filterDateStart').value;
        var dateEnd   = document.getElementById('filterDateEnd').value;

        var filtered = allItems.filter(function(item) {
            var matchAgenda = true, matchTrans = true, matchStatus = true, matchDate = true;

            if (agendaFilter) {
                var no_agenda = (item.no_agenda || '').toLowerCase();
                matchAgenda = no_agenda.includes(agendaFilter);
            }
            if (transFilter) {
                var trans = (item.transaksi || '').toLowerCase();
                matchTrans = trans.includes(transFilter);
            }
            if (statusFilter) {
                var status = (item.status || '').toLowerCase();
                matchStatus = status.includes(statusFilter);
            }
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
        filterData(); // Ensure filters are applied when shown
    };

    document.addEventListener('DOMContentLoaded', function() {
        loadSentItems('jtr').then(function(items) {
            allItems = items;
            // Note: table is hidden initially until Tampilkan is clicked
        }).catch(function(e) {
            console.warn('Error loading JTR data:', e);
        });
    });
})();
</script>

@endsection
