<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Guest\Tax;

use OrigamiMp\OrigamiApiSdk\Dtos\Tax\TaxListDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Guest\Tax\ListTaxesRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Guest\OrigamiGuestApiRepository;

class OrigamiTaxGuestApiRepository extends OrigamiGuestApiRepository
{
    /**
     * Get the list of available taxes (public endpoint)
     */
    public function list(?ListTaxesRequestParamBag $paramBag = null): TaxListDto
    {
        $response = $this->restClient->get('taxes', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new TaxListDto($responseContent);
    }
}
