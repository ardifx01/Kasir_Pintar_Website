<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class OrderList extends Component
{
    public array $orderItems = [];

    public function mount(array $orderItems = [])
    {
        $this->orderItems = $orderItems;
    }

    #[On("updateOrderItems")]
    public function updatedOrderItems($data)
    {
        $this->orderItems = $data["orderItems"];
    }

    public function render()
    {
        return view("livewire.order-list");
    }
}
