<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Lead;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

class DashboardService
{
    // Retrieve aggregated dashboard statistics
    public function getStats(): array
    {
        return [
            'companies' => Company::count(),
            'contacts' => Contact::count(),
            'leads' => Lead::count(),
            'active_deals' => Deal::whereNotIn('status', ['won', 'lost'])->count(),
            'won_deals' => Deal::where('status', 'won')->count(),
            'lost_deals' => Deal::where('status', 'lost')->count(),
            'revenue' => Deal::where('status', 'won')->sum('amount'),
        ];
    }

    // Retrieve recent leads with optimized relations
    public function getRecentLeads(): Collection
    {
        return Lead::query()
            ->with([
                'company:id,name',
                'assignedTo:id,name',
            ])
            ->latest()
            ->limit(5)
            ->get();
    }

    // Retrieve tasks due today with assigned user details
    public function getTodayTasks(): Collection
    {
        return Task::query()
            ->with('assignedUser:id,name')
            ->whereDate('due_date', today())
            ->orderBy('due_date')
            ->get();
    }
}
