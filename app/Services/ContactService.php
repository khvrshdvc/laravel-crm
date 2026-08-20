<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ContactService
{
    /**
     * Filtrlangan va paginatsiya qilingan kontaktlar ro'yxatini olish.
     */
    public function getPaginated(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        return Contact::query()
            ->with(['company', 'createdBy'])
            ->when(!empty($filters['company_id']), function ($query) use ($filters) {
                $query->where('company_id', $filters['company_id']);
            })
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Yangi kontakt yaratish (xavfsiz tranzaksiya bilan).
     */
    public function create(array $data, User $user): Contact
    {
        return DB::transaction(function () use ($data, $user) {
            return Contact::create([
                ...$data,
                'created_by' => $user->id,
            ]);
        });
    }

    /**
     * Mavjud kontaktni yangilash.
     */
    public function update(Contact $contact, array $data): Contact
    {
        return DB::transaction(function () use ($contact, $data) {
            $contact->update($data);
            return $contact;
        });
    }

    /**
     * Kontaktni o'chirish.
     */
    public function delete(Contact $contact): void
    {
        DB::transaction(function () use ($contact) {
            $contact->delete();
        });
    }
}