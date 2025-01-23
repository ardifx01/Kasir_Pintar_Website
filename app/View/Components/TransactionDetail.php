<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\SellingTransaction;
use App\Models\PurchaseTransaction;
use Illuminate\Database\Eloquent\Collection;

class TransactionDetail extends Component
{
    public SellingTransaction|PurchaseTransaction $transaction;
    public Collection|null $details;
    public string $transactionType;

    public function __construct(
        SellingTransaction|PurchaseTransaction $transaction = null,
        Collection|null $details = null,
        string $transactionType = "Transaksi"
    ) {
        $this->transaction = $transaction;
        $this->details = $details;
        $this->transactionType = $transactionType;
    }

    public function render(): View|Closure|string
    {
        return view("components.transaction-detail");
    }
}
