<?php

namespace App\Services;

use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Support\Carbon as SupportCarbon;

class TenantStatisticsService
{
    public function getOverview(): array
    {
        $total = Tenant::count();
        $thisMonth = Tenant::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
        $demo = Tenant::where('type', 'demo')->count();
        $paid = Tenant::where('type', 'paid')->count();
        $monthlyRevenue = (float) Tenant::where('type', 'paid')->where(function ($q) {
            $q->whereNull('expired_date')->orWhere('expired_date', '>=', now());
        })->sum('monthly_payment');

        $months = collect(range(0, 11))->map(function ($i) {
            return now()->copy()->subMonths($i)->startOfMonth();
        })->reverse();

        $tenantsPerMonth = [];
        foreach ($months as $month) {
            $key = $month->format('Y-m');
            $count = Tenant::whereBetween('created_at', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])->count();
            $tenantsPerMonth[$key] = $count;
        }

        return [
            'total_tenants' => $total,
            'this_month' => $thisMonth,
            'demo' => $demo,
            'paid' => $paid,
            'monthly_revenue' => round($monthlyRevenue, 2),
            'tenants_per_month' => $tenantsPerMonth,
        ];
    }
}


