<?php

namespace Tests\Unit\Repositories\Api\Data\Ticket;

use OrigamiMp\OrigamiApiSdk\Dtos\Ticket\TicketDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Ticket\CreateTicketRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Ticket\OrigamiTicketDataApiRepository;
use Tests\TestCase;

class OrigamiTicketDataApiRepositoryTest extends TestCase
{
    /**
     * `POST tickets` should send the message with the order seller, its seller and its customer.
     */
    public function test_create_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockTicketApiData(['id' => 42])]));
        $paramBag = new CreateTicketRequestParamBag;
        $paramBag->subject = 'Quote WZXIIYTYX';
        $paramBag->body = 'Can we visit the site before 6 am?';
        $paramBag->sellerId = 43;
        $paramBag->customerId = 44;
        $paramBag->orderSellerId = 45;

        $ticket = (new OrigamiTicketDataApiRepository($restClient))->create($paramBag);

        $this->assertLastApiRequestWas('POST', 'tickets');
        $this->assertLastApiRequestJsonWas([
            'subject'         => 'Quote WZXIIYTYX',
            'body'            => 'Can we visit the site before 6 am?',
            'seller_id'       => 43,
            'customer_id'     => 44,
            'order_seller_id' => 45,
        ]);
        $this->assertInstanceOf(TicketDto::class, $ticket);
        $this->assertSame(42, $ticket->id);
    }
}
