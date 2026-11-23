<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources;

use Carbon\Carbon;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceSiteEventDtoNotConstructableException;

/**
 * A closure or an event of a place, on all its resources or some of them.
 */
class ServiceSiteEventDto extends ApiResponseDto
{
    public int $id;

    public int $serviceSiteId;

    public string $label;

    public Carbon $startsAt;

    public Carbon $endsAt;

    public bool $allResources;

    /** @var array<int, int> */
    public array $resourceIds;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'              => 'id',
            'service_site_id' => 'serviceSiteId',
            'label'           => 'label',
            'starts_at'       => fn (string $startsAt) => $this->startsAt = Carbon::parse($startsAt),
            'ends_at'         => fn (string $endsAt) => $this->endsAt = Carbon::parse($endsAt),
            'all_resources'   => 'allResources',
            'resource_ids'    => 'resourceIds',
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServiceSiteEventDtoNotConstructableException($msg, previous: $previous);
    }
}
