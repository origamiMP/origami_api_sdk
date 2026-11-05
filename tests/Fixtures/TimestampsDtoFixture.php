<?php

namespace Tests\Fixtures;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasTimestamps;

/**
 * Minimal Dto with timestamps, to test the HasTimestamps trait: it only reads `created_at` and `updated_at`.
 */
class TimestampsDtoFixture extends ApiResponseDto
{
    use HasTimestamps;

    protected function getDefaultDataStructureToProperties(): array
    {
        return $this->getTimestampsAsDataStructureToProperties();
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new class($msg, previous: $previous) extends ApiResponseDtoNotConstructableException {};
    }
}
