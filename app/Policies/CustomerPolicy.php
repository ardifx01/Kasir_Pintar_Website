<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;
use App\Models\Store;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->isAdmin() ||
            $user->role === "staff" ||
            $user->role === "owner";
    }

    private function canAccessCustomer(User $user, Customer $customer): bool
    {
        return $user->isAdmin() ||
            ($user->role === "staff" &&
                $user->staff()->first()?->store_id === $customer->store_id) ||
            ($user->role === "owner" &&
                $user
                    ->stores()
                    ->where("id", $customer->store_id)
                    ->exists());
    }

    public function view(User $user, Customer $customer): bool
    {
        return $this->canAccessCustomer($user, $customer);
    }

    public function create(User $user): bool
    {
        if ($user->isAdmin() || $user->isOwner()) {
            return true;
        }

        if ($user->isStaff()) {
            return $user->staff()->exists(); // Cek apakah staff memiliki setidaknya satu toko
        }

        return false;
    }

    public function update(User $user, Customer $customer): bool
    {
        return $this->canAccessCustomer($user, $customer);
    }

    public function delete(User $user, Customer $customer): bool
    {
        return $this->canAccessCustomer($user, $customer);
    }
}
