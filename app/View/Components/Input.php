<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Input extends Component
{
    public string $id;
    public string $label;
    public string $name;
    public string $type = "text";
    public string $placeholder = "";
    public mixed $value;

    public function __construct(
        string $id,
        string $label,
        string $name,
        string $type = "text",
        string $placeholder = "",
        $value = null
    ) {
        $this->id = $id;
        $this->label = $label;
        $this->name = $name;
        $this->type = $type;
        $this->placeholder = $placeholder;
        $this->value = $value;
    }

    public function render(): View|Closure|string
    {
        return view("components.input");
    }
}
