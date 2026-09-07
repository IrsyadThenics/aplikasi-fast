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
            'managerULP'          => 'ulp',
            'managerULP_babat'    => 'ulp_babat',
            'managerULP_brondong' => 'ulp_brondong',
            'managerULP_padangan' => 'ulp_padangan',
            'managerULP_bjn'      => 'ulp_bjn',
            'managerULP_sumberejo'=> 'ulp_sumberejo',
            'managerULP_tuban'    => 'ulp_tuban',
            'managerULP_jatirogo' => 'ulp_jatirogo',
            'managerUP3'          => 'up3',
            'administrator'       => 'administrator',
            'pelayanan'           => 'pelayanan',
            'konstruksi'          => 'konstruksi',
            'jaringan'            => 'jaringan',
            'perencanaan'         => 'perencanaan',
            'transaksi'           => 'transaksi',
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

        $data = $this->getFilteredData();

        return view("dashboard.{$folder}", compact('data'));
    }

    // ------------------------------------------
    // SUB-MENU DINAMIS UNTUK SEMUA ROLE
    // ------------------------------------------

    private function getFilteredData()
    {
        $role = Auth::user()->role;
        $query = \App\Models\data::query();

        $ulpMap = [
            'managerULP'          => 'LAMONGAN',
            'managerULP_babat'    => 'BABAT',
            'managerULP_brondong' => 'BRONDONG',
            'managerULP_padangan' => 'PADANGAN',
            'managerULP_bjn'      => 'BOJONEGORO',
            'managerULP_sumberejo'=> 'SUMBEREJO',
            'managerULP_tuban'    => 'TUBAN',
            'managerULP_jatirogo' => 'JATIROGO',
        ];

        if (array_key_exists($role, $ulpMap)) {
            $query->whereRaw('LOWER(ulp) LIKE ?', ['%' . strtolower($ulpMap[$role]) . '%']);
        }

        // Data yang telah dikirim oleh ULP dipindahkan ke History dan tidak lagi
        // ditampilkan pada Data PB/PD, termasuk setelah halaman dimuat ulang.
        if (str_starts_with($role, 'managerULP')) {
            $sentItems = \App\Models\PengirimanData::query();
            if (array_key_exists($role, $ulpMap)) {
                $sentItems->whereRaw('LOWER(ulp) LIKE ?', ['%' . strtolower($ulpMap[$role]) . '%']);
            }
            $query->whereNotIn('no_agenda', $sentItems->select('no_agenda'));
        }

        // Tampilkan data dengan berbagai status yang relevan
        $query->where(function ($q) {
            $q->whereRaw('LOWER(status) LIKE ?', ['%cetak pk%'])
              ->orWhereRaw('LOWER(status) LIKE ?', ['%pengesahan pdl%'])
              ->orWhereRaw('LOWER(status) LIKE ?', ['%pdl awal%'])
              ->orWhereRaw('LOWER(status) LIKE ?', ['%mohon%'])
              ->orWhereRaw('LOWER(status) LIKE ?', ['%bayar%']);
        });

        return $query->get();
    }

    public function dataPbpd()
    {
        $data = $this->getFilteredData();

        $role = Auth::user()->role;

        // Semua role managerULP (termasuk per-ULP) pakai view khusus dengan tombol "Kirim"
        $ulpRoles = [
            'managerULP'          => 'dashboard.ulp.data_pbpd',
            'managerULP_babat'    => 'dashboard.ulp_babat.data_pbpd',
            'managerULP_brondong' => 'dashboard.ulp_brondong.data_pbpd',
            'managerULP_padangan' => 'dashboard.ulp_padangan.data_pbpd',
            'managerULP_bjn'      => 'dashboard.ulp_bjn.data_pbpd',
            'managerULP_sumberejo'=> 'dashboard.ulp_sumberejo.data_pbpd',
            'managerULP_tuban'    => 'dashboard.ulp_tuban.data_pbpd',
            'managerULP_jatirogo' => 'dashboard.ulp_jatirogo.data_pbpd',
        ];

        if (isset($ulpRoles[$role])) {
            return view($ulpRoles[$role], compact('data'));
        }

        // Semua role lain: pakai shared view yang otomatis menyembunyikan
        // data yang sudah dikirim ke JTM/JTR/Tanpa Perluasan via IndexedDB
        return view('dashboard.shared.data_pbpd', compact('data'));
    }

    public function tanpaPerluasan()
    {
        $data = $this->getFilteredData();
        $agendas = \App\Models\PengirimanData::where('dest', 'tanpa_perluasan')->pluck('no_agenda');
        $vendorReports = \App\Models\VendorReport::with('files')->whereIn('no_agenda', $agendas)->latest()->get();
        return view('dashboard.shared.tanpa_perluasan', compact('data', 'vendorReports'));
    }

    public function perluasanJtm()
    {
        $data = $this->getFilteredData();
        $agendas = \App\Models\PengirimanData::where('dest', 'jtm')->pluck('no_agenda');
        $vendorReports = \App\Models\VendorReport::with('files')->whereIn('no_agenda', $agendas)->latest()->get();
        return view('dashboard.shared.perluasan_jtm', compact('data', 'vendorReports'));
    }

    public function perluasanJtr()
    {
        $data = $this->getFilteredData();
        $agendas = \App\Models\PengirimanData::where('dest', 'jtr')->pluck('no_agenda');
        $vendorReports = \App\Models\VendorReport::with('files')->whereIn('no_agenda', $agendas)->latest()->get();
        return view('dashboard.shared.perluasan_jtr', compact('data', 'vendorReports'));
    }

    public function pengoperasian()
    {
        $data = $this->getFilteredData();
        return view('dashboard.' . $this->getViewFolder() . '.pengoperasian', compact('data'));
    }

    public function pencarian()
    {
        $data = $this->getFilteredData();
        return view('dashboard.' . $this->getViewFolder() . '.pencarian', compact('data'));
    }

    public function prosesPerluasan()
    {
        $data = $this->getFilteredData();
        return view('dashboard.' . $this->getViewFolder() . '.proses_perluasan', compact('data'));
    }

    public function restitusi()
    {
        $data = $this->getFilteredData();
        return view('dashboard.' . $this->getViewFolder() . '.restitusi', compact('data'));
    }

    public function laporan()
    {
        $role = Auth::user()->role;
        $query = \App\Models\data::query();

        $ulpMap = [
            'managerULP'          => 'LAMONGAN',
            'managerULP_babat'    => 'BABAT',
            'managerULP_brondong' => 'BRONDONG',
            'managerULP_padangan' => 'PADANGAN',
            'managerULP_bjn'      => 'BOJONEGORO',
            'managerULP_sumberejo'=> 'SUMBEREJO',
            'managerULP_tuban'    => 'TUBAN',
            'managerULP_jatirogo' => 'JATIROGO',
        ];

        if (array_key_exists($role, $ulpMap)) {
            $query->whereRaw('LOWER(ulp) LIKE ?', ['%' . strtolower($ulpMap[$role]) . '%']);
        }

        $query->where(function ($q) {
            $q->whereRaw('LOWER(status) LIKE ?', ['%peremajaan%'])
              ->orWhereRaw('LOWER(status) LIKE ?', ['%bayar%']);
        });

        $data = $query->get();
        return view('dashboard.' . $this->getViewFolder() . '.laporan', compact('data'));
    }

    public function notifikasi()
    {
        $data = $this->getFilteredData();
        return view('dashboard.' . $this->getViewFolder() . '.notifikasi', compact('data'));
    }

    public function baOperasi()
    {
        $data = $this->getFilteredData();
        return view('dashboard.' . $this->getViewFolder() . '.ba_operasi', compact('data'));
    }

    public function survey()
    {
        $data = $this->getFilteredData();
        return view('dashboard.' . $this->getViewFolder() . '.survey', compact('data'));
    }

    public function checklist()
    {
        $data = $this->getFilteredData();
        return view('dashboard.' . $this->getViewFolder() . '.checklist', compact('data'));
    }

    public function uploadData()
    {
        $data = $this->getFilteredData();
        return view('dashboard.' . $this->getViewFolder() . '.uploadData_excel', compact('data'));
    }
    public function cekKwh()
    {
        $data = $this->getFilteredData();
        return view('dashboard.' . $this->getViewFolder() . '.cek_kwh', compact('data'));
    }

    public function historyPengiriman()
    {
        $role = Auth::user()->role;
        $query = $this->getUlpPengirimanQuery();

        if ($role === 'perencanaan') {
            $query->where('vendor_sent', true)
                  ->latest('vendor_sent_at')
                  ->latest('sentAt');
        } else {
            $query->latest('sentAt');
        }

        $history = $query->get();
        return view('dashboard.ulp.history', compact('history'));
    }

    public function storeHistoryPengiriman(Request $request)
    {
        $role = Auth::user()->role;
        abort_unless(str_starts_with($role, 'managerULP') || $role === 'perencanaan', 403);

        $ulpMap = [
            'managerULP'          => 'LAMONGAN',
            'managerULP_babat'    => 'BABAT',
            'managerULP_brondong' => 'BRONDONG',
            'managerULP_padangan' => 'PADANGAN',
            'managerULP_bjn'      => 'BOJONEGORO',
            'managerULP_sumberejo'=> 'SUMBEREJO',
            'managerULP_tuban'    => 'TUBAN',
            'managerULP_jatirogo' => 'JATIROGO',
        ];

        $validated = $request->validate([
            'no_agenda'   => ['required', 'string', 'max:255'],
            'nama'        => ['required', 'string', 'max:255'],
            'alamat'      => ['nullable', 'string', 'max:1000'],
            'transaksi'   => ['nullable', 'string', 'max:100'],
            'status'      => ['nullable', 'string', 'max:100'],
            'dest'        => ['required', 'in:jtm,jtr,tanpa_perluasan'],
            'tarif_lama'  => ['nullable', 'string', 'max:100'],
            'daya_lama'   => ['nullable', 'integer', 'min:0'],
            'tarif_baru'  => ['nullable', 'string', 'max:100'],
            'daya_baru'   => ['nullable', 'integer', 'min:0'],
            'total_biaya' => ['nullable', 'numeric', 'min:0'],
            'ulp'         => ['nullable', 'string', 'max:255'],
        ]);

        $validated['agendaKey'] = $validated['no_agenda'];
        $validated['ulp']       = $request->input('ulp') ?: ($ulpMap[$role] ?? 'PERENCANAAN');
        $validated['sentAt']    = now();
        $validated['vendor_sent'] = true;
        $validated['vendor_sent_at'] = now();

        \App\Models\PengirimanData::updateOrCreate(
            ['agendaKey' => $validated['agendaKey']],
            $validated
        );

        return back()->with('success', 'Riwayat pengiriman berhasil ditambahkan.');
    }

    public function updateHistoryPengiriman(Request $request, \App\Models\PengirimanData $pengiriman)
    {
        $this->ensureUlpOwnsPengiriman($pengiriman);
        $validated = $request->validate([
            'no_agenda'   => ['nullable', 'string', 'max:255'],
            'nama'        => ['nullable', 'string', 'max:255'],
            'alamat'      => ['nullable', 'string', 'max:1000'],
            'transaksi'   => ['nullable', 'string', 'max:100'],
            'status'      => ['nullable', 'string', 'max:100'],
            'dest'        => ['required', 'in:jtm,jtr,tanpa_perluasan'],
            'tarif_lama'  => ['nullable', 'string', 'max:100'],
            'daya_lama'   => ['nullable', 'integer', 'min:0'],
            'tarif_baru'  => ['nullable', 'string', 'max:100'],
            'daya_baru'   => ['nullable', 'integer', 'min:0'],
            'total_biaya' => ['nullable', 'numeric', 'min:0'],
            'ulp'         => ['nullable', 'string', 'max:255'],
        ]);

        if (!empty($validated['no_agenda'])) {
            $validated['agendaKey'] = $validated['no_agenda'];
        }

        $pengiriman->update($validated);
        return back()->with('success', 'Riwayat pengiriman berhasil diperbarui.');
    }

    public function destroyHistoryPengiriman(\App\Models\PengirimanData $pengiriman)
    {
        $this->ensureUlpOwnsPengiriman($pengiriman);
        $pengiriman->delete();
        return back()->with('success', 'Riwayat pengiriman berhasil dihapus.');
    }

    private function getUlpPengirimanQuery()
    {
        $role = Auth::user()->role;
        abort_unless(str_starts_with($role, 'managerULP') || $role === 'perencanaan', 403);
        $ulpMap = [
            'managerULP'          => 'LAMONGAN',
            'managerULP_babat'    => 'BABAT',
            'managerULP_brondong' => 'BRONDONG',
            'managerULP_padangan' => 'PADANGAN',
            'managerULP_bjn'      => 'BOJONEGORO',
            'managerULP_sumberejo'=> 'SUMBEREJO',
            'managerULP_tuban'    => 'TUBAN',
            'managerULP_jatirogo' => 'JATIROGO',
        ];
        $query = \App\Models\PengirimanData::query();
        if (isset($ulpMap[$role])) {
            $query->whereRaw('LOWER(ulp) LIKE ?', ['%' . strtolower($ulpMap[$role]) . '%']);
        }
        return $query;
    }

    private function ensureUlpOwnsPengiriman(\App\Models\PengirimanData $pengiriman): void
    {
        abort_unless($this->getUlpPengirimanQuery()->whereKey($pengiriman->getKey())->exists(), 403);
    }

    public function storeUploadData(Request $request)
    {
        try {
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
                                'ulp'              => $row[$idx_ulp] ?? null,
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
                                'ulp'               => $row[$idx_ulp] ?? null,
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
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function apiKirimData(Request $request)
    {
        $validated = $request->validate([
            'agendaKey' => 'required|string',
            'dest' => 'required|string',
            'no_agenda' => 'required|string',
            'nama' => 'nullable|string',
            'alamat' => 'nullable|string',
            'transaksi' => 'nullable|string',
            'status' => 'nullable|string',
            'tarif_lama' => 'nullable|string',
            'daya_lama' => 'nullable|integer',
            'tarif_baru' => 'nullable|string',
            'daya_baru' => 'nullable|integer',
            'total_biaya' => 'nullable|numeric',
            'ulp' => 'nullable|string',
            'ktpCount' => 'nullable|integer',
            'ittCount' => 'nullable|integer',
        ]);

        $validated['sentAt'] = now();

        $pengiriman = \App\Models\PengirimanData::updateOrCreate(
            ['agendaKey' => $validated['agendaKey']],
            $validated
        );

        return response()->json(['success' => true, 'id' => $pengiriman->id]);
    }

    public function apiGetPengiriman(Request $request)
    {
        $dest = $request->query('dest');
        $query = \App\Models\PengirimanData::with('berkas');

        if ($dest) {
            $query->where('dest', $dest);
        }

        $role = Auth::user()->role;
        $ulpMap = [
            'managerULP'          => 'LAMONGAN',
            'managerULP_babat'    => 'BABAT',
            'managerULP_brondong' => 'BRONDONG',
            'managerULP_padangan' => 'PADANGAN',
            'managerULP_bjn'      => 'BOJONEGORO',
            'managerULP_sumberejo'=> 'SUMBEREJO',
            'managerULP_tuban'    => 'TUBAN',
            'managerULP_jatirogo' => 'JATIROGO',
        ];

        if (array_key_exists($role, $ulpMap)) {
            $query->whereRaw('LOWER(ulp) LIKE ?', ['%' . strtolower($ulpMap[$role]) . '%']);
        }

        return response()->json($query->get());
    }

    public function apiSimpanRab(Request $request)
    {
        $request->validate([
            'agendaKey' => 'required|string',
            'rab' => 'required|numeric',
        ]);

        $pengiriman = \App\Models\PengirimanData::where('agendaKey', $request->agendaKey)->first();
        if ($pengiriman) {
            $pengiriman->update(['total_biaya' => $request->rab]);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
    }

    public function apiKirimVendor(Request $request)
    {
        $request->validate([
            'agendaKey' => 'nullable|string',
            'no_agenda' => 'nullable|string',
            'vendor_pt' => 'nullable|string',
            'vendor_status_layak' => 'nullable|string',
            'file_kelayakan' => 'nullable|file',
            'file_wo_tiang' => 'nullable|file',
        ]);

        $query = \App\Models\PengirimanData::query();
        if ($request->filled('agendaKey')) {
            $query->where('agendaKey', $request->agendaKey);
        } elseif ($request->filled('no_agenda')) {
            $query->where('no_agenda', $request->no_agenda);
        } else {
            return response()->json(['success' => false, 'message' => 'No agenda or agendaKey specified.'], 400);
        }

        $pengiriman = $query->first();
        if (!$pengiriman) {
            return response()->json(['success' => false, 'message' => 'Data pengiriman tidak ditemukan.'], 404);
        }

        $updateData = [
            'vendor_sent' => true,
            'vendor_sent_at' => now(),
        ];
        if ($request->filled('vendor_pt')) {
            $updateData['vendor_pt'] = $request->vendor_pt;
        }
        if ($request->filled('vendor_status_layak')) {
            $updateData['vendor_status_layak'] = $request->vendor_status_layak;
        }

        $pengiriman->update($updateData);

        if ($request->hasFile('file_kelayakan')) {
            $file = $request->file('file_kelayakan');
            $path = $file->store('uploads', 'public');
            \App\Models\BerkasDokumen::create([
                'pengiriman_id' => $pengiriman->id,
                'jenis_berkas' => 'kelayakan',
                'nama_file' => $file->getClientOriginalName(),
                'path_file' => $path,
            ]);
        }

        if ($request->hasFile('file_wo_tiang')) {
            $file = $request->file('file_wo_tiang');
            $path = $file->store('uploads', 'public');
            \App\Models\BerkasDokumen::create([
                'pengiriman_id' => $pengiriman->id,
                'jenis_berkas' => 'wo_tiang',
                'nama_file' => $file->getClientOriginalName(),
                'path_file' => $path,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Data berhasil dikirim ke vendor.', 'data' => $pengiriman->load('berkas')]);
    }

    public function apiUploadBerkas(Request $request)
    {
        $request->validate([
            'no_agenda' => 'nullable|string',
            'agendaKey' => 'nullable|string',
            'jenis_berkas' => 'nullable|string',
            'file' => 'required|file',
        ]);

        $query = \App\Models\PengirimanData::query();
        if ($request->filled('agendaKey')) {
            $query->where('agendaKey', $request->agendaKey);
        } elseif ($request->filled('no_agenda')) {
            $query->where('no_agenda', $request->no_agenda);
        } else {
            return response()->json(['success' => false, 'message' => 'Agenda Key atau No Agenda wajib diisi.'], 400);
        }

        $pengiriman = $query->first();
        if (!$pengiriman) {
            $pengiriman = \App\Models\PengirimanData::create([
                'agendaKey' => $request->agendaKey ?? $request->no_agenda,
                'dest' => 'tanpa_perluasan',
                'no_agenda' => $request->no_agenda ?? $request->agendaKey,
                'sentAt' => now(),
            ]);
        }

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $path = $file->store('uploads', 'public');
        $jenis = $request->input('jenis_berkas', 'dokumen');

        $berkas = \App\Models\BerkasDokumen::create([
            'pengiriman_id' => $pengiriman->id,
            'jenis_berkas' => $jenis,
            'nama_file' => $fileName,
            'path_file' => $path,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berkas berhasil diunggah ke server.',
            'data' => $berkas
        ]);
    }
}

