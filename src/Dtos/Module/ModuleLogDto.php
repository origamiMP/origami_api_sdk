<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Module;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Module\ModuleLogDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Module\ModuleLogDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasTimestamps;

class ModuleLogDto extends ApiResponseDto
{
    use HasTimestamps;

    public int $id;

    public ModuleLogDtoTypeEnum $type;

    public object $log;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'   => 'id',
            'type' => fn ($type) => $this->type = ModuleLogDtoTypeEnum::from($type),
            'log'  => fn (object|array $log) => $this->log = (object) $log,
            ...$this->getTimestampsAsDataStructureToProperties(),
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ModuleLogDtoNotConstructableException($msg, previous: $previous);
    }
}
