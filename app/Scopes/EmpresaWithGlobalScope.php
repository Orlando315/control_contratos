<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class EmpresaWithGlobalScope implements Scope
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
      if(Auth::check()){
        $tableColumn = $model->getTable().'.empresa_id';

        $builder->where(function ($query) use ($tableColumn){
          $query->where($tableColumn, Auth::user()->empresa->id)
          ->orWhereNull($tableColumn);
        });
      }
    }
}
