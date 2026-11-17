<?php

namespace Tests\Unit\Repositories\Api\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServicePlanningSiteDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\UpdateServicePlanningSiteRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\ServiceResources\OrigamiServicePlanningSiteDataApiRepository;
use Tests\TestCase;

class OrigamiServicePlanningSiteDataApiRepositoryTest extends TestCase
{
    /**
     * `PATCH modules/service_resources/plannings/{id}/site` should send the place, the resource type and the breaks.
     */
    public function test_update_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => [
            'service_site_id' => 7, 'resource_type' => 'chair', 'buffer_before_minutes' => 0, 'buffer_after_minutes' => 10,
        ]]));
        $paramBag = new UpdateServicePlanningSiteRequestParamBag;
        $paramBag->serviceSiteId = 7;
        $paramBag->resourceType = 'chair';
        $paramBag->bufferAfterMinutes = 10;

        $attachment = (new OrigamiServicePlanningSiteDataApiRepository($restClient))->update(42, $paramBag);

        $this->assertLastApiRequestWas('PATCH', 'modules/service_resources/plannings/42/site');
        $this->assertLastApiRequestJsonWas(['service_site_id' => 7, 'resource_type' => 'chair', 'buffer_after_minutes' => 10]);
        $this->assertInstanceOf(ServicePlanningSiteDto::class, $attachment);
        $this->assertSame(10, $attachment->bufferAfterMinutes);
    }

    public function test_delete_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->emptyResponse());

        (new OrigamiServicePlanningSiteDataApiRepository($restClient))->delete(42);

        $this->assertLastApiRequestWas('DELETE', 'modules/service_resources/plannings/42/site');
    }
}
