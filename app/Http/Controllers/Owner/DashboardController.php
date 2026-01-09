<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Pesantren;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $today = Carbon::today();
            $expiringThreshold = $today->copy()->addDays(14);

            $total = Pesantren::count();

            $active = Pesantren::whereDate('expired_at', '>=', $today)->count();

            $expiring = Pesantren::whereDate('expired_at', '>=', $today)
                ->whereDate('expired_at', '<=', $expiringThreshold)
                ->count();

            $expired = Pesantren::whereDate('expired_at', '<', $today)->count();

            $revenue = \App\Models\Invoice::where('status', 'paid')
                ->whereMonth('paid_at', Carbon::now()->month)
                ->whereYear('paid_at', Carbon::now()->year)
                ->sum('amount');
        } catch (\Exception $e) {
            // Fallback if tables don't exist yet
            $total = $active = $expiring = $expired = 0;
            $revenue = 0;
            \Illuminate\Support\Facades\Log::error('Owner Dashboard Error: ' . $e->getMessage());
        }

        return view('owner.dashboard.index', compact('total', 'active', 'expiring', 'expired', 'revenue'));
    }
}
