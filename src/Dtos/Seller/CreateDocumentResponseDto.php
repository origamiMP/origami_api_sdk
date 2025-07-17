<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Seller;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Seller\CreateDocumentResponseDtoNotConstructableException;

class CreateDocumentResponseDto extends ApiResponseDto
{
    public DocumentDto $data;

    public function __construct(object $apiResponse)
    {
        parent::__construct($apiResponse);
        $this->validateAndFill();
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'data' => fn ($data) => $this->data = new DocumentDto($data),
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'data' => ['required', 'array'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new CreateDocumentResponseDtoNotConstructableException($msg, previous: $previous);
    }
}
