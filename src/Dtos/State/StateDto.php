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

    public ?string $key;

    public ?string $name;

    public ?string $color;

    public ?string $textColor;

    public bool $isFinal;

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
            'id'         => 'id',
            'key'        => 'key',
            'name'       => 'name',
            'color'      => 'color',
            'text_color' => 'textColor',
            'is_final'   => fn ($isFinal) => $this->isFinal = (bool) $isFinal,
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'id'         => ['required', 'integer'],
            'key'        => ['present', 'nullable', 'string'],
            'name'       => ['present', 'nullable', 'string'],
            'color'      => ['present', 'nullable', 'string'],
            'text_color' => ['present', 'nullable', 'string'],
            'is_final'   => ['required', 'boolean'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new StateDtoNotConstructableException($msg, previous: $previous);
    }
}
