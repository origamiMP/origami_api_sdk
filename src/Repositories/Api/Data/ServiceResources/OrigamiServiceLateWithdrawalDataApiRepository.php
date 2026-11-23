<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceLateWithdrawalListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceLateWithdrawalSummaryDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceLateWithdrawalListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceLateWithdrawalSummaryDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\GetServiceLateWithdrawalSummaryRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\ListServiceLateWithdrawalRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiServiceLateWithdrawalDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws ServiceLateWithdrawalListDtoNotConstructableException
     */
    public function list(?ListServiceLateWithdrawalRequestParamBag $paramBag = null): ServiceLateWithdrawalListDto
    {
        $response = $this->restClient->get('modules/service_resources/late-withdrawals', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new ServiceLateWithdrawalListDto($responseContent);
    }

    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws ServiceLateWithdrawalSummaryDtoNotConstructableException
     */
    public function getSummary(?GetServiceLateWithdrawalSummaryRequestParamBag $paramBag = null): ServiceLateWithdrawalSummaryDto
    {
        $response = $this->restClient->get('modules/service_resources/late-withdrawals/summary', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new ServiceLateWithdrawalSummaryDto($responseContent);
    }
}
