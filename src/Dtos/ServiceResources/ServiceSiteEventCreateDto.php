<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceSiteEventCreateDtoNotConstructableException;

/**
 * The event created, and the booked slots it closes that no other resource could take, for the team to handle.
 */
class ServiceSiteEventCreateDto extends ApiResponseDto
{
    public ServiceSiteEventDto $data;

    /** @var array<int, int> */
    public array $bookedSlotIdsToHandle;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'data' => fn (object $event) => $this->data = new ServiceSiteEventDto($event),
            'meta' => [
                'booked_slot_ids_to_handle' => 'bookedSlotIdsToHandle',
            ],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServiceSiteEventCreateDtoNotConstructableException($msg, previous: $previous);
    }
}
