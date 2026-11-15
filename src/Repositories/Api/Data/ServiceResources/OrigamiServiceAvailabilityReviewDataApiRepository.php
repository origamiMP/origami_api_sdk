<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceAvailabilityReviewApproveSeriesDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceAvailabilityReviewDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceAvailabilityReviewListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceAvailabilityReviewRejectSeriesDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiResponseWithoutDataException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceAvailabilityReviewApproveSeriesDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceAvailabilityReviewDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceAvailabilityReviewListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceAvailabilityReviewRejectSeriesDtoNotConstructableException;
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

    /**
     * Approves every pending review of a series (a uuid): those whose presence can no longer be granted stay pending and
     * come back as failed. The API refuses a series with nothing pending (`REVIEW_NOT_PENDING`).
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws ServiceAvailabilityReviewApproveSeriesDtoNotConstructableException
     */
    public function approveSeries(string $series): ServiceAvailabilityReviewApproveSeriesDto
    {
        $response = $this->restClient->post("modules/service_resources/availability-reviews/series/{$series}/approve");
        $responseContent = $this->decodeResponse($response);

        return new ServiceAvailabilityReviewApproveSeriesDto($responseContent);
    }

    /**
     * Rejects every pending review of a series (a uuid), with the reason told to the provider.
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws ServiceAvailabilityReviewRejectSeriesDtoNotConstructableException
     */
    public function rejectSeries(string $series, RejectServiceAvailabilityReviewRequestParamBag $paramBag): ServiceAvailabilityReviewRejectSeriesDto
    {
        $response = $this->restClient->post("modules/service_resources/availability-reviews/series/{$series}/reject", $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new ServiceAvailabilityReviewRejectSeriesDto($responseContent);
    }
}
