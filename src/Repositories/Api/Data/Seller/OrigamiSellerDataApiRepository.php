<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Seller;

use OrigamiMp\OrigamiApiSdk\Dtos\Seller\LegalTypeListDto;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiSellerDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * Get the list of legal types available for sellers
     */
    public function getLegalTypes(): LegalTypeListDto
    {
        $response = $this->restClient->get('sellers/legal_types');
        $responseContent = json_decode($response->getBody()->getContents());

        return new LegalTypeListDto($responseContent);
    }
} 