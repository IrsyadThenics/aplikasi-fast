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

        return array_flip(config('roles.routes', []))[$role] ?? $role;
    }

    private function getUlpMap(): array
    {
        return config('roles.ulp_names', []);
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

        $ulpMap = $this->getUlpMap();
        $ulpFilter = $ulpMap[$role] ?? null;

        if ($ulpFilter !== null) {
            $query->whereRaw('LOWER(ulp) LIKE ?', ['%' . strtolower($ulpFilter) . '%']);
        }

        // Data yang telah dikirim oleh ULP dipindahkan ke History dan tidak lagi
        // ditampilkan pada Data PB/PD, termasuk setelah halaman dimuat ulang.
        if (str_starts_with($role, 'managerULP')) {
            $query->whereNotExists(function ($sent) use ($ulpFilter) {
                $sent->selectRaw('1')
                    ->from('pengiriman_data')
                    ->whereNotNull('pengiriman_data.sentAt')
                    ->whereColumn('pengiriman_data.no_agenda', 'data.no_agenda')
                    ->whereColumn('pengiriman_data.nama', 'data.nama')
                    ->whereColumn('pengiriman_data.alamat', 'data.alamat');

                if ($ulpFilter !== null) {
                    $sent->whereRaw('LOWER(pengiriman_data.ulp) LIKE ?', ['%' . strtolower($ulpFilter) . '%']);
                }
            });
        }

        // Tampilkan data dengan berbagai status yang relevan
        $query->where(function ($q) {
            $q->whereRaw('LOWER(status) LIKE ?', ['%cetak pk%'])
              ->orWhereRaw('LOWER(status) LIKE ?', ['%pengesahan pdl%'])
              ->orWhereRaw('LOWER(status) LIKE ?', ['%pdl awal%'])
              ->orWhereRaw('LOWER(status) LIKE ?', ['%mohon%'])
              ->orWhereRaw('LOWER(status) LIKE ?', ['%bayar%'])
              ->orWhereRaw('LOWER(status) LIKE ?', ['%peremajaan%'])
              ->orWhereRaw('LOWER(status) LIKE ?', ['%perluasan%']);
        });

        $data = $query->get();

        if (str_starts_with($role, 'managerULP') || $role === 'perencanaan') {
            $rabByAgenda = \App\Models\PengirimanData::query()
                ->whereIn('no_agenda', $data->pluck('no_agenda')->filter()->unique())
                ->get(['no_agenda', 'total_biaya', 'rab'])
                ->keyBy('no_agenda');

            foreach ($data as $item) {
                $pengiriman = $rabByAgenda->get($item->no_agenda);
                if ($pengiriman && $pengiriman->total_biaya !== null) {
                    $item->setAttribute('total_biaya', $pengiriman->total_biaya);
                }
                if ($pengiriman && $pengiriman->rab !== null) {
                    $item->setAttribute('rab', $pengiriman->rab);
                }
            }
        }

        return $data;
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

        // Role Pelayanan memiliki kolom tambahan untuk tanggal dan durasi kerja.
        if ($role === 'pelayanan') {
            return view('dashboard.pelayanan.data_pbpd', compact('data'));
        }

        // Semua role lain: pakai shared view yang otomatis menyembunyikan
        // data yang sudah dikirim ke JTM/JTR/Tanpa Perluasan via IndexedDB
        return view('dashboard.shared.data_pbpd', compact('data'));
    }

    public function tanpaPerluasan()
    {
        return $this->renderExpansionPage('tanpa_perluasan', 'tanpa_perluasan');
    }

    public function perluasanJtm()
    {
        return $this->renderExpansionPage('jtm', 'perluasan_jtm');
    }

    public function perluasanJtr()
    {
        return $this->renderExpansionPage('jtr', 'perluasan_jtr');
    }

    private function renderExpansionPage(string $destination, string $viewName)
    {
        $data = $this->getFilteredData();
        $agendas = \App\Models\PengirimanData::where('dest', $destination)->pluck('no_agenda');
        $vendorReports = $this->getVendorReportsForCurrentRole($agendas);
        $vendorKonstruksiUsers = \App\Models\User::where('role', 'vendor_konstruksi')
            ->orderBy('name')
            ->get(['id', 'name', 'user_id']);

        return view("dashboard.shared.{$viewName}", compact('data', 'vendorReports', 'vendorKonstruksiUsers'));
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

    private function getLaporanData(string $role)
    {
        $query = \App\Models\data::query();

        $ulpMap = $this->getUlpMap();
        $ulpFilter = $ulpMap[$role] ?? null;

        if ($ulpFilter !== null) {
            $query->whereRaw('LOWER(ulp) LIKE ?', ['%' . strtolower($ulpFilter) . '%']);
        }

        // Laporan hanya berisi data yang sudah dikirim/diproses oleh ULP.
        // Role ULP tetap dibatasi ke ULP-nya sendiri, sedangkan role UP3
        // tidak diberi filter ULP sehingga dapat melihat seluruh ULP.
        $query->whereExists(function ($processed) use ($ulpFilter) {
            $processed->selectRaw('1')
                ->from('pengiriman_data')
                ->whereNotNull('pengiriman_data.sentAt')
                ->whereColumn('pengiriman_data.no_agenda', 'data.no_agenda')
                ->whereColumn('pengiriman_data.nama', 'data.nama')
                ->whereColumn('pengiriman_data.alamat', 'data.alamat')
                ->whereColumn('pengiriman_data.ulp', 'data.ulp');

            if ($ulpFilter !== null) {
                $processed->whereRaw('LOWER(pengiriman_data.ulp) LIKE ?', ['%' . strtolower($ulpFilter) . '%']);
            }
        });

        $query->where(function ($statusQuery) {
            $statusQuery->whereRaw('LOWER(status) LIKE ?', ['%cetak pk%'])
                ->orWhereRaw('LOWER(status) LIKE ?', ['%bayar%'])
                ->orWhereRaw('LOWER(status) LIKE ?', ['%pengesahan pdl%'])
                ->orWhereRaw('LOWER(status) LIKE ?', ['%peremajaan%'])
                ->orWhereRaw('LOWER(status) LIKE ?', ['%perluasan%']);
        });

        $data = $query->get();

        $pengirimanQuery = \App\Models\PengirimanData::query()
            ->whereIn('no_agenda', $data->pluck('no_agenda')->filter()->unique())
            ->whereNotNull('sentAt')
            ->orderByDesc('sentAt')
            ->orderByDesc('id')
            ->with('berkas:id,pengiriman_id,jenis_berkas,created_at');

        if ($ulpFilter !== null) {
            $pengirimanQuery->whereRaw('LOWER(ulp) LIKE ?', ['%' . strtolower($ulpFilter) . '%']);
        }

        $pengirimanByAgenda = $pengirimanQuery
            ->get(['id', 'no_agenda', 'ulp', 'sentAt', 'total_biaya', 'rab', 'detail_perluasan'])
            ->unique(fn ($item) => $item->no_agenda . '|' . $item->ulp)
            ->keyBy(fn ($item) => $item->no_agenda . '|' . $item->ulp);

        foreach ($data as $item) {
            $pengirimanKey = $item->no_agenda . '|' . $item->ulp;
            $details = $pengirimanByAgenda->get($pengirimanKey)?->detail_perluasan ?? [];
            $item->setAttribute('combo_tiang', $details['combo_tiang'] ?? null);
            $item->setAttribute('jumlah_tiang', $details['jumlah_tiang'] ?? null);
            $item->setAttribute('jumlah_konduktor', $details['jumlah_konduktor'] ?? null);
            $item->setAttribute('combo_trafo', $details['combo_trafo'] ?? null);
            $item->setAttribute('jumlah_trafo', $details['jumlah_trafo'] ?? null);
            $item->setAttribute('jumlah_kwh', $details['jumlah_kwh'] ?? null);
            $pengiriman = $pengirimanByAgenda->get($pengirimanKey);
            $berkas = $pengiriman?->berkas ?? collect();
            $item->setAttribute('checklist_pengiriman_ulp', $pengiriman?->sentAt);
            if ($pengiriman && $pengiriman->total_biaya !== null) {
                $item->setAttribute('total_biaya', $pengiriman->total_biaya);
            }
            if ($pengiriman && $pengiriman->rab !== null) {
                $item->setAttribute('rab', $pengiriman->rab);
            }
            $item->setAttribute('checklist_wo_perencanaan', $berkas->firstWhere('jenis_berkas', 'wo_perencanaan'));
            $item->setAttribute('checklist_ba_cek', $berkas->firstWhere('jenis_berkas', 'ba_cek'));
            $item->setAttribute('checklist_ba_acara', $berkas->firstWhere('jenis_berkas', 'dokumen'));
            $item->setAttribute('checklist_ba_operasi', $berkas->firstWhere('jenis_berkas', 'ba_operasi'));
            $item->setAttribute('idpel_laporan', $item->idpel);
            $item->setAttribute('ulp_asal', $item->ulp);
        }

        return $data;
    }

    public function laporan()
    {
        $role = Auth::user()->role;
        $data = $this->getLaporanData($role);
        $laporanView = 'dashboard.' . $this->getViewFolder() . '.laporan';

        // Role operasional menggunakan satu template laporan yang sama.
        // Template khusus ULP, UP3, dan transaksi tetap dipertahankan.
        if (in_array($role, ['administrator', 'jaringan', 'konstruksi', 'pelayanan', 'perencanaan'], true)) {
            $laporanView = 'dashboard.administrator.laporan';
        }

        // Folder ULP per wilayah hanya menyimpan data_pbpd saat ini.
        // Gunakan template laporan ULP utama agar semua ULP memiliki menu laporan.
        if (!view()->exists($laporanView)) {
            $laporanView = 'dashboard.ulp.laporan';
        }

        $exportRoute = $this->getViewFolder() . '.laporan.export';

        return view($laporanView, compact('data', 'exportRoute'));
    }

    public function exportLaporan()
    {
        $role = Auth::user()->role;
        abort_unless(in_array($role, array_values(config('roles.routes', [])), true), 403);

        $data = $this->getLaporanData($role);
        $headers = [
            'No.', 'Tanggal Mohon', 'Nama Pelanggan', 'IDPEL', 'ULP Asal', 'Jenis Transaksi', 'Status',
            'Tarif Lama', 'Daya Lama', 'Tarif Baru', 'Daya Baru', 'Jumlah Tiang - Combo', 'Jumlah Tiang - Buah', 'Jumlah Konduktor',
            'Jumlah Trafo - Combo', 'Jumlah Trafo - Buah', 'Jumlah kWh Meter', 'Total Biaya (BP)', 'RAB', 'Tanggal Bayar', 'Durasi Hari Kerja',
        ];

        $rows = [$headers];
        foreach ($data as $index => $item) {
            $rows[] = [
                $index + 1,
                $item->tanggal_ulp,
                $item->nama,
                $item->idpel_laporan,
                $item->ulp,
                $item->transaksi,
                $item->status,
                $item->tarif_lama,
                $item->daya_lama,
                $item->tarif_baru,
                $item->daya_baru,
                $item->combo_tiang,
                $item->jumlah_tiang,
                $item->jumlah_konduktor,
                $item->combo_trafo,
                $item->jumlah_trafo,
                $item->jumlah_kwh,
                $item->total_biaya,
                $item->rab,
                $item->tanggal_bayar,
                $item->durasi_hari_kerja,
            ];
        }

        $columnName = static function (int $number): string {
            $name = '';
            while ($number > 0) {
                $number--;
                $name = chr(65 + ($number % 26)) . $name;
                $number = intdiv($number, 26);
            }
            return $name;
        };
        $escapeXml = static fn ($value): string => htmlspecialchars((string) ($value ?? ''), ENT_XML1 | ENT_QUOTES, 'UTF-8');
        $sheetRows = '';
        foreach ($rows as $rowIndex => $row) {
            $excelRow = $rowIndex + 1;
            $cells = '';
            foreach ($row as $columnIndex => $value) {
                $reference = $columnName($columnIndex + 1) . $excelRow;
                $cells .= '<c r="' . $reference . '" t="inlineStr"><is><t xml:space="preserve">' . $escapeXml($value) . '</t></is></c>';
            }
            $sheetRows .= '<row r="' . $excelRow . '">' . $cells . '</row>';
        }

        $temporaryFile = tempnam(sys_get_temp_dir(), 'up3-laporan-');
        $workbook = new \ZipArchive();
        if ($temporaryFile === false || $workbook->open($temporaryFile, \ZipArchive::OVERWRITE) !== true) {
            abort(500, 'File Excel tidak dapat dibuat.');
        }

        $workbook->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/></Types>');
        $workbook->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>');
        $workbook->addFromString('xl/workbook.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="Laporan UP3" sheetId="1" r:id="rId1"/></sheets></workbook>');
        $workbook->addFromString('xl/_rels/workbook.xml.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/></Relationships>');
        $workbook->addFromString('xl/worksheets/sheet1.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>' . $sheetRows . '</sheetData></worksheet>');
        $workbook->close();

        $filename = 'laporan-' . strtolower($this->getViewFolder()) . '-' . now()->format('Y-m-d') . '.xlsx';

        return response()->download($temporaryFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
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
        abort_unless(str_starts_with($role, 'managerULP'), 403);

        $ulpMap = $this->getUlpMap();

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
        $ulpMap = $this->getUlpMap();
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

            // ============================================================
            // LANGKAH 1: Parse file terlebih dahulu, kumpulkan semua baris
            // ============================================================
            $parsedRows = [];
            $extension = strtolower($file->getClientOriginalExtension());

            if ($extension === 'csv') {
                if (($handle = fopen($file->getRealPath(), 'r')) !== FALSE) {
                    $header = fgetcsv($handle, 1000, ',');

                    if (count($header) == 1 && strpos($header[0], ';') !== false) {
                        fclose($handle);
                        $handle = fopen($file->getRealPath(), 'r');
                        $header = fgetcsv($handle, 1000, ';');
                        $separator = ';';
                    } else {
                        $separator = ',';
                    }

                    $map = [];
                    foreach ($header as $idx => $h) {
                        $map[strtoupper(trim($h))] = $idx;
                    }

                    $idx_no_agenda      = $map['NOAGENDA'] ?? $map['NO AGENDA'] ?? $map['NOMOR AGENDA'] ?? null;
                    $idx_nama           = $map['NAMA'] ?? $map['NAMA PELANGGAN'] ?? $map['NAMA_PELANGGAN'] ?? null;
                    $idx_idpel          = $map['IDPEL'] ?? $map['ID PELANGGAN'] ?? $map['ID_PELANGGAN'] ?? null;
                    $idx_alamat         = $map['ALAMAT'] ?? $map['ALAMAT PELANGGAN'] ?? $idx_idpel;
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
                            $parsedRows[] = [
                                'dtl'              => 'Ada',
                                'ulp'              => $row[$idx_ulp] ?? null,
                                'nama'             => $idx_nama !== null ? ($row[$idx_nama] ?? null) : null,
                                'tanggal_ulp'      => $idx_tanggal_ulp !== null ? ($row[$idx_tanggal_ulp] ?? null) : null,
                                'transaksi'        => $row[$idx_transaksi] ?? 'Pasang Baru',
                                'status'           => $row[$idx_status] ?? 'Mohon',
                                'no_agenda'        => $agenda,
                                'alamat'           => $row[$idx_alamat] ?? '',
                                'idpel'            => $idx_idpel !== null ? ($row[$idx_idpel] ?? null) : null,
                                'tarif_lama'       => $row[$idx_tarif_lama] ?? null,
                                'daya_lama'        => isset($row[$idx_daya_lama]) && is_numeric($row[$idx_daya_lama]) ? intval($row[$idx_daya_lama]) : 0,
                                'tarif_baru'       => $row[$idx_tarif_baru] ?? null,
                                'daya_baru'        => isset($row[$idx_daya_baru]) && is_numeric($row[$idx_daya_baru]) ? intval($row[$idx_daya_baru]) : 0,
                                'total_biaya'      => $idx_total_biaya !== null ? ($row[$idx_total_biaya] ?? null) : null,
                                'tanggal_bayar'    => $idx_tanggal_bayar !== null ? ($row[$idx_tanggal_bayar] ?? null) : null,
                                'durasi_hari_kerja'=> $idx_durasi_hk !== null ? ($row[$idx_durasi_hk] ?? null) : null,
                            ];
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

                    $map = [];
                    foreach ($headers as $idx => $h) {
                        $h_clean = strtoupper(trim($h));
                        $map[$h_clean] = $idx;
                    }

                    $idx_no_agenda      = $map['NOAGENDA'] ?? $map['NO AGENDA'] ?? $map['NOMOR AGENDA'] ?? null;
                    $idx_nama           = $map['NAMA'] ?? $map['NAMA PELANGGAN'] ?? $map['NAMA_PELANGGAN'] ?? null;
                    $idx_idpel          = $map['IDPEL'] ?? $map['ID PELANGGAN'] ?? $map['ID_PELANGGAN'] ?? null;
                    $idx_alamat         = $map['ALAMAT'] ?? $map['ALAMAT PELANGGAN'] ?? $idx_idpel;
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
                            $parsedRows[] = [
                                'dtl'               => 'Ada',
                                'ulp'               => $row[$idx_ulp] ?? null,
                                'nama'              => $row[$idx_nama] ?? null,
                                'tanggal_ulp'       => $row[$idx_tanggal_ulp] ?? null,
                                'transaksi'         => $row[$idx_transaksi] ?? 'Pasang Baru',
                                'status'            => $row[$idx_status] ?? 'Mohon',
                                'no_agenda'         => $agenda,
                                'alamat'            => $row[$idx_alamat] ?? '',
                                'idpel'             => $idx_idpel !== null ? ($row[$idx_idpel] ?? null) : null,
                                'tarif_lama'        => $row[$idx_tarif_lama] ?? null,
                                'daya_lama'         => isset($row[$idx_daya_lama]) && is_numeric($row[$idx_daya_lama]) ? intval($row[$idx_daya_lama]) : 0,
                                'tarif_baru'        => $row[$idx_tarif_baru] ?? null,
                                'daya_baru'         => isset($row[$idx_daya_baru]) && is_numeric($row[$idx_daya_baru]) ? intval($row[$idx_daya_baru]) : 0,
                                'total_biaya'       => $row[$idx_total_biaya] ?? null,
                                'tanggal_bayar'     => $row[$idx_tanggal_bayar] ?? null,
                                'durasi_hari_kerja' => $row[$idx_durasi_hk] ?? null,
                            ];
                        }
                    }
                } else {
                    $err = $extension === 'xlsx' ? \Shuchkin\SimpleXLSX::parseError() : \Shuchkin\SimpleXLS::parseError();
                    return back()->with('error', 'Gagal membaca file Excel: ' . $err);
                }
            } else {
                return back()->with('error', 'Format file tidak didukung. Harap unggah file CSV, XLSX, atau XLS.');
            }

            // ============================================================
            // LANGKAH 2: Hapus hanya data ULP yang ada di file yang diupload
            // Sehingga data ULP lain tetap aman dan tidak hilang
            // ============================================================
            if (!empty($parsedRows)) {
                // Kumpulkan semua nama ULP unik dari file yang diupload
                $ulpDariFile = collect($parsedRows)
                    ->pluck('ulp')
                    ->filter()              // buang null
                    ->map(fn($v) => strtolower(trim($v)))
                    ->unique()
                    ->values()
                    ->toArray();

                if (!empty($ulpDariFile)) {
                    // Hapus data lama HANYA untuk ULP yang ada di file upload
                    $deleteQuery = \App\Models\data::where(function ($q) use ($ulpDariFile) {
                        foreach ($ulpDariFile as $ulpName) {
                            $q->orWhereRaw('LOWER(TRIM(ulp)) = ?', [$ulpName]);
                        }
                    });
                    $deleteQuery->delete();
                } else {
                    // Jika kolom ULP kosong semua, hapus semua data (fallback)
                    \App\Models\data::truncate();
                }

                // ============================================================
                // LANGKAH 3: Insert data baru dari file
                // ============================================================
                foreach ($parsedRows as $rowData) {
                    \App\Models\data::create($rowData);
                }
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
            'detail_perluasan' => 'nullable|array',
            'detail_perluasan.combo_tiang' => 'nullable|string|max:50',
            'detail_perluasan.jumlah_tiang' => 'nullable|string|max:255',
            'detail_perluasan.jumlah_konduktor' => 'nullable|string|max:255',
            'detail_perluasan.combo_trafo' => 'nullable|string|max:50',
            'detail_perluasan.jumlah_trafo' => 'nullable|string|max:255',
            'detail_perluasan.jumlah_kwh' => 'nullable|string|max:255',
        ]);

        // Akun ULP hanya boleh mengirim data dari ULP-nya sendiri.
        // Validasi ini tetap diperlukan karena payload API berasal dari browser.
        $role = Auth::user()->role;
        $ulpMap = $this->getUlpMap();
        if (array_key_exists($role, $ulpMap)) {
            $source = \App\Models\data::query()
                ->where('no_agenda', $validated['no_agenda'])
                ->whereRaw('LOWER(ulp) LIKE ?', ['%' . strtolower($ulpMap[$role]) . '%'])
                ->first();

            abort_unless($source, 403, 'Data bukan milik ULP Anda.');
            $validated['ulp'] = $source->ulp;
        }

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
        $ulpMap = $this->getUlpMap();

        if (array_key_exists($role, $ulpMap)) {
            $query->whereRaw('LOWER(ulp) LIKE ?', ['%' . strtolower($ulpMap[$role]) . '%']);
        }

        $items = $query->get();

        // Pengiriman lama belum memiliki kolom idpel. Ambil IDPEL dari data
        // sumber berdasarkan identitas pelanggan dan ULP yang sama.
        foreach ($items as $item) {
            $source = \App\Models\data::query()
                ->where('no_agenda', $item->no_agenda)
                ->where('nama', $item->nama)
                ->where('alamat', $item->alamat)
                ->where('ulp', $item->ulp)
                ->first(['idpel']);
            $item->setAttribute('idpel', $source?->idpel);
        }

        return response()->json($items);
    }

    private function getVendorReportsForCurrentRole($agendas)
    {
        $query = \App\Models\VendorReport::with('files')->whereIn('no_agenda', $agendas);
        if (Auth::user()->role === 'konstruksi') {
            $query->where('recipient_role', 'konstruksi');
        } else {
            $query->where(function ($reportQuery) {
                $reportQuery->where('recipient_role', 'perencanaan')->orWhereNull('recipient_role');
            });
        }
        return $query->latest()->get();
    }

    public function apiSimpanRab(Request $request)
    {
          $role = Auth::user()->role;
          $request->validate([
              'agendaKey' => 'required|string',
              'rab' => 'required|numeric',
              'jenis' => 'nullable|in:bp,rab',
          ]);
          $jenis = $request->input('jenis', 'rab');
          abort_unless(
              str_starts_with($role, 'managerULP') ||
              ($role === 'perencanaan' && $jenis === 'rab'),
              403
          );

          $query = str_starts_with($role, 'managerULP')
              ? $this->getUlpPengirimanQuery()->where('agendaKey', $request->agendaKey)
              : \App\Models\PengirimanData::where('agendaKey', $request->agendaKey);
        $pengiriman = $query->first();
        if (!$pengiriman) {
            $source = \App\Models\data::where('no_agenda', $request->agendaKey)->first();
            if ($source) {
                $pengiriman = \App\Models\PengirimanData::create([
                    'agendaKey' => $request->agendaKey,
                    'dest' => 'tanpa_perluasan',
                    'no_agenda' => $source->no_agenda,
                    'nama' => $source->nama,
                    'alamat' => $source->alamat,
                    'transaksi' => $source->transaksi,
                    'status' => $source->status,
                    'tarif_lama' => $source->tarif_lama,
                    'daya_lama' => $source->daya_lama ?? 0,
                    'tarif_baru' => $source->tarif_baru,
                    'daya_baru' => $source->daya_baru ?? 0,
                    'ulp' => $source->ulp,
                ]);
            }
          }
          if ($pengiriman) {
              $column = $jenis === 'bp' ? 'total_biaya' : 'rab';
            $pengiriman->update([$column => $request->rab]);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
    }

    public function apiSimpanDetailPerluasan(Request $request)
    {
        $role = Auth::user()->role;
        abort_unless(str_starts_with($role, 'managerULP'), 403);

        $validated = $request->validate([
            'agendaKey' => 'required|string',
            'dest' => 'required|in:jtm,jtr',
            'detail_perluasan' => 'required|array',
            'detail_perluasan.combo_tiang' => 'nullable|string|max:50',
            'detail_perluasan.jumlah_tiang' => 'nullable|string|max:255',
            'detail_perluasan.jumlah_konduktor' => 'nullable|string|max:255',
            'detail_perluasan.combo_trafo' => 'nullable|string|max:50',
            'detail_perluasan.jumlah_trafo' => 'nullable|string|max:255',
            'detail_perluasan.jumlah_kwh' => 'nullable|string|max:255',
        ]);

        $pengiriman = $this->getUlpPengirimanQuery()
            ->where('agendaKey', $validated['agendaKey'])
            ->where('dest', $validated['dest'])
            ->first();

        if (!$pengiriman) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan atau bukan kiriman ULP ini.'], 404);
        }

        $pengiriman->update(['detail_perluasan' => $validated['detail_perluasan']]);
        return response()->json(['success' => true, 'detail_perluasan' => $pengiriman->detail_perluasan]);
    }

    public function apiKirimVendor(Request $request)
    {
        abort_unless(Auth::user()->role !== 'pelayanan', 403);

        $request->validate([
            'agendaKey' => 'nullable|string',
            'no_agenda' => 'nullable|string',
            'vendor_pt' => 'nullable|string',
            'vendor_status_layak' => 'nullable|string',
            'file_kelayakan' => 'nullable|file',
            'file_wo_tiang' => 'nullable|file',
            'vendor_konstruksi_user_id' => 'nullable|integer',
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

        $isKonstruksi = Auth::user()->role === 'konstruksi';
        if ($isKonstruksi) {
            $request->validate(['vendor_konstruksi_user_id' => ['required', 'exists:users,id']]);
            abort_unless(\App\Models\User::whereKey($request->vendor_konstruksi_user_id)->where('role', 'vendor_konstruksi')->exists(), 422);
        }
        $updateData = $isKonstruksi
            ? ['konstruksi_vendor_sent' => true, 'konstruksi_vendor_sent_at' => now(), 'konstruksi_vendor_user_id' => $request->vendor_konstruksi_user_id]
            : ['vendor_sent' => true, 'vendor_sent_at' => now()];
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
        abort_unless(Auth::user()->role !== 'pelayanan', 403);

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
        $jenis = $request->input('jenis_berkas', 'dokumen');

        if ($jenis === 'ba_cek' && Auth::user()->role !== 'konstruksi') {
            return response()->json([
                'success' => false,
                'message' => 'Berkas BA Cek hanya dapat diunggah oleh role Konstruksi.',
            ], 403);
        }

        if ($jenis === 'ba_operasi' && Auth::user()->role !== 'jaringan') {
            return response()->json([
                'success' => false,
                'message' => 'Berkas BA Operasi hanya dapat diunggah oleh role Jaringan.',
            ], 403);
        }

        if ($jenis === 'dokumen' && Auth::user()->role !== 'transaksi') {
            return response()->json([
                'success' => false,
                'message' => 'BA Acara hanya dapat diunggah oleh role Transaksi.',
            ], 403);
        }

        if ($jenis === 'wo_perencanaan' && Auth::user()->role !== 'perencanaan') {
            return response()->json([
                'success' => false,
                'message' => 'Berkas WO hanya dapat diunggah oleh role Perencanaan.',
            ], 403);
        }

        if (in_array($jenis, ['wo', 'wo_perencanaan', 'dokumen'], true)
            && !\App\Models\VendorReport::where('no_agenda', $pengiriman->no_agenda)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Unggah WO atau dokumen hanya dapat dilakukan setelah laporan vendor masuk.',
            ], 422);
        }

        $path = $file->store('uploads', 'public');

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

    public function apiHapusBaCek(\App\Models\BerkasDokumen $berkas)
    {
        abort_unless(Auth::user()->role === 'konstruksi' && $berkas->jenis_berkas === 'ba_cek', 403);

        \Illuminate\Support\Facades\Storage::disk('public')->delete($berkas->path_file);
        $berkas->delete();

        return response()->json(['success' => true, 'message' => 'Berkas BA Cek berhasil dihapus.']);
    }

    public function apiHapusBaOperasi(\App\Models\BerkasDokumen $berkas)
    {
        abort_unless(Auth::user()->role === 'jaringan' && $berkas->jenis_berkas === 'ba_operasi', 403);

        \Illuminate\Support\Facades\Storage::disk('public')->delete($berkas->path_file);
        $berkas->delete();

        return response()->json(['success' => true, 'message' => 'Berkas BA Operasi berhasil dihapus.']);
    }
}
