<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Customer;

use OrigamiMp\OrigamiApiSdk\Dtos\Customer\CustomerGroupListDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Customer\CustomerGroupListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Customer\ListCustomerGroupRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiCustomerGroupDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * The search looks into the names of the groups, in the language of the request.
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws CustomerGroupListDtoNotConstructableException
     */
    public function list(?ListCustomerGroupRequestParamBag $paramBag = null): CustomerGroupListDto
    {
        $response = $this->restClient->get('customers/groups', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new CustomerGroupListDto($responseContent);
    }
}
