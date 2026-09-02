<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ContactService
{
    public function getPaginated(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        return Contact::query()
            ->with(['company', 'createdBy'])
            ->when(!empty($filters['company_id']), function ($query) use ($filters) {
                $query->where('company_id', $filters['company_id']);
            })
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $search = $filters['search'];
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data, User $user): Contact
    {
        return DB::transaction(function () use ($data, $user) {
            return Contact::create([
                ...$data,
                'created_by' => $user->id,
            ]);
        });
    }

    public function update(Contact $contact, array $data): Contact
    {
        return DB::transaction(function () use ($contact, $data) {
            $contact->update($data);
            return $contact;
        });
    }

    public function delete(Contact $contact): void
    {
        DB::transaction(function () use ($contact) {
            $contact->delete();
        });
    }
}
