<?php

namespace Tests\Unit\Repositories\Api\Data\Module;

use OrigamiMp\OrigamiApiSdk\Dtos\Module\ModuleListDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Module\ListModuleRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Module\OrigamiModuleDataApiRepository;
use Tests\TestCase;

class OrigamiModuleDataApiRepositoryTest extends TestCase
{
    /**
     * `GET modules` answers a non-paginated list: each of its items should be read as a module.
     */
    public function test_list_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => [
            $this->mockModuleApiData(['name' => 'payment']),
            $this->mockModuleApiData(['name' => 'shipping', 'is_active' => false]),
        ]]));

        $modules = (new OrigamiModuleDataApiRepository($restClient))->list(new ListModuleRequestParamBag);

        $this->assertLastApiRequestWas('GET', 'modules');
        $this->assertInstanceOf(ModuleListDto::class, $modules);
        $this->assertSame(['payment', 'shipping'], $modules->data->pluck('name')->all());
        $this->assertSame([true, false], $modules->data->pluck('isActive')->all());
    }

    /**
     * `GET modules` should be callable without param bag, then sending no query.
     */
    public function test_list_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => [$this->mockModuleApiData(['id' => 42])]]));

        $modules = (new OrigamiModuleDataApiRepository($restClient))->list();

        $this->assertLastApiRequestWas('GET', 'modules');
        $this->assertLastApiRequestQueryWas([]);
        $this->assertInstanceOf(ModuleListDto::class, $modules);
        $this->assertSame(42, $modules->data->first()->id);
    }
}
