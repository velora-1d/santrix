<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class LandingController extends Controller
{
    /**
     * Show the landing page.
     */
    public function index()
    {
        // Get stats from settings (editable from owner dashboard)
        $stats = [
            'pesantren' => Setting::get('landing_stats_pesantren', 120),
            'santri' => Setting::get('landing_stats_santri', 69000),
            'users' => Setting::get('landing_stats_users', 480),
        ];

        // Plans from Database (Dynamic Pricing)
        $packages = \App\Models\Package::orderBy('sort_order')->orderBy('price')->get();

        return view('welcome', [
            'stats' => $stats,
            'packages' => $packages,
        ]);
    }
}


