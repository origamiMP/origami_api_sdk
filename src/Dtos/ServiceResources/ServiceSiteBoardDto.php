<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceSiteBoardDtoNotConstructableException;

/**
 * What a place does over a period: its resources, the slots of its providers with their bookings, and its events.
 */
class ServiceSiteBoardDto extends ApiResponseDto
{
    public ServiceSiteBoardSiteDto $site;

    /** @var Collection<int, ServiceSiteBoardResourceDto> */
    public Collection $resources;

    /** @var Collection<int, ServiceSiteBoardSlotDto> */
    public Collection $slots;

    /** @var Collection<int, ServiceSiteBoardEventDto> */
    public Collection $events;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'site'      => fn (object $site) => $this->site = new ServiceSiteBoardSiteDto($site),
            'resources' => fn (array $resources) => $this->initResources($resources),
            'slots'     => fn (array $slots) => $this->initSlots($slots),
            'events'    => fn (array $events) => $this->initEvents($events),
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServiceSiteBoardDtoNotConstructableException($msg, previous: $previous);
    }

    protected function initResources(array $resources): void
    {
        $this->resources = collect($resources)->map(fn (object $resource) => new ServiceSiteBoardResourceDto($resource));
    }

    protected function initSlots(array $slots): void
    {
        $this->slots = collect($slots)->map(fn (object $slot) => new ServiceSiteBoardSlotDto($slot));
    }

    protected function initEvents(array $events): void
    {
        $this->events = collect($events)->map(fn (object $event) => new ServiceSiteBoardEventDto($event));
    }
}
