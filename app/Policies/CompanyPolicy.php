<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\Company;

class CompanyPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Manager]);
    }

    public function view(User $user, Company $company): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Manager]);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Manager]);
    }

    public function update(User $user, Company $company): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Manager]);
    }

    public function delete(User $user, Company $company): bool
    {
        return $user->isAdmin();
    }
}
