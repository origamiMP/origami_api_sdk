<?php

namespace OrigamiMp\OrigamiApiSdk\Enums\Dtos\Payment;

enum PaymentTransactionDtoStateEnum: string
{
    case CREATED = 'CREATED';
    case WAITING = 'WAITING';
    case ERROR = 'ERROR';
    case SUCCESS = 'SUCCESS';
    case CANCELED = 'CANCELED';
}
