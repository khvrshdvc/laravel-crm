<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ContactService
{
    // Get paginated contacts with filters and optimized relations
    public function getPaginated(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        return Contact::query()
            ->with([
                'company:id,name',
                'createdBy:id,name',
            ])
            ->when(! empty($filters['company_id']), fn($query) => $query->where('company_id', $filters['company_id']))
            ->when(! empty($filters['search']), function ($query) use ($filters) {
                $search = $filters['search'];
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    // Get contact details with relations
    public function getContactDetails(Contact $contact): Contact
    {
        return $contact->load([
            'company:id,name',
            'createdBy:id,name',
            'notes' => fn($query) => $query->with('user:id,name')->latest(),
        ]);
    }

    // Get optimized list of companies for dropdowns
    public function getCompanyOptions(): Collection
    {
        return Company::select('id', 'name')->orderBy('name')->get();
    }

    // Create a new contact
    public function create(array $data, User $user): Contact
    {
        return DB::transaction(fn() => Contact::create([
            ...$data,
            'created_by' => $user->id,
        ]));
    }

    // Update an existing contact
    public function update(Contact $contact, array $data): Contact
    {
        return DB::transaction(function () use ($contact, $data) {
            $contact->update($data);

            return $contact;
        });
    }

    // Delete a contact
    public function delete(Contact $contact): void
    {
        DB::transaction(fn() => $contact->delete());
    }
}
