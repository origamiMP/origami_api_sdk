<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\State;

use Carbon\Carbon;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\State\StateLogDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;

class StateLogDto extends ApiResponseDto
{
    use HasIncludes;

    public int $id;

    public ?string $userGroupType;

    public ?string $message;

    public Carbon $createdAt;

    /**
     * Default include of the API: it replaces the `to_state` id of the log.
     */
    public StateDto $toState;

    public static function getAvailableIncludes(): array
    {
        return [
            'to_state' => StateDto::class,
        ];
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'              => 'id',
            'user_group_type' => 'userGroupType',
            'message'         => 'message',
            'created_at'      => fn ($createdAt) => $this->createdAt = Carbon::parse($createdAt),
            'to_state'        => fn (object $toState) => $this->toState = $this->makeIncluded($toState, StateDto::class),
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'id'              => ['required', 'integer'],
            'user_group_type' => ['present', 'nullable', 'string'],
            'message'         => ['present', 'nullable', 'string'],
            'created_at'      => ['required', 'date'],
            'to_state'        => ['required'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new StateLogDtoNotConstructableException($msg, previous: $previous);
    }
}
