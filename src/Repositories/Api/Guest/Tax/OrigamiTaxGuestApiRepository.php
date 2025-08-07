<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Guest\Tax;

use OrigamiMp\OrigamiApiSdk\Dtos\Tax\TaxListDto;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Guest\OrigamiGuestApiRepository;

class OrigamiTaxGuestApiRepository extends OrigamiGuestApiRepository
{
    /**
     * Get the list of available taxes (public endpoint)
     */
    public function list(): TaxListDto
    {
        $response = $this->restClient->get('taxes');
        $responseContent = json_decode($response->getBody()->getContents());

        return new TaxListDto($responseContent);
    }
}
