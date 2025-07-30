<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Guest\Country;

use OrigamiMp\OrigamiApiSdk\Dtos\Country\CountryListDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Country\CountryListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Guest\Country\GetCountriesRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Guest\OrigamiGuestApiRepository;

class OrigamiCountryGuestApiRepository extends OrigamiGuestApiRepository
{
    /**
     * Get the list of countries
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws CountryListDtoNotConstructableException
     */
    public function getCountries(GetCountriesRequestParamBag $paramBag): CountryListDto
    {
        $response = $this->restClient->get('countries', $paramBag);
        $responseContent = json_decode($response->getBody()->getContents());

        return new CountryListDto($responseContent);
    }
}
