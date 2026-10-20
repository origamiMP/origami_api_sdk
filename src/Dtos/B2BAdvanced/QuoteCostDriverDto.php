<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\B2BAdvanced;

use Illuminate\Validation\Rule;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\B2BAdvanced\QuoteCostDriverDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\B2BAdvanced\QuoteCostDriverDtoNotConstructableException;

/**
 * A price build-up item the operator set on the category of the quote.
 */
class QuoteCostDriverDto extends ApiResponseDto
{
    public string $label;

    public ?string $unit;

    public QuoteCostDriverDtoTypeEnum $type;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'label' => 'label',
            'unit'  => 'unit',
            'type'  => fn (string $type) => $this->type = QuoteCostDriverDtoTypeEnum::from($type),
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'label' => ['required', 'string'],
            'unit'  => ['present', 'nullable', 'string'],
            'type'  => ['required', Rule::in(collect(QuoteCostDriverDtoTypeEnum::cases())->pluck('value'))],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new QuoteCostDriverDtoNotConstructableException($msg, previous: $previous);
    }
}
