# FAST-ON

FAST-ON adalah aplikasi web internal untuk mengelola proses pelayanan pelanggan PLN, khususnya data **Pasang Baru (PB)** dan **Perubahan Daya (PD)**. Aplikasi ini mendukung alur kerja dari penerimaan data pelanggan, verifikasi dan perencanaan, perluasan jaringan, konstruksi, pengoperasian, sampai pelaporan serta pengiriman pekerjaan kepada vendor.

Repository ini berisi aplikasi web Laravel pada folder `web_aplikasi/`.

## Fitur utama

- Autentikasi login dan pembatasan akses berdasarkan role.
- Dashboard dan menu yang berbeda untuk UP3, ULP, pelayanan, perencanaan, konstruksi, jaringan, transaksi, administrator, dan vendor.
- Pengelolaan data pelanggan PB/PD.
- Pengelolaan pekerjaan tanpa perluasan, perluasan JTM, dan perluasan JTR.
- Survey, checklist konstruksi, BA operasi, pengoperasian, dan pemeriksaan KWh.
- Pengiriman agenda pekerjaan dari internal PLN kepada vendor.
- Pelaporan pekerjaan vendor tiang dan vendor konstruksi, termasuk riwayat serta lampiran berkas.
- Upload data pelanggan secara massal melalui `.csv`, `.xls`, atau `.xlsx`.
- Upload dan pengelolaan dokumen pekerjaan seperti WO, BA Cek, BA Operasi, RAB, dan dokumen terkait.
- Notifikasi, pencarian data, restitusi, dan ekspor laporan ke Excel.
- Modul latihan TOEFL sederhana pada route publik `/toefl`.

## Teknologi

- PHP 8.2 atau lebih baru
- Laravel 12
- Blade dan Vite
- Tailwind CSS 4
- Laravel Sanctum
- Maatwebsite Excel, SimpleXLS, dan SimpleXLSX
- PHPUnit untuk pengujian
- Database relasional yang didukung Laravel; konfigurasi contoh proyek menggunakan SQLite

## Struktur repository

```text
.
├── README.md
└── web_aplikasi/
    ├── app/
    │   ├── Http/Controllers/       # autentikasi, dashboard, vendor, profil
    │   ├── Http/Middleware/        # middleware role dan autentikasi
    │   └── Models/                 # model Eloquent
    ├── config/                     # konfigurasi role dan navigasi
    ├── database/
    │   ├── migrations/             # struktur tabel aplikasi
    │   ├── factories/              # factory untuk pengujian
    │   └── seeders/                # akun dan data awal
    ├── resources/
    │   ├── js/                     # entry point Vite
    │   ├── css/                    # stylesheet
    │   └── views/                  # template Blade
    ├── routes/web.php              # route web dan API internal
    ├── public/                     # asset publik dan entry point Laravel
    ├── tests/                      # unit test dan feature test
    ├── composer.json
    └── package.json
```

## Role dan area akses

| Role | Area atau fungsi |
| --- | --- |
| `administrator` | Administrasi sistem dan akses operasional umum |
| `managerUP3` | Monitoring dan pengelolaan data tingkat UP3 |
| `managerULP` | Manager ULP Lamongan |
| `managerULP_babat` | Manager ULP Babat |
| `managerULP_brondong` | Manager ULP Brondong |
| `managerULP_padangan` | Manager ULP Padangan |
| `managerULP_bjn` | Manager ULP Bojonegoro |
| `managerULP_sumberejo` | Manager ULP Sumberejo |
| `managerULP_tuban` | Manager ULP Tuban |
| `managerULP_jatirogo` | Manager ULP Jatirogo |
| `pelayanan` | Penerimaan, verifikasi, dan upload data PB/PD |
| `perencanaan` | Perencanaan pekerjaan, survey, dan pengiriman agenda |
| `konstruksi` | Proses konstruksi, checklist, dan BA Cek |
| `jaringan` | Perluasan jaringan dan BA Operasi |
| `transaksi` | Transaksi energi dan pemeriksaan KWh |
| `vendor` | Laporan pekerjaan vendor tiang |
| `vendor_konstruksi` | Laporan pekerjaan vendor konstruksi |

Route utama dikelompokkan berdasarkan prefix role, antara lain `/up3`, `/ulp`, `/pelayanan`, `/perencanaan`, `/konstruksi`, `/jaringan`, `/transaksi`, `/admin`, `/vendor-tiang`, dan `/vendor-konstruksi`. Semua area internal memerlukan login.

## Alur proses

```text
Data PB/PD
   │
   ├── Tanpa perluasan ───────────────┐
   ├── Perluasan JTM ── Survey ───────┤
   └── Perluasan JTR ── Survey ───────┤
                                      ▼
                         Perencanaan / Konstruksi
                                      │
                              Pengoperasian
                                      │
                         BA, laporan, dan restitusi
```

Untuk pekerjaan yang melibatkan vendor, agenda dapat dikirim dari aplikasi kepada vendor yang sesuai. Vendor mengirim laporan beserta berkas pendukung, lalu tim internal melanjutkan pemeriksaan dan proses pekerjaan berikutnya.

## Menjalankan aplikasi secara lokal

### Prasyarat

- PHP 8.2+
- Composer
- Node.js dan npm
- Database yang telah dikonfigurasi untuk Laravel

### Instalasi

```bash
cd web_aplikasi
composer install
npm install
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
```

Seeder membuat akun pengembangan untuk role internal dan vendor. Semua akun hasil seeder menggunakan password awal `password`; ganti password tersebut sebelum aplikasi digunakan di lingkungan nyata.

### Menjalankan server pengembangan

Perintah berikut menjalankan server Laravel, worker queue, log viewer, dan Vite secara bersamaan:

```bash
composer run dev
```

Aplikasi dapat dibuka pada `http://localhost:8000`.

Jika ingin menjalankan layanan secara terpisah:

```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```

Untuk membuat asset produksi:

```bash
npm run build
```

## Akun pengembangan hasil seeder

| User ID | Role |
| --- | --- |
| `5180` | Administrator |
| `5180MAN` | Manager UP3 |
| `51803` sampai `51808` | Manager ULP |
| `5180PA` | Pelayanan |
| `5180REN` | Perencanaan |
| `5180KON` | Konstruksi |
| `5180JAR` | Jaringan |
| `5180TEL` | Transaksi |
| `VENDOR_ALPHA`, `VENDOR_BRAVO` | Vendor tiang |
| `VENDOR_KONSTRUKSI`, `VENDOR_KONSTRUKSI_2` | Vendor konstruksi |

Password awal seluruh akun di atas adalah `password` pada data pengembangan yang dibuat oleh seeder.

## Data dan dokumen

Migrasi aplikasi mencakup tabel pengguna, data PB/PD, perluasan JTM/JTR, tanpa perluasan, pengoperasian, proses perluasan, laporan, restitusi, BA operasi, notifikasi, checklist, survey, upload data, pengiriman agenda, dokumen pekerjaan, dan laporan vendor.

File yang diunggah disimpan melalui filesystem Laravel. Jalankan `php artisan storage:link` agar file pada disk publik dapat ditampilkan oleh aplikasi.

## Pengujian

Jalankan seluruh test dengan:

```bash
php artisan test
```

Test feature yang tersedia memeriksa keberadaan route dashboard untuk role yang terdaftar serta route dashboard vendor.

## Catatan pengembangan

- Role dan prefix route didefinisikan pada `web_aplikasi/config/roles.php`.
- Menu per role didefinisikan pada `web_aplikasi/config/navigation.php`.
- Route web dan endpoint internal didefinisikan pada `web_aplikasi/routes/web.php`.
- Perubahan struktur database harus dibuat sebagai migration baru.
- Aplikasi ini ditujukan untuk kebutuhan internal dan sebaiknya tidak menggunakan akun atau password seeder di lingkungan produksi.

## Lisensi

Proyek ini bersifat privat dan dikembangkan untuk kebutuhan internal PLN.
