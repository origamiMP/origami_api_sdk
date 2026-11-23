<?php

namespace OrigamiMp\OrigamiApiSdk\Enums\Dtos\Service;

enum ServiceBookingDtoOriginEnum: string
{
    case MARKETPLACE = 'marketplace';
    case SELLER_LINK = 'seller_link';
    case STAFF = 'staff';
    case SELLER = 'seller';
}
