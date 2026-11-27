<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Shipping;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Country\CountryDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Shipping\ShippingZoneDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasTimestamps;

class ShippingZoneDto extends ApiResponseDto
{
    use HasIncludes, HasTimestamps;

    public int $id;

    public ?string $postcodes;

    /**
     * May be undefined if the corresponding data was not included.
     *
     * @var Collection<int, ShippingZoneTranslationDto>
     */
    public Collection $translations;

    /**
     * May be undefined if the corresponding data was not included.
     *
     * @var Collection<int, CountryDto>
     */
    public Collection $countries;

    public static function getAvailableIncludes(): array
    {
        return [
            'translations' => ShippingZoneTranslationDto::class,
            'countries'    => CountryDto::class,
        ];
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'           => 'id',
            'postcodes'    => 'postcodes',
            'translations' => fn (object $translations) => $this->translations = $this->collectIncluded($translations, ShippingZoneTranslationDto::class),
            'countries'    => fn (object $countries) => $this->countries = $this->collectIncluded($countries, CountryDto::class),
            ...$this->getTimestampsAsDataStructureToProperties(),
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ShippingZoneDtoNotConstructableException($msg, previous: $previous);
    }
}
