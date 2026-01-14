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
        // Use App('tenant') or session because Auth::user() inside a scope 
        // that applies to the User model causes infinite recursion.
        if (app()->has('tenant')) {
            $builder->where($model->getTable() . '.pesantren_id', app('tenant')->id);
        } elseif (session()->has('pesantren_id')) {
            $builder->where($model->getTable() . '.pesantren_id', session('pesantren_id'));
        }
    }
}
