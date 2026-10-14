<?php

namespace Tests\Unit\Repositories\Api\Guest\Tax;

use OrigamiMp\OrigamiApiSdk\Dtos\Tax\TaxListDto;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Guest\Tax\OrigamiTaxGuestApiRepository;
use Tests\TestCase;

class OrigamiTaxGuestApiRepositoryTest extends TestCase
{
    /**
     * `GET taxes` answers a non-paginated list: each of its items should be read as a tax.
     */
    public function test_list_case_1(): void
    {
        $restClient = $this->mockGuestRestClientReturning($this->jsonResponse(['data' => [
            $this->mockTaxApiData(['id' => 1]),
            $this->mockTaxApiData(['id' => 2]),
        ]]));

        $taxes = (new OrigamiTaxGuestApiRepository($restClient))->list();

        $this->assertLastApiRequestWas('GET', 'taxes');
        $this->assertInstanceOf(TaxListDto::class, $taxes);
        $this->assertSame([1, 2], $taxes->data->pluck('id')->all());
    }
}
