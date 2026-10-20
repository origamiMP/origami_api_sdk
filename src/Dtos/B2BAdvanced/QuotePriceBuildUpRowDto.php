<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\B2BAdvanced;

use Illuminate\Validation\Rule;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\B2BAdvanced\QuotePriceBuildUpRowDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\B2BAdvanced\QuotePriceBuildUpRowDtoNotConstructableException;

/**
 * A cost driver of the quote's category with the amount the seller filled: quantity × unit price for a rate, a
 * direct amount otherwise. The API sends 0 for a driver the seller did not fill.
 */
class QuotePriceBuildUpRowDto extends ApiResponseDto
{
    public string $label;

    public ?string $unit;

    public QuotePriceBuildUpRowDtoTypeEnum $type;

    /**
     * Rate drivers only.
     */
    public ?float $quantity = null;

    /**
     * Rate drivers only.
     */
    public ?float $unitPrice = null;

    public float $amount;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'poste'      => 'label',
            'unit'       => 'unit',
            'type'       => fn (string $type) => $this->type = QuotePriceBuildUpRowDtoTypeEnum::from($type),
            'quantity'   => fn ($quantity) => $this->quantity = (float) $quantity,
            'unit_price' => fn ($unitPrice) => $this->unitPrice = (float) $unitPrice,
            'montant'    => fn ($amount) => $this->amount = (float) $amount,
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'poste'      => ['required', 'string'],
            'unit'       => ['present', 'nullable', 'string'],
            'type'       => ['required', Rule::in(collect(QuotePriceBuildUpRowDtoTypeEnum::cases())->pluck('value'))],
            'quantity'   => ['numeric'],
            'unit_price' => ['numeric'],
            'montant'    => ['required', 'numeric'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new QuotePriceBuildUpRowDtoNotConstructableException($msg, previous: $previous);
    }
}
