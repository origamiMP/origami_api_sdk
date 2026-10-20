<?php

namespace Tests\Unit\Repositories\Api\Data\Order;

use OrigamiMp\OrigamiApiSdk\Dtos\Customer\CustomerDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormSubmissionDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Order\OrderLineDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Order\QuoteSellerDto;
use OrigamiMp\OrigamiApiSdk\Dtos\State\StateDto;
use OrigamiMp\OrigamiApiSdk\Dtos\State\StateLogDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Order\GetQuoteSellerRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Order\OrigamiQuoteSellerDataApiRepository;
use Tests\TestCase;

class OrigamiQuoteSellerDataApiRepositoryTest extends TestCase
{
    /**
     * `GET quote_sellers/{id}` should be callable without param bag, and read the quote from the `data` key.
     */
    public function test_get_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockQuoteSellerApiData(['id' => 42, 'order_id' => 81])]));

        $quote = (new OrigamiQuoteSellerDataApiRepository($restClient))->get(42);

        $this->assertLastApiRequestWas('GET', 'quote_sellers/42');
        $this->assertLastApiRequestQueryWas([]);
        $this->assertInstanceOf(QuoteSellerDto::class, $quote);
        $this->assertSame(42, $quote->id);
        $this->assertSame(81, $quote->orderId);
        $this->assertFalse(isset($quote->orderLines));
    }

    /**
     * `GET quote_sellers/{id}` should only send the available includes, nested ones included, and read them.
     */
    public function test_get_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockQuoteSellerApiData([
            'id'         => 42,
            'customer'   => ['data' => $this->mockCustomerApiData(['id' => 43])],
            'orderLines' => ['data' => [$this->mockOrderLineApiData([
                'id'                 => 44,
                'form_submission_id' => 45,
                'form_submission'    => ['data' => $this->mockFormSubmissionApiData(['id' => 45])],
            ])]],
            'state'      => ['data' => $this->mockStateApiData(['id' => 46])],
            'state_logs' => ['data' => [$this->mockStateLogApiData(['id' => 47])]],
        ])]));
        $paramBag = new GetQuoteSellerRequestParamBag;
        $paramBag->setIncludes(['customer', 'orderLines.form_submission.values', 'state', 'state_logs', 'seller']);

        $quote = (new OrigamiQuoteSellerDataApiRepository($restClient))->get(42, $paramBag);

        $this->assertLastApiRequestWas('GET', 'quote_sellers/42');
        $this->assertLastApiRequestQueryWas(['include' => ['customer', 'orderLines.form_submission.values', 'state', 'state_logs']]);
        $this->assertInstanceOf(CustomerDto::class, $quote->customer);
        $this->assertSame(43, $quote->customer->id);
        $this->assertInstanceOf(StateDto::class, $quote->state);
        $this->assertSame(46, $quote->state->id);
        $this->assertContainsOnlyInstancesOf(StateLogDto::class, $quote->stateLogs);
        $this->assertSame([47], $quote->stateLogs->pluck('id')->all());

        $line = $quote->orderLines->first();
        $this->assertInstanceOf(OrderLineDto::class, $line);
        $this->assertSame(44, $line->id);
        $this->assertSame(45, $line->formSubmissionId);
        $this->assertInstanceOf(FormSubmissionDto::class, $line->formSubmission);
        $this->assertSame(45, $line->formSubmission->id);
    }
}
