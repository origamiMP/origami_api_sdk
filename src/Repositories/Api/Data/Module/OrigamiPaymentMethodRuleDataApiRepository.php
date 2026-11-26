<?php

namespace OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Module;

use OrigamiMp\OrigamiApiSdk\Dtos\Module\PaymentMethodRuleListDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiClientErrorException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiMultipleException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Api\OrigamiApiUnknownException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Client\HttpClientException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Module\PaymentMethodRuleListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Module\SyncPaymentMethodRuleRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\OrigamiDataApiRepository;

class OrigamiPaymentMethodRuleDataApiRepository extends OrigamiDataApiRepository
{
    /**
     * The eligibility rules of the payment methods of an escrow payment module (an error for any other module).
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws PaymentMethodRuleListDtoNotConstructableException
     * @throws OrigamiApiClientErrorException
     */
    public function list(int $moduleId): PaymentMethodRuleListDto
    {
        $response = $this->restClient->get("modules/{$moduleId}/payment_method_rules");
        $responseContent = $this->decodeResponse($response);

        return new PaymentMethodRuleListDto($responseContent);
    }

    /**
     * Replaces all the rules of the module with the ones sent (an empty list removes them all), and answers the
     * rules saved.
     *
     * @throws HttpClientException
     * @throws OrigamiApiUnknownException
     * @throws PaymentMethodRuleListDtoNotConstructableException
     * @throws OrigamiApiClientErrorException
     * @throws OrigamiApiMultipleException
     */
    public function sync(int $moduleId, SyncPaymentMethodRuleRequestParamBag $paramBag): PaymentMethodRuleListDto
    {
        $response = $this->restClient->put("modules/{$moduleId}/payment_method_rules", $paramBag);
        $responseContent = $this->decodeResponse($response);

        return new PaymentMethodRuleListDto($responseContent);
    }
}
