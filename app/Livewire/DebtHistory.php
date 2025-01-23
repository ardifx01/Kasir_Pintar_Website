<?php

namespace App\Livewire;

use App\Models\Payable;
use App\Models\Receivable;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class DebtHistory extends Component
{
    use WithPagination;

    public $storeId;
    public $startDate;
    public $endDate;
    public $debtType = "payable"; // 'payable' or 'receivable'
    public $role;
    public $stores;

    public function mount($debtType)
    {
        $this->debtType = $debtType;
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

    public function showDebtDetail($idDebt)
    {
        $this->dispatch("updateIdDebt", [
            "idDebt" => $idDebt,
        ]);
    }

    public function render()
    {
        $query =
            $this->debtType === "payable"
                ? Payable::query()
                : Receivable::query();

        // Filter berdasarkan role dan toko
        $storeId =
            $this->role === "staff"
                ? Auth::user()->staff->store_id
                : ($this->role === "owner"
                    ? Auth::user()->stores()->first()?->id
                    : null);
        $query->when(
            $storeId,
            fn($q) => $q->whereHas(
                "transaction",
                fn($q) => $q->where("store_id", $storeId)
            )
        );

        if ($this->storeId) {
            $query->whereHas(
                "transaction",
                fn($q) => $q->where("store_id", $this->storeId)
            );
        }

        if ($this->startDate) {
            $query->whereDate("due_date", ">=", $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate("due_date", "<=", $this->endDate);
        }

        $debts = $query->orderBy("due_date", "desc")->paginate(10);

        return view("livewire.debt-history", [
            "debts" => $debts,
            "stores" => $this->stores,
            "debtType" => $this->debtType,
        ]);
    }
}
