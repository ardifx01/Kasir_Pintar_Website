<?php

namespace App\Policies;

use App\Models\Supplier;
use App\Models\User;
use App\Models\Store;
use Illuminate\Auth\Access\HandlesAuthorization;

class SupplierPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->isAdmin() ||
            $user->role === "staff" ||
            $user->role === "owner";
    }

    private function canAccessSupplier(User $user, Supplier $supplier): bool
    {
        return $user->isAdmin() ||
            ($user->role === "staff" &&
                $user->staff()->first()?->store_id === $supplier->store_id) ||
            ($user->role === "owner" &&
                $user
                    ->stores()
                    ->where("id", $supplier->store_id)
                    ->exists());
    }

    public function view(User $user, Supplier $supplier): bool
    {
        return $this->canAccessSupplier($user, $supplier);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->role === "owner" || $user->isStaff(); // Sesuaikan jika staff boleh membuat supplier
    }

    public function update(User $user, Supplier $supplier): bool
    {
        return $this->canAccessSupplier($user, $supplier);
    }

    public function delete(User $user, Supplier $supplier): bool
    {
        return $this->canAccessSupplier($user, $supplier);
    }
}
