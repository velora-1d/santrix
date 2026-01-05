<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Pesantren;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class PesantrenController extends Controller
{
    public function index(Request $request)
    {
        $query = Pesantren::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('subdomain', 'like', "%{$search}%")
                  ->orWhereHas('admin', function($qa) use ($search) {
                      $qa->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter: Status
        if ($request->filled('status')) {
             $today = now();
             if ($request->status == 'active') {
                 $query->whereDate('expired_at', '>=', $today)->where('status', 'active');
             } elseif ($request->status == 'expired') {
                 $query->whereDate('expired_at', '<', $today);
             } elseif ($request->status == 'suspended') {
                 $query->where('status', 'suspended');
             }
        }

        // Filter: Package
        if ($request->filled('package')) {
            $query->where('package', $request->package);
        }

        $pesantrens = $query->with('admin')->latest()->paginate(20);

        $packages = \App\Models\Package::orderBy('sort_order')->get();
        return view('owner.pesantren.index', compact('pesantrens', 'packages'));
    }

    public function show($id)
    {
        $pesantren = Pesantren::with(['subscriptions', 'invoices', 'admin'])->findOrFail($id);
        return view('owner.pesantren.show', compact('pesantren'));
    }

    public function edit($id)
    {
        $pesantren = Pesantren::findOrFail($id);
        return view('owner.pesantren.edit', compact('pesantren'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'package' => 'required|in:basic,advance,enterprise,trial',
            'expired_at' => 'required|date',
            'status' => 'required|in:active,suspended',
            'bank_name' => 'nullable|string|max:100',
            'account_number' => 'nullable|string|max:50',
            'account_name' => 'nullable|string|max:100',
        ];

        // Advance Package Funding Requirement
        if ($request->package === 'advance' || $request->package === 'enterprise') {
            $rules['bank_name'] = 'required';
            $rules['account_number'] = 'required';
            $rules['account_name'] = 'required';
        }

        $request->validate($rules);

        $pesantren = Pesantren::findOrFail($id);
        $pesantren->update([
            'package' => $request->package,
            'expired_at' => $request->expired_at,
            'status' => $request->status,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
        ]);

        ActivityLog::logActivity(
            'Updated subscription for tenant: ' . $pesantren->nama,
            $pesantren,
            ['old' => $pesantren->getOriginal(), 'new' => $pesantren->getAttributes()],
            'updated'
        );

        return redirect()->route('owner.pesantren.show', $id)->with('success', 'Tenant updated successfully.');
    }

    public function suspend($id)
    {
        $pesantren = Pesantren::findOrFail($id);
        
        $newStatus = $pesantren->status === 'suspended' ? 'active' : 'suspended';
        $pesantren->update(['status' => $newStatus]);
        
        $message = $newStatus === 'suspended' ? 'Tenant has been suspended.' : 'Tenant has been reactivated.';

        ActivityLog::logActivity(
            $newStatus === 'suspended' ? 'Suspended tenant: ' . $pesantren->nama : 'Reactivated tenant: ' . $pesantren->nama,
            $pesantren,
            ['status' => $newStatus],
            $newStatus === 'suspended' ? 'suspended' : 'reactivated'
        );

        return back()->withErrors(['message' => $message]);
    }

    public function destroy($id)
    {
        $pesantren = Pesantren::findOrFail($id);
        
        // Log activity
        ActivityLog::logActivity(
            'Deleted tenant: ' . $pesantren->nama,
            $pesantren,
            ['id' => $pesantren->id, 'nama' => $pesantren->nama, 'subdomain' => $pesantren->subdomain],
            'deleted'
        );
        
        // Note: Ensure database foreign keys have ON DELETE CASCADE or handle relations here
        // For safety, let's delete the admin user associated if exists
        if ($pesantren->admin) {
             $pesantren->admin->delete();
        }

        $pesantren->delete();

        return redirect()->route('owner.pesantren.index')->with('success', 'Tenant ' . $pesantren->nama . ' has been deleted successfully.');
    }
}
