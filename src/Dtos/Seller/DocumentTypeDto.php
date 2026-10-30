<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Seller;

use Carbon\Carbon;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Seller\DocumentTypeDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasTimestamps;

class DocumentTypeDto extends ApiResponseDto
{
    use HasTimestamps;

    public int $id;

    public string $type;

    public string $mandatoryOn;

    public string $format;

    public string $key;

    /**
     * @var string[]
     */
    public array $requiredBy;

    protected function getDefaultDataStructureToProperties(): array
    {
        $structure = [
            'id'           => 'id',
            'type'         => 'type',
            'mandatory_on' => 'mandatoryOn',
            'format'       => 'format',
            'key'          => 'key',
            'created_at'   => fn ($date) => $this->createdAt = Carbon::parse($date),
            'updated_at'   => fn ($date) => $this->updatedAt = Carbon::parse($date),
            'required_by'  => 'requiredBy',
        ];

        return [
            ...$structure,
            ...$this->getTimestampsAsDataStructureToProperties(),
        ];
    }

    protected function getOptionalKeys(): array
    {
        return [
            'required_by',
        ];
    }

    protected static function getDefaultNotConstructableException(string $msg, ?\Throwable $previous = null): ApiResponseDtoNotConstructableException
    {
        return new DocumentTypeDtoNotConstructableException($msg, previous: $previous);
    }
}
