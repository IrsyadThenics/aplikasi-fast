<!-- Expansion Detail Form shared by every ULP -->
<div id="expansionFormModal" class="fixed inset-0 z-[210] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden border border-slate-200">
        <div class="bg-gradient-to-r from-[#0D1B8C] to-[#2B73FE] px-6 py-4 flex items-center justify-between">
            <div><p id="expansionFormTitle" class="text-white font-bold text-sm tracking-wide">Form Perluasan</p><p class="text-blue-200 text-xs mt-0.5">Lengkapi kebutuhan material untuk setiap data</p></div>
            <button onclick="closeExpansionForm()" class="text-white/60 hover:text-white transition">✕</button>
        </div>
        <div class="p-5 overflow-y-auto max-h-[calc(90vh-145px)]"><div id="expansionFormRows" class="space-y-4"></div></div>
        <div class="border-t border-slate-100 px-5 py-3 flex justify-end gap-2 bg-slate-50"><button onclick="closeExpansionForm()" class="px-4 py-2 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-200">Batal</button><button onclick="submitExpansionForm()" class="px-5 py-2 rounded-lg text-xs font-bold text-white bg-[#0D1B8C] hover:bg-blue-800">Simpan & Kirim</button></div>
    </div>
</div>

<script>
var _expansionDest = null;

function openExpansionForm(dest) {
    _expansionDest = dest;
    closeDestModal();
    document.getElementById('expansionFormTitle').textContent = 'Form Perluasan ' + dest.toUpperCase();
    document.getElementById('expansionFormRows').innerHTML = Object.keys(_selectedItems).map(function(agendaKey, index) {
        var item = _selectedItems[agendaKey] || {};
        return '<div class="rounded-xl border border-slate-200 p-4 bg-white shadow-sm" data-agenda="' + escapeHtml(agendaKey) + '">' +
            '<div class="mb-3"><p class="text-xs font-bold text-slate-700">' + (index + 1) + '. ' + escapeHtml(item.nama || '-') + '</p><p class="text-[11px] text-slate-400">No. Agenda: ' + escapeHtml(item.no_agenda || agendaKey) + '</p></div>' +
            '<div class="overflow-hidden rounded-lg border border-slate-300"><table class="w-full border-collapse text-[11px]"><thead><tr class="bg-slate-100 text-slate-600"><th class="border-b border-slate-300 px-2 py-2 text-left">KETERANGAN</th><th class="border-b border-l border-slate-300 px-2 py-2 text-left">JENIS</th><th class="border-b border-l border-slate-300 px-2 py-2 text-left">JUMLAH</th><th class="border-b border-l border-slate-300 px-2 py-2 text-left">SATUAN</th></tr></thead><tbody>' +
            '<tr><td class="border-b border-slate-200 px-2 py-2 font-bold">JUMLAH TIANG</td><td class="border-b border-l border-slate-200 px-2 py-2"><select data-field="combo_tiang" class="w-full border border-slate-300 rounded px-2 py-1.5 bg-orange-50"><option value="">Pilih</option><option value="9">9</option><option value="11">11</option><option value="13">13</option></select></td><td class="border-b border-l border-slate-200 px-2 py-2"><input data-field="jumlah_tiang" type="text" placeholder="Isi jumlah sendiri" class="w-full border border-slate-300 rounded px-2 py-1.5 bg-orange-50"></td><td class="border-b border-l border-slate-200 px-2 py-2">BUAH</td></tr>' +
            '<tr><td class="border-b border-slate-200 px-2 py-2 font-bold">JUMLAH KONDUKTOR</td><td class="border-b border-l border-slate-200 px-2 py-2"></td><td class="border-b border-l border-slate-200 px-2 py-2"><input data-field="jumlah_konduktor" type="text" placeholder="Isi jumlah meter" class="w-full border border-slate-300 rounded px-2 py-1.5 bg-orange-50"></td><td class="border-b border-l border-slate-200 px-2 py-2">METER</td></tr>' +
            '<tr><td class="px-2 py-2 font-bold">JUMLAH TRAFO</td><td class="border-l border-slate-200 px-2 py-2"><select data-field="combo_trafo" class="w-full border border-slate-300 rounded px-2 py-1.5 bg-orange-50"><option value="">Pilih</option><option value="0">0</option><option value="100">100</option><option value="160">160</option><option value="200">200</option><option value="250">250</option></select></td><td class="border-l border-slate-200 px-2 py-2"><input data-field="jumlah_trafo" type="text" placeholder="Isi jumlah sendiri" class="w-full border border-slate-300 rounded px-2 py-1.5 bg-orange-50"></td><td class="border-l border-slate-200 px-2 py-2">BUAH</td></tr>' +
            '<tr><td class="border-b border-slate-200 px-2 py-2 font-bold">JUMLAH KWH METER</td><td class="border-b border-l border-slate-200 px-2 py-2"></td><td class="border-b border-l border-slate-200 px-2 py-2"><input data-field="jumlah_kwh" type="text" placeholder="Isi jumlah meter" class="w-full border border-slate-300 rounded px-2 py-1.5 bg-orange-50"></td><td class="border-b border-l border-slate-200 px-2 py-2">METER</td></tr>' +
            '</tbody></table></div></div>';
    }).join('');
    var modal = document.getElementById('expansionFormModal');
    modal.classList.remove('hidden'); modal.classList.add('flex');
}

function closeExpansionForm() {
    var modal = document.getElementById('expansionFormModal');
    modal.classList.add('hidden'); modal.classList.remove('flex');
    _expansionDest = null;
}

function submitExpansionForm() {
    var cards = Array.from(document.querySelectorAll('#expansionFormRows [data-agenda]'));
    if (!cards.length || !_expansionDest) return;
    var invalid = cards.some(function(card) { return !card.querySelector('[data-field="combo_tiang"]').value || !card.querySelector('[data-field="jumlah_tiang"]').value.trim() || !card.querySelector('[data-field="jumlah_konduktor"]').value.trim() || !card.querySelector('[data-field="combo_trafo"]').value || !card.querySelector('[data-field="jumlah_trafo"]').value.trim(); });
    if (invalid) { alert('Lengkapi jumlah tiang, konduktor, dan trafo untuk semua data.'); return; }
    var dest = _expansionDest;
    var promises = cards.map(function(card) {
        var agendaKey = card.getAttribute('data-agenda'), item = _selectedItems[agendaKey], docs = uploadedDocs[agendaKey] || { ktp: [], itt: [] }, detail = {};
        card.querySelectorAll('[data-field]').forEach(function(input) { detail[input.getAttribute('data-field')] = input.value.trim(); });
        var data = {agendaKey: agendaKey, dest: dest, no_agenda: item.no_agenda || agendaKey, nama: item.nama || '-', alamat: item.alamat || '-', transaksi: item.transaksi || '-', status: item.status || '-', tarif_lama: item.tarif_lama || '-', daya_lama: item.daya_lama || 0, tarif_baru: item.tarif_baru || '-', daya_baru: item.daya_baru || 0, total_biaya: item.total_biaya || 0, ulp: item.ulp || '-', ktpCount: docs.ktp ? docs.ktp.length : 0, ittCount: docs.itt ? docs.itt.length : 0, detail_perluasan: detail};
        var rolePrefix = window.location.pathname.split('/')[1] || 'ulp';
        return fetch('/' + rolePrefix + '/api/kirim-data', {method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}, body:JSON.stringify(data)}).then(function(res){return res.text().then(function(text){var payload; try { payload = JSON.parse(text); } catch (e) { throw new Error('Server mengembalikan halaman error (HTTP ' + res.status + ').'); } if (!res.ok || !payload.success) throw new Error(payload.message || 'Gagal mengirim data (HTTP ' + res.status + ').'); return payload;});}).then(function(){_checkedRows[agendaKey] = true; var row = document.getElementById('row-' + agendaKey); if(row) row.style.display = 'none';});
    });
    Promise.all(promises).then(function(){reIndexTable(); closeExpansionForm(); alert(cards.length + ' Data berhasil dikirim ke Perluasan ' + dest.toUpperCase() + '!'); _selectedItems = {}; updateSelectedCount(); checkIfAllSelected(); var checkAll = document.getElementById('checkAllKirim'); if(checkAll) checkAll.checked = false;}).catch(function(error){console.error(error); alert(error.message || 'Gagal mengirim data.');});
}
</script>
