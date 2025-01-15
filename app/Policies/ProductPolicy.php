<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->isAdmin() ||
            $user->role === "staff" ||
            $user->role === "owner";
    }

    public function view(User $user, Product $product): bool
    {
        return $this->canAccessProduct($user, $product);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isOwner() || $user->isStaff();
    }

    public function update(User $user, Product $product): bool
    {
        return $this->canAccessProduct($user, $product);
    }

    public function delete(User $user, Product $product): bool
    {
        return $this->canAccessProduct($user, $product);
    }

    public function restore(User $user, Product $product): bool
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, Product $product): bool
    {
        return $user->isAdmin();
    }

    private function canAccessProduct(User $user, Product $product): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isOwner()) {
            return $user
                ->stores()
                ->where("id", $product->store_id)
                ->exists();
        }

        if ($user->isStaff()) {
            $staff = $user->staff()->first();
            return $staff !== null && $staff->store_id === $product->store_id;
        }

        return false;
    }
}
