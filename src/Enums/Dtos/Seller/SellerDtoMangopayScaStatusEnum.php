<?php

namespace OrigamiMp\OrigamiApiSdk\Enums\Dtos\Seller;

enum SellerDtoMangopayScaStatusEnum: string
{
    case PENDING = 'PENDING';

    case VALIDATED = 'VALIDATED';

    case ERROR = 'ERROR';

    case FAILED = 'FAILED';
}
