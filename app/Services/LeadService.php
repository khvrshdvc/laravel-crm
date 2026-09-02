<?php

namespace App\Services;

use App\Enums\LeadStatus;
use App\Models\Deal;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class LeadService
{

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return Lead::query()
            ->with(['company', 'contact', 'assignedTo', 'createdBy'])
            ->when(!empty($filters['company_id']), function ($query) use ($filters) {
                $query->where('company_id', $filters['company_id']);
            })
            ->when(!empty($filters['status']), function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            })
            ->when(!empty($filters['assigned_to']), function ($query) use ($filters) {
                $query->where('assigned_to', $filters['assigned_to']);
            })
            ->when(!empty($filters['search']), function ($query) use ($filters) {
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

    public function create(array $data, User $user): Lead
    {
        return DB::transaction(function () use ($data, $user) {
            return Lead::create([
                ...$data,
                'created_by' => $user->id,
            ]);
        });
    }

    public function update(Lead $lead, array $data): Lead
    {
        return DB::transaction(function () use ($lead, $data) {
            $lead->update($data);
            return $lead;
        });
    }

    public function updateStatus(Lead $lead, LeadStatus $status): Lead
    {
        return DB::transaction(function () use ($lead, $status) {
            $lead->update([
                'status' => $status->value,
            ]);
            return $lead;
        });
    }

    public function delete(Lead $lead): void
    {
        DB::transaction(function () use ($lead) {
            $lead->delete();
        });
    }

    public function convertToDeal(
        Lead $lead,
        array $dealData,
        User $user
    ): Deal {
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
