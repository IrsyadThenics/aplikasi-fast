<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\uploadData;
use App\Models\data;

class FileController extends Controller
{
    // Cukup melihat file yang di-upload
    public function getFiles()
    {
        // Get files ordered by latest
        $files = uploadData::orderBy('created_at', 'desc')->get();
        return response()->json([
            'success' => true,
            'data' => $files
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

        // (Opsional) bisa parse CSV/Excel di sini seperti di web, atau hanya menyimpan file.
        // Jika dibutuhkan seperti DashboardController::storeUploadData, kita bisa menggunakan script yang sama.
        // Namun, untuk Flutter API, ini sudah cukup untuk upload.

        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully',
            'data' => $uploadData
        ]);
    }
}
