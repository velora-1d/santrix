<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = ActivityLog::query()
            ->where('subject_type', 'App\\Models\\Pesantren')
            ->with('causer')
            ->latest()
            ->paginate(20);

        return view('owner.logs.index', compact('logs'));
    }
}
