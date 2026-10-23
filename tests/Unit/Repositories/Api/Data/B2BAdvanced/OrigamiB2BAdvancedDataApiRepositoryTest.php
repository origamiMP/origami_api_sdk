<?php

namespace Tests\Unit\Repositories\Api\Data\B2BAdvanced;

use OrigamiMp\OrigamiApiSdk\Dtos\B2BAdvanced\QuotePriceBuildUpRowDto;
use OrigamiMp\OrigamiApiSdk\Dtos\B2BAdvanced\QuoteResponseDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\B2BAdvanced\QuotePriceBuildUpRowDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\B2BAdvanced\QuoteResponseDtoStatusEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\B2BAdvanced\QuoteCostBreakdownRowParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\B2BAdvanced\RespondToQuoteRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\B2BAdvanced\OrigamiB2BAdvancedDataApiRepository;
use Tests\TestCase;

class OrigamiB2BAdvancedDataApiRepositoryTest extends TestCase
{
    /**
     * `GET modules/b2b_advanced/quotes/{id}/response` should read the quote not answered yet, with its price build-up.
     */
    public function test_get_quote_response_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockQuoteResponseApiData(['order_seller_id' => 42])]));

        $response = (new OrigamiB2BAdvancedDataApiRepository($restClient))->getQuoteResponse(42);

        $this->assertLastApiRequestWas('GET', 'modules/b2b_advanced/quotes/42/response');
        $this->assertLastApiRequestHadNoBody();
        $this->assertInstanceOf(QuoteResponseDto::class, $response);
        $this->assertSame(42, $response->orderSellerId);
        $this->assertFalse($response->hasResponded);
        $this->assertNull($response->priceTaxExc);
        $this->assertNull($response->respondedAt);
        $this->assertFalse($response->isClosed);
        $this->assertSame(QuoteResponseDtoStatusEnum::TO_ANSWER, $response->status);
        $this->assertContainsOnlyInstancesOf(QuotePriceBuildUpRowDto::class, $response->priceBuildUp);
        $this->assertSame(QuotePriceBuildUpRowDtoTypeEnum::AMOUNT, $response->priceBuildUp->first()->type);
    }

    /**
     * `GET modules/b2b_advanced/quotes/{id}/response` should read an answered quote: the engagement the API stores as
     * a custom field string becomes an integer, and an amount driver has no quantity nor unit price.
     */
    public function test_get_quote_response_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockQuoteResponseApiData([
            'has_responded'     => true,
            'price_tax_exc'     => 4470,
            'delay_days'        => 5,
            'responded_at'      => '2024-05-12T16:12:56+00:00',
            'engagement_months' => '12',
            'is_closed'         => true,
            'status'            => 'updated',
            'price_build_up'    => [
                $this->mockQuotePriceBuildUpRowApiData(['poste' => 'Cleaning hours', 'unit' => 'h', 'type' => 'rate', 'quantity' => 120, 'unit_price' => 24.5, 'montant' => 2940]),
                $this->mockQuotePriceBuildUpRowApiData(['montant' => 1530]),
            ],
            'price_build_up_total' => 4470,
        ])]));

        $response = (new OrigamiB2BAdvancedDataApiRepository($restClient))->getQuoteResponse(42);

        $this->assertTrue($response->hasResponded);
        $this->assertSame(4470.0, $response->priceTaxExc);
        $this->assertSame(5, $response->delayDays);
        $this->assertSame('2024-05-12 16:12:56', $response->respondedAt->format('Y-m-d H:i:s'));
        $this->assertSame(12, $response->engagementMonths);
        $this->assertTrue($response->isClosed);
        $this->assertSame(QuoteResponseDtoStatusEnum::UPDATED, $response->status);

        [$rate, $amount] = $response->priceBuildUp->all();
        $this->assertSame([120.0, 24.5, 2940.0], [$rate->quantity, $rate->unitPrice, $rate->amount]);
        $this->assertSame(QuotePriceBuildUpRowDtoTypeEnum::AMOUNT, $amount->type);
        $this->assertSame([null, null, 1530.0], [$amount->quantity, $amount->unitPrice, $amount->amount]);
    }

    /**
     * `POST modules/b2b_advanced/quotes/{id}/respond` should send the offer as JSON and each cost driver row in
     * snake_case.
     */
    public function test_respond_to_quote_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockQuoteResponseApiData([
            'order_seller_id' => 42,
            'has_responded'   => true,
            'price_tax_exc'   => 4470,
        ])]));

        $hours = new QuoteCostBreakdownRowParamBag;
        $hours->poste = 'Cleaning hours';
        $hours->quantity = 120;
        $hours->unitPrice = 24.5;
        $hours->montant = 2940;
        $margin = new QuoteCostBreakdownRowParamBag;
        $margin->poste = 'Margin';
        $margin->montant = 1530;
        $paramBag = new RespondToQuoteRequestParamBag;
        $paramBag->priceTaxExc = 4470;
        $paramBag->delayDays = 5;
        $paramBag->conditions = 'Products included';
        $paramBag->engagementMonths = 12;
        $paramBag->costBreakdown = [$hours, $margin];

        $response = (new OrigamiB2BAdvancedDataApiRepository($restClient))->respondToQuote(42, $paramBag);

        $this->assertLastApiRequestWas('POST', 'modules/b2b_advanced/quotes/42/respond');
        $this->assertLastApiRequestJsonWas([
            'price_tax_exc'     => 4470,
            'delay_days'        => 5,
            'conditions'        => 'Products included',
            'engagement_months' => 12,
            'cost_breakdown'    => [
                ['poste' => 'Cleaning hours', 'quantity' => 120, 'unit_price' => 24.5, 'montant' => 2940],
                ['poste' => 'Margin', 'montant' => 1530],
            ],
        ]);
        $this->assertInstanceOf(QuoteResponseDto::class, $response);
        $this->assertSame(42, $response->orderSellerId);
        $this->assertTrue($response->hasResponded);
    }

    /**
     * `POST modules/b2b_advanced/quotes/{id}/respond` should only send the price when nothing else is set.
     */
    public function test_respond_to_quote_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockQuoteResponseApiData()]));

        $paramBag = new RespondToQuoteRequestParamBag;
        $paramBag->priceTaxExc = 150;

        (new OrigamiB2BAdvancedDataApiRepository($restClient))->respondToQuote(42, $paramBag);

        $this->assertLastApiRequestJsonWas(['price_tax_exc' => 150]);
    }
}
