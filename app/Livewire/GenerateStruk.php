<?php
namespace App\Livewire;

use App\Models\SellingTransaction;
use App\Models\PurchaseTransaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;

class GenerateStruk extends Component
{
    public $transactionType;
    public $transactionId;

    public function mount($transactionType, $transactionId)
    {
        $this->transactionType = $transactionType;
        $this->transactionId = $transactionId;
    }

    public function generatePdf()
    {
        $transaction = null;
        if ($this->transactionType === "selling") {
            $transaction = SellingTransaction::with(
                "sellingDetailTransactions"
            )->find($this->transactionId);
        } elseif ($this->transactionType === "purchasing") {
            $transaction = PurchaseTransaction::with(
                "purchaseDetailTransactions"
            )->find($this->transactionId);
        }

        if (!$transaction) {
            session()->flash("error", "Transaksi tidak ditemukan.");
            return;
        }

        $data = [
            "transaction" => $transaction,
            "type" => $this->transactionType,
            "tanggal" => $transaction->created_at->format("Y-m-d H:i:s"),
        ];

        $pdf = Pdf::loadView("livewire.struk-pdf");

        return $pdf->download(
            "struk-" .
                $this->transactionType .
                "-" .
                $this->transactionId .
                ".pdf"
        );
    }

    public function render()
    {
        return view("livewire.generate-struk");
    }
}
