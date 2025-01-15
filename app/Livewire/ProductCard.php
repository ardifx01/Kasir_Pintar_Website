<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;

class ProductCard extends Component
{
    public Product $product;
    public $quantity = 0;

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function incrementQuantity()
    {
        $this->quantity++;
        $this->dispatch("addListOrder", [
            "product" => $this->product,
            "quantity" => $this->quantity,
        ]);
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 0) {
            $this->quantity--;
            $this->dispatch("substractListOrder", [
                "product" => $this->product,
                "quantity" => $this->quantity,
            ]);
        }
    }

    public function render()
    {
        return view("livewire.product-card");
    }
}
