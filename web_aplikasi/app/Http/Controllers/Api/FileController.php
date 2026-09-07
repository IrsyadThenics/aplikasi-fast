<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengirimanData;
use App\Models\uploadData;

class FileController extends Controller
{
    // Mengambil data pengiriman dari Perencanaan beserta berkas pendukungnya
    public function getFiles()
    {
        $pengiriman = PengirimanData::with('berkas')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $pengiriman
        ]);
    }

    // Upload file
    public function uploadFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
        ]);

        // Detect destination from route prefix (perencanaan or konstruksi)
        $dest = $request->route()->getPrefix() ?? 'unknown';
        $dest = trim($dest, '/');

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $path = $file->store('uploads', 'public');

        // Simpan metadata ke tabel upload_data
        $uploadData = \App\Models\uploadData::create([
            'nama_file' => $fileName,
            'path_file' => $path,
            'dest'      => $dest,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully',
            'data'    => $uploadData
        ]);
    }
}
