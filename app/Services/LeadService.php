<?php

namespace App\Services;

use App\Enums\LeadStatus;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class LeadService
{
    /**
     * Filtrlangan va paginatsiya qilingan Lead'lar ro'yxatini olish.
     */
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
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Yangi Lead yaratish.
     */
    public function create(array $data, User $user): Lead
    {
        return DB::transaction(function () use ($data, $user) {
            return Lead::create([
                ...$data,
                'created_by' => $user->id,
            ]);
        });
    }

    /**
     * Lead ma'lumotlarini yangilash.
     */
    public function update(Lead $lead, array $data): Lead
    {
        return DB::transaction(function () use ($lead, $data) {
            $lead->update($data);
            return $lead;
        });
    }

    /**
     * Faqat Lead statusini yangilash.
     */
    public function updateStatus(Lead $lead, LeadStatus $status): Lead
    {
        return DB::transaction(function () use ($lead, $status) {
            $lead->update([
                'status' => $status->value,
            ]);
            return $lead;
        });
    }

    /**
     * Lead'ni o'chirish.
     */
    public function delete(Lead $lead): void
    {
        DB::transaction(function () use ($lead) {
            $lead->delete();
        });
    }
}
