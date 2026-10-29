<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Order;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Customer\CustomerDto;
use OrigamiMp\OrigamiApiSdk\Dtos\State\StateDto;
use OrigamiMp\OrigamiApiSdk\Dtos\State\StateLogDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Order\QuoteSellerDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasTimestamps;

/**
 * The quote one seller answers, inside a request for quotes (its order).
 */
class QuoteSellerDto extends ApiResponseDto
{
    use HasIncludes, HasTimestamps;

    public int $id;

    public ?string $reference;

    public int $orderId;

    /**
     * May be undefined if the corresponding data was not included.
     */
    public CustomerDto $customer;

    /**
     * May be undefined if the corresponding data was not included.
     *
     * @var Collection<int, OrderLineDto>
     */
    public Collection $orderLines;

    /**
     * May be undefined if the corresponding data was not included.
     */
    public StateDto $state;

    /**
     * May be undefined if the corresponding data was not included.
     *
     * @var Collection<int, StateLogDto>
     */
    public Collection $stateLogs;

    public static function getAvailableIncludes(): array
    {
        return [
            'customer' => CustomerDto::class,
            // 'seller',
            // 'shippingOffer',
            'orderLines' => OrderLineDto::class,
            'state'      => StateDto::class,
            // 'available_states',
            // 'shippingAddress',
            // 'billingAddress',
            // 'payment_transactions',
            // 'fees',
            // 'refunds',
            // 'refunds_total',
            // 'documents',
            // 'fees_invoices',
            // 'tickets',
            'state_logs' => StateLogDto::class,
            // 'quoteShippings',
            // 'payment_requests',
            // 'user',
            // 'parent',
            // 'children',
            // 'order_info',
            // 'order_returns',
            // 'shipping_label',
            // 'notifications_sent',
            // 'available_returns',
            // 'vouchers',
            // 'gift_cards',
            // 'tracking_logs',
            // 'opinion_link',
            // 'reviews',
        ];
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        $structure = [
            'id'         => 'id',
            'reference'  => 'reference',
            'order_id'   => 'orderId',
            'customer'   => fn (object $customer) => $this->customer = $this->makeIncluded($customer, CustomerDto::class),
            'orderLines' => fn (object $orderLines) => $this->orderLines = $this->collectIncluded($orderLines, OrderLineDto::class),
            'state'      => fn (object $state) => $this->state = $this->makeIncluded($state, StateDto::class),
            'state_logs' => fn (object $stateLogs) => $this->stateLogs = $this->collectIncluded($stateLogs, StateLogDto::class),
        ];

        return [
            ...$structure,
            ...$this->getTimestampsAsDataStructureToProperties(),
        ];
    }

    protected function validationRulesForProperties(): array
    {
        $rules = [
            'id'        => ['required', 'integer'],
            'reference' => ['present', 'nullable', 'string'],
            'order_id'  => ['required', 'integer'],
        ];

        return [
            ...$rules,
            ...$this->getTimestampsValidationRules(),
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new QuoteSellerDtoNotConstructableException($msg, previous: $previous);
    }
}
