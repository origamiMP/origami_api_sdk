<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\B2BAdvanced;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\B2BAdvanced\QuoteResponseDtoNotConstructableException;

/**
 * A seller's answer to a quote, read from the B2BAdvanced module: the same grid for every seller of the request.
 * $hasResponded is false until the seller sends an offer; the cost drivers are there from the start.
 */
class QuoteResponseDto extends ApiResponseDto
{
    public int $orderSellerId;

    public ?string $reference;

    public ?int $sellerId;

    public ?string $sellerName;

    public bool $hasResponded;

    public ?float $priceTaxExc;

    /**
     * The price of the previous offer, once the seller sent a new one.
     */
    public ?float $previousPriceTaxExc;

    /**
     * Keys of the terms the last offer changed (`price_tax_exc`, `delay_days`…).
     *
     * @var string[]
     */
    public array $changedFields;

    public ?int $delayDays;

    public ?string $conditions;

    public ?Carbon $respondedAt;

    public bool $isSelected;

    public ?Carbon $selectedAt;

    public bool $isConverted;

    public ?string $refusalReason;

    public ?int $engagementMonths;

    public bool $onCall247;

    public ?int $rseScore;

    public ?int $referencesCount;

    /** @var string[] */
    public array $certifications;

    /** @var Collection<int, QuoteCostDriverDto> */
    public Collection $costDrivers;

    /**
     * Every cost driver, in the operator's order, with what the seller filled.
     *
     * @var Collection<int, QuotePriceBuildUpRowDto>
     */
    public Collection $priceBuildUp;

    public float $priceBuildUpTotal;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'order_seller_id'        => 'orderSellerId',
            'reference'              => 'reference',
            'seller_id'              => 'sellerId',
            'seller_name'            => 'sellerName',
            'has_responded'          => 'hasResponded',
            'price_tax_exc'          => fn ($price) => $this->priceTaxExc = self::toFloatOrNull($price),
            'previous_price_tax_exc' => fn ($price) => $this->previousPriceTaxExc = self::toFloatOrNull($price),
            'changed_fields'         => 'changedFields',
            'delay_days'             => 'delayDays',
            'conditions'             => 'conditions',
            'responded_at'           => fn (?string $date) => $this->respondedAt = is_null($date) ? null : Carbon::parse($date),
            'is_selected'            => 'isSelected',
            'selected_at'            => fn (?string $date) => $this->selectedAt = is_null($date) ? null : Carbon::parse($date),
            'is_converted'           => 'isConverted',
            'refusal_reason'         => 'refusalReason',
            // Custom field values: the API sends them as strings.
            'engagement_months'    => fn ($value) => $this->engagementMonths = self::toIntOrNull($value),
            'on_call_247'          => 'onCall247',
            'rse_score'            => fn ($value) => $this->rseScore = self::toIntOrNull($value),
            'references_count'     => fn ($value) => $this->referencesCount = self::toIntOrNull($value),
            'certifications'       => 'certifications',
            'cost_drivers'         => fn (array $drivers) => $this->costDrivers = collect($drivers)->map(fn (object $driver) => new QuoteCostDriverDto($driver)),
            'price_build_up'       => fn (array $rows) => $this->priceBuildUp = collect($rows)->map(fn (object $row) => new QuotePriceBuildUpRowDto($row)),
            'price_build_up_total' => fn ($total) => $this->priceBuildUpTotal = (float) $total,
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'order_seller_id'        => ['required', 'integer'],
            'reference'              => ['present', 'nullable', 'string'],
            'seller_id'              => ['present', 'nullable', 'integer'],
            'seller_name'            => ['present', 'nullable', 'string'],
            'has_responded'          => ['required', 'boolean'],
            'price_tax_exc'          => ['present', 'nullable', 'numeric'],
            'previous_price_tax_exc' => ['present', 'nullable', 'numeric'],
            'changed_fields'         => ['present', 'array'],
            'delay_days'             => ['present', 'nullable', 'integer'],
            'conditions'             => ['present', 'nullable', 'string'],
            'responded_at'           => ['present', 'nullable', 'date'],
            'is_selected'            => ['required', 'boolean'],
            'selected_at'            => ['present', 'nullable', 'date'],
            'is_converted'           => ['required', 'boolean'],
            'refusal_reason'         => ['present', 'nullable', 'string'],
            'engagement_months'      => ['present', 'nullable', 'numeric'],
            'on_call_247'            => ['required', 'boolean'],
            'rse_score'              => ['present', 'nullable', 'numeric'],
            'references_count'       => ['present', 'nullable', 'numeric'],
            'certifications'         => ['present', 'array'],
            'cost_drivers'           => ['present', 'array'],
            'price_build_up'         => ['present', 'array'],
            'price_build_up_total'   => ['required', 'numeric'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new QuoteResponseDtoNotConstructableException($msg, previous: $previous);
    }

    private static function toFloatOrNull(mixed $value): ?float
    {
        return is_null($value) ? null : (float) $value;
    }

    private static function toIntOrNull(mixed $value): ?int
    {
        return is_null($value) ? null : (int) $value;
    }
}
