<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\User;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasCustomFields;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasTimestamps;

class UserGroupDto extends ApiResponseDto
{
    use HasCustomFields, HasTimestamps;

    // TODO DEV : Add all other fields and includes
    public int $id;

    public function __construct(object $apiResponse)
    {
        parent::__construct($apiResponse);

        $this->validateAndFill();
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        $structure = [
            'id' => 'id',
        ];

        return array_merge(
            $structure,
            $this->getTimestampsAsDataStructureToProperties(),
            $this->getCustomFieldsAsDataStructureToProperties(),
        );
    }

    protected function validationRulesForProperties(): array
    {
        $rules = [
            'id' => ['required', 'integer'],
        ];

        return array_merge(
            $rules,
            $this->getTimestampsValidationRules(),
            $this->getCustomFieldsValidationRules(),
        );
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new UserGroupDtoNotConstructableException($msg, previous: $previous);
    }
}
