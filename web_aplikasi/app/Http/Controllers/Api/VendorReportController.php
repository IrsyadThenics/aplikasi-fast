<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VendorReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VendorReportController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_agenda' => ['required', 'string', 'max:255'],
            'checklist' => ['nullable', 'array'],
            'checklist.*' => ['string', 'max:255'],
            'catatan' => ['nullable', 'string', 'max:2000'],
            'files' => ['required', 'array', 'min:1'],
            'files.*' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);

        $report = VendorReport::create([
            'no_agenda' => $validated['no_agenda'],
            'vendor_name' => $request->user()?->name ?? 'Vendor',
            'vendor_user_id' => $request->user()?->id,
            'recipient_role' => 'perencanaan',
            'checklist' => $validated['checklist'] ?? [],
            'catatan' => $validated['catatan'] ?? null,
        ]);

        foreach ($request->file('files', []) as $file) {
            $report->files()->create([
                'nama_file' => $file->getClientOriginalName(),
                'path_file' => $file->store('vendor-reports', 'public'),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Laporan vendor berhasil dikirim ke Perencanaan.',
            'data' => $report->load('files'),
        ], 201);
    }

    public function index(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $this->ownedReports($request)
                ->with('files')
                ->latest()
                ->get(),
        ]);
    }

    public function update(Request $request, VendorReport $report)
    {
        $this->ensureOwner($request, $report);

        $validated = $request->validate([
            'checklist' => ['nullable', 'array'],
            'checklist.*' => ['string', 'max:255'],
            'catatan' => ['nullable', 'string', 'max:2000'],
            'files' => ['nullable', 'array'],
            'files.*' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);

        $report->update([
            'checklist' => $validated['checklist'] ?? [],
            'catatan' => $validated['catatan'] ?? null,
        ]);

        foreach ($request->file('files', []) as $file) {
            $report->files()->create([
                'nama_file' => $file->getClientOriginalName(),
                'path_file' => $file->store('vendor-reports', 'public'),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Riwayat pengiriman berhasil diperbarui.',
            'data' => $report->fresh()->load('files'),
        ]);
    }

    public function destroy(Request $request, VendorReport $report)
    {
        $this->ensureOwner($request, $report);

        foreach ($report->files as $file) {
            Storage::disk('public')->delete($file->path_file);
        }
        $report->delete();

        return response()->json([
            'success' => true,
            'message' => 'Riwayat pengiriman berhasil dihapus.',
        ]);
    }

    private function ownedReports(Request $request)
    {
        $user = $request->user();

        return VendorReport::query()->where(function ($query) use ($user) {
            $query->where('vendor_user_id', $user->id)
                ->orWhere(function ($legacyQuery) use ($user) {
                    $legacyQuery->whereNull('vendor_user_id')
                        ->where('vendor_name', $user->name);
                });
        });
    }

    private function ensureOwner(Request $request, VendorReport $report): void
    {
        abort_unless($this->ownedReports($request)->whereKey($report->getKey())->exists(), 403);
    }
}
