<?php

namespace OrigamiMp\OrigamiApiSdk\Enums\ParamBags\Data\Seller;

enum CreateSellerLegalInformationLegalTypeParamEnum: string
{
    case INDIVIDUAL = 'INDIVIDUAL';

    case NON_PROFIT = 'NON_PROFIT';

    case COMPANY = 'COMPANY';

    case SOLE_TRADER = 'SOLE_TRADER';
}
