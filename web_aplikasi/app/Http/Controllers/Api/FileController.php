<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengirimanData;
use App\Models\uploadData;

class FileController extends Controller
{
    // Mengambil data pengiriman dari Perencanaan beserta seluruh berkasnya (WO, KTP, ITT, dll) per pelanggan
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

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $path = $file->store('uploads', 'public');

        // Simpan metadata ke tabel upload_data
        $uploadData = \App\Models\uploadData::create([
            'nama_file' => $fileName,
            'path_file' => $path,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully',
            'data'    => $uploadData
        ]);
    }
}
