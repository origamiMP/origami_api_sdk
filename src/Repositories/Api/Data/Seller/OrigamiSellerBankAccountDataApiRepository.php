<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Seller;

use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerBankAccountDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller\CreateBankAccountRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiSellerBankAccountDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * Create a new bank account for the seller
     */
    public function create(CreateBankAccountRequestParamBag $paramBag): SellerBankAccountDto
    {
        $response = $this->restClient->post('sellers/bank_accounts', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new SellerBankAccountDto($this->getResponseContentDataOrEmptyObject($responseContent));
    }
}
