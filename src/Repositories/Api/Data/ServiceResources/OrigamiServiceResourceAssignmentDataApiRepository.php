<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceResourceAssignmentDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceResourceAssignmentListDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiResponseWithoutDataException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceResourceAssignmentDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceResourceAssignmentListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\CreateServiceResourceAssignmentRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\ListServiceResourceAssignmentRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\UpdateServiceResourceAssignmentRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiServiceResourceAssignmentDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws ServiceResourceAssignmentListDtoNotConstructableException
     */
    public function list(?ListServiceResourceAssignmentRequestParamBag $paramBag = null): ServiceResourceAssignmentListDto
    {
        $response = $this->restClient->get('modules/service_resources/resource-assignments', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new ServiceResourceAssignmentListDto($responseContent);
    }

    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws OrigamiApiResponseWithoutDataException
     * @throws ServiceResourceAssignmentDtoNotConstructableException
     */
    public function create(CreateServiceResourceAssignmentRequestParamBag $paramBag): ServiceResourceAssignmentDto
    {
        $response = $this->restClient->post('modules/service_resources/resource-assignments', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new ServiceResourceAssignmentDto($this->getResponseContentData($responseContent));
    }

    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws OrigamiApiResponseWithoutDataException
     * @throws ServiceResourceAssignmentDtoNotConstructableException
     */
    public function update(int $id, UpdateServiceResourceAssignmentRequestParamBag $paramBag): ServiceResourceAssignmentDto
    {
        $response = $this->restClient->patch("modules/service_resources/resource-assignments/{$id}", $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new ServiceResourceAssignmentDto($this->getResponseContentData($responseContent));
    }

    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     */
    public function delete(int $id): void
    {
        $this->restClient->delete("modules/service_resources/resource-assignments/{$id}");
    }
}
