<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequiresPackage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $requiredPackage  (e.g., 'advance')
     */
    public function handle(Request $request, Closure $next, string $requiredPackage): Response
    {
        $user = $request->user();
        
        if (!$user || !$user->pesantren_id) {
            abort(403, 'No pesantren associated with this account.');
        }
        
        $pesantren = \App\Models\Pesantren::find($user->pesantren_id);
        
        if (!$pesantren) {
            abort(403, 'Invalid pesantren.');
        }
        
        // Check if package meets requirement
        if ($requiredPackage === 'advance') {
            // Check if user has any advance package (advance-3 or advance-6)
            if (!str_starts_with($pesantren->package, 'advance')) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'Fitur ini hanya tersedia untuk paket ADVANCE.',
                        'upgrade_url' => route('admin.billing.plans')
                    ], 403);
                }
                
                return redirect()
                    ->route('admin.billing.plans')
                    ->with('error', 'Fitur ini hanya tersedia untuk paket ADVANCE. Silakan upgrade paket Anda.');
            }
        }
        
        return $next($request);
    }
}
