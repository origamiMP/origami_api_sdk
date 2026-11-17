<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceResourceUpdateDtoNotConstructableException;

/**
 * The resource updated, and the booked slots of a deactivated resource that no other resource could take, for the team
 * to handle.
 */
class ServiceResourceUpdateDto extends ApiResponseDto
{
    public ServiceResourceDto $data;

    /** @var array<int, int> */
    public array $bookedSlotIdsToHandle;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'data' => fn (object $resource) => $this->data = new ServiceResourceDto($resource),
            'meta' => [
                'booked_slot_ids_to_handle' => 'bookedSlotIdsToHandle',
            ],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServiceResourceUpdateDtoNotConstructableException($msg, previous: $previous);
    }
}
