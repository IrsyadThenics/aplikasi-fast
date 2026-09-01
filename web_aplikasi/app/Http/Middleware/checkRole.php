<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class checkRole
{
    /**
     * Handle an incoming request.
     *
     * /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            // UBAH BAGIAN INI MENJADI 'auth.login'
            return redirect()->route('auth.login'); 
        }

        $user = Auth::user();

        // 2. Cek apakah role user ada di dalam daftar role yang diizinkan rute
        if (!in_array($user->role, $roles)) {
            abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
        }

        // 3. Lolos pemeriksaan, izinkan masuk ke halaman
        return $next($request);
    }
}
