<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Store; // Import model Store
use Illuminate\Support\Collection;

class SelectStore extends Component
{
    public Collection $stores;
    public ?string $action;
    public ?int $selectedStoreId;

    public function __construct(
        Collection $stores,
        ?string $action = null,
        ?int $selectedStoreId = null
    ) {
        $this->stores = $stores;
        $this->action = $action ?? "";
        $this->selectedStoreId = $selectedStoreId;
    }
    public function render(): View|Closure|string
    {
        return view("components.select-store");
    }
}
