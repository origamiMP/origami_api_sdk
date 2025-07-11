<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Fees;

use OrigamiMp\OrigamiApiSdk\Dtos\Fees\FeeProfileListDto;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiFeesDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * Get the list of available fee profiles
     */
    public function getProfiles(): FeeProfileListDto
    {
        $response = $this->restClient->get('fees/profiles');
        $responseContent = json_decode($response->getBody()->getContents());
        return new FeeProfileListDto($responseContent);
    }
} 