<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\B2BAdvanced;

use OrigamiMp\OrigamiApiSdk\Dtos\B2BAdvanced\QuoteResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\B2BAdvanced\QuoteResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\B2BAdvanced\RespondQuoteResponseRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

/**
 * Quote responses of the sellers, from the B2BAdvanced module of the Origami API.
 */
class OrigamiQuoteResponseDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws QuoteResponseDtoNotConstructableException
     */
    public function get(int $orderSellerId): QuoteResponseDto
    {
        $response = $this->restClient->get("modules/b2b_advanced/quotes/{$orderSellerId}/response");
        $responseContent = $this->decodeResponse($response);

        return new QuoteResponseDto($this->getResponseContentDataOrEmptyObject($responseContent));
    }

    /**
     * Sending again replaces the previous offer while the store has not answered it.
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws QuoteResponseDtoNotConstructableException
     */
    public function respond(int $orderSellerId, RespondQuoteResponseRequestParamBag $paramBag): QuoteResponseDto
    {
        $response = $this->restClient->post("modules/b2b_advanced/quotes/{$orderSellerId}/respond", $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new QuoteResponseDto($this->getResponseContentDataOrEmptyObject($responseContent));
    }
}
