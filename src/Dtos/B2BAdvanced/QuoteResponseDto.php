<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\B2BAdvanced;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\B2BAdvanced\QuoteResponseDtoStatusEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\B2BAdvanced\QuoteResponseDtoNotConstructableException;

/**
 * A seller's answer to a quote, read from the B2BAdvanced module: the same grid for every seller of the request.
 * $hasResponded is false until the seller sends an offer; the price build-up is there from the start.
 */
class QuoteResponseDto extends ApiResponseDto
{
    public int $orderSellerId;

    public bool $hasResponded;

    public ?float $priceTaxExc;

    public ?int $delayDays;

    public ?string $conditions;

    public ?Carbon $respondedAt;

    public bool $isSelected;

    public bool $isConverted;

    /**
     * The request is withdrawn, expired or answered by another seller: it takes no offer anymore.
     */
    public bool $isClosed;

    /**
     * Where the offer stands: the store's decision first, then whether the request is still open.
     */
    public QuoteResponseDtoStatusEnum $status;

    public ?string $refusalReason;

    public ?int $engagementMonths;

    public bool $onCall247;

    public ?int $rseScore;

    public ?int $referencesCount;

    /** @var string[] */
    public array $certifications;

    /**
     * Every cost driver, in the operator's order, with what the seller filled.
     *
     * @var Collection<int, QuotePriceBuildUpRowDto>
     */
    public Collection $priceBuildUp;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'order_seller_id' => 'orderSellerId',
            'has_responded'   => 'hasResponded',
            'price_tax_exc'   => fn ($price) => $this->priceTaxExc = is_null($price) ? null : (float) $price,
            'delay_days'      => 'delayDays',
            'conditions'      => 'conditions',
            'responded_at'    => fn (?string $date) => $this->respondedAt = is_null($date) ? null : Carbon::parse($date),
            'is_selected'     => 'isSelected',
            'is_converted'    => 'isConverted',
            'is_closed'       => 'isClosed',
            'status'          => fn (string $status) => $this->status = QuoteResponseDtoStatusEnum::from($status),
            'refusal_reason'  => 'refusalReason',
            // Custom field values: the API sends them as strings.
            'engagement_months' => fn ($value) => $this->engagementMonths = self::toIntOrNull($value),
            'on_call_247'       => 'onCall247',
            'rse_score'         => fn ($value) => $this->rseScore = self::toIntOrNull($value),
            'references_count'  => fn ($value) => $this->referencesCount = self::toIntOrNull($value),
            'certifications'    => 'certifications',
            'price_build_up'    => fn (array $rows) => $this->priceBuildUp = collect($rows)->map(fn (object $row) => new QuotePriceBuildUpRowDto($row)),
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'order_seller_id'   => ['required', 'integer'],
            'has_responded'     => ['required', 'boolean'],
            'price_tax_exc'     => ['present', 'nullable', 'numeric'],
            'delay_days'        => ['present', 'nullable', 'integer'],
            'conditions'        => ['present', 'nullable', 'string'],
            'responded_at'      => ['present', 'nullable', 'date'],
            'is_selected'       => ['required', 'boolean'],
            'is_converted'      => ['required', 'boolean'],
            'is_closed'         => ['required', 'boolean'],
            'status'            => ['required', Rule::in(collect(QuoteResponseDtoStatusEnum::cases())->pluck('value'))],
            'refusal_reason'    => ['present', 'nullable', 'string'],
            'engagement_months' => ['present', 'nullable', 'numeric'],
            'on_call_247'       => ['required', 'boolean'],
            'rse_score'         => ['present', 'nullable', 'numeric'],
            'references_count'  => ['present', 'nullable', 'numeric'],
            'certifications'    => ['present', 'array'],
            'price_build_up'    => ['present', 'array'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new QuoteResponseDtoNotConstructableException($msg, previous: $previous);
    }

    private static function toIntOrNull(mixed $value): ?int
    {
        return is_null($value) ? null : (int) $value;
    }
}
