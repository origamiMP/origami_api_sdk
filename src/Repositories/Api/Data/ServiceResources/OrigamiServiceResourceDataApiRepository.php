<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceResourceDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceResourceListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceResourceUpdateDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiResponseWithoutDataException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceResourceDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceResourceListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceResourceUpdateDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\CreateServiceResourceRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\ListServiceResourceRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\UpdateServiceResourceRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiServiceResourceDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws ServiceResourceListDtoNotConstructableException
     */
    public function list(?ListServiceResourceRequestParamBag $paramBag = null): ServiceResourceListDto
    {
        $response = $this->restClient->get('modules/service_resources/resources', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new ServiceResourceListDto($responseContent);
    }

    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws OrigamiApiResponseWithoutDataException
     * @throws ServiceResourceDtoNotConstructableException
     */
    public function create(CreateServiceResourceRequestParamBag $paramBag): ServiceResourceDto
    {
        $response = $this->restClient->post('modules/service_resources/resources', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new ServiceResourceDto($this->getResponseContentData($responseContent));
    }

    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws ServiceResourceUpdateDtoNotConstructableException
     */
    public function update(int $id, UpdateServiceResourceRequestParamBag $paramBag): ServiceResourceUpdateDto
    {
        $response = $this->restClient->patch("modules/service_resources/resources/{$id}", $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new ServiceResourceUpdateDto($responseContent);
    }

    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     */
    public function delete(int $id): void
    {
        $this->restClient->delete("modules/service_resources/resources/{$id}");
    }
}
