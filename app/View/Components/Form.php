<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Form extends Component
{
    public string $action;
    public string $method = "POST";
    public string|null $submitButtonText;

    public function __construct(
        string $action,
        string $method = "POST",
        string|null $submitButtonText = null
    ) {
        $this->action = $action;
        $this->method = $method;
        $this->submitButtonText = $submitButtonText;
    }

    public function render()
    {
        return view("components.form");
    }
}
