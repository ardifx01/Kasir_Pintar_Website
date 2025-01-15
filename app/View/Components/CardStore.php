<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CardStore extends Component
{
    public $store;

    public function __construct($store)
    {
        $this->store = $store;
    }

    public function render(): View|Closure|string
    {
        return view("components.card-store", ["store" => $this->store]);
    }
}
