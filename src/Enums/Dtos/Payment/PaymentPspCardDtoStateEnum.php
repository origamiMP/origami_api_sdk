<?php

namespace OrigamiMp\OrigamiApiSdk\Enums\Dtos\Payment;

enum PaymentPspCardDtoStateEnum: string
{
    case CREATED = 'CREATED';
    case VALIDATED = 'VALIDATED';
    case ERROR = 'ERROR';
}
