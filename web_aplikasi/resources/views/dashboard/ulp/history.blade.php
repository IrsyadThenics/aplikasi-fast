@extends('layouts.app')

@section('content')
@php
    $historyRoute = \Illuminate\Support\Str::beforeLast(request()->route()->getName(), '.');
    $historyList  = collect($history ?? []);
    $totalCount   = $historyList->count();
    $tanpaCount   = $historyList->where('dest', 'tanpa_perluasan')->count();
    $jtmCount     = $historyList->where('dest', 'jtm')->count();
    $jtrCount     = $historyList->where('dest', 'jtr')->count();
@endphp

<div class="p-5 md:p-7 space-y-6 bg-slate-50 min-h-screen">
    
    {{-- Header Title & Subtitle --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-[#0D1B8C]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l2.5 1.5M3.5 12a8.5 8.5 0 101.8-5.25M3.5 4.5v3.25h3.25" />
                </svg>
                @if ((Auth::user()->role ?? '') === 'perencanaan')
                    History Pengiriman ke Mobile Vendor
                @else
                    History Pengiriman Data PB/PD
                @endif
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                @if ((Auth::user()->role ?? '') === 'perencanaan')
                    Daftar riwayat pengiriman data dari Tanpa Perluasan, Perluasan JTM, dan Perluasan JTR ke Aplikasi Mobile Vendor.
                @else
                    Daftar riwayat data yang telah dikirim ke Tanpa Perluasan, Perluasan JTM, atau Perluasan JTR.
                @endif
            </p>
        </div>

        <button type="button" onclick="document.getElementById('createModal').showModal()" class="inline-flex items-center gap-2 bg-[#0D1B8C] hover:bg-blue-900 text-white px-4 py-2.5 rounded-xl font-bold text-sm shadow-sm transition active:scale-95">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Tambah Riwayat Manual
        </button>
    </div>

    {{-- Success Alert --}}
    @if (session('success'))
        <div class="rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-emerald-800 text-sm flex items-center gap-3 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Validation Error Alert --}}
    @if ($errors->any())
        <div class="rounded-xl bg-rose-50 border border-rose-200 px-4 py-3 text-rose-800 text-sm shadow-sm">
            <p class="font-bold">Terjadi kesalahan input:</p>
            <ul class="list-disc list-inside mt-1 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Riwayat</p>
                <p class="text-2xl font-bold text-slate-800">{{ $totalCount }}</p>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanpa Perluasan</p>
                <p class="text-2xl font-bold text-slate-800">{{ $tanpaCount }}</p>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Perluasan JTM</p>
                <p class="text-2xl font-bold text-slate-800">{{ $jtmCount }}</p>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Perluasan JTR</p>
                <p class="text-2xl font-bold text-slate-800">{{ $jtrCount }}</p>
            </div>
        </div>
    </div>

    {{-- Filter & Search Card --}}
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="relative w-full sm:w-80">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
            <input type="text" id="historySearch" onkeyup="filterHistoryTable()" placeholder="Cari Agenda, Nama, atau Alamat..." class="w-full pl-9 pr-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50 text-slate-700 transition" />
        </div>

        <div class="flex items-center gap-2 w-full sm:w-auto">
            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Tujuan:</label>
            <select id="destFilter" onchange="filterHistoryTable()" class="w-full sm:w-48 text-sm border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50 text-slate-700 transition">
                <option value="all">-- Semua Tujuan --</option>
                <option value="tanpa_perluasan">Tanpa Perluasan</option>
                <option value="jtm">Perluasan JTM</option>
                <option value="jtr">Perluasan JTR</option>
            </select>
        </div>
    </div>

    {{-- Table Area --}}
    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-xs text-left border-collapse" id="historyTable">
                <thead class="bg-[#0D1B8C] text-white font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 text-center border-r border-blue-800/50 w-12">No.</th>
                        <th class="px-4 py-3 border-r border-blue-800/50">No. Agenda</th>
                        <th class="px-4 py-3 border-r border-blue-800/50">Pelanggan</th>
                        <th class="px-4 py-3 border-r border-blue-800/50">Transaksi / Status</th>
                        <th class="px-4 py-3 border-r border-blue-800/50">Daya Baru</th>
                        <th class="px-4 py-3 border-r border-blue-800/50">Tujuan</th>
                        <th class="px-4 py-3 border-r border-blue-800/50">{{ (Auth::user()->role ?? '') === 'perencanaan' ? 'Vendor PT / Tgl Kirim' : 'Dikirim Pada' }}</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                @forelse ($history as $index => $item)
                    <tr class="hover:bg-slate-50/80 transition history-row" data-dest="{{ $item->dest }}" data-search="{{ strtolower(($item->no_agenda ?? '').' '.($item->nama ?? '').' '.($item->alamat ?? '')) }}">
                        <td class="px-4 py-3 text-center font-mono text-slate-400">{{ $index + 1 }}.</td>
                        <td class="px-4 py-3 font-semibold text-slate-900">{{ $item->no_agenda ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <p class="font-bold text-slate-800">{{ $item->nama ?? '-' }}</p>
                            <p class="text-[11px] text-slate-500 truncate max-w-xs" title="{{ $item->alamat }}">{{ $item->alamat ?? '-' }}</p>
                            <span class="inline-block mt-0.5 text-[10px] px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 font-medium">ULP: {{ $item->ulp ?? '-' }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-block px-2 py-0.5 rounded text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-100 mb-1">
                                {{ $item->transaksi ?? 'Pasang Baru' }}
                            </span>
                            <br>
                            <span class="text-[11px] text-slate-500 font-medium">Status: {{ $item->status ?? '-' }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-semibold text-blue-900">{{ $item->daya_baru ? number_format($item->daya_baru, 0, ',', '.') . ' VA' : '-' }}</p>
                            <p class="text-[10px] text-slate-400">Tarif: {{ $item->tarif_baru ?? '-' }}</p>
                        </td>
                        <td class="px-4 py-3">
                            @if($item->dest === 'tanpa_perluasan')
                                <span class="rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-1 text-[11px] font-bold inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Tanpa Perluasan
                                </span>
                            @elseif($item->dest === 'jtm')
                                <span class="rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200 px-2.5 py-1 text-[11px] font-bold inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                    Perluasan JTM
                                </span>
                            @else
                                <span class="rounded-full bg-amber-50 text-amber-700 border border-amber-200 px-2.5 py-1 text-[11px] font-bold inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Perluasan JTR
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-slate-500 text-[11px]">
                            @if ((Auth::user()->role ?? '') === 'perencanaan')
                                <p class="font-bold text-slate-800 mb-0.5">{{ $item->vendor_pt ?? '-' }} <span class="text-[10px] uppercase font-bold text-emerald-600">({{ $item->vendor_status_layak ?? 'layak' }})</span></p>
                                <p class="text-slate-500">{{ $item->vendor_sent_at ? \Carbon\Carbon::parse($item->vendor_sent_at)->format('d M Y H:i') : ($item->sentAt ? \Carbon\Carbon::parse($item->sentAt)->format('d M Y H:i') : '-') }}</p>
                            @else
                                <p class="text-slate-700 font-medium">{{ $item->sentAt ? \Carbon\Carbon::parse($item->sentAt)->format('d M Y H:i') : '-' }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-1.5">
                                {{-- Detail --}}
                                <button type="button" onclick="document.getElementById('detail-{{ $item->id }}').showModal()" class="rounded-lg bg-blue-50 hover:bg-blue-100 border border-blue-200 px-2.5 py-1.5 text-blue-700 text-xs font-bold transition flex items-center gap-1" title="Lihat Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Detail
                                </button>

                                {{-- Ubah --}}
                                <button type="button" onclick="document.getElementById('edit-{{ $item->id }}').showModal()" class="rounded-lg bg-amber-400 hover:bg-amber-500 px-2.5 py-1.5 text-xs font-bold text-slate-900 transition flex items-center gap-1" title="Ubah Data">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                    Ubah
                                </button>

                                {{-- Hapus --}}
                                <form method="POST" action="{{ route($historyRoute . '.history.destroy', $item) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus riwayat pengiriman No Agenda {{ $item->no_agenda }} ini?')">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="rounded-lg bg-rose-600 hover:bg-rose-700 px-2.5 py-1.5 text-xs font-bold text-white transition flex items-center gap-1" title="Hapus Data">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>

                            {{-- ===== MODAL DETAIL ===== --}}
                            <dialog id="detail-{{ $item->id }}" class="rounded-2xl p-0 w-full max-w-xl backdrop:bg-slate-900/50 shadow-2xl border border-slate-200">
                                <div class="bg-[#0D1B8C] text-white px-6 py-4 flex items-center justify-between rounded-t-2xl">
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                        </svg>
                                        <h2 class="font-bold text-base">Detail Riwayat Pengiriman</h2>
                                    </div>
                                    <button type="button" onclick="this.closest('dialog').close()" class="text-white/70 hover:text-white text-xl font-bold">✕</button>
                                </div>
                                <div class="p-6 space-y-4 text-xs text-slate-700 bg-white">
                                    <div class="grid grid-cols-2 gap-4 bg-slate-50 p-3.5 rounded-xl border border-slate-200">
                                        <div>
                                            <p class="text-slate-400 uppercase font-semibold text-[10px]">No Agenda</p>
                                            <p class="font-bold text-sm text-slate-900">{{ $item->no_agenda ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-slate-400 uppercase font-semibold text-[10px]">ULP</p>
                                            <p class="font-bold text-sm text-slate-900">{{ $item->ulp ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-slate-400 uppercase font-semibold text-[10px]">Nama Pelanggan</p>
                                            <p class="font-semibold text-slate-800">{{ $item->nama ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-slate-400 uppercase font-semibold text-[10px]">Tujuan Pengiriman</p>
                                            <p class="font-semibold text-blue-700 uppercase">{{ $item->dest === 'tanpa_perluasan' ? 'Tanpa Perluasan' : 'Perluasan ' . $item->dest }}</p>
                                        </div>
                                    </div>

                                    <div>
                                        <p class="text-slate-400 uppercase font-semibold text-[10px] mb-1">Alamat Pelanggan</p>
                                        <p class="bg-slate-50 p-2.5 rounded-lg border border-slate-200 font-medium">{{ $item->alamat ?? '-' }}</p>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="bg-slate-50 p-3 rounded-lg border border-slate-200">
                                            <p class="font-bold text-slate-800 text-[11px] mb-2 border-b pb-1">Spesifikasi Lama</p>
                                            <p><span class="text-slate-400">Tarif:</span> {{ $item->tarif_lama ?? '-' }}</p>
                                            <p><span class="text-slate-400">Daya:</span> {{ $item->daya_lama ? $item->daya_lama . ' VA' : '-' }}</p>
                                        </div>
                                        <div class="bg-blue-50/60 p-3 rounded-lg border border-blue-100">
                                            <p class="font-bold text-blue-900 text-[11px] mb-2 border-b border-blue-200 pb-1">Spesifikasi Baru</p>
                                            <p><span class="text-slate-500">Tarif:</span> <strong class="text-blue-900">{{ $item->tarif_baru ?? '-' }}</strong></p>
                                            <p><span class="text-slate-500">Daya:</span> <strong class="text-blue-900">{{ $item->daya_baru ? number_format($item->daya_baru, 0, ',', '.') . ' VA' : '-' }}</strong></p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-3 gap-3 bg-slate-50 p-3 rounded-xl border border-slate-200">
                                        <div>
                                            <p class="text-slate-400 uppercase text-[10px]">Jenis Transaksi</p>
                                            <p class="font-semibold text-slate-800">{{ $item->transaksi ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-slate-400 uppercase text-[10px]">Status Permohonan</p>
                                            <p class="font-semibold text-slate-800">{{ $item->status ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-slate-400 uppercase text-[10px]">Total Biaya / RAB</p>
                                            <p class="font-bold text-emerald-700">{{ $item->total_biaya ? 'Rp ' . number_format($item->total_biaya, 0, ',', '.') : '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-slate-50 px-6 py-3 border-t border-slate-200 flex justify-end rounded-b-2xl">
                                    <button type="button" onclick="this.closest('dialog').close()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 font-bold rounded-lg text-slate-700 text-xs">Tutup</button>
                                </div>
                            </dialog>

                            {{-- ===== MODAL EDIT ===== --}}
                            <dialog id="edit-{{ $item->id }}" class="rounded-2xl p-0 w-full max-w-xl backdrop:bg-slate-900/50 shadow-2xl border border-slate-200">
                                @if ((Auth::user()->role ?? '') === 'perencanaan')
                                    <form method="POST" action="/{{ \Illuminate\Support\Str::beforeLast(request()->route()->getName(), '.') }}/api/kirim-vendor" enctype="multipart/form-data" class="flex flex-col bg-white rounded-2xl">
                                        @csrf
                                        <input type="hidden" name="no_agenda" value="{{ $item->no_agenda }}">
                                        <input type="hidden" name="agendaKey" value="{{ $item->agendaKey ?? $item->no_agenda }}">

                                        <div class="bg-[#0D1B8C] text-white px-6 py-4 flex items-center justify-between rounded-t-2xl">
                                            <h2 class="font-bold text-base flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                </svg>
                                                Edit Pengiriman ke Vendor
                                            </h2>
                                            <button type="button" onclick="this.closest('dialog').close()" class="text-white/70 hover:text-white text-xl font-bold">✕</button>
                                        </div>

                                        <div class="p-6 space-y-4 text-xs text-slate-700 max-h-[75vh] overflow-y-auto font-mono">
                                            <div class="bg-blue-50/50 p-3 rounded-lg border border-blue-100 flex flex-col gap-1 mb-2">
                                                <p class="font-bold text-slate-800 text-sm">No. Agenda: {{ $item->no_agenda }}</p>
                                                <p class="text-slate-600 font-semibold">Pelanggan: {{ $item->nama }}</p>
                                                <p class="text-slate-500 text-[11px] truncate">Alamat: {{ $item->alamat }}</p>
                                            </div>

                                            <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 flex flex-col gap-4">
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    {{-- Pilih PT --}}
                                                    <div>
                                                        <label class="block text-[11px] font-bold text-slate-500 mb-1.5 uppercase tracking-wider">Tujuan Kirim ke PT</label>
                                                        <select name="vendor_pt" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white" required>
                                                            <option value="">-- Pilih PT --</option>
                                                            <option value="PT. ALPHA" @selected(($item->vendor_pt ?? '') === 'PT. ALPHA' || ($item->vendor_pt ?? '') === 'PT. A')>PT. ALPHA</option>
                                                            <option value="PT. BRAVO" @selected(($item->vendor_pt ?? '') === 'PT. BRAVO' || ($item->vendor_pt ?? '') === 'PT. B')>PT. BRAVO</option>
                                                            <option value="PT. CHARLIE" @selected(($item->vendor_pt ?? '') === 'PT. CHARLIE' || ($item->vendor_pt ?? '') === 'PT. C')>PT. CHARLIE</option>
                                                        </select>
                                                    </div>
                                                    {{-- Status Layak --}}
                                                    <div>
                                                        <label class="block text-[11px] font-bold text-slate-500 mb-1.5 uppercase tracking-wider">Status Kelayakan</label>
                                                        <div class="flex items-center gap-4 mt-1">
                                                            <label class="flex items-center gap-2 cursor-pointer group">
                                                                <input type="radio" name="vendor_status_layak" value="layak" @checked(($item->vendor_status_layak ?? 'layak') === 'layak') class="w-4 h-4 text-blue-600" required>
                                                                <span class="text-xs font-bold text-emerald-600 group-hover:text-emerald-700">LAYAK</span>
                                                            </label>
                                                            <label class="flex items-center gap-2 cursor-pointer group">
                                                                <input type="radio" name="vendor_status_layak" value="tidak_layak" @checked(($item->vendor_status_layak ?? '') === 'tidak_layak') class="w-4 h-4 text-red-600">
                                                                <span class="text-xs font-bold text-red-600 group-hover:text-red-700">TIDAK LAYAK</span>
                                                            </label>
                                                        </div>
                                                    </div> 
                                                </div>

                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    {{-- Upload Berkas Kelayakan --}}
                                                    <div>
                                                        <label class="block text-[11px] font-bold text-slate-500 mb-1.5 uppercase tracking-wider">Upload Berkas Kelayakan</label>
                                                        <input type="file" name="file_kelayakan" accept=".pdf,.jpg,.jpeg,.png" class="text-xs border border-slate-300 rounded-lg p-1.5 w-full bg-white">
                                                    </div>
                                                    {{-- Upload Berkas WO Tiang --}}
                                                    <div>
                                                        <label class="block text-[11px] font-bold text-slate-500 mb-1.5 uppercase tracking-wider">Upload Berkas WO Tiang</label>
                                                        <input type="file" name="file_wo_tiang" accept=".pdf,.jpg,.jpeg,.png" class="text-xs border border-slate-300 rounded-lg p-1.5 w-full bg-white">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="bg-slate-50 px-6 py-3 border-t border-slate-200 flex justify-end gap-2 rounded-b-2xl">
                                            <button type="button" onclick="this.closest('dialog').close()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 font-bold rounded-lg text-slate-700 text-xs">Batal</button>
                                            <button type="submit" class="px-5 py-2 bg-[#0D1B8C] hover:bg-blue-900 font-bold rounded-lg text-white text-xs shadow-sm flex items-center gap-1.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                                                Kirim ke Vendor
                                            </button>
                                        </div>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route($historyRoute . '.history.update', $item) }}" class="flex flex-col bg-white rounded-2xl">
                                        @csrf 
                                        @method('PUT')
                                        <div class="bg-[#0D1B8C] text-white px-6 py-4 flex items-center justify-between rounded-t-2xl">
                                            <h2 class="font-bold text-base flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                </svg>
                                                Ubah Riwayat Pengiriman
                                            </h2>
                                            <button type="button" onclick="this.closest('dialog').close()" class="text-white/70 hover:text-white text-xl font-bold">✕</button>
                                        </div>

                                        <div class="p-6 space-y-4 text-xs text-slate-700 max-h-[75vh] overflow-y-auto">
                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block font-semibold text-slate-600 mb-1 uppercase text-[10px]">No Agenda</label>
                                                    <input name="no_agenda" value="{{ $item->no_agenda }}" class="w-full rounded-lg border-slate-300 px-3 py-2 text-xs focus:ring-2 focus:ring-blue-500" required />
                                                </div>
                                                <div>
                                                    <label class="block font-semibold text-slate-600 mb-1 uppercase text-[10px]">Tujuan Pengiriman</label>
                                                    <select name="dest" class="w-full rounded-lg border-slate-300 px-3 py-2 text-xs focus:ring-2 focus:ring-blue-500" required>
                                                        <option value="tanpa_perluasan" @selected($item->dest === 'tanpa_perluasan')>Tanpa Perluasan</option>
                                                        <option value="jtm" @selected($item->dest === 'jtm')>Perluasan JTM</option>
                                                        <option value="jtr" @selected($item->dest === 'jtr')>Perluasan JTR</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block font-semibold text-slate-600 mb-1 uppercase text-[10px]">Nama Pelanggan</label>
                                                <input name="nama" value="{{ $item->nama }}" class="w-full rounded-lg border-slate-300 px-3 py-2 text-xs focus:ring-2 focus:ring-blue-500" required />
                                            </div>

                                            <div>
                                                <label class="block font-semibold text-slate-600 mb-1 uppercase text-[10px]">Alamat</label>
                                                <textarea name="alamat" rows="2" class="w-full rounded-lg border-slate-300 px-3 py-2 text-xs focus:ring-2 focus:ring-blue-500">{{ $item->alamat }}</textarea>
                                            </div>

                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block font-semibold text-slate-600 mb-1 uppercase text-[10px]">Jenis Transaksi</label>
                                                    <input name="transaksi" value="{{ $item->transaksi }}" class="w-full rounded-lg border-slate-300 px-3 py-2 text-xs focus:ring-2 focus:ring-blue-500" />
                                                </div>
                                                <div>
                                                    <label class="block font-semibold text-slate-600 mb-1 uppercase text-[10px]">Status Permohonan</label>
                                                    <input name="status" value="{{ $item->status }}" class="w-full rounded-lg border-slate-300 px-3 py-2 text-xs focus:ring-2 focus:ring-blue-500" />
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-2 gap-3 bg-slate-50 p-3 rounded-xl border border-slate-200">
                                                <div>
                                                    <label class="block font-semibold text-slate-600 mb-1 uppercase text-[10px]">Tarif Lama</label>
                                                    <input name="tarif_lama" value="{{ $item->tarif_lama }}" class="w-full rounded-lg border-slate-300 px-3 py-2 text-xs" />
                                                </div>
                                                <div>
                                                    <label class="block font-semibold text-slate-600 mb-1 uppercase text-[10px]">Daya Lama (VA)</label>
                                                    <input type="number" name="daya_lama" value="{{ $item->daya_lama }}" class="w-full rounded-lg border-slate-300 px-3 py-2 text-xs" />
                                                </div>
                                                <div>
                                                    <label class="block font-semibold text-slate-600 mb-1 uppercase text-[10px]">Tarif Baru</label>
                                                    <input name="tarif_baru" value="{{ $item->tarif_baru }}" class="w-full rounded-lg border-slate-300 px-3 py-2 text-xs" />
                                                </div>
                                                <div>
                                                    <label class="block font-semibold text-slate-600 mb-1 uppercase text-[10px]">Daya Baru (VA)</label>
                                                    <input type="number" name="daya_baru" value="{{ $item->daya_baru }}" class="w-full rounded-lg border-slate-300 px-3 py-2 text-xs" />
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block font-semibold text-slate-600 mb-1 uppercase text-[10px]">Total Biaya / RAB (Rp)</label>
                                                <input type="number" name="total_biaya" value="{{ $item->total_biaya }}" class="w-full rounded-lg border-slate-300 px-3 py-2 text-xs focus:ring-2 focus:ring-blue-500" />
                                            </div>
                                        </div>

                                        <div class="bg-slate-50 px-6 py-3 border-t border-slate-200 flex justify-end gap-2 rounded-b-2xl">
                                            <button type="button" onclick="this.closest('dialog').close()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 font-bold rounded-lg text-slate-700 text-xs">Batal</button>
                                            <button type="submit" class="px-5 py-2 bg-[#0D1B8C] hover:bg-blue-900 font-bold rounded-lg text-white text-xs shadow-sm">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                @endif
                            </dialog>
                        </td>
                    </tr>
                @empty
                    <tr id="emptyRow">
                        <td colspan="8" class="px-4 py-12 text-center text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.5h3m-6 0a3 3 0 013-3h3a3 3 0 013 3m-9 0h9" />
                            </svg>
                            Belum ada riwayat pengiriman data.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ===== MODAL CREATE / TAMBAH RIWAYAT MANUAL ===== --}}
<dialog id="createModal" class="rounded-2xl p-0 w-full max-w-xl backdrop:bg-slate-900/50 shadow-2xl border border-slate-200">
    <form method="POST" action="{{ route($historyRoute . '.history.store') }}" class="flex flex-col bg-white rounded-2xl">
        @csrf 
        <div class="bg-[#0D1B8C] text-white px-6 py-4 flex items-center justify-between rounded-t-2xl">
            <h2 class="font-bold text-base flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Riwayat Pengiriman Baru
            </h2>
            <button type="button" onclick="this.closest('dialog').close()" class="text-white/70 hover:text-white text-xl font-bold">✕</button>
        </div>

        <div class="p-6 space-y-4 text-xs text-slate-700 max-h-[75vh] overflow-y-auto">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold text-slate-600 mb-1 uppercase text-[10px]">No Agenda <span class="text-rose-500">*</span></label>
                    <input name="no_agenda" placeholder="Contoh: 543210987" class="w-full rounded-lg border-slate-300 px-3 py-2 text-xs focus:ring-2 focus:ring-blue-500" required />
                </div>
                <div>
                    <label class="block font-semibold text-slate-600 mb-1 uppercase text-[10px]">Tujuan Pengiriman <span class="text-rose-500">*</span></label>
                    <select name="dest" class="w-full rounded-lg border-slate-300 px-3 py-2 text-xs focus:ring-2 focus:ring-blue-500" required>
                        <option value="tanpa_perluasan">Tanpa Perluasan</option>
                        <option value="jtm">Perluasan JTM</option>
                        <option value="jtr">Perluasan JTR</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-semibold text-slate-600 mb-1 uppercase text-[10px]">Nama Pelanggan <span class="text-rose-500">*</span></label>
                <input name="nama" placeholder="Nama Pelanggan" class="w-full rounded-lg border-slate-300 px-3 py-2 text-xs focus:ring-2 focus:ring-blue-500" required />
            </div>

            <div>
                <label class="block font-semibold text-slate-600 mb-1 uppercase text-[10px]">Alamat</label>
                <textarea name="alamat" rows="2" placeholder="Alamat Pelanggan" class="w-full rounded-lg border-slate-300 px-3 py-2 text-xs focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold text-slate-600 mb-1 uppercase text-[10px]">Jenis Transaksi</label>
                    <input name="transaksi" value="Pasang Baru" placeholder="Pasang Baru / Perubahan Daya" class="w-full rounded-lg border-slate-300 px-3 py-2 text-xs focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block font-semibold text-slate-600 mb-1 uppercase text-[10px]">Status Permohonan</label>
                    <input name="status" value="Mohon" placeholder="Mohon / Bayar / Cetak PK" class="w-full rounded-lg border-slate-300 px-3 py-2 text-xs focus:ring-2 focus:ring-blue-500" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 bg-slate-50 p-3 rounded-xl border border-slate-200">
                <div>
                    <label class="block font-semibold text-slate-600 mb-1 uppercase text-[10px]">Tarif Lama</label>
                    <input name="tarif_lama" placeholder="R1M/900VA" class="w-full rounded-lg border-slate-300 px-3 py-2 text-xs" />
                </div>
                <div>
                    <label class="block font-semibold text-slate-600 mb-1 uppercase text-[10px]">Daya Lama (VA)</label>
                    <input type="number" name="daya_lama" value="0" class="w-full rounded-lg border-slate-300 px-3 py-2 text-xs" />
                </div>
                <div>
                    <label class="block font-semibold text-slate-600 mb-1 uppercase text-[10px]">Tarif Baru</label>
                    <input name="tarif_baru" placeholder="R1T/1300VA" class="w-full rounded-lg border-slate-300 px-3 py-2 text-xs" />
                </div>
                <div>
                    <label class="block font-semibold text-slate-600 mb-1 uppercase text-[10px]">Daya Baru (VA)</label>
                    <input type="number" name="daya_baru" value="1300" class="w-full rounded-lg border-slate-300 px-3 py-2 text-xs" />
                </div>
            </div>

            <div>
                <label class="block font-semibold text-slate-600 mb-1 uppercase text-[10px]">Total Biaya / RAB (Rp)</label>
                <input type="number" name="total_biaya" value="0" class="w-full rounded-lg border-slate-300 px-3 py-2 text-xs focus:ring-2 focus:ring-blue-500" />
            </div>
        </div>

        <div class="bg-slate-50 px-6 py-3 border-t border-slate-200 flex justify-end gap-2 rounded-b-2xl">
            <button type="button" onclick="this.closest('dialog').close()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 font-bold rounded-lg text-slate-700 text-xs">Batal</button>
            <button type="submit" class="px-5 py-2 bg-[#0D1B8C] hover:bg-blue-900 font-bold rounded-lg text-white text-xs shadow-sm">Simpan Riwayat</button>
        </div>
    </form>
</dialog>

<script>
function filterHistoryTable() {
    var searchVal = document.getElementById('historySearch').value.toLowerCase().trim();
    var destVal   = document.getElementById('destFilter').value;
    var rows      = document.querySelectorAll('.history-row');

    rows.forEach(function(row) {
        var rowDest   = row.getAttribute('data-dest');
        var rowSearch = row.getAttribute('data-search');

        var matchDest   = (destVal === 'all' || rowDest === destVal);
        var matchSearch = (!searchVal || rowSearch.indexOf(searchVal) !== -1);

        if (matchDest && matchSearch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>
@endsection
