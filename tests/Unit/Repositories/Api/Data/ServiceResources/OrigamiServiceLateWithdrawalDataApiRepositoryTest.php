<?php

namespace Tests\Unit\Repositories\Api\Data\ServiceResources;

use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceLateWithdrawalListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceLateWithdrawalSummaryDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\GetServiceLateWithdrawalSummaryRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources\ListServiceLateWithdrawalRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\ServiceResources\OrigamiServiceLateWithdrawalDataApiRepository;
use Tests\TestCase;

class OrigamiServiceLateWithdrawalDataApiRepositoryTest extends TestCase
{
    public function test_list_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([$this->mockServiceLateWithdrawalApiData(['id' => 1])])));
        $paramBag = new ListServiceLateWithdrawalRequestParamBag;
        $paramBag->setFilters(['service_site_id' => 42, 'withdrawn_after' => '2024-05-01']);

        $withdrawals = (new OrigamiServiceLateWithdrawalDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestWas('GET', 'modules/service_resources/late-withdrawals');
        $this->assertLastApiRequestQueryWas(['filter' => ['service_site_id' => '42', 'withdrawn_after' => '2024-05-01'], 'page' => '1', 'item_per_page' => '20']);
        $this->assertInstanceOf(ServiceLateWithdrawalListDto::class, $withdrawals);
        $this->assertSame(120, $withdrawals->data->first()->noticeMinutes);
    }

    /**
     * A range of a presence comes without planning, a slot logged by an API before the presences without presence.
     */
    public function test_list_case_2(): void
    {
        $presenceRange = $this->mockServiceLateWithdrawalApiData(['service_planning_id' => null, 'service_presence_id' => 7]);
        $slot = $this->mockServiceLateWithdrawalApiData(['service_planning_id' => 3]);
        unset($slot['service_presence_id']);
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([$presenceRange, $slot])));

        $withdrawals = (new OrigamiServiceLateWithdrawalDataApiRepository($restClient))->list(new ListServiceLateWithdrawalRequestParamBag);

        $this->assertSame([[null, 7], [3, null]], $withdrawals->data->map(fn ($withdrawal) => [$withdrawal->servicePlanningId, $withdrawal->servicePresenceId])->all());
    }

    /**
     * `GET modules/service_resources/late-withdrawals/summary` should read the late withdrawals per provider.
     */
    public function test_get_summary_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => [['seller_id' => 8, 'withdrawals' => 2, 'minutes' => 90]]]));
        $paramBag = new GetServiceLateWithdrawalSummaryRequestParamBag;
        $paramBag->setFilters(['service_site_id' => 42]);

        $summary = (new OrigamiServiceLateWithdrawalDataApiRepository($restClient))->getSummary($paramBag);

        $this->assertLastApiRequestWas('GET', 'modules/service_resources/late-withdrawals/summary');
        $this->assertLastApiRequestQueryWas(['filter' => ['service_site_id' => '42']]);
        $this->assertInstanceOf(ServiceLateWithdrawalSummaryDto::class, $summary);
        $this->assertSame([[8, 2, 90]], $summary->data->map(fn ($row) => [$row->sellerId, $row->withdrawals, $row->minutes])->all());
    }
}
