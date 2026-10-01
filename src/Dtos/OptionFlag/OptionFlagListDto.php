<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\OptionFlag;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\OptionFlag\OptionFlagListDtoNotConstructableException;

/**
 * Wraps `GET option_flags`, a flat object (`{"form_builder": true, ...}`) without a `data` key.
 */
class OptionFlagListDto extends ApiResponseDto
{
    /** @var array<string, bool> */
    public array $flags;

    public function __construct(array|object $apiResponse)
    {
        parent::__construct((object) ['flags' => (array) $apiResponse]);
    }

    public function isActive(string $name): bool
    {
        return $this->flags[$name] ?? false;
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'flags' => 'flags',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'flags'   => ['present', 'array'],
            'flags.*' => ['boolean'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new OptionFlagListDtoNotConstructableException($msg, previous: $previous);
    }
}
