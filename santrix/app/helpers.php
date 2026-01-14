<?php

if (!function_exists('tenant')) {
    /**
     * Get the current tenant (pesantren) instance
     */
    function tenant() {
        return app()->has('tenant') ? app('tenant') : null;
    }
}

if (!function_exists('tenant_logo')) {
    /**
     * Get the current tenant's logo URL
     */
    function tenant_logo() {
        $tenant = tenant();
        return $tenant?->logo_url ?? asset('images/default-logo.png');
    }
}

if (!function_exists('tenant_name')) {
    /**
     * Get the current tenant's name
     */
    function tenant_name() {
        $tenant = tenant();
        return $tenant?->nama ?? 'Pondok Pesantren';
    }
}
