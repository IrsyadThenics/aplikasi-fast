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
}