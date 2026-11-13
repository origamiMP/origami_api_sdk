<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceSiteEventDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceSiteEventListDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiResponseWithoutDataException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceSiteEventDtoNotConstructableException;
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
     * @throws OrigamiApiResponseWithoutDataException
     * @throws ServiceSiteEventDtoNotConstructableException
     */
    public function create(CreateServiceSiteEventRequestParamBag $paramBag): ServiceSiteEventDto
    {
        $response = $this->restClient->post('modules/service_resources/events', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new ServiceSiteEventDto($this->getResponseContentData($responseContent));
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
