<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Collection;

class Select extends Component
{
    public string $id;
    public string $label;
    public string $name;
    public array $options;
    public mixed $selected;

    public function __construct(
        string $id,
        string $label,
        string $name,
        array $options,
        $selected = null
    ) {
        $this->id = $id;
        $this->label = $label;
        $this->name = $name;
        $this->options = $options;
        $this->selected = $selected;
    }

    public function render(): View|Closure|string
    {
        return view("components.select");
    }
}
