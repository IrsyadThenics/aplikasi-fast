@extends('layouts.app')

@section('content')
<div class="h-full flex flex-col bg-slate-50 p-5 gap-4">

    {{-- ===== SUCCESS ALERT ===== --}}
    @if (session('success'))
        <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 rounded-xl flex items-center gap-2 text-sm font-medium flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ===== UPLOAD CARD ===== --}}
    <div class="bg-white rounded-2xl shadow-md border border-slate-200 overflow-hidden flex-shrink-0">

        {{-- Card Header --}}
        <div class="bg-[#0D1B8C] px-6 py-3 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white/80" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
            </svg>
            <div>
                <p class="text-white font-bold text-sm tracking-wide">UPLOAD DATA EXCEL</p>
                <p class="text-blue-100 text-xs">Full Acceleration & Service Tracking ON 360°</p>
            </div>
        </div>

        {{-- Form --}}
        <div class="px-6 py-5">
            <form id="uploadForm" action="{{ route('pelayanan.upload_data.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="flex items-end gap-4 flex-wrap">
                    <div class="flex-1 min-w-[260px]">
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-widest">Pilih File Excel / CSV</label>
                        <input type="file" id="fileInput" name="file" required accept=".xlsx,.xls,.csv"
                            class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-slate-50 text-slate-700 transition" />
                        @error('file')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol Preview (JS) --}}
                    <button type="button" onclick="previewFile()"
                        class="border border-[#0D1B8C] text-[#0D1B8C] hover:bg-[#0D1B8C] hover:text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-all active:scale-95 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Preview
                    </button>

                    {{-- Tombol Import ke Database --}}
                    <button type="submit" id="btnImport" disabled
                        class="bg-[#0D1B8C] text-white text-sm font-semibold px-5 py-2.5 rounded-lg shadow-sm transition-all active:scale-95 flex items-center gap-2 disabled:opacity-40 disabled:cursor-not-allowed hover:enabled:bg-[#FACC15] hover:enabled:text-[#1E3A8A]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                        </svg>
                        upload
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== PREVIEW TABLE (muncul setelah file dipilih & preview diklik) ===== --}}
    <div id="previewArea" class="hidden flex-col flex-1 min-h-0">
        <div class="bg-white rounded-2xl shadow-md border border-slate-200 flex flex-col flex-1 min-h-0 overflow-hidden">

            {{-- Header --}}
            <div class="bg-[#0D1B8C] px-5 py-2.5 flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-2">
                    <span class="text-white font-bold text-sm tracking-wide">PREVIEW ISI FILE</span>
                    <span id="previewFileName" class="bg-white/20 text-white text-xs px-2 py-0.5 rounded-full font-mono"></span>
                    <span id="previewRowCount" class="bg-yellow-400 text-[#0D1B8C] text-xs px-2 py-0.5 rounded-full font-mono font-bold"></span>
                </div>
                <button onclick="tutupPreview()" class="text-blue-200 hover:text-white text-xs transition flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Tutup
                </button>
            </div>

            {{-- Scrollable preview --}}
            <div class="overflow-auto flex-1">
                <div id="previewTableContainer" class="text-xs">
                    {{-- Tabel hasil parse file akan dimasukkan di sini oleh JS --}}
                </div>
            </div>

            {{-- Footer --}}
            <div class="border-t border-slate-100 px-5 py-2 bg-slate-50 flex-shrink-0 flex items-center justify-between">
                <span id="previewFooter" class="text-xs text-slate-400 font-mono"></span>
                <span class="text-xs text-amber-600 font-medium">⚠ Ini hanya preview. Klik "Simpan ke Database" untuk mengimpor data.</span>
            </div>

        </div>
    </div>

</div>

{{-- SheetJS CDN --}}
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<script>
    let parsedData = [];

    function previewFile() {
        const input = document.getElementById('fileInput');
        if (!input.files || input.files.length === 0) {
            alert('Pilih file terlebih dahulu!');
            return;
        }

        const file = input.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, { type: 'array' });

            // Ambil sheet pertama
            const sheetName = workbook.SheetNames[0];
            const sheet = workbook.Sheets[sheetName];

            // Ubah ke JSON (baris pertama = header)
            const jsonData = XLSX.utils.sheet_to_json(sheet, { header: 1, defval: '' });

            if (jsonData.length === 0) {
                alert('File kosong atau tidak dapat dibaca!');
                return;
            }

            parsedData = jsonData;
            renderPreviewTable(jsonData, file.name);

            // Aktifkan tombol simpan
            document.getElementById('btnImport').disabled = false;
        };

        reader.readAsArrayBuffer(file);
    }

    function renderPreviewTable(data, fileName) {
        if (data.length === 0) return;

        const headers = data[0];
        const rows = data.slice(1);
        const dataRows = rows.filter(r => r.some(cell => cell !== ''));

        // Update info
        document.getElementById('previewFileName').textContent = fileName;
        document.getElementById('previewRowCount').textContent = dataRows.length + ' baris';
        document.getElementById('previewFooter').textContent = 'Total ' + dataRows.length + ' baris data, ' + headers.length + ' kolom';

        // Bangun tabel HTML
        let html = '<table class="w-full border-collapse">';
        html += '<thead class="sticky top-0 z-10"><tr class="bg-[#0D1B8C] text-white">';

        // Kolom nomor
        html += '<th class="border border-blue-700 px-3 py-2 text-center whitespace-nowrap">NO.</th>';
        headers.forEach(function(h) {
            html += '<th class="border border-blue-700 px-3 py-2 text-center whitespace-nowrap">' + escHtml(String(h)) + '</th>';
        });
        html += '</tr></thead><tbody>';

        if (dataRows.length === 0) {
            html += '<tr><td colspan="' + (headers.length + 1) + '" class="text-center py-10 text-slate-400 italic">Tidak ada data baris</td></tr>';
        } else {
            dataRows.forEach(function(row, idx) {
                const bg = idx % 2 === 0 ? 'bg-white' : 'bg-slate-50';
                html += '<tr class="' + bg + ' hover:bg-blue-50 transition border-b border-slate-100">';
                html += '<td class="border border-slate-200 px-3 py-2 text-center text-slate-500 font-mono">' + (idx + 1) + '</td>';
                headers.forEach(function(_, colIdx) {
                    const cell = row[colIdx] !== undefined ? row[colIdx] : '';
                    html += '<td class="border border-slate-200 px-3 py-2 text-left whitespace-nowrap">' + escHtml(String(cell)) + '</td>';
                });
                html += '</tr>';
            });
        }

        html += '</tbody></table>';

        document.getElementById('previewTableContainer').innerHTML = html;

        // Tampilkan area preview
        const previewArea = document.getElementById('previewArea');
        previewArea.classList.remove('hidden');
        previewArea.classList.add('flex');

        // Scroll ke preview
        previewArea.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function tutupPreview() {
        const previewArea = document.getElementById('previewArea');
        previewArea.classList.add('hidden');
        previewArea.classList.remove('flex');
    }

    function escHtml(str) {
        return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    // Auto-preview jika file sudah dipilih langsung
    document.getElementById('fileInput').addEventListener('change', function() {
        if (this.files && this.files.length > 0) {
            previewFile();
        }
    });
</script>

@endsection