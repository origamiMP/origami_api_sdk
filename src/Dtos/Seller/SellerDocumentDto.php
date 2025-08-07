<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Seller;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Seller\SellerDocumentDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasTimestamps;

class SellerDocumentDto extends ApiResponseDto
{
    use HasTimestamps;

    public int $id;

    public int $sellerId;

    public int $documentTypeId;

    public string $name;

    public bool $validate;

    public string $url;

    protected function getDefaultDataStructureToProperties(): array
    {
        $structure = [
            'id'               => 'id',
            'seller_id'        => 'sellerId',
            'document_type_id' => 'documentTypeId',
            'name'             => 'name',
            'validate'         => fn ($value) => $this->validate = (bool) $value,
            'url'              => 'url',
        ];

        return array_merge(
            $structure,
            $this->getTimestampsAsDataStructureToProperties(),
        );
    }

    protected function validationRulesForProperties(): array
    {
        $rules = [
            'id'               => ['required', 'integer'],
            'seller_id'        => ['required', 'integer'],
            'document_type_id' => ['required', 'integer'],
            'name'             => ['required', 'string'],
            'validate'         => ['required'],
            'url'              => ['required', 'string', 'url'],
        ];

        return array_merge(
            $rules,
            $this->getTimestampsValidationRules(),
        );
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new SellerDocumentDtoNotConstructableException($msg, previous: $previous);
    }
}
