<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\User;

use OrigamiMp\OrigamiApiSdk\ParamBags\Data\DataApiRequestParamBag;

class GetCurrentUserRequestParamBag extends DataApiRequestParamBag
{
    public array $availableIncludes = [
        'user_groups',
        // TODO 'roles',
        // TODO 'user_group_users',
        // TODO 'module',
        // TODO 'user_reports_received',
    ];
}
