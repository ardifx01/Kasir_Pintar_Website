<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function home()
    {
        $role = Auth::user()->role;

        return view("dashboard.home", compact("role"));
    }

    public function product()
    {
        $role = Auth::user()->role;
        return view("dashboard.manajemen-product", compact("role"));
    }
    public function transaction()
    {
        $role = Auth::user()->role;
        return view("dashboard.manajemen-transaction", compact("role"));
    }
    public function manajemenPelanggan()
    {
        $role = Auth::user()->role;
        return view("dashboard.manajemen-pelanggan", compact("role"));
    }
    public function manajemenToko()
    {
        $role = Auth::user()->role;
        return view("dashboard.manajemen-toko", compact("role"));
    }

    public function setting()
    {
        $role = Auth::user()->role;
        return view("dashboard.setting", compact("role"));
    }
    public function manajemenUser()
    {
        $role = Auth::user()->role;
        return view("dashboard.manajemen-user", compact("role"));
    }

    public function laporan()
    {
        $role = Auth::user()->role;
        return view("dashboard.manajemen-laporan-transaction", compact("role"));
    }

    public function laporanMasalah()
    {
        $role = Auth::user()->role;
        return view("dashboard.laporan-masalah", compact("role"));
    }
}
