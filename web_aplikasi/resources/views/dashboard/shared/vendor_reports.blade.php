@if ((Auth::user()->role ?? '') === 'perencanaan')
    <script>
        window._vendorReports = @json($vendorReports ?? []);

        if (typeof window.renderVendorReportsForAgenda !== 'function') {
            window.renderVendorReportsForAgenda = function(noAgenda) {
                var container = document.getElementById("vendor-reports-detail-list");
                if (!container) return;
                container.innerHTML = "";
                var reports = window._vendorReports || [];
                var filtered = reports.filter(function(r) {
                    return String(r.no_agenda || '') === String(noAgenda || '');
                });

                if (filtered.length === 0) {
                    container.innerHTML = '<p class="text-[11px] text-slate-500 italic px-2 py-1">Belum ada laporan dari vendor untuk agenda ini.</p>';
                    return;
                }

                filtered.forEach(function(report) {
                    var card = document.createElement("div");
                    card.className = "bg-white border border-slate-200 rounded-lg p-2.5 text-[11.5px] shadow-sm";
                    
                    var dateStr = report.created_at ? new Date(report.created_at).toLocaleString('id-ID') : '';
                    var html = '<div class="flex justify-between gap-2 border-b border-slate-100 pb-1 mb-1">' +
                               '<span class="font-bold text-slate-700">Agenda: ' + (report.no_agenda || '-') + '</span>' +
                               '<span class="text-slate-400 text-[10.5px]">' + dateStr + '</span>' +
                               '</div>' +
                               '<p class="text-slate-600 mt-1"><strong class="text-slate-700">Vendor:</strong> ' + (report.vendor_name || 'Vendor') + '</p>';
                    
                    if (report.checklist && report.checklist.length) {
                        html += '<p class="text-emerald-700 mt-1"><strong class="text-emerald-800">Checklist:</strong> ' + report.checklist.join(', ') + '</p>';
                    }
                    if (report.catatan) {
                        html += '<p class="text-slate-600 mt-1"><strong class="text-slate-700">Catatan:</strong> ' + report.catatan + '</p>';
                    }
                    if (report.files && report.files.length) {
                        html += '<div class="flex flex-wrap gap-x-3 gap-y-1 mt-2 pt-1 border-t border-slate-100">';
                        report.files.forEach(function(f) {
                            html += '<a href="/storage/' + f.path_file + '" target="_blank" class="text-blue-600 font-bold hover:underline flex items-center gap-1">📎 ' + f.nama_file + '</a>';
                        });
                        html += '</div>';
                    }
                    card.innerHTML = html;
                    container.appendChild(card);
                });
            };
        }
    </script>

    <div class="bg-slate-50/60 border border-slate-200 rounded-lg p-3 flex flex-col gap-2">
        <div class="font-bold text-slate-800 uppercase tracking-wider text-[13px] flex items-center justify-between">
            <span>Laporan Vendor</span>
            <span class="text-[10px] text-slate-400 font-normal normal-case">(Tampil sesuai agenda yang dipilih)</span>
        </div>
        <div id="vendor-reports-detail-list" class="flex flex-col gap-2 max-h-48 overflow-y-auto pr-1">
            <p class="text-[11px] text-slate-500 italic px-2 py-1">Belum ada laporan dari vendor untuk agenda ini.</p>
        </div>
    </div>
@endif
