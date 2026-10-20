<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Ticket;

use OrigamiMp\OrigamiApiSdk\Dtos\Ticket\TicketDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Ticket\TicketDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Ticket\CreateTicketRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiTicketDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws TicketDtoNotConstructableException
     */
    public function create(CreateTicketRequestParamBag $paramBag): TicketDto
    {
        $response = $this->restClient->post('tickets', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new TicketDto($this->getResponseContentDataOrEmptyObject($responseContent));
    }
}
