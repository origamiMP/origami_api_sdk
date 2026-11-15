<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceSiteEventCreateDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceSiteEventListDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceSiteEventCreateDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceSiteEventListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\CreateServiceSiteEventRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\ListServiceSiteEventRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiServiceSiteEventDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws ServiceSiteEventListDtoNotConstructableException
     */
    public function list(?ListServiceSiteEventRequestParamBag $paramBag = null): ServiceSiteEventListDto
    {
        $response = $this->restClient->get('modules/service_resources/events', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new ServiceSiteEventListDto($responseContent);
    }

    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws ServiceSiteEventCreateDtoNotConstructableException
     */
    public function create(CreateServiceSiteEventRequestParamBag $paramBag): ServiceSiteEventCreateDto
    {
        $response = $this->restClient->post('modules/service_resources/events', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new ServiceSiteEventCreateDto($responseContent);
    }

    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     */
    public function delete(int $id): void
    {
        $this->restClient->delete("modules/service_resources/events/{$id}");
    }
}
