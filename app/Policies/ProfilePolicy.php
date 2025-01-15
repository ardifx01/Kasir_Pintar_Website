<?php

namespace App\Policies;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProfilePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Profile $profile): bool
    {
        return $user->id === $profile->user_id || $user->isAdmin(); // Ganti dengan logic untuk menentukan admin
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Profile $profile): bool
    {
        return $user->id === $profile->user_id || $user->isAdmin(); // Ganti dengan logic untuk menentukan admin
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Profile $profile): bool
    {
        // Hanya pemilik profil dan admin yang bisa menghapus
        return $user->id === $profile->user_id || $user->isAdmin(); // Ganti dengan logic untuk menentukan admin
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Profile $profile): bool
    {
        // Hanya admin yang bisa memulihkan (contoh)
        return $user->isAdmin(); // Ganti dengan logic untuk menentukan admin
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Profile $profile): bool
    {
        // Hanya admin yang bisa menghapus permanen (contoh)
        return $user->isAdmin(); // Ganti dengan logic untuk menentukan admin
    }
}
