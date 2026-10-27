<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Guest\Country;

use OrigamiMp\OrigamiApiSdk\Dtos\Country\CountryListDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Country\CountryListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Guest\Country\ListCountriesRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Guest\OrigamiGuestApiRepository;

class OrigamiCountryGuestApiRepository extends OrigamiGuestApiRepository
{
    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws CountryListDtoNotConstructableException
     */
    public function list(?ListCountriesRequestParamBag $paramBag = null): CountryListDto
    {
        $response = $this->restClient->get('countries', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new CountryListDto($responseContent);
    }
}
