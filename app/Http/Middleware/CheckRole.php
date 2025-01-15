<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CheckRole
{
    public function handle(Request $request, Closure $next, ?string $role)
    {
        if (!Auth::check()) {
            // Redirect jika pengguna belum login
            return Redirect::route("login");
        }

        $userRole = Auth::user()->role; // Ambil role pengguna

        if ($role === null || $userRole === $role) {
            return $next($request); // Akses diizinkan jika role cocok atau tidak ada role yang ditentukan
        }

        //Menangani jika role tidak sesuai
        if (config("app.debug") == true) {
            abort(403, "Anda tidak memiliki izin untuk mengakses halaman ini."); // Untuk debugging
        } else {
            return Redirect::route("dashboard")->with(
                "error",
                "Anda tidak memiliki izin untuk mengakses halaman ini."
            ); //Redirect ke dashboard dengan pesan error
        }
    }
}
