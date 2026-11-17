<?php

namespace OrigamiMp\OrigamiApiSdk\Enums\Dtos\Service;

enum ServicePlanningDtoBookingUnitEnum: string
{
    case TIME_SLOT = 'time_slot';
    case DAY = 'day';
    case DAY_RANGE = 'day_range';
}
