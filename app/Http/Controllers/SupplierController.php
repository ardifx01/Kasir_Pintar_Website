<?php

namespace App\Http\Controllers;
use App\Models\Supplier;
use App\Models\Store;
use App\Http\Requests\SupplierRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SupplierController extends Controller
{
    use AuthorizesRequests;

    private function getAccessibleStores(): \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return Store::all();
        } elseif ($user->role === "staff") {
            $staff = $user->staff()->first();
            return $staff ? collect([$staff->store]) : collect([]);
        } elseif ($user->role === "owner") {
            return $user->stores()->get();
        }
        return collect([]);
    }

    public function index()
    {
        $this->authorize("viewAny", Supplier::class);

        $user = Auth::user();
        $suppliers = null;
        $stores = $this->getAccessibleStores();

        if ($user->isAdmin()) {
            $suppliers = Supplier::with("store")->get();
        } elseif ($user->role === "staff") {
            $staff = $user->staff()->first();
            if ($staff) {
                $suppliers = $staff->store->suppliers()->with("store")->get();
            } else {
                $suppliers = collect([]);
            }
        } elseif ($user->role === "owner") {
            $suppliers = collect([]);
            foreach ($stores as $store) {
                $suppliers = $suppliers->merge(
                    $store->suppliers()->with("store")->get()
                );
            }
        }

        $role = $user->role;
        return view("suppliers.index", compact("suppliers", "stores", "role"));
    }

    public function create()
    {
        $this->authorize("create", Supplier::class); // No need for additional parameters here
        $stores = $this->getAccessibleStores();
        $role = Auth::user()->role;
        return view("suppliers.create", compact("stores", "role"));
    }

    public function store(SupplierRequest $request)
    {
        $this->authorize("create", Supplier::class); // No need for additional parameters here

        try {
            $validatedData = $request->validated();
            $store = Store::find($validatedData["store_id"]);

            Supplier::create($validatedData);
            return redirect()
                ->route("suppliers.index")
                ->with("success", "Supplier berhasil ditambahkan.");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with("error", "Terjadi kesalahan: " . $e->getMessage());
        }
    }

    public function edit(Supplier $supplier)
    {
        $this->authorize("update", $supplier);
        $stores = $this->getAccessibleStores();
        $role = Auth::user()->role;
        return view("suppliers.edit", compact("supplier", "stores", "role"));
    }

    public function update(SupplierRequest $request, Supplier $supplier)
    {
        $this->authorize("update", $supplier);
        try {
            $supplier->update($request->validated());
            return redirect()
                ->route("suppliers.index")
                ->with("success", "Supplier berhasil diubah.");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with("error", "Terjadi kesalahan: " . $e->getMessage());
        }
    }

    public function destroy(Supplier $supplier)
    {
        $this->authorize("delete", $supplier);
        try {
            $supplier->delete();
            return redirect()
                ->route("suppliers.index")
                ->with("success", "Supplier berhasil dihapus.");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with("error", "Terjadi kesalahan: " . $e->getMessage());
        }
    }
}
