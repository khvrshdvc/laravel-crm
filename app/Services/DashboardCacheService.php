<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class DashboardCacheService
{
    public static function flush(): void
    {
        Cache::forget('dashboard.stats');
        Cache::forget('dashboard.recent_leads');
        Cache::forget('dashboard.today_tasks');
    }
}
