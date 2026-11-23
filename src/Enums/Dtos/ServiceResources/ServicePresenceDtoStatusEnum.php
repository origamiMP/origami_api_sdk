<?php

namespace OrigamiMp\OrigamiApiSdk\Enums\Dtos\ServiceResources;

enum ServicePresenceDtoStatusEnum: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}
