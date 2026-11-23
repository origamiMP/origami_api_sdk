<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\ServiceResources;

use Carbon\Carbon;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

class CreateServiceSiteEventRequestParamBag extends RequestParamBag
{
    public int $serviceSiteId;

    public string $label;

    public Carbon $startsAt;

    public Carbon $endsAt;

    /** @var array<int, int> The resources closed by the event, all of them when not set. */
    public array $resourceIds;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'serviceSiteId' => 'service_site_id',
            'label'         => 'label',
            'startsAt'      => 'starts_at',
            'endsAt'        => 'ends_at',
            'resourceIds'   => 'resource_ids',
        ];
    }
}
