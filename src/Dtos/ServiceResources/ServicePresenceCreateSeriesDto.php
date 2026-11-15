<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServicePresenceCreateSeriesDtoNotConstructableException;

/**
 * The presences of a series, one per date, and the dates skipped.
 */
class ServicePresenceCreateSeriesDto extends ApiResponseDto
{
    /** @var Collection<int, ServicePresenceDto> */
    public Collection $data;

    /** @var Collection<int, ServicePresenceCreateSeriesSkippedDto> */
    public Collection $skipped;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'data' => fn ($presences) => $this->initData($presences),
            'meta' => [
                'skipped' => fn ($skipped) => $this->initSkipped($skipped),
            ],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServicePresenceCreateSeriesDtoNotConstructableException($msg, previous: $previous);
    }

    protected function initData(array $presences): void
    {
        $this->data = collect($presences)->map(fn ($presence) => new ServicePresenceDto($presence));
    }

    protected function initSkipped(array $skipped): void
    {
        $this->skipped = collect($skipped)->map(fn ($skippedDate) => new ServicePresenceCreateSeriesSkippedDto($skippedDate));
    }
}
