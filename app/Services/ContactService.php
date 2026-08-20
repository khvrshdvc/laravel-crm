<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\User;

class ContactService
{
    public function create(array $data, User $user): Contact
    {
        return Contact::create([
            ...$data,
            'created_by' => $user->id,
        ]);
    }

    public function update(Contact $contact, array $data): Contact
    {
        $contact->update($data);
        return $contact;
    }

    public function delete(Contact $contact): void
    {
        $contact->delete();
    }
}