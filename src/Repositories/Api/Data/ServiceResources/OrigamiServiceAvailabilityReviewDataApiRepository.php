<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceAvailabilityReviewDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceAvailabilityReviewListDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiResponseWithoutDataException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceAvailabilityReviewDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceAvailabilityReviewListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\ListServiceAvailabilityReviewRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\RejectServiceAvailabilityReviewRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiServiceAvailabilityReviewDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws ServiceAvailabilityReviewListDtoNotConstructableException
     */
    public function list(?ListServiceAvailabilityReviewRequestParamBag $paramBag = null): ServiceAvailabilityReviewListDto
    {
        $response = $this->restClient->get('modules/service_resources/availability-reviews', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new ServiceAvailabilityReviewListDto($responseContent);
    }

    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws OrigamiApiResponseWithoutDataException
     * @throws ServiceAvailabilityReviewDtoNotConstructableException
     */
    public function approve(int $id): ServiceAvailabilityReviewDto
    {
        $response = $this->restClient->post("modules/service_resources/availability-reviews/{$id}/approve");
        $responseContent = $this->decodeResponse($response);

        return new ServiceAvailabilityReviewDto($this->getResponseContentData($responseContent));
    }

    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws OrigamiApiResponseWithoutDataException
     * @throws ServiceAvailabilityReviewDtoNotConstructableException
     */
    public function reject(int $id, RejectServiceAvailabilityReviewRequestParamBag $paramBag): ServiceAvailabilityReviewDto
    {
        $response = $this->restClient->post("modules/service_resources/availability-reviews/{$id}/reject", $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new ServiceAvailabilityReviewDto($this->getResponseContentData($responseContent));
    }
}
