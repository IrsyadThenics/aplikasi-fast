<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VendorReport;
use Illuminate\Http\Request;

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
}
