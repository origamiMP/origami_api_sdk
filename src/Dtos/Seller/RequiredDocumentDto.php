<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Seller;

use Carbon\Carbon;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Seller\RequiredDocumentDtoNotConstructableException;

class RequiredDocumentDto extends ApiResponseDto
{
    public int $id;

    public string $type;

    public string $mandatoryOn;

    public string $format;

    public string $key;

    public Carbon $createdAt;

    public Carbon $updatedAt;

    /**
     * @var string[]
     */
    public array $requiredBy;

    public function __construct(object $apiResponse)
    {
        parent::__construct($apiResponse);
        $this->validateAndFill();
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'           => 'id',
            'type'         => 'type',
            'mandatory_on' => 'mandatoryOn',
            'format'       => 'format',
            'key'          => 'key',
            'created_at'   => fn ($date) => $this->createdAt = Carbon::parse($date),
            'updated_at'   => fn ($date) => $this->updatedAt = Carbon::parse($date),
            'required_by'  => 'requiredBy',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'id'            => ['required', 'integer'],
            'type'          => ['required', 'string'],
            'mandatory_on'  => ['required', 'string'],
            'format'        => ['required', 'string'],
            'key'           => ['required', 'string'],
            'created_at'    => ['required', 'date'],
            'updated_at'    => ['required', 'date'],
            'required_by'   => ['required', 'array'],
            'required_by.*' => ['required', 'string'],
        ];
    }

    protected static function getDefaultNotConstructableException(string $msg, ?\Throwable $previous = null): ApiResponseDtoNotConstructableException
    {
        return new RequiredDocumentDtoNotConstructableException($msg, previous: $previous);
    }
}
