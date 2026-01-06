<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        // Get current tenant pesantren_id
        $pesantren = app('tenant');
        
        // Get activity logs only from this tenant's users (exclude owner activities)
        $query = ActivityLog::with('user')
            ->whereHas('user', function($q) use ($pesantren) {
                $q->where('pesantren_id', $pesantren->id);
            })
            ->where('log_name', '!=', 'owner') // Exclude owner activities
            ->latest();

        // Filter by log name (model type)
        if ($request->has('model') && $request->model) {
            $query->where('log_name', $request->model);
        }

        // Filter by event type
        if ($request->has('event') && $request->event) {
            $query->where('event', $request->event);
        }

        // Filter by date range
        if ($request->has('from') && $request->from) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->has('to') && $request->to) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $logs = $query->paginate(25)->withQueryString();

        // Get unique model types for filter dropdown (only from this tenant, exclude owner)
        $modelTypes = ActivityLog::select('log_name')
            ->whereHas('user', function($q) use ($pesantren) {
                $q->where('pesantren_id', $pesantren->id);
            })
            ->where('log_name', '!=', 'owner')
            ->distinct()
            ->pluck('log_name');

        return view('admin.activity-log.index', compact('logs', 'modelTypes'));
    }

    public function show(ActivityLog $activityLog)
    {
        return view('admin.activity-log.show', compact('activityLog'));
    }
}
