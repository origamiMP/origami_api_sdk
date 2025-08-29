<?php

namespace OrigamiMp\OrigamiApiSdk\Enums\Dtos\User;

enum UserGroupInvitationStatusEnum: string
{
    case PENDING = 'pending';

    case CANCELLED = 'cancelled';
    case ACCEPTED = 'accepted';
    case EXPIRED = 'expired';
    case VALIDATED = 'validated';
}