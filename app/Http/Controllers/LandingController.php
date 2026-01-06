<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesantren;
use App\Models\Santri;

class LandingController extends Controller
{
    /**
     * Show the landing page.
     */
    public function index()
    {
        // Data Real from Database
        $stats = [
            'totalPesantren' => Pesantren::count(),
            'totalSantri' => Santri::count(),
            'totalUsers' => \App\Models\User::count(),
        ];

        // Plans from Database (Dynamic Pricing)
        $packages = \App\Models\Package::orderBy('sort_order')->orderBy('price')->get();

        return view('welcome', [
            'stats' => $stats,
            'packages' => $packages,
        ]);
    }
}
