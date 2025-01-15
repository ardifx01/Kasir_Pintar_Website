<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CardMenu extends Component
{
    public string $routeName;
    public string $img;
    public string $label;
    public array $routeParameters = [];

    public function __construct(
        string $routeName,
        string $img,
        string $label,
        array $routeParameters = []
    ) {
        $this->routeName = $routeName;
        $this->img = $img;
        $this->label = $label;
        $this->routeParameters = $routeParameters;
    }

    public function render(): View|Closure|string
    {
        return view("components.card-menu");
    }
}
