<?php

namespace App\Policies;

use App\Models\Note;
use App\Models\User;

class NotePolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    public function update(User $user, Note $note): bool
    {
        return $user->id === $note->created_by;
    }

    public function delete(User $user, Note $note): bool
    {
        return $user->id === $note->created_by;
    }
}