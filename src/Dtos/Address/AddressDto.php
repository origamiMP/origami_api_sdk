<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Address;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Address\AddressDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;

class AddressDto extends ApiResponseDto
{
    public int $id;

    public ?string $addressLine1;

    public ?string $addressLine2;

    public ?string $postcode;

    public ?string $city;

    public ?string $country;

    public ?string $countryIso;

    public ?string $region;

    public ?float $latitude;

    public ?float $longitude;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'            => 'id',
            'address_line1' => 'addressLine1',
            'address_line2' => 'addressLine2',
            'postcode'      => 'postcode',
            'city'          => 'city',
            'country'       => 'country',
            'country_iso'   => 'countryIso',
            'region'        => 'region',
            'latitude'      => 'latitude',
            'longitude'     => 'longitude',
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new AddressDtoNotConstructableException($msg, previous: $previous);
    }
}
