<?php

namespace App\Policies;

use App\Models\Store;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StorePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isOwner();
    }

    public function view(User $user, Store $store): bool
    {
        return $user->isAdmin() ||
            $user
                ->stores()
                ->where("id", $store->id)
                ->exists();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isOwner();
    }

    public function update(User $user, Store $store): bool
    {
        return $user->isAdmin() ||
            $user
                ->stores()
                ->where("id", $store->id)
                ->exists();
    }

    public function delete(User $user, Store $store): bool
    {
        return $user->isAdmin() ||
            $user
                ->stores()
                ->where("id", $store->id)
                ->exists();
    }
}
