<div id="expansionFormModalShared" class="fixed inset-0 z-[210] hidden items-center justify-center bg-black/60 p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden border border-slate-200">
    <div class="bg-gradient-to-r from-[#0D1B8C] to-[#2B73FE] px-6 py-4 flex items-center justify-between"><div><p id="sharedExpansionTitle" class="text-white font-bold text-sm tracking-wide">Form Perluasan</p><p class="text-blue-200 text-xs mt-0.5">Lengkapi kebutuhan material untuk setiap data</p></div><button onclick="closeSharedExpansion()" class="text-white/60 hover:text-white transition">✕</button></div>
    <div id="sharedExpansionRows" class="p-5 overflow-y-auto max-h-[calc(90vh-145px)] space-y-4"></div>
    <div class="border-t border-slate-100 px-5 py-3 flex justify-end gap-2 bg-slate-50"><button onclick="closeSharedExpansion()" class="px-4 py-2 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-200">Batal</button><button onclick="submitSharedExpansion()" class="px-5 py-2 rounded-lg text-xs font-bold text-white bg-[#0D1B8C] hover:bg-blue-800">Simpan & Kirim</button></div>
  </div>
</div>
<script>
(function () {
  var sharedDest = null;
  function esc(v) { return String(v == null ? '' : v).replace(/[&<>'"]/g, function(c) { return {'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#39;','"':'&quot;'}[c]; }); }
  window.openSharedExpansion = function(dest) {
    sharedDest = dest;
    if (typeof closeDestModal === 'function') closeDestModal();
    var items = window._selectedItems || {};
    document.getElementById('sharedExpansionTitle').textContent = 'Form Perluasan ' + dest.toUpperCase();
    document.getElementById('sharedExpansionRows').innerHTML = Object.keys(items).map(function(key, i) {
      var item = items[key] || {};
      return '<div class="rounded-xl border border-slate-200 p-4 bg-white shadow-sm" data-shared-agenda="' + esc(key) + '"><div class="mb-3"><p class="text-xs font-bold text-slate-700">' + (i + 1) + '. ' + esc(item.nama || '-') + '</p><p class="text-[11px] text-slate-400">No. Agenda: ' + esc(item.no_agenda || key) + '</p></div><div class="overflow-hidden rounded-lg border border-slate-300"><table class="w-full border-collapse text-[11px]"><thead><tr class="bg-slate-100 text-slate-600"><th class="border-b border-slate-300 px-2 py-2 text-left">KETERANGAN</th><th class="border-b border-l border-slate-300 px-2 py-2 text-left">JENIS</th><th class="border-b border-l border-slate-300 px-2 py-2 text-left">JUMLAH</th><th class="border-b border-l border-slate-300 px-2 py-2 text-left">SATUAN</th></tr></thead><tbody><tr><td class="border-b border-slate-200 px-2 py-2 font-bold">JUMLAH TIANG</td><td class="border-b border-l border-slate-200 px-2 py-2"><select data-shared-field="combo_tiang" class="w-full border border-slate-300 rounded px-2 py-1.5 bg-orange-50"><option value="">Pilih</option><option value="9">9</option><option value="11">11</option><option value="13">13</option></select></td><td class="border-b border-l border-slate-200 px-2 py-2"><input data-shared-field="jumlah_tiang" class="w-full border border-slate-300 rounded px-2 py-1.5 bg-orange-50" placeholder="Isi jumlah sendiri"></td><td class="border-b border-l border-slate-200 px-2 py-2">BUAH</td></tr><tr><td class="border-b border-slate-200 px-2 py-2 font-bold">JUMLAH KONDUKTOR</td><td class="border-b border-l border-slate-200 px-2 py-2"></td><td class="border-b border-l border-slate-200 px-2 py-2"><input data-shared-field="jumlah_konduktor" class="w-full border border-slate-300 rounded px-2 py-1.5 bg-orange-50" placeholder="Isi jumlah meter"></td><td class="border-b border-l border-slate-200 px-2 py-2">METER</td></tr><tr><td class="border-b border-slate-200 px-2 py-2 font-bold">JUMLAH TRAFO</td><td class="border-b border-l border-slate-200 px-2 py-2"><select data-shared-field="combo_trafo" class="w-full border border-slate-300 rounded px-2 py-1.5 bg-orange-50"><option value="">Pilih</option><option value="0">0</option><option value="100">100</option><option value="160">160</option><option value="200">200</option><option value="250">250</option></select></td><td class="border-b border-l border-slate-200 px-2 py-2"><input data-shared-field="jumlah_trafo" class="w-full border border-slate-300 rounded px-2 py-1.5 bg-orange-50" placeholder="Isi jumlah sendiri"></td><td class="border-b border-l border-slate-200 px-2 py-2">BUAH</td></tr><tr><td class="px-2 py-2 font-bold">JUMLAH KWH METER</td><td class="border-l border-slate-200 px-2 py-2"></td><td class="border-l border-slate-200 px-2 py-2"><input data-shared-field="jumlah_kwh" class="w-full border border-slate-300 rounded px-2 py-1.5 bg-orange-50" placeholder="Isi jumlah meter"></td><td class="border-l border-slate-200 px-2 py-2">METER</td></tr></tbody></table></div></div>';
    }).join('');
    var modal = document.getElementById('expansionFormModalShared'); modal.classList.remove('hidden'); modal.classList.add('flex');
  };
  window.closeSharedExpansion = function() { var modal = document.getElementById('expansionFormModalShared'); modal.classList.add('hidden'); modal.classList.remove('flex'); sharedDest = null; };
  window.submitSharedExpansion = function() {
    var cards = Array.from(document.querySelectorAll('#sharedExpansionRows [data-shared-agenda]')), items = window._selectedItems || {};
    if (!cards.length || !sharedDest) return;
    var required = ['combo_tiang','jumlah_tiang','jumlah_konduktor','combo_trafo','jumlah_trafo'];
    if (cards.some(function(card) { return required.some(function(field) { var el = card.querySelector('[data-shared-field="' + field + '"]'); return !el || !el.value.trim(); }); })) { alert('Lengkapi jumlah tiang, konduktor, dan trafo.'); return; }
    var dest = sharedDest;
    Promise.all(cards.map(function(card) {
      var key = card.getAttribute('data-shared-agenda'), item = items[key] || {}, detail = {}, docs = (window.uploadedDocs || {})[key] || {};
      card.querySelectorAll('[data-shared-field]').forEach(function(el) { detail[el.getAttribute('data-shared-field')] = el.value.trim(); });
      var prefix = location.pathname.split('/')[1] || 'ulp';
      return fetch('/' + prefix + '/api/kirim-data', {method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}, body:JSON.stringify({agendaKey:key,dest:dest,no_agenda:item.no_agenda||key,nama:item.nama||'-',alamat:item.alamat||'-',transaksi:item.transaksi||'-',status:item.status||'-',tarif_lama:item.tarif_lama||'-',daya_lama:item.daya_lama||0,tarif_baru:item.tarif_baru||'-',daya_baru:item.daya_baru||0,total_biaya:item.total_biaya||0,ulp:item.ulp||'-',ktpCount:(docs.ktp||[]).length,ittCount:(docs.itt||[]).length,detail_perluasan:detail})}).then(function(res) { return res.json().then(function(body) { if (!res.ok || !body.success) throw new Error(body.message || 'Gagal mengirim data.'); }); }).then(function() { var row=document.getElementById('row-'+key); if(row) row.style.display='none'; });
    })).then(function() { if(typeof reIndexTable==='function') reIndexTable(); closeSharedExpansion(); window._selectedItems={}; if(typeof updateSelectedCount==='function') updateSelectedCount(); if(typeof checkIfAllSelected==='function') checkIfAllSelected(); var checkAll=document.getElementById('checkAllKirim'); if(checkAll) checkAll.checked=false; alert(cards.length + ' data berhasil dikirim ke Perluasan ' + dest.toUpperCase() + '.'); }).catch(function(err) { alert(err.message); });
  };
  var originalConfirm = window.confirmKirim;
  window.confirmKirim = function(dest) { if ((dest === 'jtm' || dest === 'jtr') && Object.keys(window._selectedItems || {}).length) return window.openSharedExpansion(dest); return originalConfirm.apply(this, arguments); };
}());
</script>
