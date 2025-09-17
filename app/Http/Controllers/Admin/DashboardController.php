<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TenantStatisticsService;

class DashboardController extends Controller
{
    public function __construct(protected TenantStatisticsService $stats) {}

    public function index()
    {
        $overview = $this->stats->getOverview();
        return view('admin.dashboard', compact('overview'));
    }
}


