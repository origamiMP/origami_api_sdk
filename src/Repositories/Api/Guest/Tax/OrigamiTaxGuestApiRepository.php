<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Guest\Tax;

use OrigamiMp\OrigamiApiSdk\Dtos\Tax\TaxListDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Tax\TaxListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Guest\Tax\ListTaxRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Guest\OrigamiGuestApiRepository;

class OrigamiTaxGuestApiRepository extends OrigamiGuestApiRepository
{
    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws TaxListDtoNotConstructableException
     */
    public function list(?ListTaxRequestParamBag $paramBag = null): TaxListDto
    {
        $response = $this->restClient->get('taxes', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new TaxListDto($responseContent);
    }
}
