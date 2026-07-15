<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class HaflahScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if ($haflahId = session('haflah_id')) {
            $builder->where($model->getTable().'.haflah_id', $haflahId);
        }
    }
}
