<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'user_id' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $role = Auth::user()->role;

            // Map role ke prefix route yang sesuai di web.php
            $routePrefixes = [
                'managerUP3'    => 'up3',
                'managerULP'    => 'ulp',
                'administrator' => 'admin',
                'pelayanan'     => 'pelayanan',
                'konstruksi'    => 'konstruksi',
                'jaringan'      => 'jaringan',
                'perencanaan'   => 'perencanaan',
                'transaksi'     => 'transaksi',
            ];

            if (array_key_exists($role, $routePrefixes)) {
                return redirect()->route($routePrefixes[$role] . '.dashboard');
            }

            return redirect()->route('dashboard'); // Fallback ke rute default
        }

        return back()->withErrors([
            'user_id' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); // Diarahkan kembali ke halaman login utama
    }
}
