<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboardService)
    {
    }

    public function index()
    {
        $stats = $this->dashboardService->getStats();
        $recentLeads = $this->dashboardService->getRecentLeads();
        $todayTasks = $this->dashboardService->getTodayTasks();

        return view('dashboard', compact('stats', 'recentLeads', 'todayTasks'));
    }
}