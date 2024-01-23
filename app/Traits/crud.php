<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait crud
{
    protected Request $request;
    protected Builder $paginateQuery;
    protected Model|Builder|string|int $modelOrBuilder;
    protected array $only = [];
    protected array $except = [];
    public Builder $builder;
    protected bool $simplePaginate = false;
    /**
     * @param $query
     * @param $request
     * @param  false  $simplePaginate
     * @return mixed
     */
    public function paginate($query, $request, bool $simplePaginate = false): mixed
    {
        $this->request = $request;
        $this->paginateQuery = $query;
        $this->simplePaginate = $simplePaginate ?? $this->request->get('simple_pagination', false);

        $this->callHook('beforePaginate', $this->paginateQuery, $this->request, $this->simplePaginate);

        $limit = $this->request->get('limit', config('eloquentfilter.paginate_limit'));

        if ($limit == -1) { // for without pagination support
            return $this->paginateQuery->get();
        }

        if ($this->request->has('offset')) {
            $this->paginateQuery = $this->paginateQuery->limit($limit);

            if ($offset = $this->request->get('offset')) {
                $this->paginateQuery = $this->paginateQuery->offset($offset);
            }
        }

        $this->callHook('afterPaginate', $this->paginateQuery, $this->request, $this->simplePaginate);
        if ($this->simplePaginate) {
            return $this->paginateQuery->simplePaginateFilter($limit);
        }

        return $this->paginateQuery->paginateFilter($limit);
    }

    /**
     * @param $request
     * @return mixed
     */
    public function all($request): mixed
    {
        $this->request = $request;
        $this->builder = $this->query();

        $this->callHook('beforeAllQuery', $this->builder, $this->request);

        $this->modelOrBuilder = $this->builder->filter($request->all());

        $this->callHook('afterAllQuery', $this->modelOrBuilder, $this->request);

        $this->callHook('beforeAll', $this->modelOrBuilder, $this->request);

        return $this->paginate($this->modelOrBuilder, $request);
    }

    /**
     * @param $modelOrModelId
     * @param  Request|null  $request
     * @return Model|Collection|Builder|array|null
     */
    public function details($modelOrModelId, Request $request = null): Model|Collection|Builder|array|null
    {
        $this->request = $request;
        $this->modelOrBuilder = $modelOrModelId;
        $this->builder = $this->query();

        try {
            $this->callHook('beforeDetails', $this->modelOrBuilder, $this->request);

            $this->callHook('beforeDetailsQuery', $this->modelOrBuilder, $modelOrModelId, $this->request);

            if (!($this->modelOrBuilder instanceof Model)) {
                $this->modelOrBuilder = $this->builder->where(app($this->model())->getKeyName(), $modelOrModelId);
            }

            $this->callHook('afterDetailsQuery', $this->modelOrBuilder, $modelOrModelId, $this->request);

            $model = ($this->modelOrBuilder instanceof Builder) ? $this->modelOrBuilder->firstOrFail() : $this->modelOrBuilder;

            $this->callHook('afterDetails', $model, $this->request);

            return $model;
        } catch (\Exception $exception) {
            throw new NotFoundHttpException();
        }
    }

    public function cleanRequest(Request $request, $only = [], $except = []): array
    {
        return blank($only) ? ( blank($except) ? $request->all() : $request->except($except) ) : $request->only($only);
    }

    public function store(Request $request, $only = [], $except = []): Model|Builder
    {
        $this->modelOrBuilder = $this->query();
        $this->request = $request;
        $this->only = $only;
        $this->except = $except;
        $this->callHook('beforeStore', $this->modelOrBuilder, $this->request, $this->only, $this->except);

        $this->modelOrBuilder = $this->query()->create($this->cleanRequest($this->request, $this->only, $this->except));

        $this->callHook('afterStore', $this->modelOrBuilder, $request, $only, $except);

        return $this->modelOrBuilder;
    }

    public function update($modelOrModelId, Request $request, $only = [], $except = []): Model|Builder
    {
        $this->request = $request;
        $this->only = $only;
        $this->except = $except;
        $this->modelOrBuilder = $this->details($modelOrModelId, $request);
        $this->callHook('beforeUpdate', $this->modelOrBuilder, $this->request, $this->only, $this->except);
        $this->modelOrBuilder = tap($this->modelOrBuilder)->update($this->cleanRequest($this->request, $this->only, $this->except));
        $this->callHook('afterUpdate', $this->modelOrBuilder, $this->request, $this->only, $this->except);

        return $this->modelOrBuilder;
    }

    public function delete($programId, $request)
    {
        $model = $this->details($programId, $request);
        $this->callHook('beforeDelete', $programId, $request);
        $model->delete();
        $this->callHook('afterDelete', $programId, $request);

        return $model;
    }

    protected function callHook(string $hook, ...$parameters): void
    {
        if (! method_exists($this, $hook)) {
            return;
        }

        $this->{$hook}(...$parameters);
    }
}
