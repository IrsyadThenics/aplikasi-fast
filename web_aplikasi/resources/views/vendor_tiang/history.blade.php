@extends('vendor_tiang.layout', ['title' => 'Riwayat '.$pageTitle])

@section('content')
    <div class="mb-6 flex items-end justify-between"><div><p class="text-sm text-blue-100">{{ strtoupper($pageTitle) }}</p><h1 class="text-2xl font-bold">Riwayat Pengiriman</h1></div><a href="{{ route($routePrefix.'.dashboard') }}" class="rounded-xl bg-white/15 px-4 py-2 text-sm font-bold hover:bg-white/25">Kembali ke Agenda</a></div>
    @forelse($reports as $report)
        <article class="mb-4 rounded-2xl border border-white/20 bg-white/10 p-5 backdrop-blur">
            <div class="flex flex-wrap items-start justify-between gap-3"><div><h2 class="font-bold">Agenda {{ $report->no_agenda }}</h2><p class="text-xs text-white/55">{{ $report->created_at?->format('d M Y H:i') }} WIB</p></div><form method="POST" action="{{ route($routePrefix.'.history.destroy', $report) }}" onsubmit="return confirm('Hapus laporan ini beserta seluruh berkasnya?')">@csrf @method('DELETE')<button class="rounded-lg bg-red-500/80 px-3 py-2 text-xs font-bold hover:bg-red-500">Hapus</button></form></div>
            <form method="POST" action="{{ route($routePrefix.'.history.update', $report) }}" enctype="multipart/form-data" class="mt-4 grid gap-3 lg:grid-cols-2">@csrf @method('PUT')
                <div class="space-y-2 text-sm"><p class="font-bold">Checklist</p>@foreach($checklistOptions as $option)<label class="flex items-center gap-2"><input type="checkbox" name="checklist[]" value="{{ $option }}" @checked(in_array($option, $report->checklist ?? []))> {{ $option }}</label>@endforeach<textarea name="catatan" rows="3" class="mt-2 w-full rounded-lg border border-white/20 bg-white/10 p-2 placeholder:text-white/50" placeholder="Catatan">{{ $report->catatan }}</textarea></div>
                <div><p class="mb-2 text-sm font-bold">Berkas yang dikirim</p>@foreach($report->files as $file)<a target="_blank" href="{{ url('/storage/'.$file->path_file) }}" class="mb-1 block truncate text-sm text-sky-200 hover:underline">📎 {{ $file->nama_file }}</a>@endforeach<input multiple name="files[]" accept=".pdf,.jpg,.jpeg,.png" type="file" class="mt-3 block w-full text-sm text-white file:mr-3 file:rounded-md file:border-0 file:bg-white/20 file:px-3 file:py-2 file:text-white"><button class="mt-3 rounded-lg bg-sky-500 px-4 py-2 text-sm font-bold hover:bg-sky-400">Simpan Perubahan</button></div>
            </form>
        </article>
    @empty <div class="rounded-2xl border border-white/20 bg-white/10 p-12 text-center text-white/70">Belum ada riwayat pengiriman.</div> @endforelse
@endsection
