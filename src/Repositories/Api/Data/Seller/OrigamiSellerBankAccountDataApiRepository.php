<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Seller;

use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerBankAccountDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiResponseWithoutDataException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Seller\SellerBankAccountDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller\CreateSellerBankAccountRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiSellerBankAccountDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws OrigamiApiResponseWithoutDataException
     * @throws SellerBankAccountDtoNotConstructableException
     */
    public function create(CreateSellerBankAccountRequestParamBag $paramBag): SellerBankAccountDto
    {
        $response = $this->restClient->post('sellers/bank_accounts', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new SellerBankAccountDto($this->getResponseContentData($responseContent));
    }
}
