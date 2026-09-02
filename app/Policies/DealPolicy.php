<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Deal;
use App\Models\User;

class DealPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Manager]);
    }

    public function view(User $user, Deal $deal): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Manager]);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Manager]);
    }

    public function update(User $user, Deal $deal): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Manager]);
    }

    public function delete(User $user, Deal $deal): bool
    {
        return $user->isAdmin();
    }
}
