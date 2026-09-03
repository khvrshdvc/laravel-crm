<?php

namespace App\Services;

use App\Models\Company;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CompanyService
{
    // Retrieve paginated companies filtered by search criteria
    public function getPaginatedCompanies(?string $search = null): LengthAwarePaginator
    {
        return Company::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();
    }

    // Retrieve company details with eager loaded relationships
    public function getCompanyDetails(Company $company): Company
    {
        return $company->loadCount(['contacts', 'leads', 'deals', 'tasks', 'notes'])
            ->load([
                'contacts:id,company_id,first_name,last_name,email,phone',
                'deals:id,company_id,title,amount,status',
                'tasks:id,taskable_id,taskable_type,title,status,priority,due_date',
                'notes' => fn($query) => $query->with('user:id,name')->latest(),
            ]);
    }

    // Create a new company
    public function create(array $data, User $user): Company
    {
        $company = Company::create([
            ...$data,
            'created_by' => $user->id,
        ]);

        DashboardCacheService::flush();

        return $company;
    }

    // Update an existing company
    public function update(Company $company, array $data): Company
    {
        $company->update($data);

        DashboardCacheService::flush();

        return $company;
    }

    // Delete a company
    public function delete(Company $company): void
    {
        $company->delete();

        DashboardCacheService::flush();
    }
}
