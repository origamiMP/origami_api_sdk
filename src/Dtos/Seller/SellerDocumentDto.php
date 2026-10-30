<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Seller;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Seller\SellerDocumentDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasTimestamps;

class SellerDocumentDto extends ApiResponseDto
{
    use HasIncludes, HasTimestamps;

    public int $id;

    public int $sellerId;

    public int $documentTypeId;

    public string $name;

    public bool $validate;

    public string $url;

    /**
     * May be undefined if the corresponding data was not included.
     */
    public DocumentTypeDto $documentType;

    public static function getAvailableIncludes(): array
    {
        return [
            'document_type' => DocumentTypeDto::class,
            // 'payment_psp_documents',
        ];
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        $structure = [
            'id'               => 'id',
            'seller_id'        => 'sellerId',
            'document_type_id' => 'documentTypeId',
            'name'             => 'name',
            'validate'         => fn ($value) => $this->validate = (bool) $value,
            'url'              => 'url',

            'document_type' => fn (object $documentType) => $this->documentType = $this->makeIncluded($documentType, DocumentTypeDto::class),
        ];

        return [
            ...$structure,
            ...$this->getTimestampsAsDataStructureToProperties(),
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new SellerDocumentDtoNotConstructableException($msg, previous: $previous);
    }
}
