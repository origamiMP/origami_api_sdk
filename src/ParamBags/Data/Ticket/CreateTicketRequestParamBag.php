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
            'subject'       => 'subject',
            'body'          => 'body',
            'sellerId'      => 'seller_id',
            'customerId'    => 'customer_id',
            'orderSellerId' => 'order_seller_id',
        ];
    }
}
