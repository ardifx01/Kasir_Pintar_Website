<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\SellingTransaction;
use App\Models\SellingDetailTransaction;
use App\Models\Customer;
use App\Models\Receivable;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class PosTransaction extends Component
{
    public $searchTerm = "";
    public $products = [];
    public $sideOrders = [];
    public $selectedPaymentMethod = "cash";
    public $discountAmount = 0;
    public $sellingTransaction;
    public $showCustomerSelection = false;
    public $selectedCustomerId = null;
    public $hasDebt = false;
    public $totalAmount = 0;

    public function mount()
    {
        $this->sellingTransaction = new SellingTransaction();
    }

    public function updatedSearchTerm()
    {
        $this->products = Product::where(
            "name_product",
            "like",
            "%" . $this->searchTerm . "%"
        )
            ->orWhere("code_product", "like", "%" . $this->searchTerm . "%")
            ->get();
    }

    public function addProduct(Product $product)
    {
        $this->sideOrders[$product->id] = [
            "product" => $product,
            "quantity" => 1,
        ];
    }

    public function updateQuantity(int $productId, int $quantity)
    {
        if ($quantity > 0) {
            $this->sideOrders[$productId]["quantity"] = $quantity;
        } else {
            unset($this->sideOrders[$productId]);
        }
        $this->calculateTotalAmount();
    }

    public function completeTransaction()
    {
        DB::transaction(function () {
            $this->sellingTransaction->total_discount = $this->discountAmount;
            $this->sellingTransaction->is_debt = $this->hasDebt;
            $this->sellingTransaction->payment_method =
                $this->selectedPaymentMethod;
            $this->sellingTransaction->total_amount = $this->totalAmount;
            $this->sellingTransaction->amount_paid = $this->totalAmount; // Assuming full payment for now
            $this->sellingTransaction->change_amount = 0; // Assuming full payment for now
            $this->sellingTransaction->transaction_status = "completed";
            $this->sellingTransaction->save();

            foreach ($this->sideOrders as $order) {
                SellingDetailTransaction::create([
                    "transaction_id" => $this->sellingTransaction->id,
                    "product_id" => $order["product"]->id,
                    "quantity" => $order["quantity"],
                    "item_discount" => 0, // Add item-level discount if needed
                    "subtotal" =>
                        $order["product"]->selling_price * $order["quantity"],
                ]);
                $order["product"]->stock -= $order["quantity"];
                $order["product"]->save();
            }

            if ($this->hasDebt) {
                Receivable::create([
                    "transaction_id" => $this->sellingTransaction->id,
                    "customer_id" => $this->selectedCustomerId,
                    "amount_due" => $this->totalAmount,
                    "payment_status" => "unpaid",
                    "due_date" => now()->addDays(7), // Example due date
                ]);
            }
        });
        $this->reset([
            "searchTerm",
            "sideOrders",
            "discountAmount",
            "selectedCustomerId",
            "hasDebt",
            "showCustomerSelection",
            "totalAmount",
        ]);
        $this->sellingTransaction = new SellingTransaction();
        $this->emit("transactionCompleted");
    }

    public function calculateTotalAmount()
    {
        $this->totalAmount = collect($this->sideOrders)->sum(function ($item) {
            return $item["product"]->selling_price * $item["quantity"];
        });
        $this->totalAmount -= $this->discountAmount;
    }

    public function render()
    {
        $subtotal = $this->totalAmount + $this->discountAmount; // Corrected subtotal calculation
        return view("livewire.pos-transaction", [
            "subtotal" => $subtotal,
            "paymentMethods" => ["cash", "transfer"],
            "customers" => Customer::all(),
        ]);
    }
}
