<?php

namespace OrigamiMp\OrigamiApiSdk\Enums\Dtos\B2BAdvanced;

enum QuoteResponseDtoStatusEnum: string
{
    case TO_ANSWER = 'to_answer';

    case SENT = 'sent';

    case UPDATED = 'updated';

    case REFUSED = 'refused';

    case NOT_SELECTED = 'not_selected';

    case CLOSED = 'closed';

    case SELECTED = 'selected';
}
