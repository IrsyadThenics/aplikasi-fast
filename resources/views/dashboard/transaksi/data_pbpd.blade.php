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
                <p class="text-blue-100 text-xs">Daftar Transaksi PB/PD UP3 Bojonegoro</p>
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
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold whitespace-nowrap" rowspan="2">ASAL ULP</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold whitespace-nowrap" rowspan="2">DTL</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold whitespace-nowrap" rowspan="2">FASTON<br>360&deg;</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold whitespace-nowrap" colspan="2">SYARAT</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold whitespace-nowrap" rowspan="2">TRANSAKSI</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold whitespace-nowrap" rowspan="2">STATUS</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold whitespace-nowrap" rowspan="2">NO AGENDA</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold whitespace-nowrap" rowspan="2">NAMA PELANGGAN</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold whitespace-nowrap" rowspan="2">ALAMAT</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold whitespace-nowrap" colspan="2">LAMA</th>
                            <th class="border border-blue-700 px-3 py-2 text-center font-semibold whitespace-nowrap" colspan="2">BARU</th>
                        </tr>
                        <tr class="bg-[#0D1B8C] text-white">
                            <th class="border px-3 py-1.5 text-center text-[0.7rem]">KTP</th>
                            <th class="border px-3 py-1.5 text-center text-[0.7rem]">IJIN</th>
                            <th class="border px-3 py-1.5 text-center text-[0.7rem]">TARIF</th>
                            <th class="border px-3 py-1.5 text-center text-[0.7rem]">DAYA</th>
                            <th class="border px-3 py-1.5 text-center text-[0.7rem]">TARIF</th>
                            <th class="border px-3 py-1.5 text-center text-[0.7rem]">DAYA</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr class="bg-white hover:bg-slate-50 border-b border-slate-100 transition text-xs text-slate-700">
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center">1.</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center"><button class="p-1 border border-slate-300 bg-white rounded shadow-sm hover:text-blue-600 text-slate-500 transition"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg></button></td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center"><input type="checkbox" class="w-3.5 h-3.5 text-blue-600 rounded border-gray-300"></td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center border-r border-slate-200"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mx-auto text-slate-600"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.536 4.536 0 01-6.42-6.421l10.899-10.899m-7.828 8.16l-4.52-4.52a2.14 2.14 0 013.027-3.028l4.52 4.52m3.028 3.028l4.52 4.52a2.14 2.14 0 01-3.027 3.028l-4.52-4.52" /></svg></td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mx-auto text-slate-600"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.536 4.536 0 01-6.42-6.421l10.899-10.899m-7.828 8.16l-4.52-4.52a2.14 2.14 0 013.027-3.028l4.52 4.52m3.028 3.028l4.52 4.52a2.14 2.14 0 01-3.027 3.028l-4.52-4.52" /></svg></td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-left whitespace-nowrap">PASANG BARU</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center">BAYAR</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center">518039912601284909</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-left whitespace-nowrap">SPPG TAMANPRIJEG KEC LARE</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-left min-w-[200px]">JL TAMAN PRIJEG TAMANPRIJEG, LAREN, KAB. LAMONGAN, JAWA TIMUR</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center"></td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center">0</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center font-semibold">B2T</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center font-semibold">13200</td>
                        </tr>
                        <tr class="bg-white hover:bg-slate-50 border-b border-slate-100 transition text-xs text-slate-700">
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center">2.</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center"><button class="p-1 border border-slate-300 bg-white rounded shadow-sm hover:text-blue-600 text-slate-500 transition"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg></button></td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center"><input type="checkbox" class="w-3.5 h-3.5 text-blue-600 rounded border-gray-300"></td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center border-r border-slate-200"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mx-auto text-slate-600"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.536 4.536 0 01-6.42-6.421l10.899-10.899m-7.828 8.16l-4.52-4.52a2.14 2.14 0 013.027-3.028l4.52 4.52m3.028 3.028l4.52 4.52a2.14 2.14 0 01-3.027 3.028l-4.52-4.52" /></svg></td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mx-auto text-slate-600"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.536 4.536 0 01-6.42-6.421l10.899-10.899m-7.828 8.16l-4.52-4.52a2.14 2.14 0 013.027-3.028l4.52 4.52m3.028 3.028l4.52 4.52a2.14 2.14 0 01-3.027 3.028l-4.52-4.52" /></svg></td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-left whitespace-nowrap">PERUBAHAN DAYA</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center">BAYAR</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center">518030522602037603</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-left whitespace-nowrap">BENGKEL BONANZA MOTOR</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-left min-w-[200px]">JL JEND SUDIRMAN No.0 RT.0 RW.0 LAMONGANSIDOKUMPUL, LAMONGAN, KAB. LAMONGAN, JAWA TIMUR</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center">R2T</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center">5500</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center font-semibold">B2T</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center font-semibold">16500</td>
                        </tr>
                        <tr class="bg-white hover:bg-slate-50 border-b border-slate-100 transition text-xs text-slate-700">
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center">3.</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center"><button class="p-1 border border-slate-300 bg-white rounded shadow-sm hover:text-blue-600 text-slate-500 transition"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg></button></td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center"><input type="checkbox" class="w-3.5 h-3.5 text-blue-600 rounded border-gray-300"></td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center border-r border-slate-200"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mx-auto text-slate-600"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.536 4.536 0 01-6.42-6.421l10.899-10.899m-7.828 8.16l-4.52-4.52a2.14 2.14 0 013.027-3.028l4.52 4.52m3.028 3.028l4.52 4.52a2.14 2.14 0 01-3.027 3.028l-4.52-4.52" /></svg></td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mx-auto text-slate-600"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.536 4.536 0 01-6.42-6.421l10.899-10.899m-7.828 8.16l-4.52-4.52a2.14 2.14 0 013.027-3.028l4.52 4.52m3.028 3.028l4.52 4.52a2.14 2.14 0 01-3.027 3.028l-4.52-4.52" /></svg></td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-left whitespace-nowrap">PASANG BARU</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center">BAYAR</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center">518039912603255573</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-left whitespace-nowrap">KDKMP TUMAPEL</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-left min-w-[200px]">DN KANDANAGAN DS TUMAPEL KEC DUDUKSAMPEYAN TUMAPEL, DUDUKSAMPEYAN, KAB. GRESIK, JAWA TIMUR</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center"></td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center">0</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center font-semibold">B2T</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center font-semibold">16500</td>
                        </tr>
                        <tr class="bg-white hover:bg-slate-50 border-b border-slate-100 transition text-xs text-slate-700">
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center">11.</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center"><button class="p-1 border border-slate-300 bg-white rounded shadow-sm hover:text-blue-600 text-slate-500 transition"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg></button></td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center"><input type="checkbox" class="w-3.5 h-3.5 text-blue-600 rounded border-gray-300"></td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center border-r border-slate-200"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mx-auto text-green-500"><path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 01.208 1.04l-9 13.5a.75.75 0 01-1.154.114l-6-6a.75.75 0 011.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 011.04-.208z" clip-rule="evenodd" /></svg></td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mx-auto text-green-500"><path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 01.208 1.04l-9 13.5a.75.75 0 01-1.154.114l-6-6a.75.75 0 011.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 011.04-.208z" clip-rule="evenodd" /></svg></td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-left whitespace-nowrap">PASANG BARU</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center">BAYAR</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center">518039912603285613</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-left whitespace-nowrap">KDKMP TLOGOAGUNG</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-left min-w-[200px]">DS TLOGOAGUNG TLOGOAGUNG, KEMBANGBAHU, KAB. LAMONGAN, JAWA TIMUR</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center"></td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center">0</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center font-semibold">B2T</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center font-semibold">16500</td>
                        </tr>
                        <tr class="bg-[#ffe4e1] hover:bg-[#ffcdd2] text-xs text-slate-700">
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center">13.</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center"><button class="p-1 border border-slate-300 bg-white rounded shadow-sm hover:text-blue-600 text-slate-500 transition"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg></button></td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center"><input type="checkbox" class="w-3.5 h-3.5 text-blue-600 rounded border-gray-300"></td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center border-r border-slate-200"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mx-auto text-slate-600"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.536 4.536 0 01-6.42-6.421l10.899-10.899m-7.828 8.16l-4.52-4.52a2.14 2.14 0 013.027-3.028l4.52 4.52m3.028 3.028l4.52 4.52a2.14 2.14 0 01-3.027 3.028l-4.52-4.52" /></svg></td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mx-auto text-slate-600"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.536 4.536 0 01-6.42-6.421l10.899-10.899m-7.828 8.16l-4.52-4.52a2.14 2.14 0 013.027-3.028l4.52 4.52m3.028 3.028l4.52 4.52a2.14 2.14 0 01-3.027 3.028l-4.52-4.52" /></svg></td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-left whitespace-nowrap">PERUBAHAN DAYA</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center">BAYAR</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center">518039922603308320</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-left whitespace-nowrap">SAMSI HIDAYATI</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-left min-w-[200px]">DS TUMENGGUNGAN NO.0 RT.0 RW.0 TUMENGGUNG TUMENGGUNGAN, LAMONGAN, KAB. LAMONGAN, JAWA TIMUR</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center">R1MT</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center">900</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center font-semibold">R1T</td>
                            <td class="border-b border-slate-100 px-3 py-1.5 text-center font-semibold">1300</td>
                        </tr>
                    
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
@endsection
