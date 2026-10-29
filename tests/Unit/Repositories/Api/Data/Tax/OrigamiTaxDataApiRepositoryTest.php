<?php

namespace Tests\Unit\Repositories\Api\Data\Tax;

use OrigamiMp\OrigamiApiSdk\Dtos\Tax\TaxDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Tax\TaxTranslationDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Tax\TaxDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Tax\CreateTaxRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Tax\TaxTranslationParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Tax\UpdateTaxRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Tax\OrigamiTaxDataApiRepository;
use Tests\TestCase;

class OrigamiTaxDataApiRepositoryTest extends TestCase
{
    /**
     * `POST taxes` should send the tax as JSON, its translations included, and read the created one from the
     * `data` key of the response.
     */
    public function test_create_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockTaxApiData([
            'id'    => 42,
            'value' => 5.5,
        ])]));
        $translation = new TaxTranslationParamBag;
        $translation->languageId = 1;
        $translation->name = 'Reduced VAT';
        $paramBag = new CreateTaxRequestParamBag;
        $paramBag->type = TaxDtoTypeEnum::PERCENTAGE;
        $paramBag->value = 5.5;
        $paramBag->isDefault = false;
        $paramBag->translations = [$translation];

        $tax = (new OrigamiTaxDataApiRepository($restClient))->create($paramBag);

        $this->assertLastApiRequestWas('POST', 'taxes');
        $this->assertLastApiRequestJsonWas([
            'type'         => 'percentage',
            'value'        => 5.5,
            'is_default'   => false,
            'translations' => [
                ['language_id' => 1, 'name' => 'Reduced VAT'],
            ],
        ]);
        $this->assertInstanceOf(TaxDto::class, $tax);
        $this->assertSame(42, $tax->id);
        $this->assertSame(5.5, $tax->value);
    }

    /**
     * `PATCH taxes/{id}` should send only the properties set on the param bag as JSON.
     */
    public function test_update_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->emptyResponse());
        $paramBag = new UpdateTaxRequestParamBag;
        $paramBag->value = 10.0;

        (new OrigamiTaxDataApiRepository($restClient))->update(42, $paramBag);

        $this->assertLastApiRequestWas('PATCH', 'taxes/42');
        $this->assertLastApiRequestJsonWas(['value' => 10.0]);
    }

    public function test_delete_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->emptyResponse());

        (new OrigamiTaxDataApiRepository($restClient))->delete(42);

        $this->assertLastApiRequestWas('DELETE', 'taxes/42');
        $this->assertLastApiRequestHadNoBody();
    }

    /**
     * `POST taxes` should send the includes available on a tax as query, along with the tax as JSON, and read the
     * included translations.
     */
    public function test_create_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockTaxApiData()]));
        $paramBag = new CreateTaxRequestParamBag;
        $paramBag->value = 5.5;
        $paramBag->setIncludes(['translations', 'unknown']);

        $tax = (new OrigamiTaxDataApiRepository($restClient))->create($paramBag);

        $this->assertLastApiRequestWas('POST', 'taxes');
        $this->assertLastApiRequestQueryWas(['include' => ['translations']]);
        $this->assertLastApiRequestJsonWas(['value' => 5.5]);
        $this->assertInstanceOf(TaxDto::class, $tax);
        $this->assertContainsOnlyInstancesOf(TaxTranslationDto::class, $tax->translations);
        $this->assertSame(['VAT'], $tax->translations->pluck('name')->all());
    }
}
