<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Seller;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Seller\BankAccountDtoNotConstructableException;

class BankAccountDto extends ApiResponseDto
{
    public int $id;

    public int $userGroupId;

    public string $bankName;

    public string $ownerName;

    public string $ownerAddress;

    public string $iban;

    public string $bic;

    public bool $isDefault;

    public bool $isActive;

    public bool $pspValidationState;

    public function __construct(object $apiResponse)
    {
        parent::__construct($apiResponse);
        $this->validateAndFill();
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'                    => 'id',
            'user_group_id'         => 'userGroupId',
            'bank_name'             => 'bankName',
            'owner_name'            => 'ownerName',
            'owner_address'         => 'ownerAddress',
            'iban'                  => 'iban',
            'bic'                   => 'bic',
            'is_default'            => fn($value) => $this->isDefault = (bool) $value,
            'is_active'             => fn($value) => $this->isActive = (bool) $value,
            'psp_validation_state'  => fn($value) => $this->pspValidationState = (bool) $value,
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'id'                    => ['required', 'integer'],
            'user_group_id'         => ['required', 'integer'],
            'bank_name'             => ['required', 'string'],
            'owner_name'            => ['required', 'string'],
            'owner_address'         => ['required', 'string'],
            'iban'                  => ['required', 'string'],
            'bic'                   => ['required', 'string'],
            'is_default'            => ['required'],
            'is_active'             => ['required'],
            'psp_validation_state'  => ['required'],
        ];
    }

    protected static function getDefaultNotConstructableException(string $msg, ?\Throwable $previous = null): ApiResponseDtoNotConstructableException
    {
        return new BankAccountDtoNotConstructableException($msg, previous: $previous);
    }
} 