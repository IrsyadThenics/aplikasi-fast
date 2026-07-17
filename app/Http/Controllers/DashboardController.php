<?php

namespace App\Http\Controllers;

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

        return view("dashboard.{$folder}");
    }

    // ------------------------------------------
    // SUB-MENU DINAMIS UNTUK SEMUA ROLE
    // ------------------------------------------

    public function dataPbpd()
    {
        return view('dashboard.' . $this->getViewFolder() . '.data_pbpd');
    }

    public function prosesPerluasan()
    {
        return view('dashboard.' . $this->getViewFolder() . '.proses_perluasan');
    }

    public function restitusi()
    {
        return view('dashboard.' . $this->getViewFolder() . '.restitusi');
    }

    public function laporan()
    {
        return view('dashboard.' . $this->getViewFolder() . '.laporan');
    }

    public function notifikasi()
    {
        return view('dashboard.' . $this->getViewFolder() . '.notifikasi');
    }
}