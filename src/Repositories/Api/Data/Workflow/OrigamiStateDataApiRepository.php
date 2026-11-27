<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Workflow;

use OrigamiMp\OrigamiApiSdk\Dtos\Workflow\StateListDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Workflow\StateListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Workflow\ListStateRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiStateDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * The `workflow_type` filter keeps the states of one type of workflow (`order_seller`, `product`, `ticket_message`,
     * `review`, `user_group`, `service_booking`). The search looks into the names of the states, in the language of
     * the request, and into their keys.
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws StateListDtoNotConstructableException
     */
    public function list(?ListStateRequestParamBag $paramBag = null): StateListDto
    {
        $response = $this->restClient->get('states', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new StateListDto($responseContent);
    }
}
