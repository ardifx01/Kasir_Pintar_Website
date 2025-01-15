<?php

namespace App\Livewire;

use Livewire\Component;

class PasswordInput extends Component
{
    public string $id;
    public string $label;
    public string $name;
    public string $placeholder;
    public bool $showPassword = false;

    public function mount(
        string $id,
        string $label,
        string $name,
        string $placeholder = ""
    ) {
        $this->id = $id;
        $this->label = $label;
        $this->name = $name;
        $this->placeholder = $placeholder;
    }

    public function togglePassword()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function render()
    {
        return view("livewire.password-input", [
            "inputType" => $this->showPassword ? "text" : "password",
            "imagePath" => $this->showPassword
                ? "/storage/assets/icons/eye-crossed.png"
                : "/storage/assets/icons/eye.png",
        ]);
    }
}
