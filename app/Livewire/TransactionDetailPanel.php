<?php

namespace App\Livewire;

use App\Models\PurchaseDetailTransaction;
use App\Models\PurchaseTransaction;
use App\Models\SellingDetailTransaction;
use App\Models\SellingTransaction;
use Livewire\Component;
use Livewire\Attributes\On;

class TransactionDetailPanel extends Component
{
    public $idTransaction;
    public $transactionType;
    public $transactionDetails;
    public $transaction;
    public $showDetailPanel = false; // Tambahkan properti ini

    public function mount($transactionType)
    {
        $this->idTransaction = null;
        $this->transactionType = $transactionType;
        $this->loadTransactionDetails();
    }

    public function loadTransactionDetails(): void
    {
        if ($this->idTransaction !== null) {
            if ($this->transactionType === "selling") {
                $this->transaction = SellingTransaction::find(
                    $this->idTransaction
                );
                $this->transactionDetails = SellingDetailTransaction::with(
                    "product"
                )
                    ->where("transaction_id", $this->idTransaction)
                    ->get();
            } elseif ($this->transactionType === "purchase") {
                $this->transaction = PurchaseTransaction::find(
                    $this->idTransaction
                );
                $this->transactionDetails = PurchaseDetailTransaction::with(
                    "product"
                )
                    ->where("transaction_id", $this->idTransaction)
                    ->get();
            } else {
                $this->transactionDetails = [];
            }
        } else {
            $this->transaction = null;
            $this->transactionDetails = [];
        }
    }

    #[On("updateIdTransaction")]
    public function updatedIdTransaction($data)
    {
        $this->idTransaction = $data["idTransaction"];
        $this->loadTransactionDetails();
        $this->toggleDetailPanel();
    }

    public function toggleDetailPanel()
    {
        $this->showDetailPanel = !$this->showDetailPanel;
    }

    public function render()
    {
        return view("livewire.transaction-detail-panel");
    }
}
