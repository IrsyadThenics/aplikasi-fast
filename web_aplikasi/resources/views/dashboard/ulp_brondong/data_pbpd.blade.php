@extends('layouts.app')

@section('content')
<div class="h-full flex flex-col bg-slate-50 p-5">

    {{-- ===== FILTER CARD ===== --}}
    <div class="bg-white rounded-2xl shadow-md border border-slate-200 overflow-hidden flex-shrink-0">

        {{-- Card Header --}}
        <div class="bg-gradient-to-r from-[#0D1B8C] to-[#0D1B8C] px-6 py-3 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white/80" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v16.5c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9zm3.75 11.625a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
            <div>
                <p class="text-white font-bold text-sm tracking-wide">DAFTAR TRANSAKSI</p>
                <p class="text-blue-100 text-xs">Daftar Transaksi PB/PD ULP Brondong</p>
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
                <div class="flex items-center gap-4">
                    <button onclick="handleKirimBulk()" class="bg-[#FACC15] hover:bg-yellow-400 text-slate-900 px-4 py-1.5 rounded font-semibold text-xs transition shadow-sm flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        Kirim Terpilih (<span id="countSelectedItems">0</span>)
                    </button>
                    <button onclick="tutupTabel()" class="text-blue-200 hover:text-white text-xs transition flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Tutup
                </button>
                </div>
            </div>

            {{-- Scrollable Table --}}
            <div class="overflow-auto flex-1">
                <table class="w-full text-xs border-collapse">
                    <thead class="sticky top-0 z-10 font-bold">
                        <tr class="bg-[#0D1B8C] text-white">
                            <th class="border border-blue-700 px-3 py-2 text-center" rowspan="2">NO.</th>
                            <th class="border border-blue-700 px-2 py-2 text-center" rowspan="2" title="Checklist pengiriman">
                            <div class="flex items-center justify-center flex-col gap-1">
                                <span>âœ“</span>
                                <input type="checkbox" id="checkAllKirim" class="cursor-pointer" onclick="toggleCheckAll()" />
                            </div>
                        </th>
                            <th class="border border-blue-700 px-3 py-2 text-center" rowspan="2">DTL</th>
                            <th class="border border-blue-700 px-3 py-2 text-center" colspan="2">SYARAT</th>
                            <th class="border border-blue-700 px-3 py-2 text-center" rowspan="2">TANGGAL<br>MOHON</th>
                            <th class="border border-blue-700 px-3 py-2 text-center" rowspan="2">NO AGENDA</th>
                            <th class="border border-blue-700 px-3 py-2 text-center" rowspan="2">NAMA<br>PELANGGAN</th>
                            <th class="border border-blue-700 px-3 py-2 text-center" rowspan="2">ALAMAT</th>
                            <th class="border border-blue-700 px-3 py-2 text-center" rowspan="2">JENIS<br>TRANSAKSI</th>
                            <th class="border border-blue-700 px-3 py-2 text-center" colspan="2">LAMA</th>
                            <th class="border border-blue-700 px-3 py-2 text-center" colspan="2">BARU</th>
                            <th class="border border-blue-700 px-3 py-2 text-center" rowspan="2">TOTAL<br>BIAYA</th>
                            <th class="border border-blue-700 px-3 py-2 text-center" rowspan="2">TANGGAL<br>BAYAR</th>
                            <th class="border border-blue-700 px-3 py-2 text-center" rowspan="2">DURASI<br>HARI KERJA</th>
                            
                        </tr>
                        <tr class="bg-[#0D1B8C] text-white">
                            <th class="border border-blue-600 px-3 py-1.5 text-center font-medium">BERKAS PENDUKUNG</th>
                            <th class="border border-blue-600 px-3 py-1.5 text-center font-medium">IJIN TANAM TIANG</th>
                            <th class="border border-blue-600 px-3 py-1.5 text-center font-medium">TARIF</th>
                            <th class="border border-blue-600 px-3 py-1.5 text-center font-medium">DAYA</th>
                            <th class="border border-blue-600 px-3 py-1.5 text-center font-medium">TARIF</th>
                            <th class="border border-blue-600 px-3 py-1.5 text-center font-medium">DAYA</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @forelse($data ?? [] as $index => $item)
                        @php $agendaKey = $item->no_agenda ?? 'default'; @endphp
                        <tr class="bg-white hover:bg-slate-50 transition border-b border-slate-100 text-slate-700" id="row-{{ $agendaKey }}">
                            <td class="border border-slate-200 px-3 py-2 text-center text-slate-500 text-[11px] font-mono">{{ $index + 1 }}.</td>
                            {{-- CHECKLIST --}}
                            <td class="border border-slate-200 px-2 py-2 text-center">
                                <input type="checkbox"
                                    id="chk-send-{{ $agendaKey }}"
                                    data-item="{{ htmlspecialchars(json_encode($item)) }}"
                                    onclick="toggleSendChecklist(event, '{{ $agendaKey }}', {{ json_encode($item) }})"
                                    disabled
                                    title="Lengkapi berkas pendukung & ijin tanam tiang terlebih dahulu"
                                    class="send-chk w-4 h-4 cursor-not-allowed opacity-40 mx-auto" />
                            </td>
                            <td class="border border-slate-200 px-3 py-2 text-center">
                                <button onclick="openDetailModal({{ json_encode($item) }})" class="text-slate-500 hover:text-blue-600 transition" title="Lihat Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                </button>
                            </td>
                            {{-- BERKAS PENDUKUNG --}}
                            <td class="border border-slate-200 px-2 py-2 text-center">
                                <div class="flex flex-col items-center gap-1">
                                    <button onclick="openSyaratModal({{ json_encode($item) }}, 'ktp')" class="text-slate-400 hover:text-emerald-600 transition" title="Upload Berkas Pendukung">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                        </svg>
                                    </button>
                                    <span id="chk-ktp-{{ $agendaKey }}" class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-slate-100 text-slate-300 transition-all duration-300" title="Belum ada berkas pendukung">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                </div>
                            </td>
                            {{-- IJIN TANAM TIANG --}}
                            <td class="border border-slate-200 px-2 py-2 text-center">
                                <div class="flex flex-col items-center gap-1">
                                    <button onclick="openSyaratModal({{ json_encode($item) }}, 'ijin')" class="text-slate-400 hover:text-amber-600 transition" title="Upload Ijin Tanam Tiang">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </button>
                                    <span id="chk-itt-{{ $agendaKey }}" class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-slate-100 text-slate-300 transition-all duration-300" title="Belum ada ijin tanam tiang">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                </div>
                            </td>
                            <td class="border border-slate-200 px-2 py-2 text-center text-xs text-slate-500 whitespace-nowrap">{{ $item->tanggal_ulp ? \Carbon\Carbon::parse($item->tanggal_ulp)->format('d/m/Y') : '-' }}</td>
                            <td class="border border-slate-200 px-3 py-2 text-center font-medium text-slate-700">{{ $item->no_agenda ?? '-' }}</td>
                            <td class="border border-slate-200 px-3 py-2 text-left">{{ $item->nama ?? '-' }}</td>
                            <td class="border border-slate-200 px-3 py-2 text-left max-w-xs truncate" title="{{ $item->alamat }}">{{ $item->alamat }}</td>
                            <td class="border border-slate-200 px-3 py-2 text-left whitespace-nowrap">
                                @if(strtolower($item->transaksi) === 'pasang baru')
                                    <span class="px-2 py-1 rounded text-[10px] font-semibold bg-indigo-100 text-indigo-700">Pasang Baru</span>
                                @elseif(str_contains(strtolower($item->transaksi), 'perubahan') || str_contains(strtolower($item->transaksi), 'daya'))
                                    <span class="px-2 py-1 rounded text-[10px] font-semibold bg-purple-100 text-purple-700">{{ $item->transaksi }}</span>
                                @elseif(str_contains(strtolower($item->transaksi), 'balik'))
                                    <span class="px-2 py-1 rounded text-[10px] font-semibold bg-orange-100 text-orange-700">{{ $item->transaksi }}</span>
                                @else
                                    <span class="px-2 py-1 rounded text-[10px] font-semibold bg-slate-100 text-slate-700">{{ $item->transaksi }}</span>
                                @endif
                            </td>
                            <td class="border border-slate-200 px-3 py-2 text-center">{{ $item->tarif_lama ?? '-' }}</td>
                            <td class="border border-slate-200 px-3 py-2 text-center">{{ $item->daya_lama ? $item->daya_lama . ' VA' : '-' }}</td>
                            <td class="border border-slate-200 px-3 py-2 text-center font-semibold text-blue-900">{{ $item->tarif_baru ?? '-' }}</td>
                            <td class="border border-slate-200 px-3 py-2 text-center font-semibold text-blue-900">{{ $item->daya_baru ? $item->daya_baru . ' VA' : '-' }}</td>
                            <td class="border border-slate-200 px-3 py-2 text-center">{{ $item->total_biaya ? 'Rp '.number_format($item->total_biaya, 0, ',', '.') : '-' }}</td>
                            <td class="border border-slate-200 px-2 py-2 text-center text-xs whitespace-nowrap">{{ $item->tanggal_bayar ? \Carbon\Carbon::parse($item->tanggal_bayar)->format('d/m/Y') : '-' }}</td>
                            <td class="border border-slate-200 px-3 py-2 text-center">{{ $item->durasi_hari_kerja ?? '-' }}</td>
                            
                        </tr>
                        @empty
                        <tr id="emptyRow">
                            <td colspan="15" class="text-center py-12 text-slate-400 italic text-xs">
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
                <span class="text-xs text-slate-400 font-mono">Records {{ count($data ?? []) ? '1 to ' . count($data) : '0 to 0' }} of {{ count($data ?? []) }}</span>
            </div>

        </div>
    </div>

</div>

<!-- Destination Picker Modal -->
<div id="destModal" class="fixed inset-0 z-[200] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg relative overflow-hidden border border-slate-200">
        <div class="bg-gradient-to-r from-[#0D1B8C] to-[#2B73FE] px-6 py-4 flex items-center justify-between">
            <div>
                <p class="text-white font-bold text-sm tracking-wide">Pilih Tujuan Pengiriman</p>
                <p class="text-blue-200 text-xs mt-0.5">Data akan dikirim ke halaman yang dipilih</p>
            </div>
            <button onclick="closeDestModal()" class="text-white/60 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-6">
            <p class="text-slate-600 text-xs mb-5 text-center font-sans">Pilih tujuan pengiriman data berkas yang sudah dilengkapi:</p>
            <div class="grid grid-cols-3 gap-3">
                <button onclick="confirmKirim('jtm')" class="group flex flex-col items-center gap-3 p-4 rounded-xl border-2 border-slate-200 hover:border-blue-500 hover:bg-blue-50 transition-all duration-200 active:scale-95">
                    <div class="w-10 h-10 rounded-full bg-blue-100 group-hover:bg-blue-200 flex items-center justify-center transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" /></svg>
                    </div>
                    <div class="text-center">
                        <p class="font-bold text-slate-700 text-xs group-hover:text-blue-700 transition">Perluasan JTM</p>
                        <p class="text-[9px] text-slate-400 mt-0.5">Jaringan Tegangan Menengah</p>
                    </div>
                </button>
                <button onclick="confirmKirim('jtr')" class="group flex flex-col items-center gap-3 p-4 rounded-xl border-2 border-slate-200 hover:border-indigo-500 hover:bg-indigo-50 transition-all duration-200 active:scale-95">
                    <div class="w-10 h-10 rounded-full bg-indigo-100 group-hover:bg-indigo-200 flex items-center justify-center transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" /></svg>
                    </div>
                    <div class="text-center">
                        <p class="font-bold text-slate-700 text-xs group-hover:text-indigo-700 transition">Perluasan JTR</p>
                        <p class="text-[9px] text-slate-400 mt-0.5">Jaringan Tegangan Rendah</p>
                    </div>
                </button>
                <button onclick="confirmKirim('tanpa_perluasan')" class="group flex flex-col items-center gap-3 p-4 rounded-xl border-2 border-slate-200 hover:border-emerald-500 hover:bg-emerald-50 transition-all duration-200 active:scale-95">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 group-hover:bg-emerald-200 flex items-center justify-center transition font-sans">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" /></svg>
                    </div>
                    <div class="text-center">
                        <p class="font-bold text-slate-700 text-xs group-hover:text-emerald-700 transition">Tanpa Perluasan</p>
                        <p class="text-[9px] text-slate-400 mt-0.5">Langsung Sambung / Tanpa Jaringan</p>
                    </div>
                </button>
            </div>
            <button onclick="closeDestModal()" class="mt-4 w-full py-2 text-xs text-slate-500 hover:text-slate-700 transition font-sans">Batal</button>
        </div>
    </div>
</div>

<script>
    function reIndexTable() {
        var tbody = document.getElementById('tableBody');
        if (!tbody) return;
        var rows = tbody.querySelectorAll('tr:not(#emptyRow)');
        var visibleIndex = 1;
        var visibleCount = 0;
        rows.forEach(function(row) {
            if (row.style.display !== 'none') {
                var firstTd = row.querySelector('td');
                if (firstTd) {
                    firstTd.textContent = visibleIndex + '.';
                    visibleIndex++;
                }
                visibleCount++;
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
        
        rows.forEach(function(row) {
            var agendaKey = row.id.replace('row-', '');
            if (_checkedRows[agendaKey]) {
                row.style.display = 'none';
                return;
            }

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
            } else {
                row.style.display = 'none';
            }
        });
        
        reIndexTable();
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
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl relative overflow-hidden flex flex-col border border-slate-200">
        <!-- Title Bar -->
        <div class="bg-gradient-to-r from-[#0D1B8C] to-[#2B73FE] px-5 py-3 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span class="text-white font-bold text-sm tracking-wide">Detail Data Pelanggan</span>
            </div>
            <button onclick="closeDetailModal()" class="text-white/70 hover:text-white p-1 rounded-full hover:bg-white/20 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        
        <!-- Content -->
        <div class="p-6 text-[13.5px] text-slate-800 font-mono flex flex-col gap-4">
            
            <div class="grid grid-cols-[150px_1fr] gap-2 items-center">
                <div class="font-bold text-slate-700 uppercase tracking-wider">UP3</div>
                <div class="uppercase font-bold text-black">: <span id="mdl-up3">BOJONEGORO</span></div>
                <div class="font-bold text-slate-700 uppercase tracking-wider">ULP</div>
                <div class="uppercase font-bold text-black">: <span id="mdl-ulp">LAMONGAN</span></div>
            </div>
            
            <hr class="border-slate-200">
            
            <div class="grid grid-cols-[150px_1fr] gap-2 items-center">
                <div class="font-bold text-slate-700 uppercase tracking-wider">Transaksi</div>
                <div class="uppercase font-bold text-blue-700">: <span id="mdl-transaksi" class="bg-blue-50 px-2 py-1 rounded"></span></div>
                <div class="font-bold text-slate-700 uppercase tracking-wider">Status Permohonan</div>
                <div class="uppercase font-bold text-amber-700">: <span id="mdl-status" class="bg-amber-50 px-2 py-1 rounded"></span></div>
            </div>

            <hr class="border-slate-200">
            
            <div class="grid grid-cols-[150px_1fr] gap-2 items-center">
                <div class="font-bold text-slate-700 uppercase tracking-wider">No. Agenda</div>
                <div class="font-bold">: <span id="mdl-agenda"></span></div>
                <div class="font-bold text-slate-700 uppercase tracking-wider">ID Pelanggan</div>
                <div class="font-bold">: <span id="mdl-idpel"></span></div>
                <div class="font-bold text-slate-700 uppercase tracking-wider">Nama</div>
                <div class="uppercase font-bold">: <span id="mdl-nama"></span></div>
                <div class="font-bold text-slate-700 uppercase tracking-wider">Alamat</div>
                <div class="uppercase font-bold">: <span id="mdl-alamat"></span></div>
                <div class="font-bold text-slate-700 uppercase tracking-wider">Tarif / Daya</div>
                <div class="uppercase font-bold flex items-center gap-2">
                    : <span class="bg-emerald-50 text-emerald-700 px-2 py-1 rounded">BARU : <span id="mdl-tbaru"></span> / <span id="mdl-dbaru"></span></span>
                    <span class="text-slate-300">|</span> 
                    <span class="bg-slate-50 text-slate-600 px-2 py-1 rounded">LAMA : <span id="mdl-tlama"></span> / <span id="mdl-dlama"></span></span>
                </div>
            </div>
            
            <hr class="border-slate-200">
            
            <div class="grid grid-cols-[150px_1fr] gap-2 items-center">
                <div class="font-bold text-slate-700 uppercase tracking-wider">RAB (BP)</div>
                <div class="font-bold text-black flex items-center gap-2">
                    : Rp. <span id="mdl-rab-text">0</span>,-
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
                            <th class="border border-slate-200 py-1.5 uppercase font-bold text-[12.5px]">Bayar</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <tr>
                            <td class="border border-slate-200 py-2 font-bold" id="mdl-tgl-mohon">2026-03-30</td>
                            <td class="border border-slate-200 py-2 font-bold" id="mdl-tgl-bayar">2026-03-30</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-2 flex justify-end">
                <button onclick="closeDetailModal()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2 rounded-lg font-bold text-sm transition focus:outline-none focus:ring-2 focus:ring-slate-300">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
function openDetailModal(item) {
    document.getElementById('mdl-up3').textContent = 'BOJONEGORO';
    document.getElementById('mdl-ulp').textContent = item.ulp || '-';
    document.getElementById('mdl-transaksi').textContent = item.transaksi || '-';
    document.getElementById('mdl-status').textContent = item.status || '-';
    document.getElementById('mdl-agenda').textContent = item.no_agenda || '-';
    document.getElementById('mdl-idpel').textContent = item.no_agenda || '-';
    document.getElementById('mdl-nama').textContent = 'Pelanggan ' + (item.no_agenda || '');
    document.getElementById('mdl-alamat').textContent = item.alamat || '-';
    document.getElementById('mdl-tbaru').textContent = item.tarif_baru || '-';
    document.getElementById('mdl-dbaru').textContent = item.daya_baru || '0';
    document.getElementById('mdl-tlama').textContent = item.tarif_lama || '-';
    document.getElementById('mdl-dlama').textContent = item.daya_lama || '0';
    
    var agendaKey = item.no_agenda || 'default';
    var fmt = new Intl.NumberFormat('id-ID');
    var rabVal = item.total_biaya || 0;
    if (typeof uploadedDocs !== 'undefined' && uploadedDocs[agendaKey] && uploadedDocs[agendaKey].rab) {
        rabVal = uploadedDocs[agendaKey].rab;
    }
    document.getElementById('mdl-rab-text').textContent = fmt.format(rabVal);
    
    if(item.created_at) {
        let d = new Date(item.created_at);
        let ds = d.toISOString().split('T')[0];
        document.getElementById('mdl-tgl-mohon').textContent = ds;
        document.getElementById('mdl-tgl-bayar').textContent = ds;
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

<!-- Syarat Modal (Detail Data Pelanggan + Persyaratan FASTON 360°) -->
<div id="syaratModal" class="fixed inset-0 z-[110] hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4 overflow-y-auto">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl relative overflow-hidden flex flex-col border border-slate-200">
        <!-- Title Bar -->
        <div class="bg-gradient-to-r from-[#0D1B8C] to-[#2B73FE] px-5 py-3 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span class="text-white font-bold text-sm tracking-wide">Persyaratan Dokumen & RAB</span>
            </div>
            <button onclick="closeSyaratModal()" class="text-white/70 hover:text-white p-1 rounded-full hover:bg-white/20 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Content -->
        <div class="p-6 text-[13.5px] text-slate-800 font-mono flex flex-col gap-4">

            <!-- Info Grid -->
            <div class="grid grid-cols-[160px_1fr] gap-2 items-center">
                <div class="font-bold text-slate-700 uppercase tracking-wider">UP3</div>
                <div class="uppercase font-bold text-black">: <span id="syr-up3">BOJONEGORO</span></div>
                <div class="font-bold text-slate-700 uppercase tracking-wider">ULP</div>
                <div class="uppercase font-bold text-black">: <span id="syr-ulp">-</span></div>
            </div>

            <hr class="border-slate-200">

            <div class="grid grid-cols-[160px_1fr] gap-2 items-center">
                <div class="font-bold text-slate-700 uppercase tracking-wider">TRANSAKSI</div>
                <div class="uppercase font-bold text-blue-700">: <span id="syr-transaksi" class="bg-blue-50 px-2 py-1 rounded"></span></div>
                <div class="font-bold text-slate-700 uppercase tracking-wider">STATUS PERMOHONAN</div>
                <div class="uppercase font-bold text-amber-700">: <span id="syr-status" class="bg-amber-50 px-2 py-1 rounded"></span></div>
            </div>

            <hr class="border-slate-200">

            <div class="grid grid-cols-[160px_1fr] gap-2 items-center">
                <div class="font-bold text-slate-700 uppercase tracking-wider">NO. AGENDA</div>
                <div class="font-bold">: <span id="syr-agenda">-</span></div>
                <div class="font-bold text-slate-700 uppercase tracking-wider">ID PELANGGAN</div>
                <div class="font-bold">: <span id="syr-idpel">-</span></div>
                <div class="font-bold text-slate-700 uppercase tracking-wider">NAMA</div>
                <div class="uppercase font-bold">: <span id="syr-nama">-</span></div>
                <div class="font-bold text-slate-700 uppercase tracking-wider">ALAMAT</div>
                <div class="uppercase font-bold">: <span id="syr-alamat">-</span></div>
                <div class="font-bold text-slate-700 uppercase tracking-wider">TARIF / DAYA</div>
                <div class="uppercase font-bold flex items-center gap-2">
                    : <span class="bg-emerald-50 text-emerald-700 px-2 py-1 rounded">BARU : <span id="syr-tarif">-</span> / <span id="syr-daya">-</span></span>
                </div>
            </div>

            <hr class="border-slate-200">

            <div class="grid grid-cols-[160px_1fr] gap-2 items-center bg-blue-50/50 p-3 rounded-lg border border-blue-100">
                <div class="font-bold text-[#0D1B8C] uppercase tracking-wider flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    RAB (BP)
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-slate-600 font-bold text-sm">Rp.</span>
                    <input type="number" id="syr-rab-input" class="border border-slate-300 rounded-lg px-3 py-1.5 text-sm w-40 focus:outline-none focus:ring-2 focus:ring-[#2B73FE] transition bg-white" placeholder="0" />
                    <button onclick="saveRabField()" class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-1.5 rounded-lg shadow-sm text-[13.5px] font-bold transition flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        Simpan
                    </button>
                    <span id="syr-rab-indicator" class="text-emerald-600 text-[12.5px] font-bold opacity-0 transition-opacity flex items-center gap-1 ml-1">
                        Tersimpan!
                    </span>
                </div>
            </div>

            <!-- Persyaratan Table -->
            <table class="w-full border-collapse border border-slate-200 rounded-lg overflow-hidden shadow-sm mt-2">
                <thead>
                    <tr class="bg-gradient-to-r from-red-100 to-red-50 text-red-800">
                        <th id="syr-header-title" colspan="2" class="border border-red-100 py-2.5 uppercase font-bold tracking-wide text-[13.5px]">
                            PERSYARATAN PROSES FASTON 360°
                        </th>
                    </tr>
                    <tr class="bg-slate-50 text-slate-600">
                        <th id="syr-col-ktp-head" class="border border-slate-200 py-2 px-3 uppercase font-bold w-1/2 text-[12.5px]">UPLOAD KTP / BERKAS PENDUKUNG</th>
                        <th id="syr-col-itt-head" class="border border-slate-200 py-2 px-3 uppercase font-bold w-1/2 text-[12.5px]">UPLOAD IJIN TANAM TIANG</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <tr>
                        <!-- KTP Upload -->
                        <td id="syr-col-ktp-body" class="border border-slate-200 px-5 py-4 align-top">
                            <div class="flex flex-col items-center gap-3">
                                <div class="flex items-center justify-center gap-2 w-full">
                                    <label class="flex items-center gap-1 cursor-pointer flex-1 group">
                                        <input type="file" id="syr-file-ktp" accept=".pdf,.jpg,.jpeg,.png" class="hidden" onchange="handleFileChange('ktp')">
                                        <div class="px-3 py-1.5 bg-slate-50 border border-dashed border-slate-300 rounded-lg text-[12.5px] font-sans group-hover:bg-slate-100 group-hover:border-[#2B73FE] transition cursor-pointer text-slate-700 font-bold text-center w-full">
                                            <span class="text-[#2B73FE]">Browse...</span>
                                            <span id="syr-ktp-filename" class="text-[10px] text-slate-700 italic block mt-0.5 truncate max-w-[150px] mx-auto">No file chosen</span>
                                        </div>
                                    </label>
                                    <button onclick="uploadSyarat('ktp')" class="px-4 py-2 h-full bg-[#2B73FE] text-white rounded-lg text-[12.5px] font-sans hover:bg-blue-700 transition font-bold shadow-sm flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                                        Upload
                                    </button>
                                </div>
                                <div id="syr-preview-ktp" class="w-full mt-2 flex flex-col gap-2 items-center bg-slate-50 rounded-lg p-2 min-h-[60px] justify-center border border-slate-100">
                                    <span class="text-[10px] text-slate-400 italic">Belum ada file diupload</span>
                                </div>
                            </div>
                        </td>
                        <!-- IJIN Upload -->
                        <td id="syr-col-itt-body" class="border border-slate-200 px-5 py-4 align-top">
                            <div class="flex flex-col items-center gap-3">
                                <div class="flex items-center justify-center gap-2 w-full">
                                    <label class="flex items-center gap-1 cursor-pointer flex-1 group">
                                        <input type="file" id="syr-file-itt" accept=".pdf,.jpg,.jpeg,.png" class="hidden" onchange="handleFileChange('itt')">
                                        <div class="px-3 py-1.5 bg-slate-50 border border-dashed border-slate-300 rounded-lg text-[12.5px] font-sans group-hover:bg-slate-100 group-hover:border-[#2B73FE] transition cursor-pointer text-slate-700 font-bold text-center w-full">
                                            <span class="text-[#2B73FE]">Browse...</span>
                                            <span id="syr-itt-filename" class="text-[10px] text-slate-700 italic block mt-0.5 truncate max-w-[150px] mx-auto">No file chosen</span>
                                        </div>
                                    </label>
                                    <button onclick="uploadSyarat('itt')" class="px-4 py-2 h-full bg-[#2B73FE] text-white rounded-lg text-[12.5px] font-sans hover:bg-blue-700 transition font-bold shadow-sm flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                                        Upload
                                    </button>
                                </div>
                                <div id="syr-preview-itt" class="w-full mt-2 flex flex-col gap-2 items-center bg-slate-50 rounded-lg p-2 min-h-[60px] justify-center border border-slate-100">
                                    <span class="text-[10px] text-slate-400 italic">Belum ada file diupload</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="bg-slate-50">
                        <td class="border border-slate-200 py-2">
                            <span class="inline-flex items-center justify-center bg-blue-100 text-blue-700 rounded-full w-6 h-6 text-[13.5px] font-bold" id="syr-col-ktp-count">0</span>
                        </td>
                        <td class="border border-slate-200 py-2">
                            <span class="inline-flex items-center justify-center bg-blue-100 text-blue-700 rounded-full w-6 h-6 text-[13.5px] font-bold" id="syr-col-itt-count">0</span>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Close -->
            <div class="mt-2 flex justify-end">
                <button onclick="closeSyaratModal()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2 rounded-lg font-bold text-sm transition focus:outline-none focus:ring-2 focus:ring-slate-300">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- File Viewer Modal (Internal Preview) -->
<div id="viewFileModal" class="fixed inset-0 z-[150] hidden items-center justify-center bg-black/75 backdrop-blur-sm p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl overflow-hidden flex flex-col max-h-[85vh] relative">
        <div class="bg-slate-800 text-white px-5 py-3 flex items-center justify-between flex-shrink-0">
            <span id="viewFileTitle" class="font-bold text-sm truncate font-sans">Preview Dokumen</span>
            <button onclick="closeViewFileModal()" class="text-slate-300 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-4 overflow-auto flex-1 flex items-center justify-center bg-slate-900/5 min-h-[350px]" id="viewFileContent">
        </div>
    </div>
</div>

<script>
var currentItem = null;
var uploadedDocs = {};

// ===== IndexedDB Setup =====
var DB_NAME  = 'FastOnDocs';
var DB_STORE = 'uploadedDocs';
var SENT_STORE = 'sentItems';
var _db = null;

function openDB() {
    return new Promise(function(resolve, reject) {
        if (_db) { resolve(_db); return; }
        var req = indexedDB.open(DB_NAME, 2);
        req.onupgradeneeded = function(e) {
            var db = e.target.result;
            if (!db.objectStoreNames.contains(DB_STORE))   db.createObjectStore(DB_STORE,   { keyPath: 'agendaKey' });
            if (!db.objectStoreNames.contains(SENT_STORE)) db.createObjectStore(SENT_STORE, { keyPath: 'agendaKey' });
        };
        req.onsuccess = function(e) { _db = e.target.result; resolve(_db); };
        req.onerror   = function(e) { reject(e); };
    });
}

function loadAllDocs() {
    return openDB().then(function(db) {
        return new Promise(function(resolve, reject) {
            var tx    = db.transaction(DB_STORE, 'readonly');
            var store = tx.objectStore(DB_STORE);
            var req   = store.getAll();
            req.onsuccess = function() {
                var result = {};
                (req.result || []).forEach(function(row) {
                    result[row.agendaKey] = { ktp: row.ktp || [], itt: row.itt || [] };
                });
                resolve(result);
            };
            req.onerror = function(e) { reject(e); };
        });
    });
}

function saveAgendaDocs(agendaKey) {
    return openDB().then(function(db) {
        return new Promise(function(resolve, reject) {
            var tx    = db.transaction(DB_STORE, 'readwrite');
            var store = tx.objectStore(DB_STORE);
            var req   = store.put({
                agendaKey : agendaKey,
                ktp       : (uploadedDocs[agendaKey] && uploadedDocs[agendaKey].ktp) ? uploadedDocs[agendaKey].ktp : [],
                itt       : (uploadedDocs[agendaKey] && uploadedDocs[agendaKey].itt) ? uploadedDocs[agendaKey].itt : []
            });
            req.onsuccess = function() { resolve(); };
            req.onerror   = function(e) { reject(e); };
        });
    });
}

function saveSentItem(record) {
    return openDB().then(function(db) {
        return new Promise(function(resolve, reject) {
            var tx    = db.transaction(SENT_STORE, 'readwrite');
            var store = tx.objectStore(SENT_STORE);
            var req   = store.put(record);
            req.onsuccess = function() { resolve(); };
            req.onerror   = function(e) { reject(e); };
        });
    });
}

// On page load: load docs + mark sent rows
document.addEventListener('DOMContentLoaded', function() {
    loadAllDocs().then(function(docs) {
        uploadedDocs = docs;
        Object.keys(uploadedDocs).forEach(function(key) {
            updateRowChecklist(key);
        });
    }).catch(function(e) { console.warn('IndexedDB load error:', e); });

    openDB().then(function(db) {
        var tx    = db.transaction(SENT_STORE, 'readonly');
        var store = tx.objectStore(SENT_STORE);
        var req   = store.getAll();
        req.onsuccess = function() {
            (req.result || []).forEach(function(r) {
                _checkedRows[r.agendaKey] = true;
                var row  = document.getElementById('row-' + r.agendaKey);
                if (row) { row.style.display = 'none'; }
            });
            reIndexTable();
        };
    }).catch(function() {});
});

function saveUploadedDocs() {} // legacy stub

function ensureAgendaStorage(agendaKey) {
    if (!uploadedDocs[agendaKey]) uploadedDocs[agendaKey] = { ktp: [], itt: [] };
}

// ===== Syarat Modal =====
function openSyaratModal(item, tipe) {
    currentItem = item;
    var agendaKey = item.no_agenda || 'default';
    if (!uploadedDocs[agendaKey]) uploadedDocs[agendaKey] = { ktp: [], itt: [] };

    document.getElementById('syr-file-ktp').value = '';
    document.getElementById('syr-file-itt').value = '';
    document.getElementById('syr-ktp-filename').textContent = 'No file chosen';
    document.getElementById('syr-itt-filename').textContent = 'No file chosen';

    document.getElementById('syr-ulp').textContent       = item.ulp || '-';
    document.getElementById('syr-transaksi').textContent  = item.transaksi || '-';
    document.getElementById('syr-status').textContent    = item.status || '-';
    document.getElementById('syr-agenda').textContent    = item.no_agenda || '-';
    document.getElementById('syr-idpel').textContent     = item.id_pelanggan || item.no_agenda || '-';
    document.getElementById('syr-nama').textContent      = item.nama || ('PELANGGAN ' + (item.no_agenda || ''));
    document.getElementById('syr-alamat').textContent    = item.alamat || '-';
    document.getElementById('syr-tarif').textContent     = item.tarif_baru || '-';
    document.getElementById('syr-daya').textContent      = item.daya_baru || '-';
    var rabVal = item.total_biaya || 0;
    if (uploadedDocs[agendaKey] && uploadedDocs[agendaKey].rab) {
        rabVal = uploadedDocs[agendaKey].rab;
    }
    document.getElementById('syr-rab-input').value = rabVal;

    var ktpHead  = document.getElementById('syr-col-ktp-head');
    var ktpBody  = document.getElementById('syr-col-ktp-body');
    var ktpCount = document.getElementById('syr-col-ktp-count');
    var ittHead  = document.getElementById('syr-col-itt-head');
    var ittBody  = document.getElementById('syr-col-itt-body');
    var ittCount = document.getElementById('syr-col-itt-count');
    var headerTitle = document.getElementById('syr-header-title');

    if (tipe === 'ktp' || tipe === 'berkas') {
        if (ktpHead) ktpHead.style.display = ''; if (ktpBody) ktpBody.style.display = ''; if (ktpCount) ktpCount.style.display = '';
        if (ittHead) ittHead.style.display = 'none'; if (ittBody) ittBody.style.display = 'none'; if (ittCount) ittCount.style.display = 'none';
        if (headerTitle) headerTitle.setAttribute('colspan','1');
    } else if (tipe === 'ijin' || tipe === 'itt') {
        if (ktpHead) ktpHead.style.display = 'none'; if (ktpBody) ktpBody.style.display = 'none'; if (ktpCount) ktpCount.style.display = 'none';
        if (ittHead) ittHead.style.display = ''; if (ittBody) ittBody.style.display = ''; if (ittCount) ittCount.style.display = '';
        if (headerTitle) headerTitle.setAttribute('colspan','1');
    } else {
        if (ktpHead) ktpHead.style.display = ''; if (ktpBody) ktpBody.style.display = ''; if (ktpCount) ktpCount.style.display = '';
        if (ittHead) ittHead.style.display = ''; if (ittBody) ittBody.style.display = ''; if (ittCount) ittCount.style.display = '';
        if (headerTitle) headerTitle.setAttribute('colspan','2');
    }

    renderPreviews(agendaKey);
    updateRowChecklist(agendaKey);
    var m = document.getElementById('syaratModal');
    if (m) { m.classList.remove('hidden'); m.classList.add('flex'); }
}

function closeSyaratModal() {
    var m = document.getElementById('syaratModal');
    if (m) { m.classList.add('hidden'); m.classList.remove('flex'); }
}

function handleFileChange(tipe) {
    var input = document.getElementById('syr-file-' + tipe);
    var label = document.getElementById('syr-' + tipe + '-filename');
    if (input.files && input.files[0]) {
        label.textContent = input.files[0].name;
        label.classList.remove('text-slate-400');
        label.classList.add('text-slate-700');
    }
}

function uploadSyarat(tipe) {
    var input = document.getElementById('syr-file-' + tipe);
    if (!input || !input.files || !input.files[0]) { alert('Pilih file terlebih dahulu.'); return; }
    var file = input.files[0];
    if (file.size > 10 * 1024 * 1024) { alert('Ukuran file terlalu besar (maks 10 MB).'); return; }
    var agendaKey = (currentItem && currentItem.no_agenda) ? currentItem.no_agenda : 'default';
    if (!uploadedDocs[agendaKey]) uploadedDocs[agendaKey] = { ktp: [], itt: [] };
    var reader = new FileReader();
    reader.onload = function(e) {
        uploadedDocs[agendaKey][tipe].push({ name: file.name, size: (file.size/1024).toFixed(1)+' KB', type: file.type, url: e.target.result });
        input.value = '';
        var fn = document.getElementById('syr-' + tipe + '-filename');
        if (fn) fn.textContent = 'No file chosen';
        renderPreviews(agendaKey);
        updateRowChecklist(agendaKey);
        saveAgendaDocs(agendaKey).catch(function(err) { console.error('Gagal simpan:', err); });
    };
    reader.onerror = function() { alert('Gagal membaca file.'); };
    reader.readAsDataURL(file);
}

function removeSyarat(tipe, index) {
    var agendaKey = (currentItem && currentItem.no_agenda) ? currentItem.no_agenda : 'default';
    if (uploadedDocs[agendaKey] && uploadedDocs[agendaKey][tipe]) {
        uploadedDocs[agendaKey][tipe].splice(index, 1);
        renderPreviews(agendaKey);
        updateRowChecklist(agendaKey);
        saveAgendaDocs(agendaKey).catch(function(err) { console.error('Gagal hapus:', err); });
    }
}

function previewSyaratFile(tipe, idx) {
    var agendaKey = (currentItem && currentItem.no_agenda) ? currentItem.no_agenda : 'default';
    if (uploadedDocs[agendaKey] && uploadedDocs[agendaKey][tipe] && uploadedDocs[agendaKey][tipe][idx]) {
        var doc = uploadedDocs[agendaKey][tipe][idx];
        document.getElementById('viewFileTitle').textContent = doc.name;
        var container = document.getElementById('viewFileContent');
        if (doc.type.startsWith('image/')) {
            container.innerHTML = '<img src="' + doc.url + '" class="max-w-full max-h-[70vh] object-contain rounded shadow" />';
        } else if (doc.type === 'application/pdf') {
            container.innerHTML = '<iframe src="' + doc.url + '" class="w-full h-[70vh] rounded border-0"></iframe>';
        } else {
            container.innerHTML = '<div class="text-center py-8 font-sans"><p class="text-slate-600 font-bold mb-3">' + doc.name + '</p><a href="' + doc.url + '" download="' + doc.name + '" class="px-4 py-2 bg-blue-600 text-white rounded text-[13.5px] font-bold hover:bg-blue-700 transition">Download File</a></div>';
        }
        var m = document.getElementById('viewFileModal');
        if (m) { m.classList.remove('hidden'); m.classList.add('flex'); }
    }
}

function closeViewFileModal() {
    var m = document.getElementById('viewFileModal');
    if (m) { m.classList.add('hidden'); m.classList.remove('flex'); }
}

function renderPreviews(agendaKey) {
    ['ktp','itt'].forEach(function(tipe) {
        var container = document.getElementById('syr-preview-' + tipe);
        var countEl   = document.getElementById('syr-col-' + tipe + '-count');
        if (!container) return;
        var files = (uploadedDocs[agendaKey] && uploadedDocs[agendaKey][tipe]) ? uploadedDocs[agendaKey][tipe] : [];
        if (countEl) countEl.textContent = files.length;
        if (files.length === 0) { container.innerHTML = '<span class="text-[10px] text-slate-400 italic">Belum ada file diupload</span>'; return; }
        var html = '';
        files.forEach(function(f, idx) {
            var isImage = f.type.startsWith('image/');
            html += '<div class="flex items-center justify-between gap-2 p-1.5 bg-slate-50 border border-slate-200 rounded-lg w-full max-w-sm font-sans shadow-sm">';
            html += '  <div class="flex items-center gap-2 overflow-hidden">';
            html += isImage ? '    <img src="' + f.url + '" class="w-8 h-8 object-cover rounded border border-slate-300 flex-shrink-0" />' : '    <div class="w-8 h-8 bg-red-100 text-red-600 rounded flex items-center justify-center font-bold text-[10px] flex-shrink-0">PDF</div>';
            html += '    <div class="flex flex-col text-left overflow-hidden"><span class="text-[12.5px] font-bold text-slate-700 truncate">' + f.name + '</span><span class="text-[9px] text-slate-400">' + f.size + '</span></div>';
            html += '  </div><div class="flex items-center gap-1 flex-shrink-0">';
            html += '    <button onclick="previewSyaratFile(\'' + tipe + '\',' + idx + ')" class="p-1 text-blue-600 hover:text-blue-800 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></button>';
            html += '    <button onclick="removeSyarat(\'' + tipe + '\',' + idx + ')" class="p-1 text-rose-500 hover:text-rose-700 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>';
            html += '  </div></div>';
        });
        container.innerHTML = html;
    });
}

// ===== Checklist & Row indicators =====
function updateRowChecklist(agendaKey) {
    var docs  = uploadedDocs[agendaKey] || { ktp: [], itt: [] };
    var ktpOk = docs.ktp && docs.ktp.length > 0;
    var ittOk = docs.itt && docs.itt.length > 0;
    var allOk = ktpOk && ittOk;

    var chkKtp = document.getElementById('chk-ktp-' + agendaKey);
    if (chkKtp) {
        chkKtp.className = ktpOk
            ? 'inline-flex items-center justify-center w-4 h-4 rounded-full bg-emerald-500 text-white transition-all duration-300'
            : 'inline-flex items-center justify-center w-4 h-4 rounded-full bg-slate-100 text-slate-300 transition-all duration-300';
        chkKtp.title = ktpOk ? 'Berkas pendukung sudah dilengkapi' : 'Belum ada berkas pendukung';
    }

    var chkItt = document.getElementById('chk-itt-' + agendaKey);
    if (chkItt) {
        chkItt.className = ittOk
            ? 'inline-flex items-center justify-center w-4 h-4 rounded-full bg-emerald-500 text-white transition-all duration-300'
            : 'inline-flex items-center justify-center w-4 h-4 rounded-full bg-slate-100 text-slate-300 transition-all duration-300';
        chkItt.title = ittOk ? 'Ijin tanam tiang sudah dilengkapi' : 'Belum ada ijin tanam tiang';
    }

    var chkSend = document.getElementById('chk-send-' + agendaKey);
    if (chkSend) {
        if (allOk) {
            chkSend.disabled = false;
            chkSend.classList.remove('cursor-not-allowed','opacity-40');
            chkSend.classList.add('cursor-pointer');
            chkSend.title = 'Pilih untuk mengirim data ini';
        } else {
            chkSend.disabled = true;
            chkSend.classList.add('cursor-not-allowed','opacity-40');
            chkSend.classList.remove('cursor-pointer');
            var miss = []; if (!ktpOk) miss.push('berkas pendukung'); if (!ittOk) miss.push('ijin tanam tiang');
            chkSend.title = 'Lengkapi ' + miss.join(' & ') + ' terlebih dahulu';
            chkSend.checked = false;
            if (typeof _selectedItems !== 'undefined' && _selectedItems[agendaKey]) {
                delete _selectedItems[agendaKey];
                updateSelectedCount();
            }
        }
    }
}

// ===== Checklist send & Destination Modal =====
var _checkedRows      = {};
var _selectedItems    = {};

function toggleSendChecklist(e, agendaKey, item) {
    if (e.target.checked) {
        _selectedItems[agendaKey] = item;
    } else {
        delete _selectedItems[agendaKey];
    }
    updateSelectedCount();
    checkIfAllSelected();
}

function toggleCheckAll() {
    var checkAll = document.getElementById('checkAllKirim');
    var isChecked = checkAll.checked;
    var tableBody = document.getElementById('tableBody');
    if (!tableBody) return;
    
    var checkboxes = tableBody.querySelectorAll('.send-chk:not(:disabled)');
    checkboxes.forEach(function(chk) {
        var row = chk.closest('tr');
        if (row && row.style.display !== 'none') {
            chk.checked = isChecked;
        }
    });

    _selectedItems = {};
    checkboxes.forEach(function(chk) {
        if (chk.checked) {
             var dataStr = chk.getAttribute('data-item');
             if(dataStr) {
                 var agendaKey = chk.id.replace('chk-send-', '');
                 _selectedItems[agendaKey] = JSON.parse(dataStr);
             }
        }
    });
    updateSelectedCount();
}

function updateSelectedCount() {
    var count = Object.keys(_selectedItems).length;
    var el = document.getElementById('countSelectedItems');
    if(el) el.innerText = count;
}

function checkIfAllSelected() {
    var tableBody = document.getElementById('tableBody');
    if (!tableBody) return;
    var checkboxes = tableBody.querySelectorAll('.send-chk:not(:disabled)');
    var visibleCheckboxes = Array.from(checkboxes).filter(c => {
        var row = c.closest('tr');
        return row && row.style.display !== 'none';
    });
    var checkAll = document.getElementById('checkAllKirim');
    if (checkAll) {
        if (visibleCheckboxes.length === 0) checkAll.checked = false;
        else checkAll.checked = visibleCheckboxes.every(c => c.checked);
    }
}

function handleKirimBulk() {
    var keys = Object.keys(_selectedItems);
    if (keys.length === 0) {
        alert('Tidak ada data yang dipilih untuk dikirim.');
        return;
    }
    openDestModal();
}

function openDestModal() {
    var m = document.getElementById('destModal');
    if (m) { m.classList.remove('hidden'); m.classList.add('flex'); }
}

function closeDestModal() {
    var m = document.getElementById('destModal');
    if (m) { m.classList.add('hidden'); m.classList.remove('flex'); }
}

function confirmKirim(dest) {
    var keys = Object.keys(_selectedItems);
    if (keys.length === 0) { closeDestModal(); return; }
    
    var promises = keys.map(function(agendaKey) {
        var item = _selectedItems[agendaKey];
        var docs = uploadedDocs[agendaKey] || { ktp: [], itt: [] };
        var record = {
            agendaKey   : agendaKey, dest        : dest,
            no_agenda   : item.no_agenda   || agendaKey,
            nama        : item.nama        || '-',
            alamat      : item.alamat      || '-',
            transaksi   : item.transaksi   || '-',
            status      : item.status      || '-',
            tarif_lama  : item.tarif_lama  || '-',
            daya_lama   : item.daya_lama   || '-',
            tarif_baru  : item.tarif_baru  || '-',
            daya_baru   : item.daya_baru   || '-',
            total_biaya : item.total_biaya || 0,
            ulp         : item.ulp || '-',
            sentAt      : new Date().toISOString(),
            ktpCount    : docs.ktp ? docs.ktp.length : 0,
            ittCount    : docs.itt ? docs.itt.length : 0
        };
        return saveSentItem(record).then(function() {
            _checkedRows[agendaKey] = true;
            var row = document.getElementById('row-' + agendaKey);
            if (row) { row.style.display = 'none'; }
        });
    });

    Promise.all(promises).then(function() {
        reIndexTable();
        closeDestModal();
        alert(keys.length + ' Data berhasil dikirim ke ' + (dest === 'tanpa_perluasan' ? 'Tanpa Perluasan' : 'Perluasan ' + dest.toUpperCase()) + '!');
        _selectedItems = {};
        updateSelectedCount();
        checkIfAllSelected();
        var checkAll = document.getElementById('checkAllKirim');
        if (checkAll) { checkAll.checked = false; }
    }).catch(function(e) {
        console.error('Gagal kirim bulk:', e);
        alert('Gagal mengirim beberapa data. Silakan coba lagi.');
        closeDestModal();
    });
}

function saveRabField() {
    if (!currentItem) return;
    var agendaKey = currentItem.no_agenda || 'default';
    var rabVal = document.getElementById('syr-rab-input').value;
    if (rabVal === '') rabVal = 0;
    
    ensureAgendaStorage(agendaKey);
    uploadedDocs[agendaKey].rab = rabVal;
    
    saveAgendaDocs(agendaKey).then(function() {
        var indicator = document.getElementById('syr-rab-indicator');
        if (indicator) {
            indicator.style.opacity = '1';
            setTimeout(function() {
                indicator.style.opacity = '0';
            }, 2000);
        }
    }).catch(function(e) {
        alert('Gagal menyimpan RAB: ' + e);
    });
}
</script>

@endsection
