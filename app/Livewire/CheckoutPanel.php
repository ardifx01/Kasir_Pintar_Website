<?php

namespace App\Livewire;

use App\Models\SellingDetailTransaction;
use App\Models\SellingTransaction;
use App\Models\PurchaseDetailTransaction;
use App\Models\PurchaseTransaction;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Receivable;
use App\Models\Payable;
use League\Config\Exception\ValidationException;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Collection;

class CheckoutPanel extends Component
{
    public array $orderItems = [];
    public Collection $products;
    public $discount = 0;
    public $taxPercentage = 0;
    public $paymentAmount = 0;
    public $paymentMethod = "cash";
    public $total_belanja = 0;
    public $storeId = null;
    public $transactionType = "selling";
    public $sellingTransaction;
    public $purchaseTransaction;
    public $subtotal = 0;
    public $change = 0;
    public $search = "";
    public Collection $clients;
    public $clientId = 0;

    public function mount($transactionType = "selling")
    {
        $user = auth()->user();
        if ($user->isOwner()) {
            $this->storeId = $user->stores()->first()?->id;
        } elseif ($user->isStaff()) {
            $this->storeId = $user->staff->store_id;
        } else {
            abort(403, "Akses ditolak.");
        }
        $this->transactionType = $transactionType;
        $this->products = Product::where("store_id", $this->storeId)->get();
        if ($this->transactionType === "selling") {
            $this->clients = Customer::where("store_id", $this->storeId)->pluck(
                "name",
                "id"
            );
        } else {
            $this->clients = Supplier::where("store_id", $this->storeId)->pluck(
                "name",
                "id"
            );
        }
    }

    private function findAndUpdateItem(int $productId, int $quantity): void
    {
        $existingItem = collect($this->orderItems)->firstWhere(
            "product.id",
            $productId
        );

        if ($existingItem) {
            $existingItemIndex = array_search($existingItem, $this->orderItems);
            $product = $this->orderItems[$existingItemIndex]["product"];
            $this->orderItems[$existingItemIndex]["quantity"] = $quantity;

            $this->orderItems[$existingItemIndex]["subtotal"] =
                $product["selling_price"] *
                $this->orderItems[$existingItemIndex]["quantity"];
        }

        $this->calculateTotal();
    }

    public function updatedSearch($value)
    {
        if (!empty($value)) {
            $product = $this->products->firstWhere("code_product", $value);

            if ($product) {
                $this->handleAddProduct(["product" => $product]);
                $this->search = ""; // Kosongkan input pencarian setelah produk ditambahkan
            }
        }
    }

    #[On("addOrder")]
    public function handleAddProduct($data)
    {
        $product = $data["product"];

        $existingItem = collect($this->orderItems)->firstWhere(
            "product.id",
            $product["id"]
        );

        if ($existingItem) {
            $existingItemIndex = array_search($existingItem, $this->orderItems);
            $this->orderItems[$existingItemIndex]["quantity"] =
                $this->orderItems[$existingItemIndex]["quantity"] + 1;

            $this->orderItems[$existingItemIndex]["subtotal"] =
                $product["selling_price"] *
                $this->orderItems[$existingItemIndex]["quantity"];
        } else {
            array_push($this->orderItems, [
                "product" => $product,
                "quantity" => 1,
                "subtotal" => 1 * $product["selling_price"],
            ]);
        }

        $this->dispatch("updateOrderItems", [
            "orderItems" => $this->orderItems,
        ]);

        $this->calculateTotal();
    }

    #[On("changeQuantity")]
    public function changeQuantity($data)
    {
        $productId = $data["productId"];
        $quantity = $data["quantity"];

        $this->findAndUpdateItem($productId, $quantity);
        $this->calculateTotal();
    }

    #[On("deleteOrderItem")]
    public function removeOrderItem($data)
    {
        $productId = $data["productId"];
        $this->orderItems = array_filter($this->orderItems, function (
            $item
        ) use ($productId) {
            return $item["product"]["id"] != $productId;
        });
        $this->calculateTotal();
        $this->dispatch("updateOrderItems", [
            "orderItems" => $this->orderItems,
        ]);
    }

    public function updatedDiscount($value)
    {
        if ($value == "") {
            $this->discount = 0;
        }
        $this->calculateTotal();
    }

    public function updatedTaxPercentage($value)
    {
        if ($value == "") {
            $this->taxPercentage = 0;
        }
        $this->calculateTotal();
    }

    public function updatedPaymentAmount($value)
    {
        if ($value == "") {
            $this->paymentAmount = 0;
        }
        $this->change = $this->paymentAmount - $this->total_belanja;
    }

    public function calculateTotal()
    {
        $this->subtotal = 0;
        foreach ($this->orderItems as $item) {
            $this->subtotal +=
                $item["quantity"] * $item["product"]["selling_price"];
        }

        $taxAmount = $this->subtotal * ($this->taxPercentage / 100);
        $this->total_belanja = $this->subtotal - $this->discount + $taxAmount;
        $this->change = $this->paymentAmount - $this->total_belanja;
    }

    private function saveSellingTransaction()
    {
        $this->sellingTransaction = SellingTransaction::create([
            "store_id" => $this->storeId, // Menggunakan $storeId
            "total_discount" => $this->discount,
            "total_tax" => $this->taxPercentage,
            "is_debt" => $this->total_belanja > $this->paymentAmount,
            "description" => "",
            "payment_method" => $this->paymentMethod,
            "total_amount" => $this->total_belanja,
            "amount_paid" => $this->paymentAmount,
            "change_amount" => max(
                0,
                $this->paymentAmount - $this->total_belanja
            ),
            "transaction_status" =>
                $this->total_belanja < $this->paymentAmount
                    ? "done"
                    : "pending",
        ]);

        foreach ($this->orderItems as $item) {
            SellingDetailTransaction::create([
                "transaction_id" => $this->sellingTransaction->id,
                "product_id" => $item["product"]["id"],
                "quantity" => $item["quantity"],
                "subtotal" =>
                    $item["quantity"] * $item["product"]["selling_price"],
            ]);

            $product = Product::lockForUpdate()->find($item["product"]["id"]);
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
    }

    private function savePurchasingTransaction()
    {
        $this->purchaseTransaction = PurchaseTransaction::create([
            "store_id" => $this->storeId,
            "total_discount" => $this->discount,
            "total_tax" => $this->taxPercentage,
            "is_debt" => $this->total_belanja > $this->paymentAmount,
            "description" => "",
            "payment_method" => $this->paymentMethod,
            "total_amount" => $this->total_belanja,
            "amount_paid" => $this->paymentAmount,
            "change_amount" => max(
                0,
                $this->paymentAmount - $this->total_belanja
            ),
            "transaction_status" =>
                $this->total_belanja < $this->paymentAmount
                    ? "done"
                    : "pending",
        ]);

        foreach ($this->orderItems as $item) {
            PurchaseDetailTransaction::create([
                "transaction_id" => $this->purchaseTransaction->id,
                "product_id" => $item["product"]["id"],
                "quantity" => $item["quantity"],
                "subtotal" =>
                    $item["quantity"] * $item["product"]["purchase_price"],
            ]);

            $product = Product::lockForUpdate()->find($item["product"]["id"]);
            if ($product) {
                $product->update([
                    "stock" => $product->stock + $item["quantity"],
                ]);
            } else {
                throw new \Exception(
                    "Product not found: " . $item["product"]["id"]
                );
            }
        }
    }

    public function saveTransaction()
    {
        if ($this->change <= 0) {
            $this->validate([
                "clientId" => "required|exists:customers,id",
            ]);
        }

        $this->validate([
            "paymentAmount" => "required|numeric|min:0",
            "taxPercentage" => "required|numeric|min:0",
            "discount" => "required|numeric|min:0",
            "transactionType" => "required|in:selling,purchasing", // Validate transaction type
        ]);

        if (!$this->storeId) {
            throw ValidationException::withMessages([
                "store_id" => "Store ID tidak ditemukan.",
            ]);
        }

        try {
            DB::beginTransaction();

            if ($this->transactionType === "selling") {
                $this->saveSellingTransaction();

                if ($this->change <= 0 && $this->clientId) {
                    // Hanya buat Receivable jika ada piutang
                    Receivable::create([
                        "transaction_id" => $this->sellingTransaction->id,
                        "customer_id" => $this->clientId,
                        "amount_due" => abs($this->change), // Nilai piutang
                        "payment_status" => "unpaid", // Status awal
                        "due_date" => now()->addDays(7), // Contoh due date, sesuaikan kebutuhan
                    ]);
                }
            } else {
                $this->savePurchasingTransaction();
                if ($this->change <= 0 && $this->clientId) {
                    // Hanya buat Payable jika ada hutang
                    Payable::create([
                        "transaction_id" => $this->purchaseTransaction->id,
                        "supplier_id" => $this->clientId,
                        "amount_due" => abs($this->change), // Nilai hutang
                        "payment_status" => "unpaid", // Status awal
                        "due_date" => now()->addDays(7), // Contoh due date, sesuaikan kebutuhan
                    ]);
                }
            }

            DB::commit();

            $this->reset(
                "orderItems",
                "discount",
                "taxPercentage",
                "paymentAmount",
                "paymentMethod",
                "total_belanja",
                "subtotal",
                "change"
            );
            $this->dispatch("resetValue");
            if ($this->transactionType === "selling") {
                return redirect()->route(
                    "transactions.detail-selling",
                    $this->sellingTransaction
                );
            } else {
                return redirect()->route(
                    "transactions.detail-purchasing",
                    $this->purchaseTransaction
                );
            }
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
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
