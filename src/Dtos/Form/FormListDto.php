<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Form;

use Illuminate\Contracts\Support\Arrayable;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasAvailableIncludes;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasPagination;

class FormListDto extends ApiResponseDto implements Arrayable
{
    use HasAvailableIncludes, HasPagination;

    public static function getAvailableIncludes(): array
    {
        return [
            'pages' => FormPageDto::class,
        ];
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return $this->getPaginationAsDataStructureToProperties();
    }

    protected function validationRulesForProperties(): array
    {
        return $this->getPaginationValidationRules();
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new FormListDtoNotConstructableException($msg, previous: $previous);
    }

    protected function initData(array $data): void
    {
        $this->data = collect($data)->map(fn ($form) => new FormDto($form));
    }
}
