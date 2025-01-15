<?php

namespace App\Livewire;

use App\Models\SellingDetailTransaction;
use App\Models\SellingTransaction;
use App\Models\Product;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class CheckoutPanel extends Component
{
    public array $orderItems = [];
    public $discount = 0;
    public $taxPercentage = 0;
    public $paymentAmount = 0;
    public $paymentMethod = "cash";
    public $total_belanja = 0;
    public $storeId = null; //Menambahkan variabel untuk store id
    public $sellingTransaction;
    public $subtotal = 0;
    public $change = 0;

    public function mount()
    {
        $user = auth()->user();
        if ($user->isOwner()) {
            $this->storeId = $user->stores()->first()?->id;
        } elseif ($user->isStaff()) {
            $this->storeId = $user->staff->store_id;
        } else {
            abort(403, "Akses ditolak.");
        }
        $this->calculateTotal();
    }

    private function findAndUpdateItem(int $productId, int $quantity): void
    {
        foreach ($this->orderItems as $key => &$item) {
            // Pass by reference for direct modification
            if ($item["product"]["id"] == $productId) {
                $item["quantity"] = $quantity;
                $item["subtotal"] =
                    $quantity * $item["product"]["selling_price"];
                if ($item["quantity"] <= 0) {
                    unset($this->orderItems[$key]);
                }
                $this->orderItems = array_values($this->orderItems);
                break;
            }
        }
        $this->dispatch("updateOrderItems", [
            "orderItems" => $this->orderItems,
        ]);
    }

    #[On("addListOrder")]
    public function handleAddProduct($data)
    {
        $product = $data["product"];
        $quantity = $data["quantity"];

        $existingItem = collect($this->orderItems)->firstWhere(
            "product.id",
            $product["id"]
        );

        if ($existingItem) {
            $existingItemIndex = array_search($existingItem, $this->orderItems);
            $this->orderItems[$existingItemIndex]["quantity"] = $quantity;
            $this->orderItems[$existingItemIndex]["subtotal"] =
                $product["selling_price"] * $quantity;
        } else {
            $this->orderItems[] = [
                "product" => $product,
                "quantity" => $quantity,
                "subtotal" => $quantity * $product["selling_price"],
            ];
        }

        $this->dispatch("updateOrderItems", [
            "orderItems" => $this->orderItems,
        ]);
        $this->calculateTotal();
    }

    #[On("substractListOrder")]
    public function handleSubstractProduct($data)
    {
        $productId = $data["product"]["id"];
        $quantity = $data["quantity"];
        $this->findAndUpdateItem($productId, $quantity); // Negative quantity for subtraction
        $this->calculateTotal();
    }

    public function updatedDiscount()
    {
        $this->calculateTotal();
    }

    public function updatedTaxPercentage()
    {
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->subtotal = 0;
        foreach ($this->orderItems as $item) {
            $this->subtotal =
                $item["quantity"] * $item["product"]["selling_price"];
        }

        $taxAmount = $this->subtotal * ($this->taxPercentage / 100);
        $this->total_belanja = $this->subtotal - $this->discount + $taxAmount;
    }

    public function saveTransaction()
    {
        $this->validate([
            "paymentAmount" => "required|numeric|min:0",
            "taxPercentage" => "required|numeric|min:0",
            "discount" => "required|numeric|min:0",
        ]);

        if (!$this->storeId) {
            throw ValidationException::withMessages([
                "store_id" => "Store ID tidak ditemukan.",
            ]);
        }

        try {
            DB::beginTransaction();

            $this->sellingTransaction = SellingTransaction::create([
                "store_id" => $this->storeId, // Menggunakan $storeId
                "total_discount" => $this->discount,
                "total_tax" => $this->taxPercentage,
                "is_debt" => false,
                "description" => "",
                "payment_method" => $this->paymentMethod,
                "total_amount" => $this->total_belanja,
                "amount_paid" => $this->paymentAmount,
                "change_amount" => max(
                    0,
                    $this->paymentAmount - $this->total_belanja
                ),
                "transaction_status" => "success",
            ]);

            foreach ($this->orderItems as $item) {
                SellingDetailTransaction::create([
                    "transaction_id" => $this->sellingTransaction->id,
                    "product_id" => $item["product"]["id"],
                    "quantity" => $item["quantity"],
                    "subtotal" =>
                        $item["quantity"] * $item["product"]["selling_price"],
                ]);

                // Update Stock (dalam transaksi)
                $product = Product::lockForUpdate()->find(
                    $item["product"]["id"]
                );
                if ($product) {
                    $product->update([
                        "stock" => max(0, $product->stock - $item["quantity"]),
                    ]);
                } else {
                    throw new \Exception(
                        "Product not found: " . $item["product"]["id"]
                    );
                }
            }

            DB::commit();
            session()->flash("message", "Transaksi berhasil");
            $this->reset([
                "orderItems",
                "discount",
                "taxPercentage",
                "paymentAmount",
                "paymentMethod",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash("error", "Terjadi kesalahan: " . $e->getMessage());
        }
    }

    public function getChangeAttribute()
    {
        return max(0, $this->paymentAmount - $this->total_belanja);
    }

    public function render()
    {
        return view("livewire.checkout-panel");
    }
}
