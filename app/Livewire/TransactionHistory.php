<?php

namespace App\Livewire;

use App\Models\SellingTransaction;
use App\Models\PurchaseTransaction;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class TransactionHistory extends Component
{
    use WithPagination;

    public $storeId;
    public $startDate;
    public $endDate;
    public $isDebt;
    public $role;
    public $stores;
    public $transactionType = "selling"; // Default to selling transactions

    public function mount($transactionType = "selling")
    {
        $this->transactionType = $transactionType;
        $this->role = Auth::user()->role;
        $this->getStores();
    }

    public function getStores()
    {
        if ($this->role === "admin") {
            $this->stores = Store::all();
        } elseif ($this->role === "owner") {
            $this->stores = Auth::user()->stores()->get();
        } elseif ($this->role === "staff") {
            $staff = Auth::user()->staff;
            $this->stores = $staff ? $staff->store()->get() : collect([]);
        } else {
            $this->stores = collect([]);
        }
    }

    public function showDetailTransaction($idTransaction)
    {
        $this->dispatch("updateIdTransaction", [
            "idTransaction" => $idTransaction,
        ]);
    }

    public function render()
    {
        $query =
            $this->transactionType === "selling"
                ? SellingTransaction::query()
                : PurchaseTransaction::query();

        // Filter berdasarkan role
        if ($this->role === "staff") {
            $staff = Auth::user()->staff();
            if ($staff && $staff->store_id) {
                $query->where("store_id", $staff->store_id);
            } else {
                $query->where("store_id", null);
            }
        } elseif ($this->role === "owner") {
            $firstStore = Auth::user()->stores()->first();
            if ($firstStore) {
                $query->where("store_id", $firstStore->id);
            } else {
                $query->where("store_id", null);
            }
        }

        if ($this->storeId) {
            $query->where("store_id", $this->storeId);
        }

        if ($this->startDate) {
            $query->whereDate("created_at", ">=", $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate("created_at", "<=", $this->endDate);
        }

        if ($this->isDebt !== null) {
            $query->where("is_debt", $this->isDebt);
        }

        $transactions = $query->orderBy("created_at", "desc")->paginate(10);

        return view("livewire.transaction-history", [
            "transactions" => $transactions,
            "stores" => $this->stores,
            "transactionType" => $this->transactionType, // Pass transaction type to the view
        ]);
    }
}
