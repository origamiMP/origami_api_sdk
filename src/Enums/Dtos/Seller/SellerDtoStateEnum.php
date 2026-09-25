<?php

namespace OrigamiMp\OrigamiApiSdk\Enums\Dtos\Seller;

enum SellerDtoStateEnum: string
{
    case REGISTERED = 'REGISTERED';
    case WAITING = 'WAITING';
    case VALIDATED = 'VALIDATED';
}
