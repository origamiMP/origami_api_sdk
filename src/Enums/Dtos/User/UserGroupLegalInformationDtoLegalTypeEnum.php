<?php

namespace OrigamiMp\OrigamiApiSdk\Enums\Dtos\User;

enum UserGroupLegalInformationDtoLegalTypeEnum: string
{
    case COMPANY = 'COMPANY';

    case SOLE_TRADER = 'SOLE_TRADER';

    case INDIVIDUAL = 'INDIVIDUAL';
}
