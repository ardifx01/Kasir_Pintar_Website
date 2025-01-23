<?php

namespace App\Livewire;

use App\Models\Payable;
use App\Models\PayablePaymentHistory;
use App\Models\Receivable;
use App\Models\ReceivablePaymentHistory;
use Livewire\Component;
use Livewire\Attributes\On;

class PaymentDebtPanel extends Component
{
    public $debtId;
    public $debtType; // 'payable' or 'receivable'
    public $paymentHistories = [];
    public $debt;
    public $showDetailPanel = false;
    public $error = null;

    public function mount($debtType)
    {
        $this->debtType = $debtType;
    }

    #[On("updateIdDebt")]
    public function showPaymentDetail($data)
    {
        $this->debtId = $data["idDebt"];
        $this->loadPaymentHistories();
        $this->showDetailPanel = true;
        $this->error = null;
    }

    public function showPaymentModal($idDebt)
    {
        $this->dispatch("showModal", ["idDebt" => $idDebt]);
    }

    public function loadPaymentHistories(): void
    {
        $this->paymentHistories = [];
        $this->debt = null;

        if ($this->debtId) {
            $debtModel =
                $this->debtType === "payable"
                    ? Payable::class
                    : Receivable::class;
            $historyModel =
                $this->debtType === "payable"
                    ? PayablePaymentHistory::class
                    : ReceivablePaymentHistory::class;

            $this->debt = $debtModel::find($this->debtId);
            if ($this->debt) {
                $this->paymentHistories = $historyModel
                    ::where($this->debtType . "_id", $this->debtId)
                    ->get();
            } else {
                $this->error = "Hutang/Piutang tidak ditemukan.";
            }
        }
    }

    public function toggleDetailPanel()
    {
        $this->showDetailPanel = !$this->showDetailPanel;
    }

    public function render()
    {
        return view("livewire.payment-debt-panel");
    }
}
