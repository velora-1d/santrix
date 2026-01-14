<?php

namespace App\Traits;

use App\Scopes\TenantScope;
use App\Models\Pesantren;
use Illuminate\Support\Facades\Auth;

trait BelongsToPesantren
{
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function bootBelongsToPesantren()
    {
        // Apply the Tenant Scope automatically
        static::addGlobalScope(new TenantScope);

        // Auto-fill pesantren_id when creating new records
        static::creating(function ($model) {
            $pesantrenId = session('pesantren_id') ?: (app()->has('tenant') ? app('tenant')->id : null);
            
            if ($pesantrenId && empty($model->pesantren_id)) {
                $model->pesantren_id = $pesantrenId;
            }
        });
    }

    /**
     * Get the pesantren that owns the model.
     */
    public function pesantren()
    {
        return $this->belongsTo(Pesantren::class);
    }
}
