<?php

namespace Tests\Unit\Repositories\Api\Data\B2BAdvanced;

use OrigamiMp\OrigamiApiSdk\Dtos\B2BAdvanced\QuotePriceBuildUpRowDto;
use OrigamiMp\OrigamiApiSdk\Dtos\B2BAdvanced\QuoteResponseDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\B2BAdvanced\QuotePriceBuildUpRowDtoTypeEnum;
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
        $this->assertContainsOnlyInstancesOf(QuotePriceBuildUpRowDto::class, $response->priceBuildUp);
        $this->assertSame(QuotePriceBuildUpRowDtoTypeEnum::RATE, $response->priceBuildUp->first()->type);
    }

    /**
     * `GET modules/b2b_advanced/quotes/{id}/response` should read an answered quote: the criteria the API stores as
     * custom field strings become integers, and an amount driver has no quantity nor unit price.
     */
    public function test_get_quote_response_case_2(): void
    {
        $amountRow = $this->mockQuotePriceBuildUpRowApiData(['poste' => 'Margin', 'unit' => '€ HT', 'type' => 'amount', 'montant' => 1530]);
        unset($amountRow['quantity'], $amountRow['unit_price']);
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockQuoteResponseApiData([
            'has_responded'     => true,
            'price_tax_exc'     => 4470,
            'delay_days'        => 5,
            'responded_at'      => '2024-05-12T16:12:56+00:00',
            'engagement_months' => '12',
            'on_call_247'       => true,
            'rse_score'         => '80',
            'references_count'  => '3',
            'certifications'    => ['ISO 9001'],
            'price_build_up'    => [
                $this->mockQuotePriceBuildUpRowApiData(['quantity' => 120, 'unit_price' => 24.5, 'montant' => 2940]),
                $amountRow,
            ],
            'price_build_up_total' => 4470,
        ])]));

        $response = (new OrigamiB2BAdvancedDataApiRepository($restClient))->getQuoteResponse(42);

        $this->assertTrue($response->hasResponded);
        $this->assertSame(4470.0, $response->priceTaxExc);
        $this->assertSame(5, $response->delayDays);
        $this->assertSame('2024-05-12 16:12:56', $response->respondedAt->format('Y-m-d H:i:s'));
        $this->assertSame(12, $response->engagementMonths);
        $this->assertTrue($response->onCall247);
        $this->assertSame(80, $response->rseScore);
        $this->assertSame(3, $response->referencesCount);
        $this->assertSame(['ISO 9001'], $response->certifications);

        [$rate, $amount] = $response->priceBuildUp->all();
        $this->assertSame([120.0, 24.5, 2940.0], [$rate->quantity, $rate->unitPrice, $rate->amount]);
        $this->assertSame(QuotePriceBuildUpRowDtoTypeEnum::AMOUNT, $amount->type);
        $this->assertSame([null, null, 1530.0], [$amount->quantity, $amount->unitPrice, $amount->amount]);
    }

    /**
     * `POST modules/b2b_advanced/quotes/{id}/respond` should send the offer as JSON, the 24/7 on-call flag under the
     * key the API expects and each cost driver row in snake_case.
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
        $paramBag->onCall247 = true;
        $paramBag->rseScore = 80;
        $paramBag->referencesCount = 3;
        $paramBag->certifications = ['ISO 9001'];
        $paramBag->costBreakdown = [$hours, $margin];

        $response = (new OrigamiB2BAdvancedDataApiRepository($restClient))->respondToQuote(42, $paramBag);

        $this->assertLastApiRequestWas('POST', 'modules/b2b_advanced/quotes/42/respond');
        $this->assertLastApiRequestJsonWas([
            'price_tax_exc'     => 4470,
            'delay_days'        => 5,
            'conditions'        => 'Products included',
            'engagement_months' => 12,
            'on_call_247'       => true,
            'rse_score'         => 80,
            'references_count'  => 3,
            'certifications'    => ['ISO 9001'],
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
