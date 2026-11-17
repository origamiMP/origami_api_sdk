<?php

namespace OrigamiMp\OrigamiApiSdk\Enums\Dtos\ServiceResources;

enum ServiceAvailabilityReviewDtoStatusEnum: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}
