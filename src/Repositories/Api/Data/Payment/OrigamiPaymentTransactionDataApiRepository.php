<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Payment;

use OrigamiMp\OrigamiApiSdk\Dtos\Payment\PaymentTransactionDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiResponseWithoutDataException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Payment\PaymentTransactionDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Payment\CreatePaymentTransactionRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiPaymentTransactionDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * The state of the transaction tells whether the money was taken (`SUCCESS`), or is still waiting for the payment
     * provider. A bank refusing a saved card answers an error, and no transaction is kept.
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws OrigamiApiResponseWithoutDataException
     * @throws PaymentTransactionDtoNotConstructableException
     */
    public function create(CreatePaymentTransactionRequestParamBag $paramBag): PaymentTransactionDto
    {
        $response = $this->restClient->post('payments/transactions', $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new PaymentTransactionDto($this->getResponseContentData($responseContent));
    }
}
