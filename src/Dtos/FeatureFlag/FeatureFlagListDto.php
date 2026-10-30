<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\FeatureFlag;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\FeatureFlag\FeatureFlagListDtoNotConstructableException;

class FeatureFlagListDto extends ApiResponseDto
{
    /** @var array<string, bool> */
    public array $flags;

    public function __construct(object $apiResponse)
    {
        parent::__construct((object) ['flags' => (array) $apiResponse]);
    }

    public function isFlagActive(string $name): bool
    {
        return $this->flags[$name] ?? false;
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'flags' => fn (array $flags) => $this->initFlags($flags),
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new FeatureFlagListDtoNotConstructableException($msg, previous: $previous);
    }

    /**
     * @throws FeatureFlagListDtoNotConstructableException
     */
    protected function initFlags(array $flags): void
    {
        foreach ($flags as $name => $state) {
            if (! is_bool($state)) {
                throw self::getDefaultNotConstructableException("The state of the flag '{$name}' is not a boolean.");
            }
        }

        $this->flags = $flags;
    }
}
