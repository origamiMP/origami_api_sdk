<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Customer;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Customer\CustomerGroupDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;

class CustomerGroupDto extends ApiResponseDto
{
    use HasIncludes;

    public int $id;

    public ?string $externalUid;

    /**
     * Whether it is the default group of the customer it was included from (`customer_groups` include of
     * `CustomerDto` or `UserGroupDto`); null otherwise.
     */
    public ?bool $isDefault = null;

    /**
     * May be undefined if the corresponding data was not included.
     *
     * @var Collection<int, CustomerGroupTranslationDto>
     */
    public Collection $translations;

    public static function getAvailableIncludes(): array
    {
        return [
            'translations' => CustomerGroupTranslationDto::class,
        ];
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'           => 'id',
            'external_uid' => 'externalUid',
            'is_default'   => fn ($isDefault) => $this->isDefault = is_null($isDefault) ? null : (bool) $isDefault,
            'translations' => fn (object $translations) => $this->translations = $this->collectIncluded($translations, CustomerGroupTranslationDto::class),
        ];
    }

    protected function getOptionalKeys(): array
    {
        return [
            'is_default',
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new CustomerGroupDtoNotConstructableException($msg, previous: $previous);
    }
}
