<?php

namespace App\Helpers;

use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Cache;

class AcademicHelper
{
    /**
     * Get the currently active Academic Year ID.
     */
    /**
     * Get the currently active Academic Year ID.
     */
    public static function activeYearId()
    {
        $pesantrenId = auth()->check() ? auth()->user()->pesantren_id : null;
        $cacheKey = 'active_tahun_ajaran_id' . ($pesantrenId ? '_' . $pesantrenId : '');
        
        return Cache::remember($cacheKey, 3600, function () use ($pesantrenId) {
            $query = TahunAjaran::where('is_active', true);
            if ($pesantrenId) {
                $query->where('pesantren_id', $pesantrenId);
            }
            $active = $query->first();
            return $active ? $active->id : null;
        });
    }

    /**
     * Get the currently active Academic Year Name (e.g. "2024/2025").
     */
    public static function activeYearName()
    {
        $pesantrenId = auth()->check() ? auth()->user()->pesantren_id : null;
        $cacheKey = 'active_tahun_ajaran_name' . ($pesantrenId ? '_' . $pesantrenId : '');

        return Cache::remember($cacheKey, 3600, function () use ($pesantrenId) {
            $query = TahunAjaran::where('is_active', true);
            if ($pesantrenId) {
                $query->where('pesantren_id', $pesantrenId);
            }
            $active = $query->first();
            return $active ? $active->nama : date('Y') . '/' . (date('Y') + 1);
        });
    }

    /**
     * Get the currently active Academic Year Object.
     */
    public static function activeYear()
    {
        $pesantrenId = auth()->check() ? auth()->user()->pesantren_id : null;
        $cacheKey = 'active_tahun_ajaran' . ($pesantrenId ? '_' . $pesantrenId : '');

        return Cache::remember($cacheKey, 3600, function () use ($pesantrenId) {
            $query = TahunAjaran::where('is_active', true);
            if ($pesantrenId) {
                $query->where('pesantren_id', $pesantrenId);
            }
            return $query->first();
        });
    }
}
