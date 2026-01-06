<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class OwnerSettingsController extends Controller
{
    /**
     * Show landing page settings form
     */
    public function landingPage()
    {
        $stats = [
            'pesantren' => Setting::get('landing_stats_pesantren', 120),
            'santri' => Setting::get('landing_stats_santri', 69000),
            'users' => Setting::get('landing_stats_users', 480),
        ];

        return view('owner.settings.landing-page', compact('stats'));
    }

    /**
     * Update landing page stats
     */
    public function updateLandingStats(Request $request)
    {
        $validated = $request->validate([
            'pesantren' => 'required|integer|min:0',
            'santri' => 'required|integer|min:0',
            'users' => 'required|integer|min:0',
        ], [
            'pesantren.required' => 'Jumlah pesantren harus diisi',
            'pesantren.integer' => 'Jumlah pesantren harus berupa angka',
            'pesantren.min' => 'Jumlah pesantren tidak boleh negatif',
            'santri.required' => 'Jumlah santri harus diisi',
            'santri.integer' => 'Jumlah santri harus berupa angka',
            'santri.min' => 'Jumlah santri tidak boleh negatif',
            'users.required' => 'Jumlah pengguna harus diisi',
            'users.integer' => 'Jumlah pengguna harus berupa angka',
            'users.min' => 'Jumlah pengguna tidak boleh negatif',
        ]);

        Setting::set('landing_stats_pesantren', $validated['pesantren']);
        Setting::set('landing_stats_santri', $validated['santri']);
        Setting::set('landing_stats_users', $validated['users']);

        return back()->with('success', 'Statistik landing page berhasil diperbarui!');
    }
}
