<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Country;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Country\CountryDtoNotConstructableException;

class CountryDto extends ApiResponseDto
{
    public int $id;

    public string $name;

    public string $iso2;

    /**
     * @throws CountryDtoNotConstructableException
     */
    public function __construct(object $apiResponse)
    {
        parent::__construct($apiResponse);

        $this->validateAndFill();
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'    => 'id',
            'name'  => 'name',
            'iso_2' => 'iso2',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'id'    => ['required', 'integer'],
            'name'  => ['required', 'string'],
            'iso_2' => ['required', 'string', 'size:2'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new CountryDtoNotConstructableException($msg, previous: $previous);
    }
} 