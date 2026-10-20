<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Order;

use OrigamiMp\OrigamiApiSdk\Dtos\Order\QuoteSellerDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Order\QuoteSellerDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Order\GetQuoteSellerRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiQuoteSellerDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws QuoteSellerDtoNotConstructableException
     */
    public function get(int $id, ?GetQuoteSellerRequestParamBag $paramBag = null): QuoteSellerDto
    {
        $response = $this->restClient->get("quote_sellers/{$id}", $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new QuoteSellerDto($this->getResponseContentDataOrEmptyObject($responseContent));
    }
}
