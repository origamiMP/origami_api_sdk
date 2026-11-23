<?php

namespace OrigamiMp\OrigamiApiSdk\Enums\Dtos\Service;

enum ServiceBookingDtoStatusEnum: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case CANCELLED = 'cancelled';
    case HONORED = 'honored';
    case NO_SHOW = 'no_show';
}
