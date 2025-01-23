<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function sellingReport()
    {
        $role = Auth::user()->role; // Ambil role user yang login
        return view("reports.selling", compact("role"));
    }

    public function purchaseReport()
    {
        $role = Auth::user()->role; // Ambil role user yang login
        return view("reports.purchase", compact("role"));
    }

    public function receivableReport()
    {
        $role = Auth::user()->role; // Ambil role user yang login
        return view("reports.receivable", compact("role"));
    }
    public function payableReport()
    {
        $role = Auth::user()->role; // Ambil role user yang login
        return view("reports.payable", compact("role"));
    }
}
