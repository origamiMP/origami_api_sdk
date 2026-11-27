<?php

namespace Tests\Unit\Repositories\Api\Data\Module;

use OrigamiMp\OrigamiApiSdk\Dtos\Module\ModuleDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Module\ModuleListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Module\ModuleLogDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Module\GetModuleRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Module\ListModuleRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Module\UpdateModuleRequestParamBag;
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

    /**
     * `GET modules` sends a seller or a customer their configurations as empty JSON lists: they should be read as
     * empty objects.
     */
    public function test_list_case_3(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => [$this->mockModuleApiData([
            'configurations'           => [],
            'available_configurations' => [],
        ])]]));

        $modules = (new OrigamiModuleDataApiRepository($restClient))->list();

        $this->assertInstanceOf(ModuleListDto::class, $modules);
        $this->assertEquals((object) [], $modules->data->first()->configurations);
        $this->assertEquals((object) [], $modules->data->first()->availableConfigurations);
    }

    /**
     * `GET modules` should only send the available includes, and read the logs of each module.
     */
    public function test_list_case_4(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => [
            $this->mockModuleApiData(['logs' => ['data' => [$this->mockModuleLogApiData(['id' => 42])]]]),
        ]]));
        $paramBag = new ListModuleRequestParamBag;
        $paramBag->setIncludes(['logs', 'webhooks']);

        $modules = (new OrigamiModuleDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestWas('GET', 'modules');
        $this->assertLastApiRequestQueryWas(['include' => ['logs']]);
        $this->assertInstanceOf(ModuleListDto::class, $modules);
        $this->assertContainsOnlyInstancesOf(ModuleLogDto::class, $modules->data->first()->logs);
        $this->assertSame([42], $modules->data->first()->logs->pluck('id')->all());
    }

    /**
     * `GET modules/{id}` should read the module from the `data` key of the response.
     */
    public function test_get_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockModuleApiData(['id' => 42])]));

        $module = (new OrigamiModuleDataApiRepository($restClient))->get(42, new GetModuleRequestParamBag);

        $this->assertLastApiRequestWas('GET', 'modules/42');
        $this->assertInstanceOf(ModuleDto::class, $module);
        $this->assertSame(42, $module->id);
        $this->assertFalse(isset($module->logs));
    }

    /**
     * `GET modules/{id}` should be callable without param bag, then sending no query.
     */
    public function test_get_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockModuleApiData(['id' => 42])]));

        $module = (new OrigamiModuleDataApiRepository($restClient))->get(42);

        $this->assertLastApiRequestWas('GET', 'modules/42');
        $this->assertLastApiRequestQueryWas([]);
        $this->assertSame(42, $module->id);
    }

    /**
     * `GET modules/{id}` should only send the available includes (`webhooks` has no DTO yet), and read the logs.
     */
    public function test_get_case_3(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockModuleApiData([
            'logs' => ['data' => [$this->mockModuleLogApiData(['id' => 42]), $this->mockModuleLogApiData(['id' => 43])]],
        ])]));
        $paramBag = new GetModuleRequestParamBag;
        $paramBag->setIncludes(['logs', 'webhooks']);

        $module = (new OrigamiModuleDataApiRepository($restClient))->get(42, $paramBag);

        $this->assertLastApiRequestWas('GET', 'modules/42');
        $this->assertLastApiRequestQueryWas(['include' => ['logs']]);
        $this->assertContainsOnlyInstancesOf(ModuleLogDto::class, $module->logs);
        $this->assertSame([42, 43], $module->logs->pluck('id')->all());
    }

    /**
     * `PATCH modules/{id}` should send the configurations as JSON, nested ones included.
     */
    public function test_update_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->emptyResponse());
        $paramBag = new UpdateModuleRequestParamBag;
        $paramBag->configurations = [
            'api_url'         => 'https://example.com/api',
            'payment_methods' => ['PAYIN', 'DEBIT_AGREEMENT'],
            'PAYIN'           => ['customer_group_id' => [1, 2]],
        ];

        (new OrigamiModuleDataApiRepository($restClient))->update(42, $paramBag);

        $this->assertLastApiRequestWas('PATCH', 'modules/42');
        $this->assertLastApiRequestJsonWas(['configurations' => [
            'api_url'         => 'https://example.com/api',
            'payment_methods' => ['PAYIN', 'DEBIT_AGREEMENT'],
            'PAYIN'           => ['customer_group_id' => [1, 2]],
        ]]);
    }

    /**
     * `PATCH modules/{id}` should send an emptied multiple field as an empty list, so that the API clears it.
     */
    public function test_update_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->emptyResponse());
        $paramBag = new UpdateModuleRequestParamBag;
        $paramBag->configurations = ['payment_methods' => []];

        (new OrigamiModuleDataApiRepository($restClient))->update(42, $paramBag);

        $this->assertLastApiRequestWas('PATCH', 'modules/42');
        $this->assertLastApiRequestJsonWas(['configurations' => ['payment_methods' => []]]);
    }
}
