<?php

namespace OrigamiMp\OrigamiApiSdk\Enums\Dtos\User;

enum RoleDtoTypeEnum: string
{
    case OPERATOR = 'operator';

    case SELLER = 'seller';

    case CUSTOMER = 'customer';

    case CONNECTOR = 'connector';

    case ROOT = 'root';

    case WITHDRAWAL_POINT_MANAGER = 'withdrawal_point_manager';
}
