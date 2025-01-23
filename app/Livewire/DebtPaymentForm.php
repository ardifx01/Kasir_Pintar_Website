<?php

namespace App\Livewire;

use App\Models\Payable;
use App\Models\PayablePaymentHistory;
use App\Models\Receivable;
use App\Models\ReceivablePaymentHistory;
use App\Models\PurchaseTransaction;
use App\Models\SellingTransaction;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\On;
use Livewire\Component;

class DebtPaymentForm extends Component
{
    public $debtType = "payable";
    public $debtId;
    public $amountPaid;
    public $paymentMethod = "cash";
    public $description = "";
    public $paymentDate;
    public $showModal = false;
    public $successMessage = "";
    public $errorMessage = "";
    public $debt;

    protected $rules = [
        "debtId" => "required",
        "amountPaid" => "required|numeric|min:0.01",
        "paymentMethod" => "required|in:cash,transfer,other",
        "paymentDate" => "required|date",
    ];

    protected $messages = [
        "debtId.required" => "ID Hutang/Piutang harus diisi",
        "debtId.exists" => "ID Hutang/Piutang tidak valid",
        "amountPaid.required" => "Jumlah Bayar harus diisi",
        "amountPaid.numeric" => "Jumlah Bayar harus berupa angka",
        "amountPaid.min" => "Jumlah Bayar minimal :min",
        "paymentMethod.required" => "Metode Pembayaran harus dipilih",
        "paymentMethod.in" => "Metode Pembayaran tidak valid",
        "paymentDate.required" => "Tanggal Pembayaran harus diisi",
        "paymentDate.date" => "Tanggal Pembayaran tidak valid",
    ];

    public function updatedPaymentDate($value)
    {
        $this->paymentDate = date("Y-m-d", strtotime($value));
    }

    public function mount($debtType = "payable")
    {
        $this->debtType = $debtType;
    }

    public function updatedDebtId($value)
    {
        $this->debt =
            $this->debtType === "payable"
                ? Payable::find($value)
                : Receivable::find($value);
        $this->resetErrorBag();
        $this->errorMessage = "";
        $this->successMessage = "";
        if (!$this->debt) {
            $this->addError("debtId", "Hutang/Piutang tidak ditemukan.");
        }
        $this->debtId = $value;
        $this->amountPaid = $this->debt
            ? min($this->debt->amount_due, $this->amountPaid)
            : 0; // update amountPaid jika ada debt
    }

    #[On("showModal")]
    public function showModal($data)
    {
        $this->reset([
            "amountPaid",
            "paymentMethod",
            "description",
            "paymentDate",
        ]);
        $this->updatedDebtId($data["idDebt"]);
        $this->showModal = true;
        $this->resetErrorBag();
        $this->errorMessage = "";
        $this->successMessage = "";
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset([
            "amountPaid",
            "paymentMethod",
            "description",
            "paymentDate",
            "debtId",
        ]);
        $this->resetErrorBag();
        $this->errorMessage = "";
        $this->successMessage = "";
    }

    public function submit()
    {
        $validatedData = $this->validate();
        $validatedData["payment_date"] = $this->paymentDate;
        $paymentHistoryModel =
            $this->debtType === "payable"
                ? PayablePaymentHistory::class
                : ReceivablePaymentHistory::class;
        $debtModel =
            $this->debtType === "payable" ? Payable::class : Receivable::class;
        $transactionModel =
            $this->debtType === "payable"
                ? PurchaseTransaction::class
                : SellingTransaction::class;

        try {
            $debt = $debtModel::findOrFail($this->debtId);
            $transaction = $transactionModel::findOrFail($debt->transaction_id);

            $paymentHistory = new $paymentHistoryModel($validatedData);
            $paymentHistory->{$this->debtType . "_id"} = $this->debtId;
            $paymentHistory->amount_paid = $validatedData["amountPaid"];
            $paymentHistory->save();

            $debt->amount_due -= $this->amountPaid;
            if ($debt->amount_due <= 0) {
                $debt->payment_status = "paid";
                $transaction->amount_paid = $transaction->total_amount; //Update amount_paid di transaction
                $transaction->transaction_status = "done"; //Update status transaction
                $transaction->is_debt = false;
                $transaction->save();
                $debt->save();
            } else {
                $debt->save();
            }

            $this->successMessage = "Pembayaran berhasil ditambahkan.";
            $this->closeModal();
            $this->emit("refreshDebtList");
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->errorMessage = "Hutang/Piutang tidak ditemukan.";
        } catch (\Exception $e) {
            $this->errorMessage = "Terjadi kesalahan: " . $e->getMessage();
        }
    }

    public function render()
    {
        return view("livewire.debt-payment-form");
    }
}
