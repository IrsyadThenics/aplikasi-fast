@extends('vendor_tiang.layout', ['title' => 'Agenda '.$pageTitle])

@section('content')
    <div class="mb-6 flex flex-wrap items-end justify-between gap-3">
        <div><p class="text-sm text-blue-100">UP3 BOJONEGORO</p><h1 class="text-2xl font-bold">Agenda {{ $pageTitle }}</h1><p class="mt-1 text-sm text-blue-100">Menampilkan berkas pekerjaan yang dikirim kepada vendor.</p></div>
        <a href="{{ route($routePrefix.'.history') }}" class="rounded-xl bg-white/15 px-4 py-2 text-sm font-bold hover:bg-white/25">Riwayat Pengiriman</a>
    </div>

    @forelse($agendas as $agenda)
        <article class="mb-5 rounded-2xl border border-white/20 bg-white/10 p-5 shadow-lg backdrop-blur">
            <div class="flex flex-wrap items-start justify-between gap-3 border-b border-white/15 pb-3">
                <div><h2 class="font-mono text-lg font-bold">{{ $agenda->no_agenda }}</h2><p class="text-sm text-white/75">{{ $agenda->nama ?? '-' }}</p><p class="text-xs text-amber-200">ULP: {{ $agenda->ulp ?? '-' }} · {{ $agenda->transaksi ?? '-' }}</p></div>
                <span class="rounded-full bg-sky-300/20 px-3 py-1 text-xs font-bold text-sky-100">{{ strtoupper($agenda->status ?? 'DAFTAR') }}</span>
            </div>
            <div class="mt-4 grid gap-4 lg:grid-cols-2">
                <section class="rounded-xl border border-white/15 bg-[#07165d]/35 p-4">
                    <h3 class="mb-3 text-sm font-bold">Berkas Pekerjaan</h3>
                    @forelse($agenda->berkas as $file)
                        <a target="_blank" href="{{ url('/storage/'.$file->path_file) }}" class="mb-2 flex items-center justify-between rounded-lg bg-white/10 px-3 py-2 text-sm hover:bg-white/20"><span class="truncate">📄 {{ $file->nama_file }}</span><span class="ml-3 text-sky-200">Pratinjau</span></a>
                    @empty <p class="text-sm italic text-white/50">Belum ada berkas pekerjaan.</p> @endforelse
                </section>
                <section class="rounded-xl border border-white/15 bg-[#07165d]/35 p-4">
                    <h3 class="mb-3 text-sm font-bold">Kirim Laporan</h3>
                    <form method="POST" action="{{ route($routePrefix.'.report.store') }}" enctype="multipart/form-data" class="space-y-3">
                        @csrf <input type="hidden" name="no_agenda" value="{{ $agenda->no_agenda }}">
                        <div class="grid gap-1 text-sm">@foreach(['Dokumen pekerjaan lengkap','Pekerjaan sesuai WO','Foto dokumentasi terlampir','Siap ditindaklanjuti Perencanaan'] as $option)<label class="flex items-center gap-2"><input type="checkbox" name="checklist[]" value="{{ $option }}"> {{ $option }}</label>@endforeach</div>
                        <textarea name="catatan" rows="2" class="w-full rounded-lg border border-white/20 bg-white/10 p-2 text-sm placeholder:text-white/50" placeholder="Catatan (opsional)"></textarea>
                        <input required multiple name="files[]" accept=".pdf,.jpg,.jpeg,.png" type="file" class="block w-full text-sm text-white file:mr-3 file:rounded-md file:border-0 file:bg-sky-500 file:px-3 file:py-2 file:font-bold file:text-white hover:file:bg-sky-400">
                        <button class="rounded-lg bg-sky-500 px-4 py-2 text-sm font-bold hover:bg-sky-400">Kirim Laporan</button>
                    </form>
                </section>
            </div>
        </article>
    @empty
        <div class="rounded-2xl border border-white/20 bg-white/10 p-12 text-center text-white/70">Belum ada agenda yang dikirim kepada vendor.</div>
    @endforelse
@endsection
