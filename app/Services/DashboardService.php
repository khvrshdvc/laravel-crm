<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\Deal;
use App\Models\Task;
use Laravel\Prompts\Task as PromptsTask;

class DashboardService
{
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

    public function getRecentLeads()
    {
        return Lead::with(['company', 'assignedTo'])->latest()->limit(5)->get();
    }

    public function getTodayTasks()
    {
        return Task::with('assignedUser')
            ->whereDate('due_date', today())
            ->orderBy('due_date')
            ->get();
    }
}
