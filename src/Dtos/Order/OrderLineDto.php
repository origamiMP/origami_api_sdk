<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Order;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormSubmissionDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Order\OrderLineDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;

class OrderLineDto extends ApiResponseDto
{
    use HasIncludes;

    public int $id;

    public int $orderSellerId;

    public ?int $productId;

    public string $name;

    public int $quantity;

    /**
     * Only sent by the API while the form_builder option is on.
     */
    public ?int $formSubmissionId = null;

    /**
     * Not sent by the API when the line has no answer.
     */
    public ?FormSubmissionDto $formSubmission = null;

    public static function getAvailableIncludes(): array
    {
        return [
            // 'product_offer',
            // 'product',
            // 'product_variant',
            // 'taxes',
            // 'customizations',
            // 'associations',
            'form_submission' => FormSubmissionDto::class,
        ];
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'                 => 'id',
            'order_seller_id'    => 'orderSellerId',
            'product_id'         => 'productId',
            'name'               => 'name',
            'quantity'           => 'quantity',
            'form_submission_id' => 'formSubmissionId',
            'form_submission'    => fn (object $formSubmission) => $this->formSubmission = $this->makeIncluded($formSubmission, FormSubmissionDto::class),
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'id'                 => ['required', 'integer'],
            'order_seller_id'    => ['required', 'integer'],
            'product_id'         => ['present', 'nullable', 'integer'],
            'name'               => ['required', 'string'],
            'quantity'           => ['required', 'integer'],
            'form_submission_id' => ['nullable', 'integer'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new OrderLineDtoNotConstructableException($msg, previous: $previous);
    }
}
