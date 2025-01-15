<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Card extends Component
{
    public string|null $header;
    public string|null $footer;
    public string|null $class;

    public function __construct(
        string|null $header = null,
        string|null $footer = null,
        string|null $class = null
    ) {
        $this->header = $header;
        $this->footer = $footer;
        $this->class = $class;
    }

    public function render()
    {
        return view("components.card");
    }
}
