<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Service;

use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServiceSlotListDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Service\ServiceSlotListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Service\ListServiceSlotRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiServiceSlotDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws ServiceSlotListDtoNotConstructableException
     */
    public function list(?ListServiceSlotRequestParamBag $paramBag = null): ServiceSlotListDto
    {
        $response = $this->restClient->get('catalog/service-slots', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new ServiceSlotListDto($responseContent);
    }
}
