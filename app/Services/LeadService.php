<?php

namespace App\Services;

use App\Enums\LeadStatus;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class LeadService
{
    // Retrieve paginated leads with filters and eager loaded relations
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return Lead::query()
            ->with([
                'company:id,name',
                'contact:id,first_name,last_name',
                'assignedTo:id,name',
                'createdBy:id,name',
            ])
            ->when(! empty($filters['company_id']), fn($query) => $query->where('company_id', $filters['company_id']))
            ->when(! empty($filters['status']), fn($query) => $query->where('status', $filters['status']))
            ->when(! empty($filters['assigned_to']), fn($query) => $query->where('assigned_to', $filters['assigned_to']))
            ->when(! empty($filters['search']), function ($query) use ($filters) {
                $search = $filters['search'];
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    // Retrieve lead details with optimized relations
    public function getLeadDetails(Lead $lead): Lead
    {
        return $lead->load([
            'company:id,name',
            'contact:id,first_name,last_name',
            'deal:id,title',
            'notes' => fn($query) => $query->with('user:id,name')->latest(),
        ]);
    }

    // Retrieve dropdown options for forms
    public function getFormDataOptions(): array
    {
        return [
            'companies' => Company::select('id', 'name')->orderBy('name')->get(),
            'contacts' => Contact::select('id', 'first_name', 'last_name')->orderBy('first_name')->get(),
            'users' => User::select('id', 'name')->orderBy('name')->get(),
        ];
    }

    // Create a new lead
    public function create(array $data, User $user): Lead
    {
        return DB::transaction(fn() => Lead::create([
            ...$data,
            'created_by' => $user->id,
        ]));
    }

    // Update an existing lead
    public function update(Lead $lead, array $data): Lead
    {
        return DB::transaction(function () use ($lead, $data) {
            $lead->update($data);

            return $lead;
        });
    }

    // Update lead status
    public function updateStatus(Lead $lead, LeadStatus $status): Lead
    {
        return DB::transaction(function () use ($lead, $status) {
            $lead->update([
                'status' => $status->value,
            ]);

            return $lead;
        });
    }

    // Delete a lead
    public function delete(Lead $lead): void
    {
        DB::transaction(fn() => $lead->delete());
    }

    // Convert lead to deal
    public function convertToDeal(Lead $lead, array $dealData, User $user): Deal
    {
        return DB::transaction(function () use ($lead, $dealData, $user) {
            $deal = Deal::create([
                ...$dealData,
                'lead_id' => $lead->id,
                'company_id' => $lead->company_id,
                'contact_id' => $lead->contact_id,
                'created_by' => $user->id,
            ]);

            $lead->update([
                'status' => LeadStatus::Converted,
            ]);

            return $deal;
        });
    }
}
