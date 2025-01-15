<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class ProfilePicture extends Component
{
    use WithFileUploads;

    public $photo;
    public $photoUrl;

    public function mount()
    {
        $this->photoUrl = "/storage/assets/icons/user-profile.png";
    }

    public function updatedPhoto()
    {
        $this->photoUrl = $this->photo->temporaryUrl();
    }

    public function save()
    {
        // Simpan $this->photo ke storage (sesuaikan dengan logic penyimpanan Anda)
        // ... kode penyimpanan gambar ke storage ...  contoh:
        $path = $this->photo->store("profile_images", "public");
        // Update database, jika diperlukan

        $this->emit("photoUpdated", $path); // Emit event agar komponen lain tahu
        $this->reset("photo"); // Reset setelah simpan
    }

    public function render()
    {
        return view("livewire.profile-picture");
    }
}
