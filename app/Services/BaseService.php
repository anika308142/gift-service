<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;

abstract class BaseService
{
    abstract public function model();

    public function query(): Builder
    {
        $model = $this->model();

        return (new $model)->query();
    }
}
