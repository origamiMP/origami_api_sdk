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
            'id'              => 'id',
            'form_submission' => fn (object $formSubmission) => $this->formSubmission = $this->makeIncluded($formSubmission, FormSubmissionDto::class),
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'id' => ['required', 'integer'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new OrderLineDtoNotConstructableException($msg, previous: $previous);
    }
}
