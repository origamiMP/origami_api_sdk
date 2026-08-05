<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Guest\Language;

use OrigamiMp\OrigamiApiSdk\Dtos\Language\LanguageListDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Language\LanguageListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Guest\OrigamiGuestApiRepository;

class OrigamiLanguageGuestApiRepository extends OrigamiGuestApiRepository
{
    /**
     * Get the list of available languages (public endpoint)
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws LanguageListDtoNotConstructableException
     */
    public function list(): LanguageListDto
    {
        $response = $this->restClient->get('languages');
        $responseContent = json_decode($response->getBody()->getContents());

        return new LanguageListDto($responseContent);
    }
}
