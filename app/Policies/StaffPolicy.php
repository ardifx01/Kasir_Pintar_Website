<?php

namespace App\Policies;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StaffPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isOwner();
    }

    public function view(User $user, Staff $staff): bool
    {
        return $user->isAdmin() ||
            ($user->isOwner() && $staff->store->owner_id == $user->id);
    }

    public function create(User $user): bool
    {
        return $user->isOwner();
    }

    public function update(User $user, Staff $staff): bool
    {
        return $user->isAdmin() ||
            ($user->isOwner() &&
                $staff->store_id ==
                    $user
                        ->stores()
                        ->where("id", $staff->store_id)
                        ->exists());
    }

    public function delete(User $user, Staff $staff): bool
    {
        return $user->isAdmin() ||
            ($user->isOwner() &&
                $staff->store_id ==
                    $user
                        ->stores()
                        ->where("id", $staff->store_id)
                        ->exists());
    }
}
