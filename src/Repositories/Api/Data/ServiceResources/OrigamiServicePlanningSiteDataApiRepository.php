<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServicePlanningSiteDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiResponseWithoutDataException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServicePlanningSiteDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\UpdateServicePlanningSiteRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiServicePlanningSiteDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * Attaches the planning to a place, or changes its resource type and breaks there.
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws OrigamiApiResponseWithoutDataException
     * @throws ServicePlanningSiteDtoNotConstructableException
     */
    public function update(int $servicePlanningId, UpdateServicePlanningSiteRequestParamBag $paramBag): ServicePlanningSiteDto
    {
        $response = $this->restClient->patch("modules/service_resources/plannings/{$servicePlanningId}/site", $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new ServicePlanningSiteDto($this->getResponseContentData($responseContent));
    }

    /**
     * Detaches the planning from its place.
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     */
    public function delete(int $servicePlanningId): void
    {
        $this->restClient->delete("modules/service_resources/plannings/{$servicePlanningId}/site");
    }
}
