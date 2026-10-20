<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\State;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\State\StateDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;

class StateDto extends ApiResponseDto
{
    use HasIncludes;

    public int $id;

    public ?string $name;

    public static function getAvailableIncludes(): array
    {
        return [
            // 'next_states',
            // 'actions',
            // 'workflow',
            // 'next_states_by_user_group_type',
            // 'restricted_next_states',
            // 'actions_by_user_group_type',
            // 'restricted_actions',
            // 'order_seller_informations',
        ];
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'   => 'id',
            'name' => 'name',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'id'   => ['required', 'integer'],
            'name' => ['present', 'nullable', 'string'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new StateDtoNotConstructableException($msg, previous: $previous);
    }
}
