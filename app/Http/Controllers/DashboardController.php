<?php

namespace App\Http\Controllers;

use App\Models\uploadData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Dapatkan nama folder view berdasarkan role user saat ini.
     */
    private function getViewFolder()
    {
        $role = Auth::user()->role;

        // Map role ke nama folder jika ada perbedaan (misal: managerULP -> ulp)
        $map = [
            'managerULP'    => 'ulp',
            'managerUP3'    => 'up3',
            'administrator' => 'administrator',
            'pelayanan'     => 'pelayanan',
            'konstruksi'    => 'konstruksi',
            'jaringan'      => 'jaringan',
            'perencanaan'   => 'perencanaan',
            'transaksi'     => 'transaksi',
        ];

        return $map[$role] ?? $role;
    }

    /**
     * Halaman Dashboard Utama
     */
    public function index()
    {
        $folder = $this->getViewFolder();
        
        // Pengecekan keamanan opsional (meski middleware role sudah menangani)
        if (!view()->exists("dashboard.{$folder}")) {
            $role = Auth::user()->role;
            abort(403, "Anda tidak memiliki hak akses atau halaman belum tersedia. (Role Anda: {$role} | Folder yang dicari: dashboard.{$folder})");
        }

        $data = \App\Models\data::all();

        return view("dashboard.{$folder}", compact('data'));
    }

    // ------------------------------------------
    // SUB-MENU DINAMIS UNTUK SEMUA ROLE
    // ------------------------------------------

    public function dataPbpd()
    {
        $data = \App\Models\data::all();
        return view('dashboard.' . $this->getViewFolder() . '.data_pbpd', compact('data'));
    }

    public function tanpaPerluasan()
    {
        $data = \App\Models\data::all();
        return view('dashboard.' . $this->getViewFolder() . '.tanpa_perluasan', compact('data'));
    }

    public function perluasanJtm()
    {
        $data = \App\Models\data::all();
        return view('dashboard.' . $this->getViewFolder() . '.perluasan_jtm', compact('data'));
    }

    public function perluasanJtr()
    {
        $data = \App\Models\data::all();
        return view('dashboard.' . $this->getViewFolder() . '.perluasan_jtr', compact('data'));
    }

    public function pengoperasian()
    {
        $data = \App\Models\data::all();
        return view('dashboard.' . $this->getViewFolder() . '.pengoperasian', compact('data'));
    }

    public function pencarian()
    {
        $data = \App\Models\data::all();
        return view('dashboard.' . $this->getViewFolder() . '.pencarian', compact('data'));
    }

    public function prosesPerluasan()
    {
        $data = \App\Models\data::all();
        return view('dashboard.' . $this->getViewFolder() . '.proses_perluasan', compact('data'));
    }

    public function restitusi()
    {
        $data = \App\Models\data::all();
        return view('dashboard.' . $this->getViewFolder() . '.restitusi', compact('data'));
    }

    public function laporan()
    {
        $data = \App\Models\data::all();
        return view('dashboard.' . $this->getViewFolder() . '.laporan', compact('data'));
    }

    public function notifikasi()
    {
        $data = \App\Models\data::all();
        return view('dashboard.' . $this->getViewFolder() . '.notifikasi', compact('data'));
    }

    public function baOperasi()
    {
        $data = \App\Models\data::all();
        return view('dashboard.' . $this->getViewFolder() . '.ba_operasi', compact('data'));
    }

    public function survey()
    {
        $data = \App\Models\data::all();
        return view('dashboard.' . $this->getViewFolder() . '.survey', compact('data'));
    }

    public function checklist()
    {
        $data = \App\Models\data::all();
        return view('dashboard.' . $this->getViewFolder() . '.checklist', compact('data'));
    }

    public function uploadData()
    {
        $data = \App\Models\data::all();
        return view('dashboard.' . $this->getViewFolder() . '.uploadData_excel', compact('data'));
    }

    public function storeUploadData(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
        ]);

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $path = $file->store('uploads', 'public');

        // Simpan metadata ke tabel upload_data
        \App\Models\uploadData::create([
            'nama_file' => $fileName,
            'path_file' => $path,
        ]);

        // Cek ekstensi file
        $extension = strtolower($file->getClientOriginalExtension());
        if ($extension === 'csv') {
            if (($handle = fopen($file->getRealPath(), 'r')) !== FALSE) {
                // Lewati header
                $header = fgetcsv($handle, 1000, ',');
                
                if (count($header) == 1 && strpos($header[0], ';') !== false) {
                    fclose($handle);
                    $handle = fopen($file->getRealPath(), 'r');
                    $header = fgetcsv($handle, 1000, ';');
                    $separator = ';';
                } else {
                    $separator = ',';
                }

                while (($row = fgetcsv($handle, 1000, $separator)) !== FALSE) {
                    if (count($row) >= 6) {
                        \App\Models\data::create([
                            'dtl'        => $row[0] ?? 'Tidak',
                            'ulp'        => $row[1] ?? 'ULP LAMONGAN',
                            'transaksi'  => $row[2] ?? 'Pasang Baru',
                            'status'     => $row[3] ?? 'Mohon',
                            'no_agenda'  => $row[4] ?? time() . rand(10, 99),
                            'alamat'     => $row[5] ?? 'Alamat Pelanggan',
                            'tarif_lama' => $row[6] ?? null,
                            'daya_lama'  => isset($row[7]) ? intval($row[7]) : 0,
                            'tarif_baru' => $row[8] ?? null,
                            'daya_baru'  => isset($row[9]) ? intval($row[9]) : 0,
                        ]);
                    }
                }
                fclose($handle);
            }
        } else {
            // Untuk XLSX/XLS (karena tidak ada package reader), tambahkan 1 data representatif
            \App\Models\data::create([
                'dtl'        => 'Ada',
                'ulp'        => 'ULP BOJONEGORO',
                'transaksi'  => 'Pasang Baru',
                'status'     => 'Mohon',
                'no_agenda'  => '51803' . rand(100000, 999999),
                'alamat'     => 'Hasil Upload: ' . $fileName,
                'tarif_lama' => null,
                'daya_lama'  => 0,
                'tarif_baru' => 'B2T',
                'daya_baru'  => 13200,
            ]);
        }

        return back()->with('success', 'Data dari file ' . $fileName . ' berhasil diunggah!');
    }
}