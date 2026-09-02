<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    // Display the main dashboard with statistics and recent records
    public function index(): View
    {
        $stats = $this->dashboardService->getStats();
        $recentLeads = $this->dashboardService->getRecentLeads();
        $todayTasks = $this->dashboardService->getTodayTasks();

        return view('dashboard', compact('stats', 'recentLeads', 'todayTasks'));
    }
}
