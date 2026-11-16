<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Workflow;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Workflow\StateDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;

class StateDto extends ApiResponseDto
{
    use HasIncludes;

    public int $id;

    public string $key;

    public ?string $color;

    public bool $initial;

    public bool $isFinal;

    public bool $readonly;

    public int $workflowId;

    public string $textColor;

    public ?string $name;

    public ?string $actionName;

    public ?string $description;

    public static function getAvailableIncludes(): array
    {
        return [
            // 'translations',
            // 'next_states',
            // 'actions',
            // 'workflow',
            // 'next_states_by_user_group_type',
            // 'restricted_next_states',
            // 'actions_by_user_group_type',
            // 'restricted_actions',
            // 'order_seller_informations',
            // 'service_booking_informations',
        ];
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'          => 'id',
            'key'         => 'key',
            'color'       => 'color',
            'initial'     => 'initial',
            'is_final'    => 'isFinal',
            'readonly'    => 'readonly',
            'workflow_id' => 'workflowId',
            'text_color'  => 'textColor',
            'name'        => 'name',
            'action_name' => 'actionName',
            'description' => 'description',
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new StateDtoNotConstructableException($msg, previous: $previous);
    }
}
