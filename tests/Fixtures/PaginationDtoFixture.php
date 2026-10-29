<?php

namespace Tests\Fixtures;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasPagination;

/**
 * Minimal paginated list Dto, to test the HasPagination trait of Dtos: its items are `IncludesDtoFixture`.
 */
class PaginationDtoFixture extends ApiResponseDto
{
    use HasPagination;

    protected function getDefaultDataStructureToProperties(): array
    {
        return $this->getPaginationAsDataStructureToProperties();
    }

    protected function validationRulesForProperties(): array
    {
        return $this->getPaginationValidationRules();
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new class($msg, previous: $previous) extends ApiResponseDtoNotConstructableException {};
    }

    protected function initData(array $data): void
    {
        $this->data = collect($data)->map(fn (object $item) => new IncludesDtoFixture($item));
    }
}
