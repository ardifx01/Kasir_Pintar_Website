<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Store;
use App\Http\Requests\CustomerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CustomerController extends Controller
{
    use AuthorizesRequests;

    private function getAccessibleStores()
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return Store::all();
        } elseif ($user->role === "staff") {
            $staff = $user->staff()->first();
            return $staff ? collect([$staff->store]) : collect([]); // Handle case where staff has no store assigned
        } elseif ($user->role === "owner") {
            return $user->stores()->get();
        }
        return collect([]); // Return empty collection for other roles
    }

    public function index()
    {
        $this->authorize("viewAny", Customer::class);

        $user = Auth::user();
        $customers = null;
        $stores = null;

        if ($user->isAdmin()) {
            $customers = Customer::with("store")->get();
            $stores = Store::all();
        } elseif ($user->role === "staff") {
            $staff = $user->staff()->first();
            if ($staff) {
                $customers = $staff->store->customers()->with("store")->get();
                $stores = collect([$staff->store]);
            } else {
                $customers = collect([]);
                $stores = collect([]);
            }
        } elseif ($user->role === "owner") {
            $stores = $user->stores()->get();
            $customers = collect([]);
            foreach ($stores as $store) {
                $customers = $customers->merge(
                    $store->customers()->with("store")->get()
                );
            }
        }

        $role = Auth::user()->role;
        return view("customers.index", compact("customers", "stores", "role"));
    }

    public function create()
    {
        $this->authorize("create", Customer::class);
        $stores = $this->getAccessibleStores();
        $role = Auth::user()->role;
        return view("customers.create", compact("stores", "role"));
    }

    public function store(CustomerRequest $request)
    {
        $validatedData = $request->validated();
        $store = Store::find($validatedData["store_id"]);
        $this->authorize("create", Customer::class);

        try {
            Customer::create($request->validated());
            return redirect()
                ->route("customers.index")
                ->with("success", "Customer berhasil ditambahkan.");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with("error", "Terjadi kesalahan: " . $e->getMessage());
        }
    }

    public function edit(Customer $customer)
    {
        $this->authorize("update", $customer);
        $stores = $this->getAccessibleStores();
        $role = Auth::user()->role;
        return view("customers.edit", compact("customer", "stores", "role"));
    }

    public function update(CustomerRequest $request, Customer $customer)
    {
        $this->authorize("update", $customer);

        try {
            $customer->update($request->validated());
            return redirect()
                ->route("customers.index")
                ->with("success", "Customer berhasil diubah.");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with("error", "Terjadi kesalahan: " . $e->getMessage());
        }
    }

    public function destroy(Customer $customer)
    {
        $this->authorize("delete", $customer);
        try {
            $customer->delete();
            return redirect()
                ->route("customers.index")
                ->with("success", "Customer berhasil dihapus.");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with("error", "Terjadi kesalahan: " . $e->getMessage());
        }
    }
}
