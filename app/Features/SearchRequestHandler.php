<?php

namespace App\Features;

use App\Contracts\DataRequest;
use App\Contracts\RequestHandler;
use App\Services\QueryBuilderService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Spatie\Enum\Laravel\Enum;

/**
 * @template TRequest of DataRequest<TResponse>
 * @template TResponse of ResourceCollection|JsonResource|Response
 * @implements RequestHandler<TRequest, TResponse>
 */
abstract class SearchRequestHandler implements RequestHandler
{
    public function __construct(
        private readonly QueryBuilderService $queryBuilderService
    ) {}

    /**
     * @inheritDoc
     */
    public function handle($request)
    {
        // note: ->all() doesn't transform the dto, all the parsed data is returned as it was parsed. (and validated)
        $requestData = collect($request->all());
        $prohibitedSearchCharacters = collect(["\n", "\\n", "\r", "\t", "\0", "%0A"]);
        if (in_array($requestData->get("q", ""), $prohibitedSearchCharacters->toArray())
            || $prohibitedSearchCharacters->filter(fn($value) => strpos($requestData->get("q", ""), $value) !== false)->count() > 0) {
            throw ValidationException::withMessages([
                "q" => "The q parameter cannot contain any of the following characters: \\n, \\r, \\t, \\0, %0A"
            ]);
        }
        $builder = $this->queryBuilderService->query(
            $this->prepareOrderByParam($requestData)
        );
        $page = $requestData->get("page");
        $limit = $requestData->get("limit");
        $paginator = $this->queryBuilderService->paginateBuilder($builder, $page, $limit);

        return $this->renderResponse($paginator);
    }

    /**
     * @param LengthAwarePaginator $paginator
     * @return TResponse
     */
    protected abstract function renderResponse(LengthAwarePaginator $paginator);

    protected function prepareOrderByParam(Collection $requestData): Collection
    {
        if ($requestData->has('order_by') && !is_null($requestData->get("order_by"))) {
            $requestData->offsetSet("order_by", $requestData->get("order_by")->label);
            return $requestData;
        }

        return $requestData;
    }
}
