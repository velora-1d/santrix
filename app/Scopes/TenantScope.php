<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        // Only apply if user is logged in
        if (Auth::check()) {
            $user = Auth::user();

            // Only apply if user belongs to a pesantren (Tenant User)
            // Owner and Admin Pusat might be exceptions depending on design, 
            // but for now, we assume everyone using the models is scoped.
            // If Owner needs to see all, we can add a check here.
            
            if ($user->pesantren_id) {
                $builder->where($model->getTable() . '.pesantren_id', $user->pesantren_id);
            }
        }
    }
}
