<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Service;

use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServicePlanningListDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Service\ServicePlanningListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Service\ListServicePlanningRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiServicePlanningDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws ServicePlanningListDtoNotConstructableException
     */
    public function list(?ListServicePlanningRequestParamBag $paramBag = null): ServicePlanningListDto
    {
        $response = $this->restClient->get('catalog/service-plannings', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new ServicePlanningListDto($responseContent);
    }
}
