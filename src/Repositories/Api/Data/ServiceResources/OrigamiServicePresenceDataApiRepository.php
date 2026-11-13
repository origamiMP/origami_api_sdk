<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServicePresenceDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServicePresenceListDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiResponseWithoutDataException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServicePresenceDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServicePresenceListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\CreateServicePresenceRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\ListServicePresenceRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\UpdateServicePresenceRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiServicePresenceDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws ServicePresenceListDtoNotConstructableException
     */
    public function list(?ListServicePresenceRequestParamBag $paramBag = null): ServicePresenceListDto
    {
        $response = $this->restClient->get('modules/service_resources/presences', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new ServicePresenceListDto($responseContent);
    }

    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws OrigamiApiResponseWithoutDataException
     * @throws ServicePresenceDtoNotConstructableException
     */
    public function create(CreateServicePresenceRequestParamBag $paramBag): ServicePresenceDto
    {
        $response = $this->restClient->post('modules/service_resources/presences', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new ServicePresenceDto($this->getResponseContentData($responseContent));
    }

    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws OrigamiApiResponseWithoutDataException
     * @throws ServicePresenceDtoNotConstructableException
     */
    public function update(int $id, UpdateServicePresenceRequestParamBag $paramBag): ServicePresenceDto
    {
        $response = $this->restClient->patch("modules/service_resources/presences/{$id}", $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new ServicePresenceDto($this->getResponseContentData($responseContent));
    }

    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     */
    public function delete(int $id): void
    {
        $this->restClient->delete("modules/service_resources/presences/{$id}");
    }
}
