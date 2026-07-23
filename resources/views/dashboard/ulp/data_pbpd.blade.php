@extends('layouts.app')

@section('content')
<div class="h-full flex flex-col pt-1">

    {{-- ===== FILTER CARD ===== --}}
    <div class="glass-card overflow-hidden flex-shrink-0 mb-6 border-0 shadow-[0_4px_20px_rgba(0,0,0,0.08)]">
        
        {{-- Card Header matching the table header style --}}
        <div class="px-6 py-3 flex items-center justify-between" style="background-color: var(--sidebar-bg); border-top-left-radius: 12px; border-top-right-radius: 12px;">
            <div class="flex items-center gap-3">
                <p class="text-white font-bold text-[0.95rem] tracking-wide">Filter Transaksi PB/PD</p>
            </div>
        </div>

        {{-- Filter Fields --}}
        <div class="px-6 py-5 bg-white">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
                {{-- No Agenda --}}
                <div>
                    <label class="block text-[0.7rem] font-bold text-slate-500 mb-1.5 uppercase tracking-wider">No Agenda</label>
                    <input type="text" placeholder="Cari no. agenda..."
                        class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 bg-slate-50 text-slate-700 transition placeholder:text-slate-400" />
                </div>
                {{-- Transaksi --}}
                <div class="flex items-center gap-2">
                    <span class="text-sm font-semibold text-slate-600">Jenis Transaksi :</span>
                    <select class="border border-slate-200 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-slate-50 text-slate-700 transition min-w-[150px]">
                        <option>--- pilih transaksi ---</option>
                        <option>Perluasan Pasang baru</option>
                        <option>Perluasan Perubahan daya</option>
                    </select>
                </div>
                {{-- Status Mohon --}}
                <div class="flex items-center gap-2">
                    <span class="text-sm font-semibold text-slate-600">Status Mohon :</span>
                    <select class="border border-slate-200 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-slate-50 text-slate-700 transition min-w-[150px]">
                        <option>--- pilih transaksi ---</option>
                        <option>Perluasan Pasang baru</option>
                        <option>Perluasan Perubahan daya</option>
                    </select>
                </div>

                {{-- status --}}
                <div class="flex items-center gap-2">
                    <span class="text-sm font-semibold text-slate-600">status :</span>
                    <select class="border border-slate-200 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-slate-50 text-slate-700 transition min-w-[150px]">
                        <option>--- pilih status ---</option>
                        <option>Perluasan JTM</option>
                        <option>Perluasan JTR</option>
                    </select>
                </div>
            </div>

            {{-- Date Row --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-4 border-t border-slate-100">
                <div class="flex items-center gap-3 flex-wrap">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Tanggal Bayar :</span>
                    <div class="flex items-center gap-2 bg-[#ffece8] rounded-lg px-2 border border-red-100 text-red-700 text-sm">
                        <input type="date" value="2026-08-07" class="bg-transparent border-none focus:ring-0 text-sm py-1.5 w-[120px] cursor-pointer" />
                        <span class="text-red-300">-</span>
                        <input type="date" value="2026-08-07" class="bg-transparent border-none focus:ring-0 text-sm py-1.5 w-[120px] cursor-pointer" />
                    </div>
                </div>
                
                <button onclick="tampilkanTabel()"
                    class="bg-[#0D1B8C] hover:bg-[#FACC15] text-white text-sm font-semibold px-6 py-2 rounded-lg shadow-[0_4px_10px_rgba(59,130,246,0.3)] transition-all active:scale-95 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    Tampilkan Data
                </button>
            </div>
        </div>
    </div>

    {{-- ===== TABLE AREA ===== --}}
    <div id="tableArea" class="hidden mt-2 flex flex-col flex-1 min-h-0">
        <div class="glass-card flex flex-col flex-1 min-h-0 overflow-hidden border-0 shadow-[0_4px_20px_rgba(0,0,0,0.08)]">

            {{-- Table header bar matching image "Recent Visitor" --}}
            <div class="px-5 py-3 flex items-center justify-between flex-shrink-0" style="background-color: var(--sidebar-bg); border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <div class="flex items-center gap-3">
                    <span class="text-white font-bold text-[0.95rem] tracking-wide">Data Transaksi PB/PD</span>
                </div>
                
                <div class="flex items-center gap-3">
                    {{-- Match the search / filter dropdown from image --}}
                    <div class="relative bg-white rounded-full overflow-hidden flex items-center px-3 py-1 w-48">
                        <input type="text" placeholder="Search" class="bg-transparent border-none focus:ring-0 text-xs text-slate-700 w-full p-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                    </div>
                    
                    <button class="bg-[#3b82f6] text-white text-xs font-semibold px-3 py-1.5 rounded-full flex items-center gap-1">
                        Filter By
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    
                    <button onclick="tutupTabel()" class="ml-2 w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition text-[10px]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Scrollable Table --}}
            <div class="overflow-auto flex-1 bg-white">
                <table class="w-full text-[0.75rem] border-collapse relative">
                    <thead class="sticky top-0 z-10 bg-white shadow-sm font-bold text-slate-700">
                        <tr>
                            <th class="border-b border-slate-200 px-4 py-3 text-center whitespace-nowrap" rowspan="2">NO.</th>
                            <th class="border-b border-slate-200 px-4 py-3 text-center whitespace-nowrap" rowspan="2">DTL</th>
                            <th class="border-b border-slate-200 px-4 py-3 text-left whitespace-nowrap" rowspan="2">TRANSAKSI</th>
                            <th class="border-b border-slate-200 px-4 py-3 text-center whitespace-nowrap" rowspan="2">STATUS</th>
                            <th class="border-b border-slate-200 px-4 py-3 text-center whitespace-nowrap" rowspan="2">NO AGENDA</th>
                            <th class="border-b border-slate-200 px-4 py-3 text-left whitespace-nowrap" rowspan="2">NAMA PELANGGAN</th>
                            <th class="border-b border-slate-200 px-4 py-3 text-left whitespace-nowrap" rowspan="2">ALAMAT</th>
                            <th class="border-b border-slate-200 px-4 py-2 text-center whitespace-nowrap" colspan="2">LAMA</th>
                            <th class="border-b border-slate-200 px-4 py-2 text-center whitespace-nowrap" colspan="2">BARU</th>
                            <th class="border-b border-slate-200 px-4 py-3 text-center whitespace-nowrap" rowspan="2">KET</th>
                        </tr>
                        <tr>
                            <th class="border-b border-slate-200 px-3 py-1.5 text-center text-[0.7rem]">TARIF</th>
                            <th class="border-b border-slate-200 px-3 py-1.5 text-center text-[0.7rem]">DAYA</th>
                            <th class="border-b border-slate-200 px-3 py-1.5 text-center text-[0.7rem]">TARIF</th>
                            <th class="border-b border-slate-200 px-3 py-1.5 text-center text-[0.7rem]">DAYA</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        {{-- Alternating row colors as shown in the image (white and extremely light blue) --}}
                        <tr class="bg-white hover:bg-slate-50 transition border-b border-slate-100">
                            <td colspan="12" class="text-center py-16 text-slate-400 text-xs">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="p-3 rounded-full bg-slate-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v16.5c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9zm3.75 11.625a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                        </svg>
                                    </div>
                                    <span>Tidak ada data ditemukan. Silakan sesuaikan filter Anda.</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Footer --}}
            <div class="px-5 py-3 bg-slate-50 flex items-center justify-between border-t border-slate-200">
                <span class="text-xs text-slate-500 font-medium font-mono">0 record(s)</span>
                <span class="text-[0.65rem] text-slate-400">FASTON360</span>
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
            // Adding a small animation effect
            el.style.opacity = 0;
            el.style.transform = 'translateY(10px)';
            setTimeout(() => {
                el.style.transition = 'all 0.3s ease-out';
                el.style.opacity = 1;
                el.style.transform = 'translateY(0)';
            }, 10);
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
@endsection
