<?php

namespace App\Dto;


use App\Concerns\HasRequestFingerprint;
use App\Contracts\DataRequest;
use Illuminate\Http\JsonResponse;
use Spatie\LaravelData\Data;

/**
 * @implements DataRequest<JsonResponse>
 */
final class QueryRecentlyAddedEpisodesCommand extends Data implements DataRequest
{
    use HasRequestFingerprint;
}
