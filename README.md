# ⚡ Fast-On — Sistem Manajemen Pelayanan Pelanggan PLN

**Fast-On** adalah sistem manajemen pelayanan pelanggan PLN yang terdiri dari dua komponen utama:

- 🌐 **`web_aplikasi`** — Aplikasi web berbasis **Laravel 12** untuk admin, manajer, dan tim internal PLN
- 📱 **`aplikasi_vendor`** — Aplikasi mobile berbasis **Flutter** untuk vendor/teknisi lapangan

Sistem ini digunakan untuk mengelola proses **Pasang Baru (PB)** dan **Perubahan Daya (PD)** pelanggan PLN, mulai dari pengajuan hingga pengoperasian di lapangan, mencakup seluruh wilayah kerja UP3 dan ULP.

---

## 📁 Struktur Proyek

```
Fast-On/
├── web_aplikasi/       # Backend & Frontend Web (Laravel 12)
└── aplikasi_vendor/    # Aplikasi Mobile Vendor (Flutter)
```

---

## 🌐 Web Aplikasi (Laravel 12)

### Tech Stack

| Komponen        | Detail                              |
|-----------------|-------------------------------------|
| Framework       | Laravel 12 (PHP ^8.2)               |
| Database        | PostgreSQL                          |
| Frontend Build  | Vite + Blade Templating             |
| Auth            | Laravel Built-in Auth + Role-based  |
| Excel/CSV       | SimpleXLSX, SimpleXLS               |
| Package Manager | Composer + NPM                      |

### Fitur Utama

- 🔐 **Autentikasi berbasis Role** — Login dengan redirect otomatis sesuai peran pengguna
- 📊 **Dashboard per Role** — Tampilan dashboard yang berbeda-beda sesuai jabatan
- 📋 **Manajemen Data PB/PD** — Data Pasang Baru dan Perubahan Daya pelanggan
- 🔌 **Alur Perluasan Jaringan**:
  - Tanpa Perluasan
  - Perluasan JTM (Jaringan Tegangan Menengah)
  - Perluasan JTR (Jaringan Tegangan Rendah)
- ⚙️ **Pengoperasian** — Pengelolaan proses pengoperasian di lapangan
- 📝 **Survey & Checklist** — Form survey dan checklist teknis
- 📑 **BA Operasi** — Berita Acara pengoperasian
- 🔔 **Notifikasi** — Sistem notifikasi internal
- 💰 **Restitusi** — Pengelolaan data restitusi pelanggan
- 📈 **Laporan** — Laporan status peremajaan dan pembayaran
- 📁 **Upload Data Excel/CSV** — Import massal data pelanggan dari file `.csv`, `.xlsx`, atau `.xls`

### Sistem Role

Sistem mendukung multi-role dengan kontrol akses penuh:

| Role                    | Keterangan                              |
|-------------------------|-----------------------------------------|
| `administrator`         | Admin sistem dengan akses penuh         |
| `managerUP3`            | Manager UP3 (Unit Pelaksana Pelanggan)  |
| `managerULP_Lamongan`            | Manager ULP Lamongan                    |
| `managerULP_babat`      | Manager ULP Babat                       |
| `managerULP_brondong`   | Manager ULP Brondong                    |
| `managerULP_padangan`   | Manager ULP Padangan                    |
| `managerULP_bjn`        | Manager ULP Bojonegoro                  |
| `managerULP_sumberejo`  | Manager ULP Sumberejo                   |
| `managerULP_tuban`      | Manager ULP Tuban                       |
| `managerULP_jatirogo`   | Manager ULP Jatirogo                    |
| `pelayanan`             | Tim Pelayanan Pelanggan                 |
| `konstruksi`            | Tim Konstruksi                          |
| `jaringan`              | Tim Jaringan                            |
| `perencanaan`           | Tim Perencanaan                         |
| `transaksi`             | Tim Transaksi Energi                    |

### Struktur Database (Tabel Utama)

| Tabel                 | Deskripsi                                      |
|-----------------------|------------------------------------------------|
| `users`               | Data pengguna & autentikasi                    |
| `data`                | Data utama PB/PD pelanggan                     |
| `perluasan_j_t_m_s`   | Data perluasan Jaringan Tegangan Menengah       |
| `perluasan_jtrs`      | Data perluasan Jaringan Tegangan Rendah         |
| `tanpa_perluasans`    | Data permohonan tanpa perluasan                |
| `pengoperasians`      | Data pengoperasian                             |
| `proses_perluasans`   | Data proses perluasan                          |
| `laporans`            | Data laporan                                   |
| `restitusis`          | Data restitusi                                 |
| `ba_operasis`         | Data Berita Acara Operasi                      |
| `notifikasis`         | Data notifikasi                                |
| `checklists`          | Data checklist teknis                          |
| `surveys`             | Data hasil survey                              |
| `upload_data`         | Metadata file yang diupload                    |

### Cara Menjalankan Web Aplikasi

#### Prasyarat

- PHP >= 8.2
- Composer
- Node.js & NPM
- PostgreSQL

#### Langkah Instalasi

```bash
# 1. Masuk ke folder web
cd web_aplikasi

# 2. Install dependensi PHP
composer install

# 3. Install dependensi Node
npm install

# 4. Salin file environment
cp .env.example .env

# 5. Generate application key
php artisan key:generate
```

#### Konfigurasi Database (`.env`)

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=isi dengan konfigurasi anda
DB_USERNAME=isi sesuaikan dengan konfigurasi kalian
DB_PASSWORD=isi dengan konfigurasi anda

APP_URL=http://localhost:8000
APP_ENV=local
APP_DEBUG=true
```

#### Migrasi & Jalankan

```bash
# 6. Jalankan migrasi database
php artisan migrate

# 7. Jalankan semua layanan sekaligus (server, queue, logs, vite)
composer run dev
```

> Aplikasi akan berjalan di `http://localhost:8000`

#### Atau jalankan secara terpisah:

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite build (frontend)
npm run dev
```

---

## 📱 Aplikasi Vendor (Flutter)

### Tech Stack

| Komponen   | Detail             |
|------------|--------------------|
| Framework  | Flutter            |
| Bahasa     | Dart               |
| SDK        | Dart ^3.10.7       |
| Versi App  | 1.0.0+1            |

### Cara Menjalankan Aplikasi Flutter

#### Prasyarat

- Flutter SDK terinstal
- Android Studio / VS Code dengan plugin Flutter
- Emulator Android/iOS atau perangkat fisik

#### Langkah Menjalankan

```bash
# 1. Masuk ke folder aplikasi vendor
cd aplikasi_vendor

# 2. Install dependensi Flutter
flutter pub get

# 3. Jalankan aplikasi
flutter run
```

#### Build APK (Android)

```bash
flutter build apk --release
```

---

## 🔄 Alur Sistem

```
Upload Data Excel/CSV
        |
        v
   Data PB/PD Masuk ke Database
        |
        v
   Tim Pelayanan Verifikasi
        |
        |---> Tanpa Perluasan ------------------------------------------------->|
        |                                                                        |
        |---> Perluasan JTM ---> Survey ---> Konstruksi ----------------------->|
        |                                                                        |
        `---> Perluasan JTR ---> Survey ---> Jaringan ------------------------->|
                                                                                 v
                                                                    Pengoperasian / BA Operasi
                                                                                 |
                                                                                 v
                                                                         Laporan & Restitusi
```

---

## 🗂️ Struktur Folder Web Aplikasi

```
web_aplikasi/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php       # Login, logout
│   │   │   ├── DashboardController.php  # Semua halaman dashboard & upload
│   │   │   └── ProfileController.php    # Profil pengguna
│   │   └── Middleware/
│   └── Models/                          # Model Eloquent (data, User, dll.)
├── database/
│   └── migrations/                      # 17 file migrasi database
├── resources/
│   └── views/
│       ├── auth/                        # Halaman login
│       ├── dashboard/                   # View per-role & shared views
│       ├── layouts/                     # Layout utama Blade
│       └── profile/                     # Halaman profil
├── routes/
│   └── web.php                          # Definisi semua rute aplikasi
└── public/                              # Asset publik
```

---

## 📦 Upload Data

Sistem mendukung import data pelanggan secara massal melalui:

- **Format yang didukung:** `.csv`, `.xlsx`, `.xls`
- **Kolom yang dikenali:** `NOAGENDA`, `NAMA`, `ALAMAT`, `TARIF`, `DAYA`, `STATUS`, `ULP`, dll.
- Setiap kali data baru diupload, data lama akan diganti dengan data terbaru.

---

## 🔐 Keamanan

- Semua rute dilindungi middleware `auth`
- Setiap role hanya dapat mengakses prefix route yang sesuai (middleware `role:`)
- Sesi menggunakan driver `database` dengan masa aktif 120 menit
- Password di-hash menggunakan BCrypt (12 rounds)

---

## 👥 Tim Pengembang

> Proyek ini dikembangkan sebagai bagian dari program **magang** di PLN UP3 wilayah Lamongan/Bojonegoro.

---

## 📄 Lisensi

Proyek ini bersifat **privat** dan dikembangkan untuk kebutuhan internal PLN.
