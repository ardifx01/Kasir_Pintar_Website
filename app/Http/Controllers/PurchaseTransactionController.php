<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use App\Models\PurchaseTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Abort;
use Barryvdh\DomPDF\Facade\Pdf;

class PurchaseTransactionController extends Controller
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

        $role = $user->role;
        return view(
            "transaction-purchase.index",
            compact("stores", "products", "role", "storeId")
        );
    }

    public function show(PurchaseTransaction $purchaseTransaction)
    {
        $details = $purchaseTransaction->purchaseDetailTransactions()->get();
        return view(
            "transaction-purchase.show",
            compact("purchaseTransaction", "details")
        );
    }

    public function printPdf(PurchaseTransaction $purchaseTransaction)
    {
        $details = $purchaseTransaction->purchaseDetailTransactions()->get();
        $data = [
            "transaction" => $purchaseTransaction,
            "details" => $details,
            "type" => "purchasing",
            "tanggal" => $purchaseTransaction->created_at->format(
                "Y-m-d H:i:s"
            ),
        ];

        $pdf = Pdf::loadView("livewire.struk-pdf", $data); // Menggunakan Dompdf
        return $pdf->download(
            "struk-pembelian-" . $purchaseTransaction->id . ".pdf"
        );
    }
}
