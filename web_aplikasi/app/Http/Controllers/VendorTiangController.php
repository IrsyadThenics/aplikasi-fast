<?php

namespace App\Http\Controllers;

use App\Models\PengirimanData;
use App\Models\VendorReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VendorTiangController extends Controller
{
    private const CHECKLIST = [
        'Dokumen pekerjaan lengkap',
        'Pekerjaan sesuai WO',
        'Foto dokumentasi terlampir',
        'Siap ditindaklanjuti Perencanaan',
    ];

    public function dashboard()
    {
        $agendas = PengirimanData::with(['berkas' => fn ($query) => $query->whereIn('jenis_berkas', ['wo_tiang', 'ba_cek'])])
            ->where($this->sentColumn(), true)
            ->when($this->assignedUserColumn(), fn ($query, $column) => $query->where($column, Auth::id()))
            ->latest($this->sentAtColumn())
            ->latest()
            ->get();

        return view('vendor_tiang.dashboard', ['agendas' => $agendas, 'routePrefix' => $this->routePrefix(), 'pageTitle' => $this->pageTitle()]);
    }

    public function storeReport(Request $request)
    {
        $validated = $request->validate([
            'no_agenda' => ['required', 'string', 'max:255'],
            'checklist' => ['nullable', 'array'],
            'checklist.*' => ['in:' . implode(',', self::CHECKLIST)],
            'catatan' => ['nullable', 'string', 'max:2000'],
            'files' => ['required', 'array', 'min:1'],
            'files.*' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);

        $report = VendorReport::create([
            'no_agenda' => $validated['no_agenda'],
            'vendor_name' => Auth::user()->name,
            'vendor_user_id' => Auth::id(),
            'recipient_role' => $this->recipientRole(),
            'checklist' => $validated['checklist'] ?? [],
            'catatan' => $validated['catatan'] ?? null,
        ]);
        $this->storeFiles($report, $request);

        return redirect()->route($this->routePrefix() . '.history')->with('success', 'Laporan berhasil dikirim.');
    }

    public function history()
    {
        $reports = $this->ownedReports()->with('files')->latest()->get();
        return view('vendor_tiang.history', ['reports' => $reports, 'checklistOptions' => self::CHECKLIST, 'routePrefix' => $this->routePrefix(), 'pageTitle' => $this->pageTitle()]);
    }

    public function update(Request $request, VendorReport $report)
    {
        $this->ensureOwner($report);
        $validated = $request->validate([
            'checklist' => ['nullable', 'array'],
            'checklist.*' => ['in:' . implode(',', self::CHECKLIST)],
            'catatan' => ['nullable', 'string', 'max:2000'],
            'files' => ['nullable', 'array'],
            'files.*' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);
        $report->update(['checklist' => $validated['checklist'] ?? [], 'catatan' => $validated['catatan'] ?? null]);
        $this->storeFiles($report, $request);

        return back()->with('success', 'Riwayat pengiriman berhasil diperbarui.');
    }

    public function destroy(VendorReport $report)
    {
        $this->ensureOwner($report);
        foreach ($report->files as $file) Storage::disk('public')->delete($file->path_file);
        $report->delete();
        return back()->with('success', 'Riwayat pengiriman berhasil dihapus.');
    }

    private function ownedReports()
    {
        $query = VendorReport::query();
        if ($this->recipientRole() === 'perencanaan') {
            $query->where(function ($recipientQuery) {
                $recipientQuery->where('recipient_role', 'perencanaan')->orWhereNull('recipient_role');
            });
        } else {
            $query->where('recipient_role', $this->recipientRole());
        }
        return $query->where(function ($query) {
            $query->where('vendor_user_id', Auth::id())
                ->orWhere(function ($legacyQuery) {
                    $legacyQuery->whereNull('vendor_user_id')->where('vendor_name', Auth::user()->name);
                });
        });
    }

    private function ensureOwner(VendorReport $report): void
    {
        abort_unless($this->ownedReports()->whereKey($report->id)->exists(), 403);
    }

    private function storeFiles(VendorReport $report, Request $request): void
    {
        foreach ($request->file('files', []) as $file) {
            $report->files()->create([
                'nama_file' => $file->getClientOriginalName(),
                'path_file' => $file->store('vendor-reports', 'public'),
            ]);
        }
    }

    protected function routePrefix(): string { return 'vendor_tiang'; }
    protected function recipientRole(): string { return 'perencanaan'; }
    protected function sentColumn(): string { return 'vendor_sent'; }
    protected function sentAtColumn(): string { return 'vendor_sent_at'; }
    protected function pageTitle(): string { return 'Vendor Tiang'; }
    protected function assignedUserColumn(): ?string { return null; }
}
