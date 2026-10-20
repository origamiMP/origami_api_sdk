<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Ticket;

use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

/**
 * The API fills neither the seller nor the customer from the order seller: send them.
 */
class CreateTicketRequestParamBag extends RequestParamBag
{
    public string $subject;

    public string $body;

    public int $sellerId;

    public int $customerId;

    public int $orderSellerId;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'subject',
            'body',
            'sellerId',
            'customerId',
            'orderSellerId',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'subject'         => ['required', 'string'],
            'body'            => ['required', 'string'],
            'seller_id'       => ['integer'],
            'customer_id'     => ['integer'],
            'order_seller_id' => ['integer'],
        ];
    }
}
