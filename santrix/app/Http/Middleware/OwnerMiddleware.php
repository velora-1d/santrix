<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OwnerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(401);
        }

        // central domain check
        $host = $request->getHost();
        $normalizedHost = str_replace(['http://', 'https://'], '', $host);
        
        $centralDomains = config('tenancy.central_domains', []);

        // allow exact match OR allow localhost variants OR any host starting with owner.
        $isCentral = in_array($normalizedHost, $centralDomains, true) || str_starts_with($normalizedHost, 'owner.');

        if (!$isCentral) {
            // stealth: pretend route not found on tenant subdomain
            abort(404, 'Host ' . $normalizedHost . ' not allowed in OwnerMiddleware');
        }
        
        // role check
        if (($user->role ?? null) !== 'owner') {
             // access to central domain but not owner (e.g. tenant admin trying to access /owner)
            abort(403);
        }

        // Set default parameter for route generation
        \Illuminate\Support\Facades\URL::defaults(['central_domain' => $host]);

        return $next($request);
    }
}
