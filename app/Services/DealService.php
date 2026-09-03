<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class DealService
{
    // Get paginated deals with search/filter criteria and optimized eager loading
    public function getPaginatedDeals(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return Deal::query()
            ->with([
                'company:id,name',
                'contact:id,first_name,last_name',
                'assignedUser:id,name',
            ])
            ->when(! empty($filters['search']), fn($query) => $query->where('title', 'like', "%{$filters['search']}%"))
            ->when(! empty($filters['status']), fn($query) => $query->where('status', $filters['status']))
            ->when(! empty($filters['assigned_to']), fn($query) => $query->where('assigned_to', $filters['assigned_to']))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    // Get deal details with relations
    public function getDealDetails(Deal $deal): Deal
    {
        return $deal->load([
            'lead:id,title',
            'company:id,name',
            'contact:id,first_name,last_name',
            'assignedUser:id,name',
            'creator:id,name',
            'notes' => fn($query) => $query->with('user:id,name')->latest(),
        ]);
    }

    // Get dropdown options for forms
    public function getFormDataOptions(): array
    {
        return [
            'companies' => Company::select('id', 'name')->orderBy('name')->get(),
            'contacts' => Contact::select('id', 'first_name', 'last_name')->orderBy('first_name')->get(),
            'users' => User::select('id', 'name')->orderBy('name')->get(),
        ];
    }

    // Create a new deal
    public function create(array $data, User $user): Deal
    {
        $deal = Deal::create([
            ...$data,
            'created_by' => $user->id,
        ]);

        DashboardCacheService::flush();

        return $deal;
    }

    // Update an existing deal
    public function update(Deal $deal, array $data): Deal
    {
        $deal->update($data);

        DashboardCacheService::flush();

        return $deal->fresh();
    }

    // Delete a deal
    public function delete(Deal $deal): void
    {
        $deal->delete();

        DashboardCacheService::flush();
    }
}
