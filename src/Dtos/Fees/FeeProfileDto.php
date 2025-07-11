<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Fees;

use Carbon\Carbon;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Fees\FeeProfileDtoNotConstructableException;

class FeeProfileDto extends ApiResponseDto
{
    public int $id;

    public bool $isDefault;

    public string $name;

    public string $type;

    public Carbon $createdAt;

    public bool $taxable;

    public ?float $taxPercentage;

    public string $userGroupLegalType;

    public function __construct(object $apiResponse)
    {
        parent::__construct($apiResponse);
        $this->validateAndFill();
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'                    => 'id',
            'is_default'            => fn ($v) => $this->isDefault = (bool)$v,
            'name'                  => 'name',
            'type'                  => 'type',
            'created_at'            => fn ($v) => $this->createdAt = Carbon::parse($v),
            'taxable'               => fn ($v) => $this->taxable = (bool)$v,
            'tax_percentage'        => 'taxPercentage',
            'user_group_legal_type' => 'userGroupLegalType',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'id'                    => ['required', 'integer'],
            'is_default'            => ['required'],
            'name'                  => ['required', 'string'],
            'type'                  => ['required', 'string'],
            'created_at'            => ['required', 'date'],
            'taxable'               => ['required'],
            'tax_percentage'        => ['nullable', 'numeric'],
            'user_group_legal_type' => ['required', 'string'],
        ];
    }

    protected static function getDefaultNotConstructableException(string $msg, ?\Throwable $previous = null): ApiResponseDtoNotConstructableException
    {
        return new FeeProfileDtoNotConstructableException($msg, previous: $previous);
    }
}
