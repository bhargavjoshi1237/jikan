<?php

namespace App\Services;

use App\Concerns\ResolvesPaginatorParams;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class EloquentBuilderPaginatorService implements QueryBuilderPaginatorService
{
    use ResolvesPaginatorParams;

    public function paginate(\Illuminate\Database\Eloquent\Builder|\Laravel\Scout\Builder $builder, ?int $limit = null, ?int $page = null): LengthAwarePaginator
    {
        ["limit" => $limit, "page" => $page] = $this->getPaginatorParams($limit, $page);

        return $builder->paginate($limit, ['*'], null, $page);
    }
}
