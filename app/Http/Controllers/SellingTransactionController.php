<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use App\Models\SellingTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Abort;
use Barryvdh\DomPDF\Facade\Pdf;

class SellingTransactionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $storeId = $request->query("store_id");

        // Determine stores and products based on user role
        if ($user->isStaff()) {
            $staff = $user->staff; // Get the staff record
            if (!$staff) {
                abort(403, "Staff not found."); // Handle case where staff record is missing
            }
            $stores = Store::where("id", $staff->store_id)->get(); // Staff only sees their store
            $products = Product::where("store_id", $staff->store_id)->get(); // Products from their store
        } elseif ($user->isOwner()) {
            $stores = $user->stores()->get(); // Owner sees all their stores
            $products = Product::where("store_id", $stores->first()->id)->get(); // Products from their FIRST store (you might want to adjust this logic)
        } else {
            // Admin sees all stores and products
            $stores = Store::all();
            $products = Product::all();
        }

        $role = $user->role; //pass role to view

        return view(
            "transaction-selling.index",
            compact("stores", "products", "role", "storeId")
        );
    }

    public function show(SellingTransaction $sellingTransaction)
    {
        $details = $sellingTransaction->sellingDetailTransactions()->get();
        return view(
            "transaction-selling.show",
            compact("sellingTransaction", "details")
        );
    }

    public function printPdf(SellingTransaction $sellingTransaction)
    {
        $details = $sellingTransaction->sellingDetailTransactions()->get();
        $data = [
            "transaction" => $sellingTransaction,
            "details" => $details,
            "type" => "selling",
            "tanggal" => $sellingTransaction->created_at->format("Y-m-d H:i:s"),
        ];

        $pdf = Pdf::loadView("livewire.struk-pdf", $data); // Menggunakan Dompdf
        return $pdf->download(
            "struk-penjualan-" . $sellingTransaction->id . ".pdf"
        );
    }
}
