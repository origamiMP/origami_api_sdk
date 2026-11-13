<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceSiteBoardDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceSiteDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceSiteListDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiResponseWithoutDataException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceSiteBoardDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceSiteDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceSiteListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\CreateServiceSiteRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\GetServiceSiteBoardRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\ListServiceSiteRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\UpdateServiceSiteRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiServiceSiteDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws ServiceSiteListDtoNotConstructableException
     */
    public function list(?ListServiceSiteRequestParamBag $paramBag = null): ServiceSiteListDto
    {
        $response = $this->restClient->get('modules/service_resources/sites', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new ServiceSiteListDto($responseContent);
    }

    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws OrigamiApiResponseWithoutDataException
     * @throws ServiceSiteDtoNotConstructableException
     */
    public function get(int $id): ServiceSiteDto
    {
        $response = $this->restClient->get("modules/service_resources/sites/{$id}");
        $responseContent = $this->decodeResponse($response);

        return new ServiceSiteDto($this->getResponseContentData($responseContent));
    }

    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws OrigamiApiResponseWithoutDataException
     * @throws ServiceSiteDtoNotConstructableException
     */
    public function create(CreateServiceSiteRequestParamBag $paramBag): ServiceSiteDto
    {
        $response = $this->restClient->post('modules/service_resources/sites', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new ServiceSiteDto($this->getResponseContentData($responseContent));
    }

    /**
     * Opening hours and providers sent replace those of the place.
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws OrigamiApiResponseWithoutDataException
     * @throws ServiceSiteDtoNotConstructableException
     */
    public function update(int $id, UpdateServiceSiteRequestParamBag $paramBag): ServiceSiteDto
    {
        $response = $this->restClient->patch("modules/service_resources/sites/{$id}", $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new ServiceSiteDto($this->getResponseContentData($responseContent));
    }

    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     */
    public function delete(int $id): void
    {
        $this->restClient->delete("modules/service_resources/sites/{$id}");
    }

    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws OrigamiApiResponseWithoutDataException
     * @throws ServiceSiteBoardDtoNotConstructableException
     */
    public function getBoard(int $id, ?GetServiceSiteBoardRequestParamBag $paramBag = null): ServiceSiteBoardDto
    {
        $response = $this->restClient->get("modules/service_resources/sites/{$id}/board", $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new ServiceSiteBoardDto($this->getResponseContentData($responseContent));
    }
}
