<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class OrderItem extends Component
{
    public $id;
    public $product;
    public $quantity;
    public $subtotal;

    public function mount($id, $product, $quantity, $subtotal)
    {
        $this->id = $id;
        $this->product = $product;
        $this->quantity = $quantity;
        $this->subtotal = $subtotal;
    }

    #[On("refresh")]
    public function refreshOrderItem($data)
    {
        // $orderItem = $data["orderItems"][$this->id];
        // $this->updatedQuantity($orderItem["quantity"]);
    }

    public function updatedQuantity($value)
    {
        $this->quantity = max(1, (int) $value);
        $this->subtotal = $this->quantity * $this->product["selling_price"];
        $this->dispatch("changeQuantity", [
            "quantity" => $this->quantity,
            "productId" => $this->product["id"],
        ]);
    }

    public function deleteOrder()
    {
        $this->dispatch("deleteOrderItem", [
            "productId" => $this->product["id"],
        ]);
    }

    public function render()
    {
        return view("livewire.order-item");
    }
}
