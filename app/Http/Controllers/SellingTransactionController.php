<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Abort;

class SellingTransactionController extends Controller
{
    private function getAccessibleStores(): \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return Store::all();
        } elseif ($user->isStaff()) {
            $staff = $user->staff()->first();
            return $staff ? collect([$staff->store]) : collect([]);
        } elseif ($user->isOwner()) {
            return $user->stores()->get();
        }
        return collect([]);
    }

    public function index()
    {
        $user = Auth::user();
        $role = $user->role;
        $stores = $this->getAccessibleStores();

        if ($user->isOwner()) {
            $products = ($storeId = $user->stores()->first()?->id)
                ? Product::where("store_id", $storeId)
                    ->with("categoryProduct", "store")
                    ->get()
                : [];
        } elseif ($user->isStaff()) {
            $products = Product::where("store_id", $stores->id)->get();
        } else {
            abort(403, "Akses ditolak."); //Atau handle error lain yang sesuai
        }

        return view(
            "transaction-selling.index",
            compact("stores", "products", "role")
        );
    }
}
