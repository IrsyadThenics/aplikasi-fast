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

        // Hapus data lama agar setiap kali upload, data di menu PB/PD berubah menjadi data terbaru
        \App\Models\data::truncate();

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

                // Map header names to column index
                $map = [];
                foreach ($header as $idx => $h) {
                    $map[strtoupper(trim($h))] = $idx;
                }

                // Match indices based on header names
                $idx_no_agenda      = $map['NOAGENDA'] ?? $map['NO AGENDA'] ?? $map['NOMOR AGENDA'] ?? null;
                $idx_nama           = $map['NAMA'] ?? $map['NAMA PELANGGAN'] ?? null;
                $idx_alamat         = $map['ALAMAT'] ?? $map['ALAMAT PELANGGAN'] ?? null;
                $idx_tarif_lama     = $map['TARIF_LAMA'] ?? $map['TARIF LAMA'] ?? null;
                $idx_daya_lama      = $map['DAYA_LAMA'] ?? $map['DAYA LAMA'] ?? null;
                $idx_tarif_baru     = $map['TARIF'] ?? $map['TARIF_BARU'] ?? $map['TARIF BARU'] ?? null;
                $idx_daya_baru      = $map['DAYA'] ?? $map['DAYA_BARU'] ?? $map['DAYA BARU'] ?? null;
                $idx_transaksi      = $map['JENIS_TRANSAKSI'] ?? $map['TRANSAKSI'] ?? $map['JENIS TRANSAKSI'] ?? null;
                $idx_status         = $map['STATUS_PERMOHONAN'] ?? $map['STATUS'] ?? $map['STATUS PERMOHONAN'] ?? null;
                $idx_ulp            = $map['NAMAUP'] ?? $map['ULP'] ?? $map['NAMA_UP'] ?? $map['NAMA ULP'] ?? null;
                $idx_tanggal_ulp    = $map['TGLMOHON'] ?? $map['TGL_MOHON'] ?? $map['TANGGAL MOHON'] ?? null;
                $idx_total_biaya    = $map['TOTALBIAYA'] ?? $map['TOTAL_BIAYA'] ?? $map['TOTAL BIAYA'] ?? null;
                $idx_tanggal_bayar  = $map['TGLBAYAR'] ?? $map['TGL_BAYAR'] ?? $map['TANGGAL BAYAR'] ?? null;
                $idx_durasi_hk      = $map['DURASI_HARI_KERJA'] ?? $map['DURASI HARI KERJA'] ?? null;

                // Fallbacks if header mapping fails
                if ($idx_no_agenda === null) $idx_no_agenda = 4;
                if ($idx_nama === null) $idx_nama = null;
                if ($idx_alamat === null) $idx_alamat = 5;
                if ($idx_tarif_lama === null) $idx_tarif_lama = 6;
                if ($idx_daya_lama === null) $idx_daya_lama = 7;
                if ($idx_tarif_baru === null) $idx_tarif_baru = 8;
                if ($idx_daya_baru === null) $idx_daya_baru = 9;
                if ($idx_transaksi === null) $idx_transaksi = 2;
                if ($idx_status === null) $idx_status = 3;
                if ($idx_ulp === null) $idx_ulp = 1;

                while (($row = fgetcsv($handle, 1000, $separator)) !== FALSE) {
                    $agenda = trim($row[$idx_no_agenda] ?? '');
                    if ($agenda !== '') {
                        \App\Models\data::create([
                            'dtl'              => 'Ada',
                            'ulp'              => $row[$idx_ulp] ?? 'ULP LAMONGAN',
                            'nama'             => $idx_nama !== null ? ($row[$idx_nama] ?? null) : null,
                            'tanggal_ulp'      => $idx_tanggal_ulp !== null ? ($row[$idx_tanggal_ulp] ?? null) : null,
                            'transaksi'        => $row[$idx_transaksi] ?? 'Pasang Baru',
                            'status'           => $row[$idx_status] ?? 'Mohon',
                            'no_agenda'        => $agenda,
                            'alamat'           => $row[$idx_alamat] ?? '',
                            'tarif_lama'       => $row[$idx_tarif_lama] ?? null,
                            'daya_lama'        => isset($row[$idx_daya_lama]) && is_numeric($row[$idx_daya_lama]) ? intval($row[$idx_daya_lama]) : 0,
                            'tarif_baru'       => $row[$idx_tarif_baru] ?? null,
                            'daya_baru'        => isset($row[$idx_daya_baru]) && is_numeric($row[$idx_daya_baru]) ? intval($row[$idx_daya_baru]) : 0,
                            'total_biaya'      => $idx_total_biaya !== null ? ($row[$idx_total_biaya] ?? null) : null,
                            'tanggal_bayar'    => $idx_tanggal_bayar !== null ? ($row[$idx_tanggal_bayar] ?? null) : null,
                            'durasi_hari_kerja'=> $idx_durasi_hk !== null ? ($row[$idx_durasi_hk] ?? null) : null,
                        ]);
                    }
                }
                fclose($handle);
            }
        } elseif (in_array($extension, ['xlsx', 'xls'])) {
            $rows = [];
            if ($extension === 'xlsx') {
                if ($xlsx = \Shuchkin\SimpleXLSX::parse($file->getRealPath())) {
                    $rows = $xlsx->rows();
                }
            } else {
                if ($xls = \Shuchkin\SimpleXLS::parse($file->getRealPath())) {
                    $rows = $xls->rows();
                }
            }

            if (!empty($rows)) {
                $headers = array_shift($rows);
                
                // Map header names to column index
                $map = [];
                foreach ($headers as $idx => $h) {
                    $h_clean = strtoupper(trim($h));
                    $map[$h_clean] = $idx;
                }

                // Match indices based on header names (case-insensitive)
                $idx_no_agenda      = $map['NOAGENDA'] ?? $map['NO AGENDA'] ?? $map['NOMOR AGENDA'] ?? null;
                $idx_nama           = $map['NAMA'] ?? $map['NAMA PELANGGAN'] ?? null;
                $idx_alamat         = $map['ALAMAT'] ?? $map['ALAMAT PELANGGAN'] ?? null;
                $idx_tarif_lama     = $map['TARIF_LAMA'] ?? $map['TARIF LAMA'] ?? null;
                $idx_daya_lama      = $map['DAYA_LAMA'] ?? $map['DAYA LAMA'] ?? null;
                $idx_tarif_baru     = $map['TARIF'] ?? $map['TARIF_BARU'] ?? $map['TARIF BARU'] ?? null;
                $idx_daya_baru      = $map['DAYA'] ?? $map['DAYA_BARU'] ?? $map['DAYA BARU'] ?? null;
                $idx_transaksi      = $map['JENIS_TRANSAKSI'] ?? $map['TRANSAKSI'] ?? $map['JENIS TRANSAKSI'] ?? null;
                $idx_status         = $map['STATUS_PERMOHONAN'] ?? $map['STATUS'] ?? $map['STATUS PERMOHONAN'] ?? null;
                $idx_ulp            = $map['NAMAUP'] ?? $map['ULP'] ?? $map['NAMA_UP'] ?? $map['NAMA ULP'] ?? null;
                $idx_tanggal_ulp    = $map['TGLMOHON'] ?? $map['TGL_MOHON'] ?? $map['TANGGAL MOHON'] ?? null;
                $idx_total_biaya    = $map['TOTALBIAYA'] ?? $map['TOTAL_BIAYA'] ?? $map['TOTAL BIAYA'] ?? null;
                $idx_tanggal_bayar  = $map['TGLBAYAR'] ?? $map['TGL_BAYAR'] ?? $map['TANGGAL BAYAR'] ?? null;
                $idx_durasi_hk      = $map['DURASI_HARI_KERJA'] ?? $map['DURASI HARI KERJA'] ?? null;
                
                // Fallbacks if headers differ but are close to typical indexes
                if ($idx_no_agenda === null) $idx_no_agenda = 0;
                if ($idx_nama === null) $idx_nama = 4;
                if ($idx_alamat === null) $idx_alamat = 5;
                if ($idx_tarif_lama === null) $idx_tarif_lama = 11;
                if ($idx_daya_lama === null) $idx_daya_lama = 12;
                if ($idx_tarif_baru === null) $idx_tarif_baru = 13;
                if ($idx_daya_baru === null) $idx_daya_baru = 14;
                if ($idx_transaksi === null) $idx_transaksi = 15;
                if ($idx_status === null) $idx_status = 33;
                if ($idx_ulp === null) $idx_ulp = 40;
                if ($idx_tanggal_ulp === null) $idx_tanggal_ulp = 2;
                if ($idx_total_biaya === null) $idx_total_biaya = 17;
                if ($idx_tanggal_bayar === null) $idx_tanggal_bayar = 18;
                if ($idx_durasi_hk === null) $idx_durasi_hk = 19;

                foreach ($rows as $row) {
                    $agenda = trim($row[$idx_no_agenda] ?? '');
                    if ($agenda !== '') {
                        \App\Models\data::create([
                            'dtl'               => 'Ada',
                            'ulp'               => $row[$idx_ulp] ?? 'ULP LAMONGAN',
                            'nama'              => $row[$idx_nama] ?? null,
                            'tanggal_ulp'       => $row[$idx_tanggal_ulp] ?? null,
                            'transaksi'         => $row[$idx_transaksi] ?? 'Pasang Baru',
                            'status'            => $row[$idx_status] ?? 'Mohon',
                            'no_agenda'         => $agenda,
                            'alamat'            => $row[$idx_alamat] ?? '',
                            'tarif_lama'        => $row[$idx_tarif_lama] ?? null,
                            'daya_lama'         => isset($row[$idx_daya_lama]) && is_numeric($row[$idx_daya_lama]) ? intval($row[$idx_daya_lama]) : 0,
                            'tarif_baru'        => $row[$idx_tarif_baru] ?? null,
                            'daya_baru'         => isset($row[$idx_daya_baru]) && is_numeric($row[$idx_daya_baru]) ? intval($row[$idx_daya_baru]) : 0,
                            'total_biaya'       => $row[$idx_total_biaya] ?? null,
                            'tanggal_bayar'     => $row[$idx_tanggal_bayar] ?? null,
                            'durasi_hari_kerja' => $row[$idx_durasi_hk] ?? null,
                        ]);
                    }
                }
            } else {
                $err = $extension === 'xlsx' ? \Shuchkin\SimpleXLSX::parseError() : \Shuchkin\SimpleXLS::parseError();
                return back()->with('error', 'Gagal membaca file Excel: ' . $err);
            }
        } else {
            return back()->with('error', 'Format file tidak didukung. Harap unggah file CSV, XLSX, atau XLS.');
        }

        return back()->with('success', 'Data dari file ' . $fileName . ' berhasil diunggah!');
    }
}