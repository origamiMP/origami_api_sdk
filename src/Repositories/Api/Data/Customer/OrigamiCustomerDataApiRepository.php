<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Customer;

use OrigamiMp\OrigamiApiSdk\Dtos\Customer\CustomerDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Customer\CustomerListDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiResponseWithoutDataException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Customer\CustomerDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Customer\CustomerListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Customer\CreateCustomerRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Customer\GetCustomerRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Customer\ListCustomerRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiCustomerDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws CustomerListDtoNotConstructableException
     */
    public function list(?ListCustomerRequestParamBag $paramBag = null): CustomerListDto
    {
        $response = $this->restClient->get('customers', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new CustomerListDto($responseContent);
    }

    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws OrigamiApiResponseWithoutDataException
     * @throws CustomerDtoNotConstructableException
     */
    public function get(int $id, ?GetCustomerRequestParamBag $paramBag = null): CustomerDto
    {
        $response = $this->restClient->get("customers/{$id}", $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new CustomerDto($this->getResponseContentData($responseContent));
    }

    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws OrigamiApiResponseWithoutDataException
     * @throws CustomerDtoNotConstructableException
     */
    public function create(CreateCustomerRequestParamBag $paramBag): CustomerDto
    {
        $response = $this->restClient->post('customers', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new CustomerDto($this->getResponseContentData($responseContent));
    }
}
