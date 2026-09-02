<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\Contact;

class ContactPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Contact $contact): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Contact $contact): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Manager])
            || $contact->created_by === $user->id;
    }

    public function delete(User $user, Contact $contact): bool
    {
        return $user->isAdmin();
    }
}
